@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">CF (Underconstruction)</h1>
            <p class="text-muted mb-0">
                Prototype portal diskusi komunitas untuk ekosistem pasar kerja.
            </p>
        </div>
        <span class="badge text-bg-warning mt-3 mt-md-0">Under Construction</span>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 fw-bold">Tujuan Portal</h2>
            <p class="mb-0">
                Menjadi pusat interaksi antara pelaku usaha dan pencari kerja untuk berbagi kebutuhan,
                insight rekrutmen, peluang kerja, serta diskusi pengembangan kompetensi.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h3 class="h5 fw-bold">Struktur Topik Utama</h3>
                    <ul class="mb-0 ps-3">
                        <li>Lowongan & Kebutuhan Talenta</li>
                        <li>Pencari Kerja & Profil Kandidat</li>
                        <li>Diskusi Industri per Sektor</li>
                        <li>Tips CV, Interview, dan Hiring</li>
                        <li>Event Job Fair / Walk In Interview</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h3 class="h5 fw-bold">Fitur Inti (MVP)</h3>
                    <ul class="mb-0 ps-3">
                        <li>Pembuatan thread dan balasan</li>
                        <li>Kategori dan tag topik</li>
                        <li>Pencarian thread berdasarkan kata kunci</li>
                        <li>Moderasi dasar dan pelaporan konten</li>
                        <li>Pin topik prioritas ketenagakerjaan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
