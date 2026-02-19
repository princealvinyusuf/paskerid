<?php

namespace App\Http\Controllers;

use App\Models\CounselingResult;
use App\Models\CounselingResultEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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
        return view('form_hasil_konseling.index', compact('jenisOptions'));
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


