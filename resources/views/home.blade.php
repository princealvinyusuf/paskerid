@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 home-ocean-theme">
    @php
        $welcomePopupItems = collect($welcomePopups ?? [])->values();
        $showWelcomePopup = $welcomePopupItems->isNotEmpty();
    @endphp

    @if($showWelcomePopup)
        <div class="modal fade" id="welcomePopupModal" tabindex="-1" aria-labelledby="welcomePopupModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered welcome-popup-dialog">
                <div class="modal-content welcome-popup-modal">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="welcomePopupModalLabel">Notifikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <div id="welcomePopupCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                            @if($welcomePopupItems->count() > 1)
                                <div class="carousel-indicators position-static mb-3">
                                    @foreach($welcomePopupItems as $popupItem)
                                        <button type="button"
                                            data-bs-target="#welcomePopupCarousel"
                                            data-bs-slide-to="{{ $loop->index }}"
                                            class="{{ $loop->first ? 'active' : '' }}"
                                            aria-current="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $loop->iteration }}"></button>
                                    @endforeach
                                </div>
                            @endif

                            <div class="carousel-inner">
                                @foreach($welcomePopupItems as $popupItem)
                                    @php
                                        $popupImageSrc = '';
                                        $popupPayload = trim((string) ($popupItem->image_base64 ?? ''));
                                        if ($popupPayload !== '') {
                                            $popupMime = trim((string) ($popupItem->mime_type ?? ''));
                                            $popupMime = $popupMime !== '' ? $popupMime : 'image/jpeg';
                                            if (
                                                \Illuminate\Support\Str::startsWith($popupPayload, '/') ||
                                                \Illuminate\Support\Str::startsWith($popupPayload, ['http://', 'https://', 'data:'])
                                            ) {
                                                $popupImageSrc = $popupPayload;
                                            } else {
                                                $popupImageSrc = 'data:' . $popupMime . ';base64,' . $popupPayload;
                                            }
                                        }
                                    @endphp
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        @if($popupImageSrc !== '')
                                            <div class="text-center mb-3">
                                                <img src="{{ $popupImageSrc }}" alt="Welcome image" class="img-fluid rounded-3 welcome-popup-image">
                                            </div>
                                        @endif
                                        <h4 class="fw-bold mb-2 text-center">{{ $popupItem->title ?? '' }}</h4>
                                        <p class="text-muted mb-0 text-center">{{ $popupItem->subtitle ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>

                            @if($welcomePopupItems->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#welcomePopupCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#welcomePopupCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Hero Banner Section --}}
    <section class="home-hero" style="--home-hero-image: url('{{ asset('images/banner_bg_2.jpg') }}');">
        <span class="home-hero-grid" aria-hidden="true"></span>
        <span class="home-hero-glow home-hero-glow-one" aria-hidden="true"></span>
        <span class="home-hero-glow home-hero-glow-two" aria-hidden="true"></span>

        <div class="home-hero-inner">
            <div class="home-hero-copy" data-aos="fade-right">
                <span class="home-hero-eyebrow">
                    <i class="fa-solid fa-chart-line"></i>
                    Pusat Pasar Kerja
                </span>
                <h1 class="hero-title-responsive">
                    <span>Get A Job</span>
                    <span>Live Better</span>
                </h1>

                <form action="{{ route('informasi.index') }}" method="GET" class="home-hero-search searchbar-responsive">
                    <label for="homeHeroSearch" class="visually-hidden">Cari informasi</label>
                    <span class="home-hero-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input id="homeHeroSearch"
                        type="text"
                        name="search"
                        class="searchbar-input-responsive"
                        placeholder="Cari informasi, statistik, atau publikasi..."
                        aria-label="Cari informasi">
                    <button class="searchbar-btn-responsive" type="submit">
                        <span>Cari</span><i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="home-hero-points" aria-label="Keunggulan Pasker ID">
                    <span><i class="fa-solid fa-circle-check"></i> Data terpercaya</span>
                    <span><i class="fa-solid fa-circle-check"></i> Informasi terkini</span>
                    <span><i class="fa-solid fa-circle-check"></i> Peluang karier</span>
                </div>
            </div>

            <div class="home-hero-visual" aria-hidden="true" data-aos="fade-left">
                <div class="home-hero-signal">
                    <span class="home-hero-signal-icon"><i class="fa-solid fa-arrow-trend-up"></i></span>
                    <span class="home-hero-signal-bars">
                        <i></i><i></i><i></i><i></i><i></i>
                    </span>
                </div>
                <span class="home-hero-orbit home-hero-orbit-one"></span>
                <span class="home-hero-orbit home-hero-orbit-two"></span>
            </div>
        </div>
    </section>
    <section class="running-text-strip" aria-label="Informasi berjalan">
        <span class="running-text-label" aria-hidden="true"><i class="fa-solid fa-bullhorn"></i></span>
        <div class="running-text-track">
            <span class="running-text-item">Dengan Integritas dan Pelayanan Prima, Pusat Pasar Kerja Siap Menuju WBK dan WBBM — Mari Dukung Kami</span>
            <span class="running-text-item" aria-hidden="true">Dengan Integritas dan Pelayanan Prima, Pusat Pasar Kerja Siap Menuju WBK dan WBBM — Mari Dukung Kami</span>
        </div>
    </section>

    {{-- Highlight Pasar Kerja Section --}}
    <section class="stat-carousel-section market-highlight-section position-relative">
        <span class="market-highlight-shape market-highlight-shape-one" aria-hidden="true"></span>
        <span class="market-highlight-shape market-highlight-shape-two" aria-hidden="true"></span>
        <div class="container-fluid position-relative px-2 px-sm-3 px-lg-4">
            <div class="market-highlight-heading" data-aos="fade-up">
                <span class="market-highlight-heading-icon"><i class="fa-solid fa-chart-column"></i></span>
                <h3>Highlight Pasar Kerja</h3>
                <span class="market-highlight-heading-line" aria-hidden="true"></span>
            </div>

            {{-- First Carousel --}}
            <div class="market-carousel-panel market-carousel-panel-blue" data-aos="fade-up">
                <button id="statScrollPrev" class="market-carousel-button market-carousel-button-prev" type="button" aria-label="Sebelumnya">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <div id="statScrollRow" class="market-carousel-track">
                    @foreach($statistics as $stat)
                        <a href="{{ route('informasi.index', ['type' => 'statistik', 'search' => $stat->title]) }}" class="market-stat-link">
                            <article class="market-stat-card">
                                <div class="market-stat-top">
                                    <span class="market-stat-icon"><i class="fa {{ $stat->logo ?? 'fa-chart-bar' }}"></i></span>
                                    <span class="market-stat-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="stat-title market-stat-title">{{ $stat->title }}</div>
                                <div class="market-stat-reading">
                                    <span class="stat-value market-stat-value">{{ $stat->value }}</span>
                                    @if($stat->unit)
                                        <span class="stat-unit market-stat-unit">{{ $stat->unit }}</span>
                                    @endif
                                </div>
                                @if($stat->description)
                                    <div class="stat-desc market-stat-description">{{ $stat->description }}</div>
                                @endif
                                <span class="market-stat-action"><i class="fa-solid fa-arrow-right"></i></span>
                            </article>
                        </a>
                    @endforeach
                </div>
                <button id="statScrollNext" class="market-carousel-button market-carousel-button-next" type="button" aria-label="Berikutnya">
                    <i class="fa fa-chevron-right"></i>
                </button>
                <div class="market-carousel-dots" id="statDots"></div>
            </div>

            {{-- Second Carousel --}}
            <div class="market-carousel-panel market-carousel-panel-green" data-aos="fade-up">
                <button id="highlightStatScrollPrev" class="market-carousel-button market-carousel-button-prev" type="button" aria-label="Sebelumnya">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <div id="highlightStatScrollRow" class="market-carousel-track">
                    @foreach($highlightStatistics as $stat)
                        <a href="{{ route('informasi.index', ['type' => 'statistik', 'search' => $stat->title]) }}" class="market-stat-link">
                            <article class="market-stat-card">
                                <div class="market-stat-top">
                                    <span class="market-stat-icon"><i class="fa {{ $stat->logo ?? 'fa-star' }}"></i></span>
                                    <span class="market-stat-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="stat-title market-stat-title">{{ $stat->title }}</div>
                                <div class="market-stat-reading">
                                    <span class="stat-value market-stat-value">{{ $stat->value }}</span>
                                    @if($stat->unit)
                                        <span class="stat-unit market-stat-unit">{{ $stat->unit }}</span>
                                    @endif
                                </div>
                                @if($stat->description)
                                    <div class="stat-desc market-stat-description">{{ $stat->description }}</div>
                                @endif
                                <span class="market-stat-action"><i class="fa-solid fa-arrow-right"></i></span>
                            </article>
                        </a>
                    @endforeach
                </div>
                <button id="highlightStatScrollNext" class="market-carousel-button market-carousel-button-next" type="button" aria-label="Berikutnya">
                    <i class="fa fa-chevron-right"></i>
                </button>
                <div class="market-carousel-dots" id="highlightStatDots"></div>
            </div>

            {{-- Third Carousel (Secondary Highlights) --}}
            <div class="market-source-panel home-ocean-banner-pink" data-aos="fade-up">
                <div class="market-source-intro">
                    <h2>Informasi bersumber dari</h2>
                    <span class="thelogo-wrapper">
                        <img src="{{ asset('images/services/karirhub_logo.png') }}" alt="Karirhub" class="img-fluid thelogo-inline">
                    </span>
                </div>
                <div class="market-source-carousel">
                    <button id="highlightStat2ScrollPrev" class="market-carousel-button market-carousel-button-prev" type="button" aria-label="Sebelumnya">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <div id="highlightStat2ScrollRow" class="market-carousel-track">
                        @foreach($highlightStatistics2 ?? [] as $stat)
                            <a href="{{ route('informasi.index', ['type' => 'statistik', 'search' => $stat->title]) }}" class="market-stat-link">
                                <article class="market-stat-card">
                                    <div class="market-stat-top">
                                        <span class="market-stat-icon"><i class="fa {{ $stat->logo ?? 'fa-diamond' }}"></i></span>
                                        <span class="market-stat-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="stat-title market-stat-title">{{ $stat->title }}</div>
                                    <div class="market-stat-reading">
                                        <span class="stat-value market-stat-value">{{ $stat->value }}</span>
                                        @if($stat->unit)
                                            <span class="stat-unit market-stat-unit">{{ $stat->unit }}</span>
                                        @endif
                                    </div>
                                    @if($stat->description)
                                        <div class="stat-desc market-stat-description">{{ $stat->description }}</div>
                                    @endif
                                    <span class="market-stat-action"><i class="fa-solid fa-arrow-right"></i></span>
                                </article>
                            </a>
                        @endforeach
                    </div>
                    <button id="highlightStat2ScrollNext" class="market-carousel-button market-carousel-button-next" type="button" aria-label="Berikutnya">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                    <div class="market-carousel-dots market-carousel-dots-light" id="highlightStat2Dots"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- {{-- Karakteristik Lowongan Kerja Section --}}
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5 overlap-hero" data-aos="fade-up">
        <h3 class="text-center mb-4">Insight Lowongan Kerja</h3>
        <style>
            /* Responsive Tableau Embed */
            .tableauPlaceholder,
            .tableauPlaceholder object {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
                height: 427px !important; /* Fixed height as requested */
            }
        </style>
        <div class="row gx-3 gy-4 justify-content-center">
            @foreach($jobCharacteristics as $char)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                    <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                        <h5 class="fw-bold mb-2">{{ $char->title }}</h5>
                        <p class="mb-2 text-muted">{{ $char->description }}</p>
                        <div class="w-100" style="min-height: 350px;">
                            <div class="tableau-embed-wrapper w-100" style="min-height: 350px;">
                                <div style="width: 100%;">
                                    {!! str_replace([
                                        "vizElement.style.width='400px';",
                                        'vizElement.style.width = \"400px\";',
                                        'vizElement.style.width = \"100%\";',
                                        'vizElement.style.height=\'427px\';',
                                        'vizElement.style.height = \"427px\";'
                                    ], [
                                        "vizElement.style.width='100%';",
                                        'vizElement.style.width = "100%";',
                                        'vizElement.style.width = "100%";',
                                        "vizElement.style.height='427px';",
                                        'vizElement.style.height = "427px";'
                                    ], $char->tableau_embed_code) !!}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('informasi_pasar_kerja.index') }}#section1Cards" class="btn btn-success rounded-pill px-4">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section> -->


     <!-- {{-- Karakteristik Lowongan Kerja 2 Section --}}
     <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Insight Pencari Kerja</h3>
        <div class="row gx-3 gy-4 justify-content-center">
            @foreach($jobCharacteristics2 as $char)
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
                        <div class="mt-3">
                            <a href="{{ route('informasi_pasar_kerja.index') }}#section2Cards" class="btn btn-success rounded-pill px-4">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section> -->

    <!-- {{-- Karakteristik Lowongan Kerja 3 Section --}}
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Kebutuhan Tenaga Kerja 3</h3>
        <div class="row gx-3 gy-4 justify-content-center">
            @foreach($jobCharacteristics3 as $char)
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
    </section> -->

<!-- 
    {{-- Hero Section: Statistics --}}
    <section class="hero my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Data Ketenagakerjaan</h3>
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
    </section> -->

    <!-- {{-- Charts Section --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Persediaan Tenaga Kerja</h3>
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
    </section> -->

    <!-- {{-- Top 5 Lists Section as Carousel --}}
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
    </section> -->

    {{-- Data dan Informasi --}}
    @php
        $informationCategories = [
            ['id' => 'spark', 'label' => 'SPARK', 'fullLabel' => 'Seputar Pasar Kerja', 'icon' => 'fa-bolt', 'items' => $spark, 'tone' => 'blue'],
            ['id' => 'infografis-sipk', 'label' => 'Infografis SIPK', 'fullLabel' => 'Infografis SIPK', 'icon' => 'fa-chart-pie', 'items' => $infografisSIPK, 'tone' => 'purple'],
            ['id' => 'angkatan-kerja', 'label' => 'Angkatan Kerja', 'fullLabel' => 'Angkatan Kerja', 'icon' => 'fa-users', 'items' => $angkatanKerja, 'tone' => 'cyan'],
            ['id' => 'regulasi', 'label' => 'Regulasi', 'fullLabel' => 'Pedoman & Regulasi', 'icon' => 'fa-scale-balanced', 'items' => $regulasi, 'tone' => 'orange'],
            ['id' => 'lmir', 'label' => 'LMIR', 'fullLabel' => 'Labour Market Intelligence Report', 'icon' => 'fa-book-open', 'items' => $lmir, 'tone' => 'green'],
            ['id' => 'infografis-job-fair', 'label' => 'Job Fair', 'fullLabel' => 'Infografis Job Fair', 'icon' => 'fa-calendar-day', 'items' => $infografisJobFair, 'tone' => 'pink'],
        ];
    @endphp
    <section class="information-showcase my-5 mx-2 mx-md-4 mx-lg-5" id="informasi-terbaru" data-aos="fade-up">
        <span class="information-orb information-orb-one" aria-hidden="true"></span>
        <span class="information-orb information-orb-two" aria-hidden="true"></span>

        <div class="information-showcase-inner">
            <div class="information-heading">
                <div>
                    <span class="information-eyebrow"><i class="fa-solid fa-chart-line"></i> Wawasan Pasar Kerja</span>
                    <h2>Data dan Informasi</h2>
                    <p>Temukan publikasi, infografis, dan laporan terbaru seputar pasar kerja Indonesia.</p>
                </div>
                <a href="{{ route('informasi.index') }}" class="information-all-link d-none d-md-inline-flex">
                    Jelajahi semua <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>

            <ul class="nav information-tabs" id="infoTab" role="tablist" aria-label="Kategori data dan informasi">
                @foreach($informationCategories as $category)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                            id="{{ $category['id'] }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{ $category['id'] }}"
                            type="button"
                            role="tab"
                            aria-controls="{{ $category['id'] }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            <i class="fa-solid {{ $category['icon'] }}"></i>
                            <span>{{ $category['label'] }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content information-content">
                @foreach($informationCategories as $category)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                        id="{{ $category['id'] }}"
                        role="tabpanel"
                        aria-labelledby="{{ $category['id'] }}-tab"
                        tabindex="0">
                        <div class="information-category-meta">
                            <span class="information-category-icon tone-{{ $category['tone'] }}">
                                <i class="fa-solid {{ $category['icon'] }}"></i>
                            </span>
                            <div>
                                <span class="information-category-kicker">Kategori pilihan</span>
                                <h3>{{ $category['fullLabel'] }}</h3>
                            </div>
                            <span class="information-count">{{ $category['items']->count() }} publikasi</span>
                        </div>

                        @if($category['items']->isNotEmpty())
                            <div class="information-grid">
                                @foreach($category['items'] as $info)
                                    <a href="{{ route('informasi.index', ['subject' => $info->subject, 'show' => $info->id]) }}"
                                        class="information-card tone-{{ $category['tone'] }}">
                                        <div class="information-card-top">
                                            <span class="information-card-icon">
                                                <i class="fa-solid {{ $category['icon'] }}"></i>
                                            </span>
                                            <span class="information-card-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <div class="information-card-body">
                                            <span class="information-card-date">
                                                <i class="fa-regular fa-calendar"></i> {{ indo_date($info->date) }}
                                            </span>
                                            <h4>{{ $info->title }}</h4>
                                        </div>
                                        <div class="information-card-footer">
                                            <span>Baca selengkapnya</span>
                                            <span class="information-card-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="information-empty">
                                <i class="fa-regular fa-folder-open"></i>
                                <p>Belum ada publikasi dalam kategori ini.</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4 d-md-none">
                <a href="{{ route('informasi.index') }}" class="information-all-link">
                    Jelajahi semua <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
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
            <h2 class="fw-bold" style="font-size:2.2rem;">Layanan Pasar Kerja</h2>
        </div>
        <div class="row justify-content-center g-4">
            @foreach($services as $service)
                @php
                    $normalizedServiceTitle = preg_replace('/[^a-z0-9]+/', '', strtolower(trim((string) $service->title)));
                    $hideServiceCard = in_array($normalizedServiceTitle, ['talenthub', 'jobfair', 'jobfairvirtual'], true);
                @endphp
                @continue($hideServiceCard)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="card service-card-compact shadow rounded-4 border-0 p-3 w-100 h-100 mx-auto d-flex flex-column align-items-center justify-content-center text-center">
                        <div class="mb-3 d-flex align-items-center justify-content-center mx-auto service-logo-container">
                            <img src="{{ asset('images/services/' . $service->logo) }}" alt="{{ $service->title }} Logo" class="service-logo-img">
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
        <div class="text-center mt-4" >
            <a href="{{ route('virtual-karir.index') }}" class="btn btn-outline-success rounded-pill px-4 py-2">
                Lihat Layanan Lainnya <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </section>

    {{-- for ads --}}
 <section class="my-5 py-5 text-white rounded-4 mx-5 home-ocean-banner" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center">
            {{-- Bagian kiri --}}
            <div class="col-md-4 mb-4 mb-md-0">
                <a href="https://karirhub.kemnaker.go.id/" target="_blank">
    <img src="{{ asset('images/services/karirhub_logo.png') }}" alt="Karirhub" class="img-fluid" style="max-height: 50px;">
                </a>
                <h2 class="fw-bold" style="font-size:3rem;">Lowongan Kerja Terkini Buat Kamu</h2>
            </div>

            {{-- Bagian kanan --}}
            <div class="col-md-8 position-relative">
                <div id="jobCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        @foreach ($ads->chunk(3) as $chunkIndex => $adChunk)
                            <div class="carousel-item @if($chunkIndex === 0) active @endif">
                                <div class="row g-3">
                                    @foreach ($adChunk as $ad)
                                        <div class="col-md-4">
                                            <a href="https://karirhub.kemnaker.go.id/" class="text-decoration-none text-dark">
                                            <div class="job-card bg-white text-dark shadow-sm rounded-4 border p-3 h-100">
                                                <div class="text-center mb-3">
                                                    @php
                                                        $rawImage = trim((string) ($ad->image_base64 ?? ''));
                                                        $rawMime = strtolower(trim((string) ($ad->mime_type ?? '')));
                                                        $adImageSrc = '';

                                                        $normalizeMime = static function (string $mime): string {
                                                            $mime = strtolower(trim($mime));
                                                            $mime = ltrim($mime, '.');
                                                            $map = [
                                                                'jpg' => 'image/jpeg',
                                                                'jpeg' => 'image/jpeg',
                                                                'png' => 'image/png',
                                                                'gif' => 'image/gif',
                                                                'webp' => 'image/webp',
                                                                'svg' => 'image/svg+xml',
                                                            ];
                                                            if (isset($map[$mime])) {
                                                                return $map[$mime];
                                                            }
                                                            return str_contains($mime, '/') ? $mime : '';
                                                        };

                                                        if ($rawImage !== '') {
                                                            if (\Illuminate\Support\Str::startsWith($rawImage, 'data:')) {
                                                                [$meta, $payload] = array_pad(explode(',', $rawImage, 2), 2, '');
                                                                if ($payload !== '') {
                                                                    $detectedMime = '';
                                                                    if (preg_match('/^data:([^;]+);base64$/i', $meta, $matches) === 1) {
                                                                        $detectedMime = $normalizeMime((string) ($matches[1] ?? ''));
                                                                    }
                                                                    if ($detectedMime === '') {
                                                                        $detectedMime = $normalizeMime($rawMime);
                                                                    }
                                                                    if ($detectedMime === '') {
                                                                        $detectedMime = 'image/jpeg';
                                                                    }
                                                                    $adImageSrc = "data:{$detectedMime};base64,{$payload}";
                                                                }
                                                            } else {
                                                                $detectedMime = $normalizeMime($rawMime);
                                                                if ($detectedMime === '') {
                                                                    $detectedMime = 'image/jpeg';
                                                                }
                                                                $adImageSrc = "data:{$detectedMime};base64,{$rawImage}";
                                                            }
                                                        }
                                                    @endphp

                                                    @if($adImageSrc !== '')
                                                        <img src="{{ $adImageSrc }}"
                                                            alt="{{ $ad->company_name }}"
                                                            class="rounded" style="height: 50px; object-fit: contain;">
                                                    @elseif(!empty($ad->company_logo_uri))
                                                        <img src="{{ $ad->company_logo_uri }}"
                                                            alt="{{ $ad->company_name }}"
                                                            class="rounded" style="height: 50px; object-fit: contain;">
                                                    @else
                                                        <img src="{{ asset('images/services/karirhub.png') }}"
                                                            alt="{{ $ad->company_name }}"
                                                            class="rounded" style="height: 50px; object-fit: contain;">
                                                    @endif
                                                </div>
                                                <h6 class="fw-bold text-center">{{ \Illuminate\Support\Str::limit($ad->job_title, 15, '...') }}</h6>
                                                <p class="text-center mb-1 text-muted small">{{ \Illuminate\Support\Str::limit($ad->company_name, 20, '...') }}</p>
                                                <p class="text-center mb-1 text-secondary small">
                                                    {{ strtoupper($ad->city ?? 'KOTA BELUM DIISI') }}
                                                    <br>
                                                    <!-- {{ strtoupper(\Illuminate\Support\Str::limit($ad->province ?? 'PROVINSI BELUM DIISI', 20, '...')) }} -->
                                                </p>
                                                

                                                <div class="text-center mt-2 mb-2">
                                                    <small class="text-muted">Kisaran Gaji</small>
                                                    @php
                                                        $salaryMin = (float) ($ad->salary_min ?? 0);
                                                        $salaryMax = (float) ($ad->salary_max ?? 0);
                                                    @endphp
                                                    @if ((int) ($ad->secret ?? 1) === 1)
                                                        <div class="fw-semibold text-primary">Dirahasiakan</div>
                                                    @elseif($salaryMin <= 0 && $salaryMax <= 0)
                                                        <div class="fw-semibold text-primary">Tidak Dicantumkan</div>
                                                    @elseif($salaryMin > 0 && $salaryMax <= 0)
                                                        <div class="fw-semibold text-primary">
                                                            Rp{{ number_format($salaryMin, 0, ',', '.') }}
                                                        </div>
                                                    @elseif($salaryMax > 0 && $salaryMin <= 0)
                                                        <div class="fw-semibold text-primary">
                                                            Rp{{ number_format($salaryMax, 0, ',', '.') }}
                                                        </div>
                                                    @else
                                                        <div class="fw-semibold text-primary">
                                                            Rp{{ number_format($salaryMin, 0, ',', '.') }} -
                                                            Rp{{ number_format($salaryMax, 0, ',', '.') }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="card-footer border-top pt-2 bg-transparent d-flex justify-content-between align-items-center small text-muted">
                                                    <span>Lowongan dari</span>
                                                    <img src="{{ asset('images/services/karirhub.png') }}" alt="Karirhub" height="18">
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>





    {{-- DATASET Section --}}
    @php
        $datasetCategoryMeta = [
            'Pencari Kerja' => ['tone' => 'amber', 'fallbackIcon' => 'fa-user-group'],
            'Pemberi Kerja' => ['tone' => 'blue', 'fallbackIcon' => 'fa-building'],
            'Lowongan Kerja' => ['tone' => 'green', 'fallbackIcon' => 'fa-briefcase'],
        ];
    @endphp
    <section class="dataset-showcase my-5 mx-2 mx-md-4 mx-lg-5" data-aos="fade-up">
        <span class="dataset-showcase-decoration dataset-showcase-decoration-one" aria-hidden="true"></span>
        <span class="dataset-showcase-decoration dataset-showcase-decoration-two" aria-hidden="true"></span>

        <div class="dataset-showcase-inner">
            <div class="dataset-showcase-heading">
                <span class="dataset-showcase-heading-icon"><i class="fa-solid fa-database"></i></span>
                <h2>Dataset Pasar Kerja</h2>
                <span class="dataset-showcase-heading-line" aria-hidden="true"></span>
            </div>

            <div class="dataset-category-grid">
                @foreach($datasets as $category => $cards)
                    @php
                        $categoryMeta = $datasetCategoryMeta[$category] ?? ['tone' => 'blue', 'fallbackIcon' => 'fa-table'];
                        $categoryIcon = $cards->first()->icon ?: $categoryMeta['fallbackIcon'];
                    @endphp
                    <article class="dataset-category dataset-tone-{{ $categoryMeta['tone'] }}"
                        data-aos="fade-up"
                        data-aos-delay="{{ $loop->index * 100 }}">
                        <header class="dataset-category-header">
                            <span class="dataset-category-icon">
                                <i class="fa {{ $categoryIcon }}"></i>
                            </span>
                            <h3><span>Dataset</span> {{ $category }}</h3>
                            <span class="dataset-category-count">{{ str_pad($cards->count(), 2, '0', STR_PAD_LEFT) }}</span>
                        </header>

                        <div class="dataset-card-list">
                            @foreach($cards as $card)
                                <div class="dataset-item">
                                    <div class="dataset-item-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                                    <h4>{{ $card->title }}</h4>
                                    @if($card->description)
                                        <p class="dataset-item-description">{{ $card->description }}</p>
                                    @endif
                                    <div class="dataset-item-meta">
                                        <span><i class="fa-solid fa-location-dot"></i> {{ $card->location ?? '-' }}</span>
                                        <span><i class="fa-regular fa-calendar"></i> {{ $card->years ?? '-' }}</span>
                                    </div>
                                    @if($card->csv_url || $card->xlsx_url)
                                        <div class="dataset-item-actions">
                                            @if($card->csv_url)
                                                <a href="{{ asset($card->csv_url) }}" class="dataset-format dataset-format-csv" download>
                                                    <i class="fa-solid fa-file-csv"></i> CSV
                                                </a>
                                            @endif
                                            @if($card->xlsx_url)
                                                <a href="{{ asset($card->xlsx_url) }}" class="dataset-format dataset-format-xlsx" target="_blank">
                                                    <i class="fa-solid fa-file-excel"></i> XLSX
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('datasets.index') }}" class="dataset-showcase-all">
                    Lihat Semua <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>


    {{-- Topik Data Section --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="font-size:2.2rem;">Topik Data</h2>
        </div>
        <div class="row justify-content-center g-4">
            @foreach($topicData as $topic)
                <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('topicdata.download', $topic->id) }}" class="text-decoration-none w-100 h-100" target="_blank">
                        <div class="card mb-3 shadow-sm rounded-4 px-4 py-3 d-flex flex-row align-items-center h-100" style="cursor:pointer; min-height:120px; transition:box-shadow 0.2s,transform 0.2s;">
                            <div class="me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:64px; height:64px;">
                                @if($topic->image_url)
                                    <img src="{{ asset($topic->image_url) }}" alt="{{ $topic->title }}" style="width:64px; height:64px; object-fit:contain;">
                                @else
                                    <span class="d-flex align-items-center justify-content-center" style="width:64px; height:64px;">
                                        <i class="fa fa-file-pdf-o" style="font-size:2.5rem; color:#c00;"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                <div class="fw-bold text-dark mb-1" style="font-size:1.1rem;">{{ $topic->title }}</div>
                                @if($topic->description)
                                    <div class="text-muted small mb-1">{{ $topic->description }}</div>
                                @endif
                                @if($topic->date)
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($topic->date)->format('d M Y') }}</div>
                                @endif
                            </div>
                            <div class="ms-auto d-flex align-items-center">
                                <span class="btn btn-danger rounded-pill px-3 py-2 fw-bold d-flex align-items-center" style="font-size:1rem; pointer-events:none;">
                                    <i class="fa fa-download me-2"></i> Download
                                </span>
                            </div>
                        </div>
                    </a>
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
                    <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="height:340px;">
                        @if($featuredNews->image_url)
                            <img src="{{ $featuredNews->image_url }}" alt="{{ $featuredNews->title }}" class="w-100 h-100 object-fit-cover" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
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

    {{-- Mitra Kerja Section --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="font-size:2.2rem;">Mitra Kerja</h2>
        </div>
        <div class="mitra-grid">
            @foreach ($mitraKerja->take(36) as $stakeholder)
                <div class="p-1 shadow-sm rounded-4 bg-white h-100 stakeholder-card w-100 transition-all">
                    @if($stakeholder->logo)
                        <div class="mb-0 text-center">
                            @if(!empty($stakeholder->website_url))
                                <a href="{{ $stakeholder->website_url }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset($stakeholder->logo) }}" alt="Logo" class="stakeholder-logo">
                                </a>
                            @else
                                <img src="{{ asset($stakeholder->logo) }}" alt="Logo" class="stakeholder-logo">
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ url('/mitra-kerja') }}" class="btn btn-outline-success rounded-pill px-4 py-2">
                Lihat Semua Mitra <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </section>

    {{-- Testimonials Section - disabled --}}
    {{-- 
    @php
        use Illuminate\Support\Collection;
        $testimonialChunks = $testimonials->chunk(4);
    @endphp
    <section class="pb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
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
        <div class="text-center mt-4">
            <div class="d-inline-flex flex-wrap gap-2 justify-content-center">
                <span class="badge bg-success fs-6 p-3 rounded-pill">
                    <i class="fa fa-users me-2"></i>
                    Total Pengunjung: {{ number_format($totalVisitors ?? 0) }}
                </span>
                <span class="badge bg-success fs-6 p-3 rounded-pill">
                    <i class="fa fa-user-check me-2"></i>
                    Pengunjung Hari Ini: {{ number_format($todayVisitors ?? 0) }}
                </span>
                <span class="badge bg-success fs-6 p-3 rounded-pill">
                    <i class="fa fa-bolt me-2"></i>
                    Kunjungan Hari Ini: {{ number_format($todayVisits ?? 0) }}
                </span>
                <span class="badge bg-success fs-6 p-3 rounded-pill">
                    <i class="fa fa-eye me-2"></i>
                    Total Kunjungan: {{ number_format($visitCount) }}
                </span>
            </div>
        </div>
    </section>
    --}}

</div>

{{-- Mini Video Player (Floating) --}}
<div id="mini-video-player" style="display:none; position:fixed; bottom:24px; right:24px; z-index:9999; width:360px; max-width:90vw; background:#222; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.25); overflow:hidden;">
    <div style="display:flex; justify-content:space-between; align-items:center; background:#111; color:#fff; padding:8px 16px;">
        <span id="mini-video-title" style="font-size:1rem; font-weight:500;"></span>
        <button id="mini-video-close" style="background:none; border:none; color:#fff; font-size:1.5rem; cursor:pointer;">&times;</button>
    </div>
    <div id="mini-video-iframe-container" style="width:100%; aspect-ratio:16/9; background:#000;"></div>
</div>

<!-- Social Media Floating (Desktop Only) -->
<div class="social-float" aria-label="Ikuti Kami">
    <div class="social-menu shadow-sm">
        <a href="https://www.instagram.com/pusatpasarkerja" target="_blank" class="social-chip" rel="noopener">
            <span class="chip-left">
                <span class="chip-icon"><i class="fab fa-instagram"></i></span>
                <span>Instagram</span>
            </span>
            <i class="fa fa-angle-right"></i>
        </a>
        <a href="https://twitter.com/pusatpasarkerja" target="_blank" class="social-chip" rel="noopener">
            <span class="chip-left">
                <span class="chip-icon"><i class="fab fa-twitter"></i></span>
                <span>Twitter (X)</span>
            </span>
            <i class="fa fa-angle-right"></i>
        </a>
        <a href="https://www.facebook.com/pusatpasarkerja" target="_blank" class="social-chip" rel="noopener">
            <span class="chip-left">
                <span class="chip-icon"><i class="fab fa-facebook"></i></span>
                <span>Facebook</span>
            </span>
            <i class="fa fa-angle-right"></i>
        </a>
        <a href="https://www.youtube.com/pusatpasarkerja" target="_blank" class="social-chip" rel="noopener">
            <span class="chip-left">
                <span class="chip-icon"><i class="fab fa-youtube"></i></span>
                <span>YouTube</span>
            </span>
            <i class="fa fa-angle-right"></i>
        </a>
        <a href="https://www.tiktok.com/@pusatpasarkerja" target="_blank" class="social-chip" rel="noopener">
            <span class="chip-left">
                <span class="chip-icon"><i class="fab fa-tiktok"></i></span>
                <span>TikTok</span>
            </span>
            <i class="fa fa-angle-right"></i>
        </a>
        <a href="{{ route('media_sosial') }}" class="social-other">Kanal Lainnya..</a>
    </div>
    <a href="{{ route('media_sosial') }}" class="social-fab shadow">
        <span class="fab-icons">
            <i class="fab fa-instagram"></i>
            <i class="fab fa-twitter"></i>
            <i class="fab fa-facebook"></i>
            <i class="fab fa-youtube"></i>
        </span>
        <span class="fab-text">Ikuti Kami</span>
    </a>
</div>

<!-- WBS Floating Button -->
<a href="https://wbs.kemnaker.go.id/" class="wbs-float" target="_blank" rel="noopener noreferrer" aria-label="Whistle Blowing System">
    <img src="{{ asset('images/wbs.png') }}" alt="Whistle Blowing System">
</a>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/628118712018" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat via WhatsApp">
    <i class="fab fa-whatsapp" style="font-size:1.6rem;"></i>
    <span class="wa-label">Tanya JobiCare</span>
    </a>

    <!-- Zapier Chatbot Embed - DISABLED -->
    <!-- <script async type='module' src='https://interfaces.zapier.com/assets/web-components/zapier-interfaces/zapier-interfaces.esm.js'></script> -->
    <!-- <zapier-interfaces-chatbot-embed is-popup='true' chatbot-id='cme2m5bo7000dpuncd9nwwgbe'></zapier-interfaces-chatbot-embed> -->
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    
    <script>
    let miniVideos = [];
    let currentVideoIndex = 0;
    let player = null;

    // Fetch videos from backend
    function fetchMiniVideos() {
        fetch('{{ route('mini-videos.index') }}')
            .then(res => res.json())
            .then(data => {
                miniVideos = data;
                if (miniVideos.length > 0) {
                    showMiniVideoPlayer(0);
                }
            });
    }

    // Show the mini player and load a video
    function showMiniVideoPlayer(index) {
        currentVideoIndex = index;
        const video = miniVideos[index];
        if (!video) return;
        document.getElementById('mini-video-title').textContent = video.title;
        document.getElementById('mini-video-player').style.display = 'block';

        // Load YouTube player
        loadYouTubePlayer(getYouTubeId(video.youtube_url));
    }

    // Extract YouTube video ID from URL
    function getYouTubeId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\\?v=|\\&v=)([^#\\&\\?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    // YouTube API callback
    function onYouTubeIframeAPIReady() {
        // Will be called automatically by the API
    }

    // Load or update the YouTube player
    function loadYouTubePlayer(videoId) {
        if (!videoId) return;
        if (player) {
            player.loadVideoById(videoId);
        } else {
            player = new YT.Player('mini-video-iframe-container', {
                height: '202',
                width: '360',
                videoId: videoId,
                playerVars: { 'autoplay': 1, 'controls': 1, 'rel': 0 },
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
        }
    }

    // When video ends, play next
    function onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.ENDED) {
            if (currentVideoIndex + 1 < miniVideos.length) {
                showMiniVideoPlayer(currentVideoIndex + 1);
            } else {
                // Optionally loop or close
                document.getElementById('mini-video-player').style.display = 'none';
            }
        }
    }

    // Close button
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('mini-video-close').onclick = function() {
            document.getElementById('mini-video-player').style.display = 'none';
            if (player) player.pauseVideo();
        };
        fetchMiniVideos();
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const welcomePopup = document.getElementById('welcomePopupModal');
        if (welcomePopup && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const popupModal = new bootstrap.Modal(welcomePopup);
            const popupCarouselEl = document.getElementById('welcomePopupCarousel');
            let popupCarousel = null;

            if (popupCarouselEl && bootstrap.Carousel) {
                popupCarousel = bootstrap.Carousel.getOrCreateInstance(popupCarouselEl, {
                    interval: 5000,
                    pause: false,
                    touch: true,
                    wrap: true
                });
                welcomePopup.addEventListener('shown.bs.modal', function () {
                    popupCarousel.cycle();
                });
                welcomePopup.addEventListener('hidden.bs.modal', function () {
                    popupCarousel.pause();
                });
            }

            popupModal.show();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
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

    // --- Responsive market highlight carousels ---
    document.addEventListener('DOMContentLoaded', function () {
        const carousels = [
            ['statScrollRow', 'statScrollPrev', 'statScrollNext', 'statDots'],
            ['highlightStatScrollRow', 'highlightStatScrollPrev', 'highlightStatScrollNext', 'highlightStatDots'],
            ['highlightStat2ScrollRow', 'highlightStat2ScrollPrev', 'highlightStat2ScrollNext', 'highlightStat2Dots'],
        ];

        carousels.forEach(([rowId, prevId, nextId, dotsId]) => {
            const row = document.getElementById(rowId);
            const prev = document.getElementById(prevId);
            const next = document.getElementById(nextId);
            const dots = document.getElementById(dotsId);
            if (!row || !prev || !next || !dots) return;

            const cards = Array.from(row.querySelectorAll('.market-stat-link'));
            let currentPage = 0;
            let resizeTimer = null;

            const metrics = () => {
                if (!cards.length) return { step: 1, visible: 1, pages: 0 };
                const styles = window.getComputedStyle(row);
                const gap = parseFloat(styles.columnGap || styles.gap) || 0;
                const step = cards[0].getBoundingClientRect().width + gap;
                const visible = Math.max(1, Math.floor((row.clientWidth + gap) / step));
                const pages = Math.max(1, Math.ceil(cards.length / visible));
                return { step, visible, pages };
            };

            const updateControls = () => {
                const { step, visible, pages } = metrics();
                const maxScroll = Math.max(0, row.scrollWidth - row.clientWidth);
                const firstVisible = step > 0 ? Math.round(row.scrollLeft / step) : 0;
                currentPage = Math.min(pages - 1, Math.floor(firstVisible / visible));

                prev.disabled = row.scrollLeft <= 2;
                next.disabled = row.scrollLeft >= maxScroll - 2;
                prev.hidden = maxScroll <= 2;
                next.hidden = maxScroll <= 2;

                dots.innerHTML = '';
                if (pages <= 1) return;
                for (let page = 0; page < pages; page++) {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'stat-dot' + (page === currentPage ? ' active' : '');
                    dot.setAttribute('aria-label', `Halaman ${page + 1}`);
                    dot.addEventListener('click', () => {
                        const target = Math.min(page * visible, Math.max(0, cards.length - visible));
                        row.scrollTo({ left: target * step, behavior: 'smooth' });
                    });
                    dots.appendChild(dot);
                }
            };

            const move = (direction) => {
                const { step, visible } = metrics();
                row.scrollBy({ left: direction * step * visible, behavior: 'smooth' });
            };

            prev.addEventListener('click', () => move(-1));
            next.addEventListener('click', () => move(1));
            row.addEventListener('scroll', updateControls, { passive: true });
            window.addEventListener('resize', () => {
                window.clearTimeout(resizeTimer);
                resizeTimer = window.setTimeout(updateControls, 100);
            });

            updateControls();
        });
    });

    </script>
@endsection

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
<style>
.home-ocean-theme {
    --home-ocean-accent: #0f5fa8;
    --home-ocean-secondary: #00a38a;
    --home-ocean-tertiary: #2f9fe8;
    --home-ocean-bg: radial-gradient(980px 520px at -10% -10%, rgba(37, 99, 235, 0.2), transparent 58%),
        radial-gradient(920px 520px at 110% -6%, rgba(16, 185, 129, 0.16), transparent 60%),
        radial-gradient(760px 430px at 50% 105%, rgba(47, 159, 232, 0.13), transparent 62%),
        #f2f7ff;
    background: var(--home-ocean-bg);
}

.welcome-popup-modal {
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid rgba(15, 95, 168, 0.2);
}

.welcome-popup-dialog {
    width: calc(100% - 24px);
    max-width: 560px;
    margin-left: auto;
    margin-right: auto;
}

.welcome-popup-image {
    display: block;
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: calc(100vh - 190px);
    margin: 0 auto;
    object-fit: contain;
}

@media (max-width: 575.98px) {
    .welcome-popup-modal .modal-body {
        padding-left: 12px;
        padding-right: 12px;
    }

    .welcome-popup-image {
        max-height: calc(100vh - 170px);
    }
}

#welcomePopupCarousel .carousel-control-prev-icon,
#welcomePopupCarousel .carousel-control-next-icon {
    filter: invert(1) grayscale(100);
}

.home-ocean-theme .card {
    position: relative;
    overflow: hidden;
    border-radius: 18px !important;
    border: 1px solid rgba(15, 95, 168, 0.2) !important;
    box-shadow: 0 12px 30px rgba(12, 50, 82, 0.1) !important;
    background: linear-gradient(165deg, rgba(255, 255, 255, 0.97), rgba(244, 251, 255, 0.94)) !important;
    transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
}

.home-ocean-theme .card::before {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    background: linear-gradient(
        130deg,
        rgba(47, 159, 232, 0.1) 0%,
        rgba(15, 95, 168, 0.08) 30%,
        rgba(0, 163, 138, 0.08) 72%,
        rgba(47, 159, 232, 0.12) 100%
    );
    opacity: 0.75;
}

.home-ocean-theme .card .card-body,
.home-ocean-theme .card .card-footer {
    position: relative;
    z-index: 1;
}

.home-ocean-theme .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 38px rgba(12, 50, 82, 0.14) !important;
    border-color: rgba(15, 95, 168, 0.3) !important;
}

.home-ocean-theme .stat-title {
    color: var(--home-ocean-accent) !important;
}

.home-ocean-theme .stat-card .stat-icon {
    background: linear-gradient(135deg, var(--home-ocean-tertiary) 0%, var(--home-ocean-secondary) 100%);
}

.home-ocean-theme .home-ocean-banner {
    background: linear-gradient(
        120deg,
        rgba(15, 95, 168, 0.96) 0%,
        rgba(47, 159, 232, 0.95) 48%,
        rgba(0, 163, 138, 0.9) 100%
    ) !important;
    border: 1px solid rgba(255, 255, 255, 0.28);
    box-shadow: 0 14px 34px rgba(15, 23, 42, 0.16);
}

.home-ocean-theme .home-ocean-banner-pink {
    background: linear-gradient(
        120deg,
        #fc6c85 0%,
        #e83f6f 45%,
        #b51757 100%
    ) !important;
}

.home-ocean-theme .thelogo-inline {
    max-height: 48px;
    width: auto;
}

/* Data and Information editorial showcase */
.information-showcase {
    position: relative;
    overflow: hidden;
    border-radius: 32px;
    color: #eaf6ff;
    background:
        radial-gradient(circle at 88% 12%, rgba(45, 212, 191, 0.22), transparent 26%),
        radial-gradient(circle at 8% 90%, rgba(56, 189, 248, 0.18), transparent 28%),
        linear-gradient(135deg, #062d4e 0%, #084f75 52%, #087d78 100%);
    box-shadow: 0 24px 60px rgba(6, 45, 78, 0.22);
    isolation: isolate;
}

.information-showcase::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -1;
    opacity: 0.12;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.5) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.5) 1px, transparent 1px);
    background-size: 44px 44px;
    mask-image: linear-gradient(to bottom, #000, transparent 78%);
}

