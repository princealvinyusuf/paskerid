<?php

namespace App\Http\Controllers;

use App\Models\JobHoaxReport;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use RuntimeException;
use ZipArchive;

class LaporLokerController extends Controller
{
    public function index()
    {
        $reports = JobHoaxReport::query()
            ->where('status', 'approved')
            ->orderByDesc('tanggal_terdeteksi')
            ->orderByDesc('id')
            ->paginate(10);

        return view('lapor_loker.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_terduga_pelaku' => ['required', 'email', 'max:255'],
            'tanggal_terdeteksi' => ['required', 'date'],
            'nama_perusahaan_digunakan' => ['required', 'string', 'max:255'],
            'nama_hr_digunakan' => ['required', 'string', 'max:255'],
            'provinsi' => ['required', 'string', 'max:150'],
            'kota' => ['required', 'string', 'max:150'],
            'nomor_kontak_terduga' => ['required', 'string', 'max:60'],
            'platform_sumber' => ['required', 'string', 'max:120'],
            'tautan_informasi' => ['required', 'url', 'max:500'],
            'bukti_pendukung' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx'],
            'kronologi' => ['nullable', 'string', 'max:5000'],
            'pelapor_nama' => ['required', 'string', 'max:120'],
            'pelapor_email' => ['required', 'email', 'max:255'],
        ]);

        $data = $validated;
        unset($data['bukti_pendukung']);

        if ($request->hasFile('bukti_pendukung')) {
            $file = $request->file('bukti_pendukung');
            if ($file) {
                $data['bukti_pendukung_path'] = $file->store('lapor_loker/bukti_pendukung', 'public');
                $data['bukti_pendukung_nama'] = $file->getClientOriginalName();
            }
        }

        JobHoaxReport::create($data + ['status' => 'pending']);

        return redirect()
            ->route('lapor-loker.index')
            ->with('success', 'Laporan berhasil dikirim. Tim kami akan memverifikasi terlebih dahulu.');
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validateWithBag('bulkReport', [
            'bulk_passcode' => ['required', 'string', 'max:64'],
            'bulk_file' => ['required', 'file', 'max:10240', 'mimes:xlsx,csv'],
        ], [
            'bulk_file.mimes' => 'File bulk harus berformat .xlsx atau .csv.',
        ]);

        if (!$this->isValidPortalCodePasscode($validated['bulk_passcode'])) {
            return redirect()
                ->route('lapor-loker.index')
                ->withErrors(['bulk_passcode' => 'Passcode tidak valid. Gunakan Portal Code (unique) yang aktif.'], 'bulkReport')
                ->withInput();
        }

        try {
            $rows = $this->parseBulkFile($request->file('bulk_file'));
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('lapor-loker.index')
                ->withErrors(['bulk_file' => $exception->getMessage()], 'bulkReport')
                ->withInput();
        }

        if (empty($rows)) {
            return redirect()
                ->route('lapor-loker.index')
                ->withErrors(['bulk_file' => 'File tidak memiliki data laporan.'], 'bulkReport')
                ->withInput();
        }

        $header = array_shift($rows);
        $mappedHeader = $this->mapBulkHeader($header);

        $requiredColumns = [
            'email_terduga_pelaku',
            'tanggal_terdeteksi',
            'nama_perusahaan_digunakan',
            'nama_hr_digunakan',
            'provinsi',
            'kota',
            'nomor_kontak_terduga',
            'platform_sumber',
            'tautan_informasi',
            'pelapor_nama',
            'pelapor_email',
        ];

        $missing = [];
        foreach ($requiredColumns as $requiredColumn) {
            if (!in_array($requiredColumn, $mappedHeader, true)) {
                $missing[] = $requiredColumn;
            }
        }

        if (!empty($missing)) {
            return redirect()
                ->route('lapor-loker.index')
                ->withErrors(['bulk_file' => 'Kolom wajib belum lengkap di file: ' . implode(', ', $missing)], 'bulkReport')
                ->withInput();
        }

        $inserted = 0;
        $rowErrors = [];

