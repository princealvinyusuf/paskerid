@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    :root {
        --primary-green: #187C19;
        --secondary-green: #69B41E;
        --accent-green: #8DC71E;
        --light-green: #B8D53D;
        --dark-green: #0D5B11;
    }
    .vk-hero {
        background: linear-gradient(90deg, var(--primary-green) 0%, var(--secondary-green) 100%);
        color: #fff;
        border-radius: 1.5rem;
        padding: 2.5rem 2rem 2rem 2rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        min-height: 220px;
    }
    .vk-hero-content {
        z-index: 2;
        flex: 1 1 300px;
    }
    .vk-hero-img {
        position: absolute;
        right: 2rem;
        bottom: 0;
        max-width: 220px;
        opacity: 0.18;
        pointer-events: none;
        z-index: 1;
    }
    .vk-hero-logo {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(24,124,25,0.10);
    }
    .vk-section {
        background: linear-gradient(135deg, #f8f9fa 80%, var(--light-green) 100%);
        border-radius: 1.25rem;
        padding: 2rem 1.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .vk-section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--primary-green);
        letter-spacing: -1px;
    }
    .vk-section-desc {
        color: var(--dark-green);
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }
    .vk-divider {
        border: none;
        border-top: 2.5px solid var(--accent-green);
        margin: 2.5rem 0 2rem 0;
        width: 120px;
        background: transparent;
        border-radius: 2px;
    }
    .vk-service-card {
        transition: box-shadow 0.2s, transform 0.2s;
        min-height: 340px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .vk-service-card:hover {
        box-shadow: 0 8px 32px rgba(24,124,25,0.15);
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
        background: linear-gradient(135deg, var(--accent-green) 0%, var(--primary-green) 100%);
        color: #fff;
        font-size: 2rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(24,124,25,0.10);
    }
    .vk-learn-more, .btn-primary {
        background: linear-gradient(90deg, var(--primary-green) 0%, var(--secondary-green) 100%) !important;
        color: #fff !important;
        border: none;
        border-radius: 2rem;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(24,124,25,0.10);
    }
    .vk-learn-more:hover, .btn-primary:hover {
        background: linear-gradient(90deg, var(--secondary-green) 0%, var(--primary-green) 100%) !important;
        color: #fff !important;
        text-decoration: none;
    }
    .vk-jobfair-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: linear-gradient(90deg, var(--accent-green) 0%, var(--primary-green) 100%);
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.3rem 1rem;
        border-radius: 1rem;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(24,124,25,0.10);
    }
    .vk-jobfair-countdown {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--primary-green);
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
        color: var(--accent-green);
        vertical-align: middle;
    }
    .vk-agenda-row-upcoming {
        background: var(--light-green) !important;
        font-weight: 600;
        color: var(--primary-green);
    }
    .vk-agenda-row-odd {
        background: #f8f9fa;
    }
    .vk-fadein {
        animation: fadeInUp 0.8s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .breadcrumb-item.active {
        color: var(--primary-green) !important;
        font-weight: 600;
    }
    @media (max-width: 767.98px) {
        .vk-hero {
            padding: 1.5rem 1rem 1rem 1rem;
            font-size: 1.1rem;
        }
        .vk-section {
            padding: 1.2rem 0.5rem;
        }
        .vk-service-card, .vk-jobfair-card {
            min-height: unset;
        }
    }
</style>
<div class="container py-4">
    <div class="vk-hero mb-5 animate__animated animate__fadeInDown">
        <div class="vk-hero-content">
            <div class="vk-hero-logo mb-3">
                <img src="/images/logo_siapkerja.svg" alt="Logo" style="width:40px;height:40px;">
            </div>
            <h1 class="display-5 fw-bold mb-2">Virtual Karir</h1>
            <p class="lead mb-0">Satu pintu layanan karir, job fair, dan agenda pasar kerja Indonesia secara digital dan terintegrasi.</p>
        </div>
        <img src="/images/logo_siapkerja_white.svg" class="vk-hero-img" alt="Virtual Karir">
    </div>
    {{-- Removed breadcrumb navigation --}}

    <div class="vk-section mb-5 animate__animated animate__fadeInUp animate__delay-1s">
        <div class="vk-section-title">Layanan Pusat Pasar Kerja</div>
        <div class="vk-section-desc">Berbagai layanan digital untuk mendukung pengembangan karir dan akses pasar kerja nasional.</div>
        <div class="row mb-2">
            @foreach($services as $service)
                <div class="col-md-4 mb-4 d-flex align-items-stretch">
                    <div class="card vk-service-card w-100 shadow-sm animate__animated animate__fadeInUp animate__faster">
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
    <div class="vk-section mb-5 animate__animated animate__fadeInUp animate__delay-2s">
        <div class="vk-section-title">Layanan Job Fair</div>
        <div class="vk-section-desc">Temukan dan ikuti berbagai event job fair nasional secara daring maupun luring.</div>
        @if($jobFairs->count() > 1)
            <div id="jobFairCarousel" class="carousel slide vk-jobfair-carousel" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($jobFairs as $idx => $jobFair)
                        <div class="carousel-item @if($idx === 0) active @endif">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card h-100 shadow-sm position-relative animate__animated animate__fadeInUp animate__faster">
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
                                                @if($eventDate->isPast())
                                                    <button class="btn btn-secondary btn-sm mt-2" disabled style="background: #adb5bd; border-color: #adb5bd; cursor: not-allowed;">Daftar Sekarang</button>
                                                @else
                                                    <a href="{{ $jobFair->register_url }}" class="btn btn-primary btn-sm mt-2">Daftar Sekarang</a>
                                                @endif
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
                        <div class="card h-100 shadow-sm position-relative animate__animated animate__fadeInUp animate__faster">
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
                                    @if($eventDate->isPast())
                                        <button class="btn btn-secondary btn-sm mt-2" disabled style="background: #adb5bd; border-color: #adb5bd; cursor: not-allowed;">Daftar Sekarang</button>
                                    @else
                                        <a href="{{ $jobFair->register_url }}" class="btn btn-primary btn-sm mt-2">Daftar Sekarang</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <hr class="vk-divider">
    <div class="vk-section animate__animated animate__fadeInUp animate__delay-3s">
        <div class="vk-section-title">Agenda Pusat Pasar Kerja</div>
        <div class="vk-section-desc">Jadwal kegiatan, webinar, pelatihan, dan agenda penting lainnya dari Pusat Pasar Kerja.</div>
        <div class="card p-4 animate__animated animate__fadeIn animate__delay-4s">
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
                                <td>
                                    {{ $agenda->description }}
                                    <button class="btn btn-outline-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#agendaDetailModal"
                                        data-title="{{ e($agenda->title) }}"
                                        data-organizer="{{ e($agenda->organizer) }}"
                                        data-date="{{ $date->format('d M Y') }}"
                                        data-location="{{ e($agenda->location) }}"
                                        data-image="{{ $agenda->image_url }}"
                                        data-registration="{{ $agenda->registration_url }}"
                                        data-description="{{ e($agenda->description) }}"
                                    >Detail</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Agenda Detail -->
<div class="modal fade" id="agendaDetailModal" tabindex="-1" aria-labelledby="agendaDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agendaDetailModalLabel">Detail Agenda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-5 mb-3 mb-md-0">
            <img id="agendaModalImage" src="" alt="Agenda Image" class="img-fluid rounded w-100" style="object-fit:cover;max-height:320px;">
          </div>
          <div class="col-md-7">
            <h4 id="agendaModalTitle" class="fw-bold mb-1"></h4>
            <div class="mb-2 text-muted" id="agendaModalOrganizer"></div>
            <div class="mb-2"><i class="fa fa-calendar-alt me-2"></i><span id="agendaModalDate"></span></div>
            <div class="mb-2"><i class="fa fa-map-marker-alt me-2"></i><span id="agendaModalLocation"></span></div>
            <div class="mb-2" id="agendaModalRegistration"></div>
            <div class="mt-3" id="agendaModalDescription"></div>
          </div>
        </div>
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