.information-showcase-inner {
    position: relative;
    z-index: 2;
    padding: clamp(1.5rem, 4vw, 3.5rem);
}

.information-orb {
    position: absolute;
    z-index: 0;
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 50%;
    pointer-events: none;
}

.information-orb-one {
    width: 250px;
    height: 250px;
    top: -145px;
    right: 14%;
}

.information-orb-two {
    width: 130px;
    height: 130px;
    right: -35px;
    bottom: 12%;
}

.information-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 2rem;
    margin-bottom: 2rem;
}

.information-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    margin-bottom: 0.7rem;
    color: #7ee8dc;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.information-heading h2 {
    margin: 0 0 0.65rem;
    color: #fff;
    font-size: clamp(2rem, 4vw, 3.35rem);
    font-weight: 800;
    letter-spacing: -0.04em;
}

.information-heading p {
    max-width: 650px;
    margin: 0;
    color: rgba(234, 246, 255, 0.75);
    font-size: 1rem;
}

.information-all-link {
    display: inline-flex;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    gap: 0.65rem;
    padding: 0.8rem 1.15rem;
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 999px;
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
    font-weight: 700;
    text-decoration: none;
    backdrop-filter: blur(10px);
    transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}

.information-all-link:hover {
    color: #063b5c;
    border-color: #fff;
    background: #fff;
    transform: translateY(-2px);
}

