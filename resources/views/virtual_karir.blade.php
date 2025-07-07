@extends('layouts.app')

@section('content')
<style>
    .vk-hero {
        background: linear-gradient(90deg, #005baa 0%, #00c6fb 100%);
        color: #fff;
        border-radius: 1rem;
        padding: 2.5rem 2rem 2rem 2rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        position: relative;
        overflow: hidden;
    }
    .vk-section {
        background: #f8f9fa;
        border-radius: 1rem;
        padding: 2rem 1.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .vk-section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #005baa;
    }
    .vk-section-desc {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    .vk-divider {
        border: none;
        border-top: 2px solid #00c6fb;
        margin: 2.5rem 0 2rem 0;
        width: 100px;
        background: transparent;
    }
</style>
<div class="container py-4">
    <div class="vk-hero mb-5">
        <h1 class="display-5 fw-bold mb-2">Virtual Karir</h1>
        <p class="lead mb-0">Satu pintu layanan karir, job fair, dan agenda pasar kerja Indonesia secara digital dan terintegrasi.</p>
        <img src="/images/hero-bg.jpg" alt="Virtual Karir" style="position:absolute;right:2rem;bottom:0;max-width:200px;opacity:0.15;pointer-events:none;">
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white px-0">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Layanan</li>
            <li class="breadcrumb-item active" aria-current="page">Virtual Karir</li>
        </ol>
    </nav>

    <div class="vk-section mb-5">
        <div class="vk-section-title">Layanan Pusat Pasar Kerja</div>
        <div class="vk-section-desc">Berbagai layanan digital untuk mendukung pengembangan karir dan akses pasar kerja nasional.</div>
        <div class="row mb-2">
            @foreach($services as $service)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="mb-2">
                                @if($service->icon)
                                    <i class="{{ $service->icon }} fa-2x"></i>
                                @endif
                            </div>
                            <h5 class="card-title fw-bold">{{ $service->title }}</h5>
                            <p class="card-text">{{ $service->description }}</p>
                            @if($service->link)
                                <a href="{{ $service->link }}" class="text-primary">Selengkapnya &rarr;</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <hr class="vk-divider">
    <div class="vk-section mb-5">
        <div class="vk-section-title">Layanan Job Fair</div>
        <div class="vk-section-desc">Temukan dan ikuti berbagai event job fair nasional secara daring maupun luring.</div>
        <div class="row mb-2">
            @foreach($jobFairs as $jobFair)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($jobFair->image_url)
                            <img src="{{ $jobFair->image_url }}" class="card-img-top" alt="{{ $jobFair->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $jobFair->title }}</h5>
                            <p class="card-text">{{ $jobFair->description }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">{{ \Carbon\Carbon::parse($jobFair->date)->format('d M Y') }}</small>
                                <small class="text-muted">{{ $jobFair->author }}</small>
                            </div>
                            @if($jobFair->register_url)
                                <a href="{{ $jobFair->register_url }}" class="btn btn-primary btn-sm mt-2">Daftar Sekarang</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <hr class="vk-divider">
    <div class="vk-section">
        <div class="vk-section-title">Agenda Pusat Pasar Kerja</div>
        <div class="vk-section-desc">Jadwal kegiatan, webinar, pelatihan, dan agenda penting lainnya dari Pusat Pasar Kerja.</div>
        <div class="card p-4">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agendas as $agenda)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($agenda->date)->format('d M Y') }}</td>
                                <td>{{ $agenda->title }}</td>
                                <td>{{ $agenda->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 