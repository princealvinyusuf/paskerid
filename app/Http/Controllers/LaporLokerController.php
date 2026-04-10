<?php

namespace App\Http\Controllers;

use App\Models\JobHoaxReport;
use Illuminate\Http\Request;

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
}
