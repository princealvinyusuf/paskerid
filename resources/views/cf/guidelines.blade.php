@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Panduan Komunitas CF</h1>
            <p class="text-muted mb-0">Aturan diskusi, standar perilaku, dan transparansi penegakan.</p>
        </div>
        <a href="{{ route('cf.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Forum</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h2 class="h6 fw-bold">Aturan Utama</h2>
            <ul class="mb-0">
                <li>Fokus pada topik ketenagakerjaan, rekrutmen, dan pengembangan karier.</li>
                <li>Dilarang spam, penipuan, tautan phishing, hoaks, dan promosi menyesatkan.</li>
                <li>Dilarang ujaran kebencian, diskriminasi, pelecehan, atau ancaman.</li>
                <li>Hormati privasi; jangan unggah data pribadi sensitif tanpa izin.</li>
                <li>Gunakan data lowongan/kompensasi secara jujur dan dapat dipertanggungjawabkan.</li>
            </ul>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h2 class="h6 fw-bold">Transparansi Penegakan</h2>
            <ul class="mb-0">
                <li>Laporan masuk akan diprioritaskan berdasarkan skor risiko (low/medium/high).</li>
                <li>Moderator dapat menandai status: open, resolved, atau rejected, disertai catatan.</li>
                <li>Pelanggaran berat dapat ditindak lebih cepat sesuai urgensi konten.</li>
                <li>Riwayat penanganan tercatat di Moderation Center untuk audit internal.</li>
            </ul>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h2 class="h6 fw-bold">Cara Melaporkan</h2>
            <p class="mb-0">
                Gunakan tombol <strong>Laporkan</strong> pada thread atau balasan. Jelaskan alasan secara spesifik
                agar moderator dapat menilai konteks dengan tepat.
            </p>
        </div>
    </div>
</div>
@endsection