.information-tabs {
    display: flex;
    flex-wrap: nowrap;
    gap: 0.6rem;
    margin-bottom: 2rem;
    padding: 0.45rem;
    overflow-x: auto;
    border: 1px solid rgba(255, 255, 255, 0.13);
    border-radius: 18px;
    background: rgba(2, 30, 51, 0.28);
    scrollbar-width: thin;
}

.information-tabs .nav-item {
    flex: 1 0 auto;
}

.information-tabs .nav-link {
    display: flex;
    width: 100%;
    min-height: 48px;
    align-items: center;
    justify-content: center;
    gap: 0.55rem;
    padding: 0.65rem 0.9rem;
    border: 0;
    border-radius: 13px;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.86rem;
    font-weight: 700;
    white-space: nowrap;
    transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
}

.information-tabs .nav-link:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
}

.information-tabs .nav-link.active {
    color: #074a68;
    background: #fff;
    box-shadow: 0 8px 22px rgba(2, 23, 40, 0.2);
}

.information-content {
    min-height: 350px;
}

.information-category-meta {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    margin-bottom: 1.25rem;
}

.information-category-icon {
    display: inline-flex;
    width: 48px;
    height: 48px;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    color: var(--info-tone);
    background: color-mix(in srgb, var(--info-tone) 16%, white);
    font-size: 1.15rem;
}

