@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="background: #edf8e9;">
    {{-- Hero Banner Section (Static) --}}
    <section class="hero-banner position-relative text-white mb-0 d-flex flex-column justify-content-center align-items-center h-100" style="background: url('{{ asset('images/banner_bg_2.jpg') }}') center center/cover no-repeat; min-height: 420px;">
        <div class="w-100">
            <h1 class="fw-bold mb-3 text-center lh-sm hero-title-responsive" style="text-shadow: 2px 2px 6px #000, 0 0 2px #fff, 0 0 8px #000;">
                Get A Job Live Better
            </h1>
        </div>
        <div class="w-100 d-flex justify-content-center mt-3 px-2 px-sm-3">
            <form action="{{ route('informasi.index') }}" method="GET" class="d-flex flex-column flex-sm-row align-items-stretch shadow rounded-pill bg-white p-2 gap-2 searchbar-responsive" style="max-width: 600px; width: 100%; overflow: hidden;">
                <input type="text" name="search" class="form-control border-0 rounded-pill ps-3 py-2 flex-grow-1 searchbar-input-responsive" placeholder="Cari informasi, statistik, atau publikasi..." aria-label="Cari informasi" style="font-size: 1rem; background: transparent; min-width:0;">
                <button class="btn btn-success rounded-pill px-4 d-flex align-items-center justify-content-center searchbar-btn-responsive" type="submit" style="font-size: 1rem; min-width: 90px;">
                    <i class="fa fa-search me-2"></i> Cari
                </button>
            </form>
        </div>
    </section>

    {{-- Karakteristik Lowongan Kerja Section --}}
    <section class="my-5 px-2 px-md-4 px-lg-5 overlap-hero" data-aos="fade-up">
        <div class="rounded-section-bg p-4 p-md-5">
            <h3 class="text-center mb-4">Karakteristik Lowongan Kerja</h3>
            <div class="row gx-3 gy-4 justify-content-center">
                @foreach($jobCharacteristics as $char)
                    <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                        <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <h5 class="fw-bold mb-2">{{ $char->title }}</h5>
                            <p class="mb-2 text-muted">{{ $char->description }}</p>
                            <div class="w-100" style="min-height: 350px;">
                                <div class="tableau-embed-wrapper w-100" style="min-height: 350px; overflow-x: auto;">
                                    <div style="min-width: 400px;">
                                        {!! $char->tableau_embed_code !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Statistic Cards Carousel (Floating over Banner) --}}
    <section class="stat-carousel-section position-relative" style="z-index: 10; margin-top: -90px;">
        <div class="container position-relative rounded-section-bg p-4 p-md-5" style="max-width:1200px;">
            <h3 class="text-center mb-4">Pasar Kerja Indonesia Dalam Angka</h3>
            <div class="d-flex align-items-center position-relative">
                <button id="statScrollPrev" class="btn btn-light shadow rounded-circle position-absolute start-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <div id="statScrollRow" class="d-flex px-7" style="scroll-behavior:smooth; gap:16px; width:100%; overflow-x:hidden;">
                    @foreach($statistics as $stat)
                        <a href="{{ route('informasi.index', ['type' => 'statistik', 'search' => $stat->title]) }}" class="text-decoration-none">
                            <div class="card shadow-sm stat-card text-center flex-shrink-0" style="max-width:260px; min-width:180px; cursor:pointer;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <div class="stat-icon mb-3">
                                        <i class="fa fa-chart-bar fa-2x text-success"></i>
                                    </div>
                                    <div class="stat-title fw-bold mb-1" style="font-size:1.1rem; color:#187C19;">{{ $stat->title }}</div>
                                    <div class="stat-value fw-bold mb-1" style="font-size:2.2rem; color:#222;">{{ $stat->value }} <span class="stat-unit" style="font-size:1.2rem;">{{ $stat->unit }}</span></div>
                                    @if($stat->description)
                                        <div class="stat-desc text-muted mt-1" style="font-size:0.95rem;">{{ $stat->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button id="statScrollNext" class="btn btn-light shadow rounded-circle position-absolute end-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
            <div class="d-flex justify-content-center mt-3" id="statDots"></div>
        </div>
    </section>


    {{-- Hero Section: Statistics --}}
    <section class="hero my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Pasar Kerja Indonesia Dalam Angka</h3>
        <div class="d-flex justify-content-center flex-wrap gap-3" style="gap:16px;">
            @foreach($heroStatistics as $stat)
                <div class="card shadow-sm stat-card text-center flex-shrink-0" style="width:90vw; max-width:260px; min-width:180px;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="stat-icon mb-3">
                            <i class="fa fa-chart-bar fa-2x text-success"></i>
                        </div>
                        <div class="stat-title fw-bold mb-1" style="font-size:1.1rem; color:#187C19;">{{ $stat->title }}</div>
                        <div class="stat-value fw-bold mb-1" style="font-size:2.2rem; color:#222;">{{ $stat->value }} <span class="stat-unit" style="font-size:1.2rem;">{{ $stat->unit }}</span></div>
                        @if($stat->description)
                            <div class="stat-desc text-muted mt-1" style="font-size:0.95rem;">{{ $stat->description }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Charts Section --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Karakteristik Pencari Kerja</h3>
        <div class="row gx-2 gy-3 justify-content-center">
            @foreach($charts as $chart)
                <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="card shadow-sm rounded-4 border-0" style="min-height: 280px; max-width: 370px;">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <h5 class="card-title mb-2" style="font-size:1.1rem;">{{ $chart->title }}</h5>
                            <p class="mb-2" style="font-size:0.97rem;">{{ $chart->description }}</p>
                            <canvas id="chart-{{ $chart->id }}" height="140"></canvas>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Top 5 Lists Section as Carousel --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Top 5 Lists</h3>
        <div class="row gx-3 gy-4 justify-content-center">
            @foreach($topLists as $list)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                    <div class="card shadow-sm rounded-4 border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                        <canvas id="top5-chart-{{ $list->type }}" height="180"></canvas>
                        <h4 class="fw-bold mb-2 mt-3" style="font-size:1.1rem;"><i class="fa {{ $list->meta['icon'] ?? '' }} me-2 text-success"></i>{{ $list->meta['title'] ?? ucfirst($list->type) }}</h4>
                        <p class="mb-2" style="font-size:0.97rem;">{{ $list->meta['desc'] ?? '' }}</p>
                        @if($list->date)
                            <div class="text-muted small">Data diperbarui pada {{ indo_date($list->date) }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Informasi Terbaru --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" id="informasi-terbaru" data-aos="fade-up">
        <h3 class="text-center mb-4">Informasi Terbaru</h3>
        <ul class="nav nav-tabs justify-content-center mb-4" id="infoTab" role="tablist" >
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="statistik-tab" data-bs-toggle="tab" data-bs-target="#statistik" type="button" role="tab" aria-controls="statistik" aria-selected="true">Tabel Statistik</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="publikasi-tab" data-bs-toggle="tab" data-bs-target="#publikasi" type="button" role="tab" aria-controls="publikasi" aria-selected="false">Publikasi</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="statistik" role="tabpanel" aria-labelledby="statistik-tab">
                @foreach($statistik as $info)
                    <a href="{{ route('informasi.index', ['type' => 'statistik', 'subject' => $info->subject, 'show' => $info->id]) }}" class="text-decoration-none">
                        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="cursor:pointer;">
                            <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-clipboard"></i></div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $info->title }}</div>
                                <div class="text-muted small">{{ indo_date($info->date) }}</div>
                            </div>
                            <div>
                                <i class="fa fa-arrow-right fa-lg text-success"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="tab-pane fade" id="publikasi" role="tabpanel" aria-labelledby="publikasi-tab">
                @foreach($publikasi as $info)
                    <a href="{{ route('informasi.index', ['type' => 'publikasi', 'subject' => $info->subject, 'show' => $info->id]) }}" class="text-decoration-none">
                        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="cursor:pointer;">
                            <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-book"></i></div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $info->title }}</div>
                                <div class="text-muted small">{{ indo_date($info->date) }}</div>
                            </div>
                            <div>
                                <i class="fa fa-arrow-right fa-lg text-success"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('informasi.index') }}" class="btn btn-outline-success rounded-pill px-4 py-2">Lihat Semua <i class="fa fa-arrow-right"></i></a>
        </div>
    </section>

    {{-- Contributions Section 
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <div class="text-center mb-2">
            <h3 class="fw-bold mb-1" style="font-size:2.5rem;">Kontribusi Pasker</h3>
            <h3 class="fw-bold mb-1" style="font-size:2.5rem;">Untuk Masyarakat Indonesia</h3>
        </div>
        <div class="row justify-content-center g-4">
            @foreach($contributions as $contrib)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="card shadow rounded-4 border-0 p-4 w-100 h-100 mx-auto d-flex flex-column align-items-center justify-content-center text-center">
                        <div class="stat-icon mb-3">
                            @if($contrib->icon === 'fa-users')
                                <!-- Example SVG for Matching -->
                                <svg width="40" height="40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="10" fill="#4ecb8f"/><path d="M13 25a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm14 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM10 28v-1a4 4 0 0 1 4-4h.5m11 5v-1a4 4 0 0 0-4-4h-.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif($contrib->icon === 'fa-graduation-cap')
                                <!-- Example SVG for Skills -->
                                <svg width="40" height="40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="10" fill="#4ecb8f"/><path d="M20 13l10 5-10 5-10-5 10-5Zm0 10v4m-7-4v2a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-2" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif($contrib->icon === 'fa-lightbulb')
                                <!-- Example SVG for Inovasi -->
                                <svg width="40" height="40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="10" fill="#4ecb8f"/><path d="M20 28v2m-4 0h8m-4-18a6 6 0 0 1 6 6c0 2.5-1.5 4.5-3 6a2 2 0 0 0-1 1.7V26h-4v-2.3a2 2 0 0 0-1-1.7c-1.5-1.5-3-3.5-3-6a6 6 0 0 1 6-6Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @elseif($contrib->icon === 'fa-briefcase')
                                <!-- Example SVG for Inklusif -->
                                <svg width="40" height="40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="10" fill="#4ecb8f"/><path d="M14 17v-2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2m-12 0h12m-12 0v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V17m-12 0v-2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            @else
                                <!-- Fallback icon -->
                                <svg width="40" height="40"><rect width="40" height="40" rx="10" fill="#4ecb8f"/></svg>
                            @endif
                        </div>
                        <div class="stat-title fw-bold mb-1" style="font-size:1.1rem; color:#187C19;">{{ $contrib->title }}</div>
                        <div class="stat-desc text-muted mt-1" style="font-size:0.95rem;">{{ $contrib->description }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section> --}}

    {{-- Services Section --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="font-size:2.2rem;">Layanan Ketenagakerjaan</h2>
            <p class="text-muted mb-0">Berbagai layanan untuk mendukung pasar kerja Indonesia</p>
        </div>
        <div class="row justify-content-center g-4">
            @foreach($services as $service)
                <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="card shadow rounded-4 border-0 p-4 w-100 h-100 mx-auto d-flex flex-column align-items-center justify-content-center text-center">
                        <div class="service-icon-bg mb-4 d-flex align-items-center justify-content-center mx-auto">
                            <i class="fa {{ $service->icon }} fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-dark text-center">{{ $service->title }}</h5>
                        <p class="text-dark mb-3 text-center" style="font-size:1rem;">{{ $service->description }}</p>
                        @if($service->link)
                            <div class="text-center mt-auto">
                                <a href="{{ $service->link }}" target="_blank" class="btn btn-success rounded-pill px-4">Kunjungi <i class="fa fa-arrow-right ms-1"></i></a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- News Section --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h2 class="fw-bold text-center mb-4" style="font-size:2rem;">Berita Terkini</h2>
        <div class="row g-4 align-items-stretch">
            @php
                $featuredNews = $news->first();
                $otherNews = $news->slice(1, 2);
            @endphp
            <div class="col-lg-7">
                @if($featuredNews)
                    <div class="position-relative h-100 rounded-4 overflow-hidden shadow-sm" style="min-height:340px;">
                        @if($featuredNews->image_url)
                            <img src="{{ $featuredNews->image_url }}" alt="{{ $featuredNews->title }}" class="w-100 h-100 object-fit-cover" style="min-height:340px;">
                        @endif
                        <a href="{{ route('news.DetailBerita', $featuredNews->id) }}" class="stretched-link"></a>
                        <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(0deg,rgba(0,0,0,0.7) 70%,rgba(0,0,0,0.1) 100%);">
                            <div class="text-white mb-1" style="font-size:1rem;">{{ indo_date($featuredNews->date) }}</div>
                            <h3 class="fw-bold text-white mb-2" style="font-size:1.5rem;">{{ $featuredNews->title }}</h3>
                            <p class="text-white mb-0" style="font-size:1.1rem;">{{ Str::limit($featuredNews->content, 120) }}</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-5 d-flex flex-column gap-4">
                @foreach($otherNews as $item)
                    <div class="d-flex flex-row rounded-4 shadow-sm bg-white overflow-hidden h-100 position-relative" style="min-height:150px;">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="object-fit-cover" style="width:160px; height:100%; object-fit:cover;">
                        @endif
                        <a href="{{ route('news.DetailBerita', $item->id) }}" class="stretched-link"></a>
                        <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <div class="text-muted mb-1" style="font-size:0.95rem;">{{ indo_date($item->date) }}</div>
                                <div class="fw-bold mb-1" style="font-size:1.1rem;">{{ $item->title }}</div>
                                <div class="text-muted" style="font-size:1rem;">{{ Str::limit($item->content, 80) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('news.index') }}" class="btn btn-outline-success rounded-pill px-4 py-2">
                Lihat Semua Berita <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </section>

    {{-- Testimonials Section --}}
    @php
        use Illuminate\Support\Collection;
        $testimonialChunks = $testimonials->chunk(4);
    @endphp
    <section class="my-5 pb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="fw-bold text-center mb-4" style="font-size:2rem;">Testimoni</h3>
        <div id="testiCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
            <div class="carousel-inner">
                @foreach($testimonialChunks as $chunkIndex => $chunk)
                    <div class="carousel-item @if($chunkIndex === 0) active @endif" data-aos="fade-up" data-aos-delay="{{ $chunkIndex * 100 }}">
                        <div class="row justify-content-center g-4">
                            @foreach($chunk as $testi)
                                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                    <div class="card h-100 shadow-sm border-0 rounded-4 p-3 text-center">
                                        @if($testi->photo_url)
                                            <img src="{{ $testi->photo_url }}" class="rounded-circle mx-auto mb-3" style="width:64px; height:64px; object-fit:cover;" alt="{{ $testi->name }}">
                                        @endif
                                        <blockquote class="blockquote mb-2" style="font-size:1rem;">
                                            <p class="mb-0">"{{ $testi->quote }}"</p>
                                        </blockquote>
                                        <footer class="blockquote-footer mt-2">
                                            <span class="fw-bold">{{ $testi->name }}</span><br>
                                            <span class="text-muted small">{{ $testi->position }} @ {{ $testi->company }}</span>
                                        </footer>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testiCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testiCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <div class="carousel-indicators mt-3">
                @foreach($testimonialChunks as $chunkIndex => $chunk)
                    <button type="button" data-bs-target="#testiCarousel" data-bs-slide-to="{{ $chunkIndex }}" @if($chunkIndex === 0) class="active" aria-current="true" @endif aria-label="Slide {{ $chunkIndex+1 }}"></button>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const row = document.getElementById('statScrollRow');
        const prev = document.getElementById('statScrollPrev');
        const next = document.getElementById('statScrollNext');
        const dots = document.getElementById('statDots');
        const cards = row.querySelectorAll('.stat-card');
        const cardWidth = 256; // width + gap
        let visible = Math.floor(row.offsetWidth / cardWidth) || 1;
        const total = cards.length;
        let current = 0;

        function updateDots() {
            dots.innerHTML = '';
            visible = Math.floor(row.offsetWidth / cardWidth) || 1;
            const dotCount = Math.ceil(total / visible);
            for (let i = 0; i < dotCount; i++) {
                const dot = document.createElement('span');
                dot.className = 'stat-dot' + (i === Math.floor(current / visible) ? ' active' : '');
                dot.addEventListener('click', () => {
                    row.scrollTo({ left: i * cardWidth * visible, behavior: 'smooth' });
                    current = i * visible;
                    updateDots();
                });
                dots.appendChild(dot);
            }
        }

        function scrollToCurrent() {
            row.scrollTo({ left: current * cardWidth, behavior: 'smooth' });
            updateDots();
        }

        prev.addEventListener('click', () => {
            current = Math.max(0, current - visible);
            scrollToCurrent();
        });
        next.addEventListener('click', () => {
            current = Math.min(total - visible, current + visible);
            scrollToCurrent();
        });

        row.addEventListener('scroll', () => {
            current = Math.round(row.scrollLeft / cardWidth);
            updateDots();
        });

        window.addEventListener('resize', () => {
            updateDots();
        });

        updateDots();

        // --- Chart.js for Trend Pencari Kerja ---
        const chartsData = @json($charts->mapWithKeys(function($chart) {
            return [$chart->id => json_decode($chart->data_json)];
        }));
        Object.entries(chartsData).forEach(([id, chartData]) => {
            const ctx = document.getElementById('chart-' + id);
            if (ctx && chartData) {
                // Define a colorful palette (repeat if more bars)
                const colorPalette = [
                    'rgba(255, 99, 132, 0.7)',   // Red
                    'rgba(54, 162, 235, 0.7)',   // Blue
                    'rgba(255, 206, 86, 0.7)',   // Yellow
                    'rgba(75, 192, 192, 0.7)',   // Teal
                    'rgba(153, 102, 255, 0.7)'   // Purple
                ];
                const borderPalette = [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ];
                // Repeat colors if there are more bars than colors
                const bgColors = chartData.data.map((_, i) => colorPalette[i % colorPalette.length]);
                const borderColors = chartData.data.map((_, i) => borderPalette[i % borderPalette.length]);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: '',
                            data: chartData.data,
                            backgroundColor: bgColors,
                            borderColor: borderColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        });

        // --- Chart.js for Top 5 Section ---
        const top5Data = {
            @foreach($topLists as $list)
                '{{ $list->type }}': {
                    labels: {!! json_encode(collect($list->items)->pluck('name')) !!},
                    data: {!! json_encode(collect($list->items)->pluck('count')) !!}
                },
            @endforeach
        };
        Object.entries(top5Data).forEach(([type, chartData]) => {
            const ctx = document.getElementById('top5-chart-' + type);
            if (ctx && chartData.labels.length) {
                // Define a colorful palette (repeat if more bars)
                const colorPalette = [
                    'rgba(255, 99, 132, 0.7)',   // Red
                    'rgba(54, 162, 235, 0.7)',   // Blue
                    'rgba(255, 206, 86, 0.7)',   // Yellow
                    'rgba(75, 192, 192, 0.7)',   // Teal
                    'rgba(153, 102, 255, 0.7)'   // Purple
                ];
                const borderPalette = [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ];
                // Repeat colors if there are more bars than colors
                const bgColors = chartData.data.map((_, i) => colorPalette[i % colorPalette.length]);
                const borderColors = chartData.data.map((_, i) => borderPalette[i % borderPalette.length]);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: '',
                            data: chartData.data,
                            backgroundColor: bgColors,
                            borderColor: borderColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        });
    });
    </script>
@endsection

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
<style>
.contrib-card-v3 {
    border-radius: 1.5rem;
    background: #fff;
    /* Stronger, more visible green shadow */
    box-shadow: 0 8px 32px 0 rgba(76,203,143,0.35), 0 1.5px 6px 0 rgba(0,0,0,0.04);
    transition: box-shadow 0.2s, transform 0.2s;
    min-height: 340px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    border: none;
    padding: 2rem 1.5rem;
}
.contrib-card-v3:hover {
    box-shadow: 0 16px 48px 0 rgba(76,203,143,0.45), 0 3px 12px 0 rgba(0,0,0,0.08);
    transform: translateY(-6px) scale(1.03);
    z-index: 2;
}
.svg-icon-bg {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: none;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}
.service-card-v2 {
    border-radius: 1.5rem;
    background: #fff;
    box-shadow: 0 8px 32px 0 rgba(40,167,69,0.13), 0 1.5px 6px 0 rgba(0,0,0,0.04);
    transition: box-shadow 0.2s, transform 0.2s;
    min-height: 340px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    border: none;
    padding: 2rem 1.5rem;
}
.service-card-v2:hover {
    box-shadow: 0 16px 48px 0 rgba(40,167,69,0.22), 0 3px 12px 0 rgba(0,0,0,0.08);
    transform: translateY(-6px) scale(1.03);
    z-index: 2;
}
.service-icon-bg {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: linear-gradient(135deg, #4ecb8f 0%, #28a745 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}
.object-fit-cover {
    object-fit: cover;
}
.card.blockquote {
    font-size: 1rem;
}
.stat-carousel-section {
    background: transparent;
    padding-top: 2rem;
    padding-bottom: 2rem;
}
.swiper {
    width: 100%;
    padding-bottom: 32px;
}
.swiper-wrapper {
    display: flex;
}
.swiper-slide {
    display: block;
}
.stat-card {
    border-radius: 1.5rem;
    padding: 2.5rem 1.5rem;
    margin: 0.5rem 0;
    min-height: 220px;
    width: 260px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    /* Remove background and box-shadow here! */
}
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #B8D53D 0%, #69B41E 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: #fff;
}
.swiper-pagination-bullet {
    background: var(--primary-green) !important;
    opacity: 0.5;
}
.swiper-pagination-bullet-active {
    background: var(--secondary-green) !important;
    opacity: 1;
}
.swiper-button-next, .swiper-button-prev {
    color: var(--primary-green);
    top: 45%;
}
#statScrollRow::-webkit-scrollbar {
    display: none;
}
.stat-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #B8D53D;
    margin: 0 4px;
    opacity: 0.4;
    transition: opacity 0.2s;
    display: inline-block;
}
.stat-dot.active {
    background: #187C19;
    opacity: 1;
}
.stat-hero-card {
    border-radius: 2rem;
    padding: 1rem 1rem 1.5rem 1rem;
    transition: box-shadow 0.2s, transform 0.2s;
    min-height: 200px;
    max-width: 320px;
    width: 50%;
    position: relative;
}
.stat-hero-card:hover {
    transform: translateY(-4px) scale(1.04);
    z-index: 2;
}
.stat-hero-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #B8D53D 0%, #69B41E 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.3rem;
    box-shadow: 0 2px 8px 0 rgba(40,167,69,0.18);
    position: absolute;
    top: -22px;
    left: 50%;
    transform: translateX(-50%);
}
.stat-hero-flex-container {
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    align-items: stretch;
    gap: 2rem;
    flex-direction: row;
}
.stat-hero-flex-item {
    flex: 1 1 0;
    max-width: 320px;
    min-width: 220px;
    display: flex;
    flex-direction: column;
    justify-content: stretch;
}
.stat-hero-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}
.stat-hero-card .card-body {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
@media (max-width: 991px) {
    .stat-hero-flex-container {
        flex-wrap: wrap;
        gap: 1rem;
    }
    .stat-hero-flex-item {
        max-width: 100%;
        min-width: 180px;
    }
}
@media (max-width: 767px) {
    .stat-hero-flex-container {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    .stat-hero-flex-item {
        width: 100%;
        max-width: 100%;
    }
}
.hero-title-responsive {
    font-size: 2.1rem;
}
@media (min-width: 576px) {
    .hero-title-responsive {
        font-size: 2.5rem;
    }
}
@media (min-width: 768px) {
    .hero-title-responsive {
        font-size: 3rem;
    }
}
@media (max-width: 575.98px) {
    .hero-title-responsive {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        text-shadow: 1px 1px 3px #000, 0 0 1px #fff, 0 0 4px #000;
    }
    .searchbar-responsive {
        flex-direction: column !important;
        border-radius: 1rem !important;
        padding: 0.75rem 0.5rem !important;
        gap: 0.5rem !important;
    }
    .searchbar-input-responsive {
        border-radius: 0.75rem !important;
        width: 100% !important;
        margin-bottom: 0.5rem !important;
    }
    .searchbar-btn-responsive {
        border-radius: 0.75rem !important;
        width: 100% !important;
        min-width: 0 !important;
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
}
.stat-carousel-section a {
    display: block;
    height: 100%;
    text-decoration: none;
    padding: 0;
    margin: 0;
}
.stat-carousel-section .stat-card {
    height: 100%;
    min-height: 220px;
    width: 260px;
    max-width: 260px;
    min-width: 180px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.tableau-embed-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%;
}
.tableau-embed-wrapper iframe {
    min-width: 400px;
    width: 100%;
    border: none;
    display: block;
}
.rounded-section-bg {
    background: #edf8e9;
    border-radius: 2rem;
    box-shadow: 0 8px 32px 0 rgba(76,203,143,0.10);
}
.overlap-hero {
    margin-top: -140px; /* Overlap by about 1/3 of the section height, adjust as needed */
    position: relative;
    z-index: 2;
}
@media (max-width: 767px) {
    .overlap-hero {
        margin-top: -60px; /* Less overlap on mobile */
    }
}
</style>
@endpush 