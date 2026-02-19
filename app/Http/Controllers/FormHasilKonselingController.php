<?php

namespace App\Http\Controllers;

use App\Models\CounselingResult;
use App\Models\CounselingResultEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class FormHasilKonselingController extends Controller
{
    public const JENIS_KONSELING_OPTIONS = [
        'Career Boost Day_Online',
        'Konseling JKP',
        'Konseling & Bedah CV_Walk In Interview',
        'Konseling Karier_Offline',
    ];

    public function index()
    {
        $jenisOptions = self::JENIS_KONSELING_OPTIONS;

        $konselorOptions = collect();
        $konseliOptions = collect();
        if (Schema::hasTable('counseling_results')) {
            // Keep it light: only bring a reasonable number for datalist autocomplete.
            $konselorOptions = DB::table('counseling_results')
                ->select('nama_konselor')
                ->whereNotNull('nama_konselor')
                ->where('nama_konselor', '<>', '')
                ->groupBy('nama_konselor')
                ->orderBy('nama_konselor')
                ->limit(500)
                ->pluck('nama_konselor');

            $konseliOptions = DB::table('counseling_results')
                ->select('nama_konseli')
                ->whereNotNull('nama_konseli')
                ->where('nama_konseli', '<>', '')
                ->groupBy('nama_konseli')
                ->orderBy('nama_konseli')
                ->limit(500)
                ->pluck('nama_konseli');
        }

        // Optional: allow picking from accepted/booked Career Boost Day sessions to prefill quickly
        $bookedOptions = collect();
        $bookedAvailable =
            Schema::hasTable('career_boostday_consultations') &&
            Schema::hasColumn('career_boostday_consultations', 'admin_status') &&
            Schema::hasColumn('career_boostday_consultations', 'booked_date') &&
            Schema::hasColumn('career_boostday_consultations', 'pic_id') &&
            Schema::hasTable('career_boostday_pics');

        if ($bookedAvailable) {
            // For "hasil konseling" we usually need past sessions too.
            $from = Carbon::today()->subDays(120)->toDateString();

            $bookedOptions = DB::table('career_boostday_consultations as c')
                ->leftJoin('career_boostday_pics as p', 'p.id', '=', 'c.pic_id')
                ->where('c.admin_status', 'accepted')
                ->whereNotNull('c.booked_date')
                ->where('c.booked_date', '>=', $from)
                ->orderBy('c.booked_date', 'desc')
                ->orderBy('c.booked_time_start', 'desc')
                ->limit(300)
                ->get([
                    'c.id',
                    'c.name as nama_konseli',
                    DB::raw('p.name as nama_konselor'),
                    'c.booked_date',
                    'c.booked_time_start',
                    'c.booked_time_finish',
                    'c.jenis_konseling as jenis_konseling_raw',
                ])->map(function ($r) {
                    $jenis = (string)($r->jenis_konseling_raw ?? '');
                    $mapped = 'Konseling JKP';
                    if (stripos($jenis, 'online') !== false) $mapped = 'Career Boost Day_Online';
                    elseif (stripos($jenis, 'offline') !== false) $mapped = 'Konseling Karier_Offline';

                    $time = null;
                    if (!empty($r->booked_time_start) && !empty($r->booked_time_finish)) {
                        $time = substr((string)$r->booked_time_start, 0, 5) . ' - ' . substr((string)$r->booked_time_finish, 0, 5);
                    }

                    return (object) [
                        'id' => $r->id,
                        'nama_konselor' => (string)($r->nama_konselor ?? ''),
                        'nama_konseli' => (string)($r->nama_konseli ?? ''),
                        'tanggal_konseling' => (string)($r->booked_date ?? ''),
                        'jenis_konseling' => $mapped,
                        'time' => $time,
                    ];
                });
        }

        return view('form_hasil_konseling.index', compact('jenisOptions', 'konselorOptions', 'konseliOptions', 'bookedOptions', 'bookedAvailable'));
    }

    public function prefill(Request $request)
    {
        $validated = $request->validate([
            'nama_konselor' => ['required', 'string', 'max:120'],
            'nama_konseli' => ['required', 'string', 'max:120'],
        ]);

        if (!Schema::hasTable('counseling_results')) {
            return response()->json(['found' => false]);
        }

        $nk = trim($validated['nama_konselor']);
        $np = trim($validated['nama_konseli']);

        $row = DB::table('counseling_results')
            ->whereRaw('TRIM(nama_konselor) = ?', [$nk])
            ->whereRaw('TRIM(nama_konseli) = ?', [$np])
            ->orderBy('tanggal_konseling', 'desc')
            ->orderBy('id', 'desc')
            ->first([
                'id',
                'tanggal_konseling',
                'jenis_konseling',
                'hal_yang_dibahas',
                'saran_untuk_pencaker',
                'updated_at',
            ]);

        if (!$row) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'data' => [
                'id' => $row->id,
                'tanggal_konseling' => $row->tanggal_konseling,
                'jenis_konseling' => $row->jenis_konseling,
                'hal_yang_dibahas' => $row->hal_yang_dibahas,
                'saran_untuk_pencaker' => $row->saran_untuk_pencaker,
                'updated_at' => $row->updated_at,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_konselor' => ['required', 'string', 'max:120'],
            'nama_konseli' => ['required', 'string', 'max:120'],
            'tanggal_konseling' => ['required', 'date'],
            'jenis_konseling' => ['required', 'string', 'in:' . implode(',', self::JENIS_KONSELING_OPTIONS)],
            'hal_yang_dibahas' => ['required', 'string', 'max:5000'],
            'saran_untuk_pencaker' => ['required', 'string', 'max:5000'],
            'bukti' => ['nullable', 'array', 'max:5'],
            'bukti.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,webp'],
        ]);

        $files = $request->file('bukti', []);
        $hasFiles = is_array($files) && collect($files)->filter()->isNotEmpty();
        if ($hasFiles && !Schema::hasTable('counseling_result_evidences')) {
            return back()
                ->withErrors(['bukti' => 'Fitur upload bukti belum aktif di database. Silakan jalankan migrasi terlebih dahulu.'])
                ->withInput();
        }

        $result = CounselingResult::create([
            'nama_konselor' => $validated['nama_konselor'],
            'nama_konseli' => $validated['nama_konseli'],
            'tanggal_konseling' => $validated['tanggal_konseling'],
            'jenis_konseling' => $validated['jenis_konseling'],
            'hal_yang_dibahas' => $validated['hal_yang_dibahas'],
            'saran_untuk_pencaker' => $validated['saran_untuk_pencaker'],
        ]);

        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $originalName = $file->getClientOriginalName();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();
            $path = $file->store('hasil_konseling/bukti', 'public');

            CounselingResultEvidence::create([
                'counseling_result_id' => $result->id,
                'file_path' => $path,
                'original_name' => $originalName,
                'mime_type' => $mime,
                'file_size' => $size,
            ]);
        }

        return redirect()
            ->route('form-hasil-konseling.index')
            ->with('success', 'Terima kasih! Hasil konseling berhasil dikirim.');
    }
}