.information-category-kicker {
    display: block;
    margin-bottom: 0.1rem;
    color: rgba(255, 255, 255, 0.56);
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

.information-category-meta h3 {
    margin: 0;
    color: #fff;
    font-size: 1.15rem;
    font-weight: 750;
}

.information-count {
    margin-left: auto;
    padding: 0.38rem 0.7rem;
    border-radius: 999px;
    color: rgba(255, 255, 255, 0.7);
    background: rgba(255, 255, 255, 0.09);
    font-size: 0.76rem;
    font-weight: 700;
}

.information-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
}

.information-card {
    --info-tone: #38bdf8;
    display: flex;
    min-height: 220px;
    flex-direction: column;
    padding: 1.25rem;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.72);
    border-radius: 20px;
    color: #163047;
    background: rgba(255, 255, 255, 0.94);
    box-shadow: 0 12px 30px rgba(2, 28, 48, 0.14);
    text-decoration: none;
    backdrop-filter: blur(12px);
    transition: transform 0.24s ease, box-shadow 0.24s ease, border-color 0.24s ease;
}

.information-card:first-child {
    grid-column: span 2;
    background:
        radial-gradient(circle at 92% 10%, color-mix(in srgb, var(--info-tone) 18%, transparent), transparent 35%),
        rgba(255, 255, 255, 0.97);
}

