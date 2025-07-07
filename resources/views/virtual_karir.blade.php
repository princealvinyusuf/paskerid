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
    .vk-service-card {
        transition: box-shadow 0.2s, transform 0.2s;
        min-height: 340px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .vk-service-card:hover {
        box-shadow: 0 8px 32px rgba(0,123,255,0.15);
        transform: translateY(-4px) scale(1.03);
        z-index: 2;
    }
    .vk-service-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #00c6fb 0%, #005baa 100%);
        color: #fff;
        font-size: 2rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,123,255,0.10);
    }
    .vk-learn-more {
        margin-top: 1.5rem;
        color: #fff;
        background: linear-gradient(90deg, #005baa 0%, #00c6fb 100%);
        border: none;
        border-radius: 2rem;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .vk-learn-more:hover {
        background: linear-gradient(90deg, #00c6fb 0%, #005baa 100%);
        color: #fff;
        text-decoration: none;
    }
    .vk-jobfair-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: linear-gradient(90deg, #00c6fb 0%, #005baa 100%);
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.3rem 1rem;
        border-radius: 1rem;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0,123,255,0.10);
    }
    .vk-jobfair-countdown {
        font-size: 0.95rem;
        font-weight: 500;
        color: #005baa;
        margin-top: 0.5rem;
    }
    .vk-jobfair-carousel .carousel-inner {
        padding-bottom: 2rem;
    }
    .vk-jobfair-carousel .carousel-control-prev, .vk-jobfair-carousel .carousel-control-next {
        filter: invert(1);
    }
    .vk-agenda-icon {
        font-size: 1.3rem;
        margin-right: 0.5rem;
        color: #00c6fb;
        vertical-align: middle;
    }
    .vk-agenda-row-upcoming {
        background: #e3f2fd !important;
        font-weight: 600;
        color: #005baa;
    }
    .vk-agenda-row-odd {
        background: #f8f9fa;
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
                <div class="col-md-4 mb-4 d-flex align-items-stretch">
                    <div class="card vk-service-card w-100 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                @if($service->icon)
                                    <div class="vk-service-icon mb-3"><i class="{{ $service->icon }}"></i></div>
                                @endif
                                <h5 class="card-title fw-bold">{{ $service->title }}</h5>
                                <p class="card-text">{{ $service->description }}</p>
                            </div>
                            @if($service->link)
                                <a href="{{ $service->link }}" class="vk-learn-more mt-auto" target="_blank" rel="noopener">Learn More</a>
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
        @if($jobFairs->count() > 1)
            <div id="jobFairCarousel" class="carousel slide vk-jobfair-carousel" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($jobFairs as $idx => $jobFair)
                        <div class="carousel-item @if($idx === 0) active @endif">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card h-100 shadow-sm position-relative">
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $eventDate = \Carbon\Carbon::parse($jobFair->date);
                                            $isUpcoming = $eventDate->isFuture();
                                            $isToday = $eventDate->isToday();
                                            $isOngoing = $isToday;
                                        @endphp
                                        @if($isOngoing)
                                            <span class="vk-jobfair-badge">Ongoing</span>
                                        @elseif($isUpcoming)
                                            <span class="vk-jobfair-badge">Upcoming</span>
                                        @endif
                                        @if($jobFair->image_url)
                                            <img src="{{ $jobFair->image_url }}" class="card-img-top" alt="{{ $jobFair->title }}">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold">{{ $jobFair->title }}</h5>
                                            <p class="card-text">{{ $jobFair->description }}</p>
                                            <div class="vk-jobfair-countdown" data-date="{{ $jobFair->date }}" id="countdown-{{ $jobFair->id }}"></div>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <small class="text-muted">{{ $eventDate->format('d M Y') }}</small>
                                                <small class="text-muted">{{ $jobFair->author }}</small>
                                            </div>
                                            @if($jobFair->register_url)
                                                <a href="{{ $jobFair->register_url }}" class="btn btn-primary btn-sm mt-2">Daftar Sekarang</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#jobFairCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#jobFairCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        @else
            <div class="row mb-2">
                @foreach($jobFairs as $jobFair)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm position-relative">
                            @php
                                $today = \Carbon\Carbon::today();
                                $eventDate = \Carbon\Carbon::parse($jobFair->date);
                                $isUpcoming = $eventDate->isFuture();
                                $isToday = $eventDate->isToday();
                                $isOngoing = $isToday;
                            @endphp
                            @if($isOngoing)
                                <span class="vk-jobfair-badge">Ongoing</span>
                            @elseif($isUpcoming)
                                <span class="vk-jobfair-badge">Upcoming</span>
                            @endif
                            @if($jobFair->image_url)
                                <img src="{{ $jobFair->image_url }}" class="card-img-top" alt="{{ $jobFair->title }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $jobFair->title }}</h5>
                                <p class="card-text">{{ $jobFair->description }}</p>
                                <div class="vk-jobfair-countdown" data-date="{{ $jobFair->date }}" id="countdown-{{ $jobFair->id }}"></div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">{{ $eventDate->format('d M Y') }}</small>
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
        @endif
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
                        @php
                            $now = \Carbon\Carbon::now();
                            $nextIdx = null;
                            foreach($agendas as $idx => $agenda) {
                                if ($nextIdx === null && \Carbon\Carbon::parse($agenda->date)->isFuture()) {
                                    $nextIdx = $idx;
                                }
                            }
                        @endphp
                        @foreach($agendas as $idx => $agenda)
                            @php
                                $date = \Carbon\Carbon::parse($agenda->date);
                                $isUpcoming = $idx === $nextIdx;
                                $rowClass = $isUpcoming ? 'vk-agenda-row-upcoming' : ($idx % 2 === 1 ? 'vk-agenda-row-odd' : '');
                                // Simple icon logic based on title/desc
                                $icon = 'fa-calendar';
                                if (stripos($agenda->title, 'webinar') !== false) $icon = 'fa-chalkboard-teacher';
                                elseif (stripos($agenda->title, 'pelatihan') !== false) $icon = 'fa-user-graduate';
                                elseif (stripos($agenda->title, 'job fair') !== false) $icon = 'fa-briefcase';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>{{ $date->format('d M Y') }}</td>
                                <td><i class="fas {{ $icon }} vk-agenda-icon"></i>{{ $agenda->title }}</td>
                                <td>{{ $agenda->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function updateCountdowns() {
        document.querySelectorAll('.vk-jobfair-countdown').forEach(function(el) {
            var dateStr = el.getAttribute('data-date');
            var eventDate = new Date(dateStr);
            var now = new Date();
            var diff = eventDate - now;
            if (diff > 0) {
                var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                var hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                var minutes = Math.floor((diff / (1000 * 60)) % 60);
                el.innerHTML = 'Mulai dalam: <b>' + days + ' hari</b> ' + hours + ' jam ' + minutes + ' menit';
            } else if (Math.abs(diff) < 1000 * 60 * 60 * 24) {
                el.innerHTML = '<span class="text-success">Sedang berlangsung!</span>';
            } else {
                el.innerHTML = '<span class="text-muted">Sudah lewat</span>';
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        updateCountdowns();
        setInterval(updateCountdowns, 60000);
    });
</script>
@endsection 