        foreach ($rows as $idx => $row) {
            $sheetRowNumber = $idx + 2;
            $payload = [];

            foreach ($mappedHeader as $columnIndex => $fieldName) {
                if ($fieldName === '' || $fieldName === null) {
                    continue;
                }
                $payload[$fieldName] = isset($row[$columnIndex]) ? trim((string) $row[$columnIndex]) : '';
            }

            if ($this->isBulkRowEmpty($payload)) {
                continue;
            }

            $validator = Validator::make($payload, [
                'email_terduga_pelaku' => ['required', 'email', 'max:255'],
                'tanggal_terdeteksi' => ['required', 'date'],
                'nama_perusahaan_digunakan' => ['required', 'string', 'max:255'],
                'nama_hr_digunakan' => ['required', 'string', 'max:255'],
                'provinsi' => ['required', 'string', 'max:150'],
                'kota' => ['required', 'string', 'max:150'],
                'nomor_kontak_terduga' => ['required', 'string', 'max:60'],
                'platform_sumber' => ['required', 'string', 'max:120'],
                'tautan_informasi' => ['required', 'url', 'max:500'],
                'kronologi' => ['nullable', 'string', 'max:5000'],
                'pelapor_nama' => ['required', 'string', 'max:120'],
                'pelapor_email' => ['required', 'email', 'max:255'],
            ]);

            if ($validator->fails()) {
                $firstError = $validator->errors()->first();
                $rowErrors[] = "Baris {$sheetRowNumber}: {$firstError}";
                continue;
            }

            $validData = $validator->validated();
            JobHoaxReport::create($validData + ['status' => 'pending']);
            $inserted++;
        }

        if ($inserted === 0) {
            return redirect()
                ->route('lapor-loker.index')
                ->withErrors(['bulk_file' => 'Tidak ada data yang berhasil diimpor. ' . implode(' | ', array_slice($rowErrors, 0, 3))], 'bulkReport')
                ->withInput();
        }

        $message = "Import berhasil: {$inserted} laporan masuk untuk verifikasi admin.";
        if (!empty($rowErrors)) {
            $message .= ' Beberapa baris dilewati: ' . implode(' | ', array_slice($rowErrors, 0, 3));
        }