.information-card:hover {
    color: #102b40;
    border-color: var(--info-tone);
    box-shadow: 0 18px 34px rgba(2, 28, 48, 0.24);
    transform: translateY(-5px);
}

.information-card-top,
.information-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.information-card-icon {
    display: inline-flex;
    width: 42px;
    height: 42px;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    color: var(--info-tone);
    background: color-mix(in srgb, var(--info-tone) 13%, white);
}

.information-card-number {
    color: color-mix(in srgb, var(--info-tone) 60%, #718096);
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.08em;
}

.information-card-body {
    display: flex;
    flex: 1;
    flex-direction: column;
    justify-content: flex-end;
    padding: 1.3rem 0 1rem;
}

.information-card-date {
    display: block;
    margin-bottom: 0.55rem;
    color: #6b7e8e;
    font-size: 0.76rem;
    font-weight: 650;
}

.information-card-date i {
    margin-right: 0.3rem;
    color: var(--info-tone);
}

.information-card h4 {
    display: -webkit-box;
    margin: 0;
    overflow: hidden;
    color: #102b40;
    font-size: 1rem;
    font-weight: 750;
    line-height: 1.45;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

.information-card:first-child h4 {
    max-width: 680px;
    font-size: clamp(1.15rem, 2vw, 1.45rem);
    -webkit-line-clamp: 2;
}

.information-card-footer {
    padding-top: 0.85rem;
    border-top: 1px solid #e7eef3;
    color: #567083;
    font-size: 0.78rem;
    font-weight: 750;
}

.information-card-arrow {
    display: inline-flex;
    width: 30px;
    height: 30px;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #fff;
    background: var(--info-tone);
    transition: transform 0.2s ease;
}

.information-card:hover .information-card-arrow {
    transform: translateX(3px);
}

.tone-blue { --info-tone: #2583e8; }
.tone-purple { --info-tone: #8b5cf6; }
.tone-cyan { --info-tone: #0891b2; }
.tone-orange { --info-tone: #ea7b17; }
.tone-green { --info-tone: #059669; }
.tone-pink { --info-tone: #db4d82; }

.information-empty {
    display: grid;
    min-height: 240px;
    place-content: center;
    border: 1px dashed rgba(255, 255, 255, 0.28);
    border-radius: 20px;
    color: rgba(255, 255, 255, 0.68);
    text-align: center;
}

.information-empty i {
    margin-bottom: 0.75rem;
    font-size: 2rem;
}

.information-empty p {
    margin: 0;
}

@media (max-width: 991.98px) {
    .information-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .information-tabs .nav-item {
        flex: 0 0 auto;
    }
}

@media (max-width: 575.98px) {
    .information-showcase {
        border-radius: 24px;
    }

    .information-heading {
        margin-bottom: 1.4rem;
    }

    .information-heading p {
        font-size: 0.9rem;
    }

    .information-tabs {
        margin-right: -0.35rem;
        margin-left: -0.35rem;
        border-radius: 15px;
    }

    .information-tabs .nav-link {
        min-height: 44px;
        padding: 0.55rem 0.75rem;
        font-size: 0.8rem;
    }

    .information-category-meta {
        align-items: flex-start;
    }

    .information-count {
        display: none;
    }

    .information-grid {
        grid-template-columns: 1fr;
    }

    .information-card,
    .information-card:first-child {
        grid-column: auto;
        min-height: 205px;
    }
}

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
    padding: 2rem 0.5rem; /* Reduced horizontal padding */
    margin: 0.5rem 0;
    height: 320px;
    width: 260px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.stat-card .card-body {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
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

/* Market highlight */
.market-highlight-section {
    position: relative;
    z-index: 10;
    padding: clamp(2.5rem, 6vw, 5rem) 0;
    overflow: hidden;
    background:
        radial-gradient(circle at 5% 18%, rgba(47, 159, 232, 0.13), transparent 22%),
        radial-gradient(circle at 96% 78%, rgba(0, 163, 138, 0.12), transparent 24%);
}

.market-highlight-shape {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.market-highlight-shape-one {
    width: 250px;
    height: 250px;
    top: 5%;
    left: -170px;
    border: 42px solid rgba(47, 159, 232, 0.08);
}

.market-highlight-shape-two {
    width: 180px;
    height: 180px;
    right: -110px;
    bottom: 8%;
    border: 32px solid rgba(0, 163, 138, 0.08);
}

.market-highlight-heading {
    display: flex;
    max-width: 1280px;
    align-items: center;
    gap: 1rem;
    margin: 0 auto 1.75rem;
}

.market-highlight-heading h3 {
    flex: 0 0 auto;
    margin: 0;
    color: #0b3859;
    font-size: clamp(1.75rem, 3vw, 2.65rem);
    font-weight: 800;
    letter-spacing: -0.035em;
}

.market-highlight-heading-icon {
    display: inline-flex;
    width: 52px;
    height: 52px;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    color: #fff;
    background: linear-gradient(135deg, #1686d9, #06a88f);
    box-shadow: 0 10px 25px rgba(15, 95, 168, 0.24);
}

.market-highlight-heading-line {
    width: 100%;
    height: 1px;
    margin-left: 0.5rem;
    background: linear-gradient(90deg, rgba(15, 95, 168, 0.3), transparent);
}

.market-carousel-panel {
    --market-accent: #1686d9;
    --market-accent-rgb: 22, 134, 217;
    position: relative;
    max-width: 1280px;
    margin: 0 auto 1.25rem;
    padding: 1.1rem 3.75rem 2.6rem;
    border: 1px solid rgba(var(--market-accent-rgb), 0.15);
    border-radius: 28px;
    background:
        linear-gradient(120deg, rgba(var(--market-accent-rgb), 0.08), transparent 32%),
        rgba(255, 255, 255, 0.64);
    box-shadow: 0 16px 38px rgba(12, 50, 82, 0.08);
    backdrop-filter: blur(10px);
}

.market-carousel-panel-green {
    --market-accent: #059f86;
    --market-accent-rgb: 5, 159, 134;
}

.market-carousel-track {
    display: flex;
    align-items: stretch;
    gap: 1rem;
    width: 100%;
    padding: 0.5rem 0;
    overflow-x: auto;
    scroll-behavior: smooth;
    scroll-snap-type: x mandatory;
    scrollbar-width: none;
}

.market-carousel-track::-webkit-scrollbar {
    display: none;
}

.market-highlight-section a.market-stat-link {
    display: flex;
    width: clamp(260px, 24vw, 300px);
    height: auto;
    flex: 0 0 clamp(260px, 24vw, 300px);
    margin: 0;
    padding: 0;
    color: inherit;
    text-decoration: none;
    scroll-snap-align: start;
}

.market-stat-card {
    position: relative;
    display: flex;
    width: 100%;
    min-height: 300px;
    flex-direction: column;
    padding: 1.35rem;
    overflow: hidden;
    border: 1px solid rgba(var(--market-accent-rgb), 0.14);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 9px 25px rgba(11, 56, 89, 0.08);
    transition: border-color 0.22s ease, box-shadow 0.22s ease, transform 0.22s ease;
}

.market-stat-card::before {
    content: "";
    position: absolute;
    width: 125px;
    height: 125px;
    top: -72px;
    right: -55px;
    border-radius: 50%;
    background: rgba(var(--market-accent-rgb), 0.1);
    transition: transform 0.3s ease;
}

.market-stat-link:hover .market-stat-card {
    border-color: rgba(var(--market-accent-rgb), 0.48);
    box-shadow: 0 17px 34px rgba(11, 56, 89, 0.16);
    transform: translateY(-4px);
}

.market-stat-link:hover .market-stat-card::before {
    transform: scale(1.18);
}

.market-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.market-stat-icon {
    display: inline-flex;
    width: 48px;
    height: 48px;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    color: var(--market-accent);
    background: rgba(var(--market-accent-rgb), 0.11);
    font-size: 1.15rem;
}

.market-stat-index {
    position: relative;
    color: rgba(var(--market-accent-rgb), 0.7);
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.1em;
}

.home-ocean-theme .market-stat-title {
    width: 100%;
    margin-bottom: 0.8rem;
    color: #17405c !important;
    font-size: 1rem;
    font-weight: 750;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.market-stat-reading {
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    gap: 0.35rem;
    margin-bottom: 0.9rem;
}

.market-stat-value {
    color: #092d47;
    font-size: clamp(1.8rem, 3vw, 2.45rem);
    font-weight: 850;
    line-height: 1.05;
    letter-spacing: -0.045em;
    overflow-wrap: anywhere;
}

.market-stat-unit {
    color: #48697e;
    font-size: 0.9rem;
    font-weight: 700;
}

.market-highlight-section .market-stat-description {
    width: 100%;
    max-height: none;
    margin: 0;
    padding: 0 2.5rem 0 0;
    overflow: visible;
    color: #647b8b !important;
    font-size: 0.88rem !important;
    line-height: 1.55;
    text-overflow: clip;
    overflow-wrap: anywhere;
}

.market-stat-action {
    display: inline-flex;
    width: 34px;
    height: 34px;
    align-items: center;
    justify-content: center;
    align-self: flex-end;
    margin-top: auto;
    border-radius: 50%;
    color: #fff;
    background: var(--market-accent);
    box-shadow: 0 7px 16px rgba(var(--market-accent-rgb), 0.25);
    transition: transform 0.2s ease;
}

.market-stat-link:hover .market-stat-action {
    transform: translateX(3px);
}

.market-carousel-button {
    position: absolute;
    z-index: 4;
    top: calc(50% - 0.75rem);
    display: inline-flex;
    width: 42px;
    height: 42px;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(var(--market-accent-rgb), 0.16);
    border-radius: 50%;
    color: var(--market-accent);
    background: #fff;
    box-shadow: 0 9px 22px rgba(11, 56, 89, 0.15);
    transform: translateY(-50%);
    transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease, opacity 0.2s ease;
}

.market-carousel-button:hover:not(:disabled) {
    color: #fff;
    background: var(--market-accent);
    transform: translateY(-50%) scale(1.06);
}

.market-carousel-button:disabled {
    cursor: default;
    opacity: 0.35;
}

.market-carousel-button-prev {
    left: 0.85rem;
}

.market-carousel-button-next {
    right: 0.85rem;
}

.market-carousel-dots {
    position: absolute;
    right: 0;
    bottom: 0.95rem;
    left: 0;
    display: flex;
    min-height: 10px;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
}

.market-carousel-dots .stat-dot {
    width: 7px;
    height: 7px;
    margin: 0;
    padding: 0;
    border: 0;
    border-radius: 999px;
    background: rgba(var(--market-accent-rgb), 0.38);
    opacity: 1;
    transition: width 0.2s ease, background 0.2s ease;
}

.market-carousel-dots .stat-dot.active {
    width: 24px;
    background: var(--market-accent);
}

.market-source-panel {
    --market-accent: #e83f6f;
    --market-accent-rgb: 232, 63, 111;
    display: grid;
    max-width: 1280px;
    grid-template-columns: minmax(230px, 0.85fr) minmax(0, 2fr);
    align-items: center;
    gap: clamp(1rem, 3vw, 2.5rem);
    margin: 2rem auto 0;
    padding: clamp(1.5rem, 3vw, 2.5rem);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.26);
    border-radius: 28px;
    background: linear-gradient(125deg, #fb7185 0%, #e83f6f 48%, #b51757 100%);
    box-shadow: 0 18px 42px rgba(181, 23, 87, 0.23);
}

.market-source-intro {
    position: relative;
    z-index: 1;
}

.market-source-intro::before {
    content: "";
    position: absolute;
    z-index: -1;
    width: 210px;
    height: 210px;
    top: 50%;
    left: -95px;
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 50%;
    transform: translateY(-50%);
}

.market-source-intro h2 {
    margin: 0 0 1rem;
    color: #fff;
    font-size: clamp(1.8rem, 3.5vw, 3rem);
    font-weight: 800;
    line-height: 1.08;
    letter-spacing: -0.04em;
}

.market-source-intro .thelogo-wrapper {
    display: inline-flex;
    padding: 0.7rem 0.85rem;
    border: 1px solid rgba(255, 255, 255, 0.38);
    border-radius: 14px;
    background: rgba(103, 10, 48, 0.28);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.12), 0 10px 22px rgba(85, 10, 38, 0.18);
    backdrop-filter: blur(8px);
}

.market-source-intro .thelogo-inline {
    opacity: 1;
    filter: drop-shadow(0 2px 5px rgba(67, 5, 31, 0.24));
}

.market-source-carousel {
    position: relative;
    min-width: 0;
    padding: 0 3rem 2rem;
}

.market-source-panel .market-stat-card {
    min-height: 315px;
}

.market-source-panel .market-carousel-button-prev {
    left: 0;
}

.market-source-panel .market-carousel-button-next {
    right: 0;
}

.market-source-panel .market-carousel-dots {
    bottom: 0;
}

.market-carousel-dots-light .stat-dot {
    background: rgba(255, 255, 255, 0.45);
}

.market-carousel-dots-light .stat-dot.active {
    background: #fff;
}

@media (max-width: 991.98px) {
    .market-source-panel {
        grid-template-columns: 1fr;
    }

    .market-source-intro {
        text-align: center;
    }

    .market-source-intro h2 {
        margin-bottom: 0.75rem;
    }
}

@media (max-width: 575.98px) {
    .market-highlight-heading {
        align-items: flex-start;
    }

    .market-highlight-heading-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
    }

    .market-highlight-heading-line {
        display: none;
    }

    .market-carousel-panel {
        padding: 0.75rem 0.75rem 2.5rem;
        border-radius: 22px;
    }

    .market-highlight-section a.market-stat-link {
        width: calc(100vw - 4.5rem);
        flex-basis: calc(100vw - 4.5rem);
    }

    .market-stat-card {
        min-height: 0;
        padding: 1.15rem;
        border-radius: 18px;
    }

    .market-carousel-panel .market-carousel-button {
        display: none;
    }

    .market-source-panel {
        padding: 1.35rem 0.75rem;
        border-radius: 22px;
    }

    .market-source-carousel {
        padding: 0 0 2rem;
    }

    .market-source-panel .market-stat-card {
        min-height: 0;
    }

    .market-source-panel .market-carousel-button {
        display: none;
    }
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
/* Homepage hero */
.home-hero {
    position: relative;
    min-height: clamp(520px, 70vh, 680px);
    overflow: hidden;
    isolation: isolate;
    background-color: #031b1c;
    background-image: var(--home-hero-image);
    background-position: center;
    background-size: cover;
}

.home-hero::before {
    content: "";
    position: absolute;
    z-index: -1;
    inset: 0;
    background:
        linear-gradient(90deg, rgba(1, 20, 23, 0.94) 0%, rgba(1, 28, 28, 0.82) 35%, rgba(2, 29, 27, 0.2) 70%, rgba(1, 14, 16, 0.3) 100%),
        linear-gradient(0deg, rgba(0, 15, 17, 0.62), transparent 45%);
}

.home-hero::after {
    content: "";
    position: absolute;
    z-index: -1;
    right: 0;
    bottom: 0;
    left: 0;
    height: 145px;
    background: linear-gradient(to top, rgba(0, 14, 16, 0.74), transparent);
    pointer-events: none;
}

.home-hero-grid {
    position: absolute;
    z-index: -1;
    inset: 0;
    opacity: 0.12;
    pointer-events: none;
    background-image:
        linear-gradient(rgba(94, 234, 212, 0.45) 1px, transparent 1px),
        linear-gradient(90deg, rgba(94, 234, 212, 0.45) 1px, transparent 1px);
    background-size: 58px 58px;
    mask-image: linear-gradient(90deg, #000, transparent 62%);
}

.home-hero-glow {
    position: absolute;
    z-index: -1;
    border-radius: 50%;
    pointer-events: none;
    filter: blur(10px);
}

.home-hero-glow-one {
    width: 390px;
    height: 390px;
    top: -250px;
    left: 22%;
    background: rgba(16, 185, 129, 0.2);
}

.home-hero-glow-two {
    width: 260px;
    height: 260px;
    bottom: -170px;
    left: 3%;
    background: rgba(245, 158, 11, 0.14);
}

.home-hero-inner {
    display: grid;
    width: min(1380px, 100%);
    min-height: inherit;
    grid-template-columns: minmax(0, 0.95fr) minmax(320px, 1.05fr);
    align-items: center;
    gap: clamp(2rem, 5vw, 5rem);
    margin: 0 auto;
    padding: clamp(3rem, 7vw, 6.5rem) clamp(1.25rem, 5vw, 5rem);
}

.home-hero-copy {
    position: relative;
    z-index: 2;
    max-width: 650px;
    padding: clamp(1.4rem, 3vw, 2.5rem);
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 30px;
    background: linear-gradient(140deg, rgba(3, 39, 41, 0.78), rgba(3, 30, 32, 0.44));
    box-shadow: 0 24px 60px rgba(0, 10, 12, 0.28);
    backdrop-filter: blur(13px);
}

.home-hero-copy::before {
    content: "";
    position: absolute;
    width: 62px;
    height: 4px;
    top: 0;
    left: 2rem;
    border-radius: 0 0 8px 8px;
    background: linear-gradient(90deg, #f5c451, #34d399);
}

.home-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    margin-bottom: 1.1rem;
    color: #6ee7d2;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.13em;
    text-transform: uppercase;
}

.home-hero .hero-title-responsive {
    margin: 0 0 1.6rem;
    color: #fff;
    font-size: clamp(2.65rem, 5.5vw, 5.25rem);
    font-weight: 850;
    line-height: 0.98;
    letter-spacing: -0.055em;
    text-shadow: 0 5px 22px rgba(0, 0, 0, 0.35);
}

.home-hero .hero-title-responsive span {
    display: block;
}

.home-hero .hero-title-responsive span:last-child {
    color: #f4d26f;
}

.home-hero-search {
    display: grid;
    grid-template-columns: auto minmax(0, 1fr) auto;
    align-items: center;
    gap: 0.55rem;
    width: 100%;
    padding: 0.45rem;
    border: 1px solid rgba(255, 255, 255, 0.28);
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.96);
    box-shadow: 0 14px 34px rgba(0, 12, 13, 0.3);
}

.home-hero-search-icon {
    display: inline-flex;
    width: 40px;
    height: 40px;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    color: #07816d;
    background: #e4f8f3;
}

.home-hero-search input {
    width: 100%;
    min-width: 0;
    padding: 0.75rem 0.35rem;
    border: 0;
    outline: 0;
    color: #17384a;
    background: transparent;
    font-size: 0.95rem;
}

.home-hero-search input::placeholder {
    color: #78909d;
    opacity: 1;
}

.home-hero-search button {
    display: inline-flex;
    min-height: 46px;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    padding: 0.7rem 1rem;
    border: 0;
    border-radius: 13px;
    color: #fff;
    background: linear-gradient(135deg, #0b8d76, #08705f);
    box-shadow: 0 8px 18px rgba(7, 112, 95, 0.25);
    font-size: 0.9rem;
    font-weight: 800;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.home-hero-search button:hover {
    box-shadow: 0 11px 23px rgba(7, 112, 95, 0.34);
    transform: translateY(-2px);
}

.home-hero-points {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem 1rem;
    margin-top: 1.15rem;
}

.home-hero-points span {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    color: rgba(255, 255, 255, 0.76);
    font-size: 0.75rem;
    font-weight: 650;
}

.home-hero-points i {
    color: #5eead4;
}

.home-hero-visual {
    position: relative;
    min-height: 420px;
    pointer-events: none;
}

.home-hero-signal {
    position: absolute;
    top: 4%;
    right: 4%;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.75rem 0.9rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    background: rgba(3, 39, 41, 0.58);
    box-shadow: 0 14px 30px rgba(0, 9, 10, 0.2);
    backdrop-filter: blur(10px);
}

.home-hero-signal-icon {
    display: inline-flex;
    width: 38px;
    height: 38px;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    color: #082e2c;
    background: #f4d26f;
}

.home-hero-signal-bars {
    display: flex;
    height: 34px;
    align-items: flex-end;
    gap: 4px;
}

.home-hero-signal-bars i {
    display: block;
    width: 6px;
    border-radius: 5px 5px 2px 2px;
    background: linear-gradient(to top, #10b981, #6ee7d2);
    animation: homeHeroBars 2.2s ease-in-out infinite alternate;
}

.home-hero-signal-bars i:nth-child(1) { height: 30%; animation-delay: -0.8s; }
.home-hero-signal-bars i:nth-child(2) { height: 52%; animation-delay: -0.4s; }
.home-hero-signal-bars i:nth-child(3) { height: 42%; animation-delay: -1.1s; }
.home-hero-signal-bars i:nth-child(4) { height: 75%; animation-delay: -0.2s; }
.home-hero-signal-bars i:nth-child(5) { height: 100%; animation-delay: -0.65s; }

@keyframes homeHeroBars {
    to { transform: scaleY(0.68); transform-origin: bottom; }
}

.home-hero-orbit {
    position: absolute;
    border: 1px solid rgba(110, 231, 210, 0.26);
    border-radius: 50%;
}

.home-hero-orbit::after {
    content: "";
    position: absolute;
    width: 9px;
    height: 9px;
    top: 15%;
    right: 12%;
    border-radius: 50%;
    background: #f4d26f;
    box-shadow: 0 0 18px rgba(244, 210, 111, 0.9);
}

.home-hero-orbit-one {
    width: 260px;
    height: 260px;
    right: 2%;
    bottom: -12%;
}

.home-hero-orbit-two {
    width: 145px;
    height: 145px;
    right: 26%;
    bottom: 5%;
}

.running-text-strip {
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
    border-top: 1px solid rgba(110, 231, 210, 0.36);
    border-bottom: 1px solid rgba(110, 231, 210, 0.3);
    color: #fff;
    background: linear-gradient(90deg, #064e45, #087266 52%, #075c53);
}

.running-text-label {
    position: relative;
    z-index: 2;
    display: inline-flex;
    width: 52px;
    min-height: 42px;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    color: #17403a;
    background: #f4d26f;
    box-shadow: 8px 0 20px rgba(2, 44, 39, 0.25);
}

.running-text-track {
    display: inline-flex;
    min-width: 100%;
    gap: 3rem;
    padding: 0.65rem 0;
    white-space: nowrap;
    animation: runningTextScroll 22s linear infinite;
}

.running-text-item {
    font-size: 0.88rem;
    font-weight: 650;
    letter-spacing: 0.01em;
}

@keyframes runningTextScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

@media (max-width: 991.98px) {
    .home-hero {
        min-height: 590px;
        background-position: 62% center;
    }

    .home-hero-inner {
        grid-template-columns: minmax(0, 1fr) minmax(130px, 0.35fr);
        gap: 1rem;
    }

    .home-hero-copy {
        max-width: 620px;
    }

    .home-hero-visual {
        min-height: 320px;
    }
}

@media (max-width: 767.98px) {
    .home-hero {
        min-height: 620px;
        background-position: 64% center;
    }

    .home-hero::before {
        background:
            linear-gradient(90deg, rgba(1, 20, 23, 0.9), rgba(1, 24, 25, 0.52)),
            linear-gradient(0deg, rgba(0, 15, 17, 0.8), rgba(0, 15, 17, 0.08));
    }

    .home-hero-inner {
        display: flex;
        align-items: flex-end;
        padding-top: 7rem;
        padding-bottom: 2rem;
    }

    .home-hero-copy {
        width: 100%;
    }

    .home-hero-visual {
        display: none;
    }
}

@media (max-width: 575.98px) {
    .home-hero {
        min-height: 650px;
    }

    .home-hero-inner {
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }

    .home-hero-copy {
        padding: 1.3rem 1rem;
        border-radius: 22px;
    }

    .home-hero .hero-title-responsive {
        font-size: clamp(2.55rem, 13vw, 3.6rem);
    }

    .home-hero-search {
        grid-template-columns: auto minmax(0, 1fr);
    }

    .home-hero-search button {
        grid-column: 1 / -1;
        width: 100%;
    }

    .home-hero-points {
        gap: 0.45rem 0.8rem;
    }

    .running-text-label {
        width: 46px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .home-hero-signal-bars i,
    .running-text-track {
        animation: none;
    }
}
.stat-carousel-section a {
    display: block;
    height: 100%;
    text-decoration: none;
    padding: 0;
    margin: 0;
}
.stat-card {
    height: 320px;
    width: 260px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.stat-card .card-body {
    flex: 1 1 0;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}
.stat-desc {
    margin-top: auto;
    width: 100%; /* Make description use full width */
    display: block;
    line-height: 1.35;
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 5.2em; /* ~4 lines at 1.3 line-height */
    font-size: 0.95rem; /* Start size; will auto-shrink via JS if needed */
    overflow-wrap: anywhere; /* break long words/links */
    padding: 0; /* Remove any padding if present */
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
    background: linear-gradient(165deg, rgba(255, 255, 255, 0.95), rgba(242, 247, 255, 0.92));
    border-radius: 2rem;
    box-shadow: 0 8px 32px 0 rgba(15, 95, 168, 0.12);
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
/* Dataset library */
.dataset-showcase {
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(15, 95, 168, 0.12);
    border-radius: 32px;
    background:
        radial-gradient(circle at 4% 10%, rgba(250, 187, 35, 0.12), transparent 22%),
        radial-gradient(circle at 97% 88%, rgba(0, 163, 138, 0.12), transparent 25%),
        linear-gradient(145deg, rgba(255, 255, 255, 0.94), rgba(237, 247, 253, 0.96));
    box-shadow: 0 20px 50px rgba(12, 50, 82, 0.1);
}

.dataset-showcase::before {
    content: "";
    position: absolute;
    inset: 0;
    opacity: 0.32;
    pointer-events: none;
    background-image: radial-gradient(rgba(15, 95, 168, 0.18) 1px, transparent 1px);
    background-size: 22px 22px;
    mask-image: linear-gradient(115deg, #000, transparent 35%, transparent 70%, #000);
}

.dataset-showcase-inner {
    position: relative;
    z-index: 2;
    padding: clamp(1.5rem, 4vw, 3.5rem);
}

.dataset-showcase-decoration {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.dataset-showcase-decoration-one {
    width: 180px;
    height: 180px;
    top: -115px;
    left: 12%;
    border: 28px solid rgba(250, 187, 35, 0.09);
}

.dataset-showcase-decoration-two {
    width: 220px;
    height: 220px;
    right: -150px;
    bottom: 8%;
    border: 36px solid rgba(0, 163, 138, 0.08);
}

.dataset-showcase-heading {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.dataset-showcase-heading-icon {
    display: inline-flex;
    width: 54px;
    height: 54px;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    border-radius: 17px;
    color: #fff;
    background: linear-gradient(135deg, #0f5fa8, #00a38a);
    box-shadow: 0 11px 25px rgba(15, 95, 168, 0.24);
    font-size: 1.2rem;
}

.dataset-showcase-heading h2 {
    flex: 0 0 auto;
    margin: 0;
    color: #0b3859;
    font-size: clamp(1.75rem, 3vw, 2.55rem);
    font-weight: 800;
    letter-spacing: -0.035em;
}

.dataset-showcase-heading-line {
    width: 100%;
    height: 1px;
    margin-left: 0.4rem;
    background: linear-gradient(90deg, rgba(15, 95, 168, 0.28), transparent);
}

.dataset-category-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    align-items: start;
    gap: 1.25rem;
}

.dataset-category {
    --dataset-accent: #2583e8;
    --dataset-accent-rgb: 37, 131, 232;
    position: relative;
    padding: 0.75rem;
    overflow: hidden;
    border: 1px solid rgba(var(--dataset-accent-rgb), 0.2);
    border-radius: 24px;
    background:
        linear-gradient(180deg, rgba(var(--dataset-accent-rgb), 0.12), transparent 135px),
        rgba(255, 255, 255, 0.74);
    box-shadow: 0 12px 30px rgba(12, 50, 82, 0.08);
    backdrop-filter: blur(12px);
}

.dataset-tone-amber {
    --dataset-accent: #d98c08;
    --dataset-accent-rgb: 217, 140, 8;
}

.dataset-tone-blue {
    --dataset-accent: #2578d5;
    --dataset-accent-rgb: 37, 120, 213;
}

.dataset-tone-green {
    --dataset-accent: #079273;
    --dataset-accent-rgb: 7, 146, 115;
}

.dataset-category-header {
    position: relative;
    display: flex;
    min-height: 86px;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 0.7rem 1rem;
}

.dataset-category-icon {
    display: inline-flex;
    width: 46px;
    height: 46px;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    color: #fff;
    background: var(--dataset-accent);
    box-shadow: 0 8px 18px rgba(var(--dataset-accent-rgb), 0.25);
}

.dataset-category-header h3 {
    margin: 0;
    color: #153c56;
    font-size: 1rem;
    font-weight: 800;
    line-height: 1.35;
}

.dataset-category-header h3 span {
    display: block;
    margin-bottom: 0.05rem;
    color: var(--dataset-accent);
    font-size: 0.7rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

.dataset-category-count {
    align-self: flex-start;
    margin-left: auto;
    padding: 0.25rem 0.45rem;
    border: 1px solid rgba(var(--dataset-accent-rgb), 0.18);
    border-radius: 8px;
    color: var(--dataset-accent);
    background: rgba(255, 255, 255, 0.7);
    font-size: 0.7rem;
    font-weight: 850;
    letter-spacing: 0.06em;
}

.dataset-card-list {
    display: grid;
    gap: 0.75rem;
}

.dataset-item {
    position: relative;
    padding: 1.1rem;
    overflow: hidden;
    border: 1px solid rgba(var(--dataset-accent-rgb), 0.12);
    border-radius: 17px;
    background: rgba(255, 255, 255, 0.94);
    box-shadow: 0 6px 16px rgba(12, 50, 82, 0.055);
    transition: border-color 0.22s ease, box-shadow 0.22s ease, transform 0.22s ease;
}

.dataset-item:hover {
    border-color: rgba(var(--dataset-accent-rgb), 0.4);
    box-shadow: 0 12px 24px rgba(12, 50, 82, 0.11);
    transform: translateY(-3px);
}

.dataset-item-number {
    position: absolute;
    top: 0.9rem;
    right: 0.95rem;
    color: rgba(var(--dataset-accent-rgb), 0.56);
    font-size: 0.68rem;
    font-weight: 850;
    letter-spacing: 0.08em;
}

.dataset-item h4 {
    margin: 0 2rem 0.75rem 0;
    color: #14384f;
    font-size: 0.97rem;
    font-weight: 750;
    line-height: 1.45;
    overflow-wrap: anywhere;
}

.dataset-item-description {
    margin: -0.25rem 0 0.8rem;
    color: #6a7e8c;
    font-size: 0.82rem;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.dataset-item-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    margin-bottom: 0.9rem;
}

.dataset-item-meta span {
    display: inline-flex;
    min-width: 0;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.55rem;
    border-radius: 9px;
    color: #5e7584;
    background: #f0f6f9;
    font-size: 0.74rem;
    font-weight: 650;
    overflow-wrap: anywhere;
}

.dataset-item-meta i {
    flex: 0 0 auto;
    color: var(--dataset-accent);
}

.dataset-item-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding-top: 0.85rem;
    border-top: 1px solid #e8eff3;
}

.dataset-format {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.42rem 0.68rem;
    border: 1px solid transparent;
    border-radius: 9px;
    font-size: 0.72rem;
    font-weight: 800;
    text-decoration: none;
    transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
}

.dataset-format:hover {
    transform: translateY(-2px);
}

.dataset-format-csv {
    border-color: #f3d28c;
    color: #8b5905;
    background: #fff5d9;
}

.dataset-format-csv:hover {
    color: #fff;
    background: #d98c08;
}

.dataset-format-xlsx {
    border-color: #9ddbc9;
    color: #057258;
    background: #e5f8f2;
}

.dataset-format-xlsx:hover {
    color: #fff;
    background: #079273;
}

.dataset-showcase-all {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.78rem 1.2rem;
    border: 1px solid rgba(15, 95, 168, 0.28);
    border-radius: 999px;
    color: #0f5fa8;
    background: rgba(255, 255, 255, 0.72);
    font-weight: 750;
    text-decoration: none;
    box-shadow: 0 8px 20px rgba(12, 50, 82, 0.07);
    transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
}

.dataset-showcase-all:hover {
    color: #fff;
    background: #0f5fa8;
    transform: translateY(-2px);
}

@media (max-width: 991.98px) {
    .dataset-category-grid {
        grid-template-columns: 1fr;
    }

    .dataset-card-list {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 575.98px) {
    .dataset-showcase {
        border-radius: 24px;
    }

    .dataset-showcase-heading {
        align-items: flex-start;
    }

    .dataset-showcase-heading-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
    }

    .dataset-showcase-heading-line {
        display: none;
    }

    .dataset-card-list {
        grid-template-columns: 1fr;
    }

    .dataset-category {
        border-radius: 20px;
    }
}

.service-card-compact {
    border-radius: 1.5rem;
    background: #fff;
    box-shadow: 0 8px 32px 0 rgba(40,167,69,0.13), 0 1.5px 6px 0 rgba(0,0,0,0.04);
    transition: box-shadow 0.2s, transform 0.2s;
    min-height: 260px;
    max-height: 340px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    border: none;
    padding: 1.5rem 1rem;
}
.service-card-compact:hover {
    box-shadow: 0 16px 48px 0 rgba(40,167,69,0.22), 0 3px 12px 0 rgba(0,0,0,0.08);
    transform: translateY(-6px) scale(1.03);
    z-index: 2;
}
@media (max-width: 991px) {
    .service-card-compact {
        min-height: 200px;
        max-height: 280px;
        padding: 1rem 0.5rem;
    }
}
@media (max-width: 767px) {
    .service-card-compact {
        min-height: 140px;
        max-height: 220px;
        padding: 0.75rem 0.25rem;
    }
    .service-card-compact img {
        width: 70px !important;
        height: 70px !important;
    }
}
.service-logo-container {
    width: 100%;
    min-height: 100px;
    height: 110px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.service-logo-img {
    max-width: 70%;
    max-height: 90px;
    width: auto;
    height: auto;
    object-fit: contain;
}
@media (max-width: 991px) {
    .service-logo-container {
        min-height: 80px;
        height: 90px;
    }
    .service-logo-img {
        max-height: 70px;
    }
}
@media (max-width: 767px) {
    .service-logo-container {
        min-height: 70px;
        height: 80px;
    }
    .service-logo-img {
        max-height: 65px;
    }
}
    .job-card {
    transition: box-shadow 0.3s ease;
}

.job-card:hover {
    box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.1);
}

.job-card img {
    max-height: 60px;
}
.job-card {
    font-size: 0.9rem;
    transition: transform 0.2s ease;
}
.job-card:hover {
    transform: scale(1.02);
}

/* Social Floating Button (Desktop Only) */
.social-float {
    position: fixed;
    left: calc(32px + 48px + 16px); /* beside Back to Top */
    bottom: 32px; /* same as Back to Top */
    z-index: 9998;
    display: none; /* hidden on mobile by default */
    padding-bottom: 0; /* keep exact alignment with Back to Top */
}
/* Invisible hover bridge between button and menu without changing layout */
.social-float::before {
    content: '';
    position: absolute;
    left: -8px;
    right: -8px;
    bottom: 48px; /* above the fab */
    height: 88px; /* bridge area to reach the menu */
    background: transparent;
}
@media (min-width: 992px) {
    .social-float { display: block; }
}
.social-fab {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #fff;
    color: #e91e63;
    border-radius: 999px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12), 0 0 0 2px #fff inset;
}
.social-fab .fab-icons i { margin-right: 6px; }
.social-fab .fab-icons i:last-child { margin-right: 0; }

.social-menu {
    position: absolute;
    bottom: 64px; /* sits right above the fab; no gap */
    right: 0;
    width: 280px;
    background: #ffffff;
    border-radius: 18px;
    padding: 14px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.18), 0 0 0 2px rgba(255,255,255,0.9) inset;
    transform-origin: bottom right;
    transform: translateY(6px) scale(0.98);
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: transform .18s ease, opacity .18s ease, visibility .18s;
}
.social-menu:after {
    content: '';
    position: absolute;
    bottom: -10px;
    right: 40px;
    border-width: 10px 10px 0 10px;
    border-style: solid;
    border-color: #ffffff transparent transparent transparent;
    filter: drop-shadow(0 -1px 1px rgba(0,0,0,.06));
}
.social-float:hover .social-menu,
.social-menu:hover,
.social-float:focus-within .social-menu {
    transform: translateY(0) scale(1);
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}
.social-chip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    width: 100%;
    background: #1f2b3a;
    color: #fff;
    border-radius: 12px;
    padding: 10px 12px;
    margin-bottom: 10px;
    text-decoration: none;
}
.social-chip .chip-left { display: inline-flex; align-items: center; gap: 10px; }
.social-chip .chip-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: rgba(255,255,255,0.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.social-chip:hover { background: #213246; }

.social-other {
    display: block;
    width: 100%;
    text-align: center;
    padding: 10px 12px;
    border-radius: 12px;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
    background: linear-gradient(90deg, #ff6a00, #ee0979, #7F00FF);
}
.social-other:hover { filter: brightness(1.05); }


/* Mitra Kerja: make stakeholder cards transparent */
.stakeholder-card {
    background-color: transparent !important;
    box-shadow: none !important;
}

/* Mitra Kerja: 9x4 grid layout */
.mitra-grid {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    gap: 0.25rem;
    align-items: center;
    justify-items: center;
}
.stakeholder-logo {
    max-width: 140px;
    max-height: 100px;
    width: 100%;
    height: auto;
    object-fit: contain;
}
/* Responsive column count for Mitra grid */
@media (max-width: 1399.98px) {
    .mitra-grid { grid-template-columns: repeat(9, 1fr); }
}
@media (max-width: 1199.98px) {
    .mitra-grid { grid-template-columns: repeat(8, 1fr); }
}
@media (max-width: 991.98px) {
    .mitra-grid { grid-template-columns: repeat(6, 1fr); }
    .stakeholder-logo { max-width: 120px; max-height: 90px; }
}
@media (max-width: 767.98px) {
    .mitra-grid { grid-template-columns: repeat(4, 1fr); }
    .stakeholder-logo { max-width: 100px; max-height: 70px; }
}
@media (max-width: 399.98px) {
    .mitra-grid { grid-template-columns: repeat(2, 1fr); }
    .stakeholder-logo { max-width: 90px; max-height: 60px; }
}
.mitra-grid .stakeholder-card {
    background-color: transparent !important;
    box-shadow: none !important;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* WhatsApp Floating Button */
.whatsapp-float {
    position: fixed;
    right: 24px;
    bottom: 24px;
    height: 56px;
    min-width: 56px;
    padding: 0 16px;
    border-radius: 999px;
    background: #25D366; /* WhatsApp green */
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    text-decoration: none;
    z-index: 10001; /* above mini-video */
    transition: transform .15s ease, filter .15s ease;
}
.whatsapp-float:hover {
    filter: brightness(1.05);
    transform: translateY(-2px);
}

/* WBS Floating Button */
.wbs-float {
    position: fixed;
    right: 24px;
    bottom: 92px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    z-index: 10000;
    transition: transform .15s ease, box-shadow .15s ease;
}

.wbs-float:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.2);
}

.wbs-float img {
    display: block;
    width: 180px;
    height: auto;
    max-width: 40vw;
}

.wa-label {
    font-weight: 700;
    font-size: 0.95rem;
    color: #ffffff;
    white-space: nowrap;
}

@media (max-width: 575.98px) {
    .wbs-float {
        right: 16px;
        bottom: 88px;
        padding: 6px;
        border-radius: 14px;
    }
    .wbs-float img {
        width: 130px;
        max-width: 48vw;
    }
    .whatsapp-float { padding: 0 12px; }
    .whatsapp-float .wa-label { display: none; }
}

html.dark-mode .home-ocean-theme {
    --home-ocean-bg:
        radial-gradient(980px 520px at -10% -10%, rgba(37, 99, 235, 0.16), transparent 58%),
        radial-gradient(920px 520px at 110% -6%, rgba(16, 185, 129, 0.12), transparent 60%),
        #0b1120;
    color: var(--theme-text);
}
html.dark-mode .home-ocean-theme .card:not(.home-ocean-banner),
html.dark-mode .home-ocean-theme .contrib-card-v3,
html.dark-mode .home-ocean-theme .service-card-v2,
html.dark-mode .home-ocean-theme .service-card-compact {
    background: linear-gradient(165deg, #17243a, #111b2d) !important;
    color: var(--theme-text) !important;
    border-color: var(--theme-border) !important;
}
html.dark-mode .home-ocean-theme .card::before {
    background: linear-gradient(130deg, rgba(47, 159, 232, 0.08), rgba(0, 163, 138, 0.06));
    opacity: 0.45;
}
html.dark-mode .home-ocean-theme .rounded-section-bg {
    background: linear-gradient(135deg, #111b2d, #17243a) !important;
}
html.dark-mode .home-ocean-theme .dataset-bg-yellow,
html.dark-mode .home-ocean-theme .dataset-bg-blue,
html.dark-mode .home-ocean-theme .dataset-bg-green {
    background: #1c2940 !important;
    color: var(--theme-text);
}
html.dark-mode .home-ocean-theme [style*="color:#222"],
html.dark-mode .home-ocean-theme [style*="color: #222"] {
    color: var(--theme-text) !important;
}
html.dark-mode .home-ocean-theme .card-footer {
    border-color: var(--theme-border);
}
html.dark-mode .market-highlight-heading h3 {
    color: var(--theme-text);
}
html.dark-mode .market-highlight-heading-line {
    background: linear-gradient(90deg, rgba(125, 211, 252, 0.34), transparent);
}
html.dark-mode .market-carousel-panel {
    border-color: rgba(var(--market-accent-rgb), 0.24);
    background:
        linear-gradient(120deg, rgba(var(--market-accent-rgb), 0.13), transparent 32%),
        rgba(17, 27, 45, 0.78);
}
html.dark-mode .dataset-showcase {
    border-color: var(--theme-border);
    background:
        radial-gradient(circle at 4% 10%, rgba(250, 187, 35, 0.09), transparent 22%),
        radial-gradient(circle at 97% 88%, rgba(0, 163, 138, 0.1), transparent 25%),
        linear-gradient(145deg, #111b2d, #17243a);
}
html.dark-mode .dataset-showcase-heading h2 {
    color: var(--theme-text);
}
html.dark-mode .dataset-category {
    background:
        linear-gradient(180deg, rgba(var(--dataset-accent-rgb), 0.14), transparent 135px),
        rgba(17, 27, 45, 0.8);
}
html.dark-mode .dataset-category-header h3 {
    color: var(--theme-text);
}
html.dark-mode .dataset-category-count {
    background: rgba(15, 23, 42, 0.68);
}
html.dark-mode .dataset-item {
    background: #17243a;
    border-color: rgba(var(--dataset-accent-rgb), 0.2);
}
html.dark-mode .dataset-item h4 {
    color: var(--theme-text);
}
html.dark-mode .dataset-item-description,
html.dark-mode .dataset-item-meta span {
    color: var(--theme-text-muted);
}
html.dark-mode .dataset-item-meta span {
    background: #1c2940;
}
html.dark-mode .dataset-item-actions {
    border-color: var(--theme-border);
}
html.dark-mode .dataset-showcase-all {
    color: #7dd3fc;
    background: rgba(15, 23, 42, 0.62);
}

</style>
@endpush 