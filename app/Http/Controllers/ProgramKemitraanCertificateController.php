<?php

namespace App\Http\Controllers;

use App\Models\ProgramKemitraanEvaluation;
use App\Services\ProgramKemitraanCertificatePdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProgramKemitraanCertificateController extends Controller
{
    public function redeem(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('sertifikat', [
            'certificate_code' => ['required', 'string', 'max:64', 'regex:/^[A-Za-z0-9-]+$/'],
        ]);

        $certificateCode = strtoupper(trim((string) ($validated['certificate_code'] ?? '')));

        $evaluation = ProgramKemitraanEvaluation::query()
            ->where('certificate_code', $certificateCode)
            ->first();

        if ($evaluation === null) {
            return redirect()
                ->route('program-kemitraan.create', ['tab' => 'sertifikat'])
                ->withInput()
                ->with('sertifikat_error', 'Kode sertifikat tidak ditemukan. Periksa kembali lalu coba lagi.');
        }

        return redirect()
            ->route('program-kemitraan.create', ['tab' => 'sertifikat'])
            ->with('sertifikat_success', 'Kode berhasil diredeem. Klik tombol berikut untuk mengunduh sertifikat.')
            ->with('sertifikat_download_url', route('program-kemitraan.sertifikat.download', ['code' => $certificateCode]));
    }

    public function download(Request $request, ProgramKemitraanCertificatePdfService $pdfService): Response
    {
        $code = strtoupper(trim((string) $request->route('code')));
        if ($code === '' || !preg_match('/^[A-Z0-9-]+$/', $code)) {
            abort(404);
        }

        $evaluation = ProgramKemitraanEvaluation::query()
            ->where('certificate_code', $code)
            ->first();

        if ($evaluation === null) {
            abort(404);
        }

        $pdfBinary = $pdfService->generate($evaluation);
        $safeName = preg_replace('/[^A-Za-z0-9_-]+/', '-', strtolower((string) ($evaluation->respondent_name ?? 'peserta')));
        $safeName = trim((string) $safeName, '-');
        if ($safeName === '') {
            $safeName = 'peserta';
        }
        $fileName = 'sertifikat-partisipasi-' . $safeName . '.pdf';

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