        return redirect()
            ->route('lapor-loker.index')
            ->with('success', $message);
    }

    public function downloadBulkTemplate()
    {
        $headers = [
            'Email Terduga Pelaku',
            'Tanggal Terdeteksi',
            'Nama Perusahaan Yang Digunakan',
            'Nama HR Yang Digunakan',
            'Provinsi',
            'Kota',
            'Nomor Kontak Terduga',
            'Platform Sumber',
            'Tautan Informasi',
            'Kronologi',
            'Nama Pelapor',
            'Email Pelapor',
        ];

        $sampleRow = [
            'scammer@example.com',
            '2026-04-10',
            'PT Contoh Palsu',
            'Budi Recruiter',
            'DKI Jakarta',
            'Jakarta Selatan',
            '+6281234567890',
            'WhatsApp',
            'https://contoh-link-loker.com',
            'Pelaku meminta transfer biaya administrasi.',
            'Nama Pelapor',
            'pelapor@example.com',
        ];

        return response()->streamDownload(function () use ($headers, $sampleRow) {
            $output = fopen('php://output', 'w');
            if (!$output) {
                return;
            }

            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $headers);
            fputcsv($output, $sampleRow);
            fclose($output);
        }, 'template_lapor_loker_bulk.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function isBulkRowEmpty(array $payload): bool
    {
        foreach ($payload as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function mapBulkHeader(array $headerRow): array
    {
        $aliases = [
            'emailterdugapelaku' => 'email_terduga_pelaku',
            'tanggalterdeteksi' => 'tanggal_terdeteksi',
            'namaperusahaanyangdigunakan' => 'nama_perusahaan_digunakan',
            'namahryangdigunakan' => 'nama_hr_digunakan',
            'provinsi' => 'provinsi',
            'kota' => 'kota',
            'nomorkontakterduga' => 'nomor_kontak_terduga',
            'platformsumber' => 'platform_sumber',
            'tautaninformasi' => 'tautan_informasi',
            'kronologi' => 'kronologi',
            'kronologisingkat' => 'kronologi',
            'namapelapor' => 'pelapor_nama',
            'pelapornama' => 'pelapor_nama',
            'emailpelapor' => 'pelapor_email',
            'pelaporemail' => 'pelapor_email',
        ];

        $result = [];
        foreach ($headerRow as $columnName) {
            $normalized = preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) $columnName)));
            $result[] = $aliases[$normalized] ?? '';
        }

        return $result;
    }

    private function parseBulkFile(UploadedFile $file): array
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        if ($extension === 'csv') {
            return $this->parseCsv($file->getRealPath());
        }

        if ($extension === 'xlsx') {
            return $this->parseXlsx($file->getRealPath());
        }

        throw new RuntimeException('Format file tidak didukung.');
    }

    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            throw new RuntimeException('Gagal membaca file CSV.');
        }

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = array_map(static fn ($value) => trim((string) $value), $data);
        }
        fclose($handle);

        return $rows;
    }

    private function parseXlsx(string $path): array
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new RuntimeException('Gagal membuka file XLSX.');
        }

        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            throw new RuntimeException('File XLSX tidak valid (sheet1 tidak ditemukan).');
        }

        $sharedStrings = [];
        if ($sharedStringsXml !== false) {
            $sharedStringsElement = simplexml_load_string($sharedStringsXml);
            if ($sharedStringsElement && isset($sharedStringsElement->si)) {
                foreach ($sharedStringsElement->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                        continue;
                    }

                    $value = '';
                    if (isset($si->r)) {
                        foreach ($si->r as $r) {
                            $value .= (string) ($r->t ?? '');
                        }
                    }
                    $sharedStrings[] = $value;
                }
            }
        }

        $sheet = simplexml_load_string($sheetXml);
        if (!$sheet || !isset($sheet->sheetData)) {
            throw new RuntimeException('File XLSX tidak valid (sheetData tidak ditemukan).');
        }

        $rows = [];
        foreach ($sheet->sheetData->row as $rowElement) {
            $rowData = [];
            foreach ($rowElement->c as $cell) {
                $cellRef = (string) ($cell['r'] ?? '');
                $columnLetters = preg_replace('/[^A-Z]/', '', strtoupper($cellRef));
                $columnIndex = $this->xlsxColumnToIndex($columnLetters);
                $cellType = (string) ($cell['t'] ?? '');
                $value = '';

                if ($cellType === 'inlineStr' && isset($cell->is->t)) {
                    $value = (string) $cell->is->t;
                } elseif (isset($cell->v)) {
                    $raw = (string) $cell->v;
                    if ($cellType === 's') {
                        $sharedIndex = (int) $raw;
                        $value = $sharedStrings[$sharedIndex] ?? '';
                    } else {
                        $value = $raw;
                    }
                }

                $rowData[$columnIndex] = trim($value);
            }

            if (empty($rowData)) {
                continue;
            }

            ksort($rowData);
            $maxIndex = max(array_keys($rowData));
            $orderedRow = [];
            for ($i = 0; $i <= $maxIndex; $i++) {
                $orderedRow[] = $rowData[$i] ?? '';
            }
            $rows[] = $orderedRow;
        }

        return $rows;
    }

    private function xlsxColumnToIndex(string $columnLetters): int
    {
        if ($columnLetters === '') {
            return 0;
        }

        $columnLetters = strtoupper($columnLetters);
        $index = 0;
        $length = strlen($columnLetters);
        for ($i = 0; $i < $length; $i++) {
            $index = ($index * 26) + (ord($columnLetters[$i]) - ord('A') + 1);
        }

        return $index - 1;
    }

    private function isValidPortalCodePasscode(string $passcode): bool
    {
        $passcode = trim($passcode);
        if ($passcode === '') {
            return false;
        }

        $host = (string) config('database.connections.mysql.host', '127.0.0.1');
        $port = (int) config('database.connections.mysql.port', 3306);
        $username = (string) config('database.connections.mysql.username', 'root');
        $password = (string) config('database.connections.mysql.password', '');

        try {
            $mysqli = @new \mysqli($host, $username, $password, 'job_admin_prod', $port);
        } catch (\Throwable $e) {
            return false;
        }

        if ($mysqli->connect_errno) {
            return false;
        }

        $query = "SELECT 1 FROM karirhub_mitra_monitoring WHERE LOWER(portal_code) = LOWER(?) AND is_active = 1 LIMIT 1";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            $mysqli->close();
            return false;
        }

        $stmt->bind_param('s', $passcode);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $mysqli->close();

        return $exists;
    }
}
