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

    {{-- Highlight Pasar Kerja Section (Both Carousels) --}}
    <section class="stat-carousel-section position-relative" style="z-index: 10;">
        <div class="container-fluid position-relative px-2 px-sm-3">
            <h3 class="text-center mb-4">Highlight Pasar Kerja</h3>
            {{-- First Carousel --}}
            <div class="d-flex align-items-center position-relative mb-4">
                <button id="statScrollPrev" class="btn btn-light shadow rounded-circle position-absolute start-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <div id="statScrollRow" class="d-flex justify-content-center px-0" style="scroll-behavior:smooth; gap:16px; width:100%; overflow-x:hidden;">
                    @foreach($statistics as $stat)
                        <a href="{{ route('informasi.index', ['type' => 'statistik', 'search' => $stat->title]) }}" class="text-decoration-none">
                            <div class="card shadow-sm stat-card text-center flex-shrink-0" style="max-width:260px; min-width:180px; cursor:pointer;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <div class="stat-icon mb-3">
                                        <i class="fa {{ $stat->logo ?? 'fa-chart-bar' }} fa-2x text-success"></i>
                                    </div>
                                    <div class="stat-title fw-bold mb-1" style="font-size:1.1rem; color:#187C19;">{{ $stat->title }}</div>
                                    <div class="stat-value fw-bold mb-0" style="font-size:2.2rem; color:#222;">{{ $stat->value }}</div>
                                    @if($stat->unit)
                                        <div class="stat-unit" style="font-size:1.2rem; color:#222;">{{ $stat->unit }}</div>
                                    @endif
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
            {{-- Second Carousel --}}
            <div class="d-flex align-items-center position-relative">
                <button id="highlightStatScrollPrev" class="btn btn-light shadow rounded-circle position-absolute start-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <div id="highlightStatScrollRow" class="d-flex justify-content-center px-0" style="scroll-behavior:smooth; gap:16px; width:100%; overflow-x:hidden;">
                    @foreach($highlightStatistics as $stat)
                        <a href="{{ route('informasi.index', ['type' => 'statistik', 'search' => $stat->title]) }}" class="text-decoration-none">
                            <div class="card shadow-sm stat-card text-center flex-shrink-0" style="max-width:260px; min-width:180px; cursor:pointer;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <div class="stat-icon mb-3">
                                        <i class="fa {{ $stat->logo ?? 'fa-star' }} fa-2x text-warning"></i>
                                    </div>
                                    <div class="stat-title fw-bold mb-1" style="font-size:1.1rem; color:#187C19;">{{ $stat->title }}</div>
                                    <div class="stat-value fw-bold mb-0" style="font-size:2.2rem; color:#222;">{{ $stat->value }}</div>
                                    @if($stat->unit)
                                        <div class="stat-unit" style="font-size:1.2rem; color:#222;">{{ $stat->unit }}</div>
                                    @endif
                                    @if($stat->description)
                                        <div class="stat-desc text-muted mt-1" style="font-size:0.95rem;">{{ $stat->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button id="highlightStatScrollNext" class="btn btn-light shadow rounded-circle position-absolute end-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
            <div class="d-flex justify-content-center mt-3" id="highlightStatDots"></div>
            {{-- Third Carousel (Secondary Highlights) with Blue Background Wrapper --}}
            <div class="my-4 py-4 text-white rounded-4" style="background: linear-gradient(to right, #388FE8, #4DA4F3);">
                <div class="container-fluid px-2 px-sm-3">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <a href="https://karirhub.kemnaker.go.id/" target="_blank">
                                <img src="{{ asset('images/services/karirhub_logo.png') }}" alt="Karirhub" class="img-fluid" style="max-height: 50px;">
                            </a>
                            <h2 class="fw-bold" style="font-size:3rem;">Informasi bersumber dari Karirhub</h2>
                        </div>
                        <div class="col-md-8 position-relative">
                            <div class="d-flex align-items-center position-relative">
                                <button id="highlightStat2ScrollPrev" class="btn btn-light shadow rounded-circle position-absolute start-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                                <div id="highlightStat2ScrollRow" class="d-flex px-0" style="scroll-behavior:smooth; gap:16px; width:100%; overflow-x:hidden;">
                                    @foreach($highlightStatistics2 ?? [] as $stat)
                                        <a href="{{ route('informasi.index', ['type' => 'statistik', 'search' => $stat->title]) }}" class="text-decoration-none">
                                            <div class="card shadow-sm stat-card text-center flex-shrink-0" style="max-width:260px; min-width:180px; cursor:pointer;">
                                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                                    <div class="stat-icon mb-3">
                                                        <i class="fa {{ $stat->logo ?? 'fa-diamond' }} fa-2x text-info"></i>
                                                    </div>
                                    <div class="stat-title fw-bold mb-1" style="font-size:1.1rem; color:#187C19;">{{ $stat->title }}</div>
                                    <div class="stat-value fw-bold mb-0" style="font-size:2.2rem; color:#222;">{{ $stat->value }}</div>
                                    @if($stat->unit)
                                        <div class="stat-unit" style="font-size:1.2rem; color:#222;">{{ $stat->unit }}</div>
                                    @endif
                                                    @if($stat->description)
                                                        <div class="stat-desc text-muted mt-1" style="font-size:0.95rem;">{{ $stat->description }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <button id="highlightStat2ScrollNext" class="btn btn-light shadow rounded-circle position-absolute end-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-center mt-3" id="highlightStat2Dots"></div>
                        </div>
                    </div>
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

    {{-- Informasi Terbaru --}}
    <section class="my-5 px-2 px-md-4 px-lg-5" id="informasi-terbaru" data-aos="fade-up">
        <h3 class="text-center mb-4">Data dan Informasi</h3>
        <ul class="nav nav-tabs justify-content-center mb-4" id="infoTab" role="tablist" >
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="spark-tab" data-bs-toggle="tab" data-bs-target="#spark" type="button" role="tab" aria-controls="spark" aria-selected="true">Seputar Pasar Kerja (SPARK)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="infografis-sipk-tab" data-bs-toggle="tab" data-bs-target="#infografis-sipk" type="button" role="tab" aria-controls="infografis-sipk" aria-selected="false">Infografis SIPK</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="angkatan-kerja-tab" data-bs-toggle="tab" data-bs-target="#angkatan-kerja" type="button" role="tab" aria-controls="angkatan-kerja" aria-selected="false">Angkatan Kerja</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="regulasi-tab" data-bs-toggle="tab" data-bs-target="#regulasi" type="button" role="tab" aria-controls="regulasi" aria-selected="false">Pedoman / Regulasi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lmir-tab" data-bs-toggle="tab" data-bs-target="#lmir" type="button" role="tab" aria-controls="lmir" aria-selected="false">Labour Market Inteligence Report</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="infografis-job-fair-tab" data-bs-toggle="tab" data-bs-target="#infografis-job-fair" type="button" role="tab" aria-controls="infografis-job-fair" aria-selected="false">Infografis Job Fair</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="spark" role="tabpanel" aria-labelledby="spark-tab">
                @foreach($spark as $info)
                    <a href="{{ route('informasi.index', ['subject' => $info->subject, 'show' => $info->id]) }}" class="text-decoration-none">
                        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="cursor:pointer;">
                            <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-bolt"></i></div>
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
            <div class="tab-pane fade" id="infografis-sipk" role="tabpanel" aria-labelledby="infografis-sipk-tab">
                @foreach($infografisSIPK as $info)
                    <a href="{{ route('informasi.index', ['subject' => $info->subject, 'show' => $info->id]) }}" class="text-decoration-none">
                        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="cursor:pointer;">
                            <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-chart-pie"></i></div>
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
            <div class="tab-pane fade" id="angkatan-kerja" role="tabpanel" aria-labelledby="angkatan-kerja-tab">
                @foreach($angkatanKerja as $info)
                    <a href="{{ route('informasi.index', ['subject' => $info->subject, 'show' => $info->id]) }}" class="text-decoration-none">
                        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="cursor:pointer;">
                            <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-users"></i></div>
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
            <div class="tab-pane fade" id="regulasi" role="tabpanel" aria-labelledby="regulasi-tab">
                @foreach($regulasi as $info)
                    <a href="{{ route('informasi.index', ['subject' => $info->subject, 'show' => $info->id]) }}" class="text-decoration-none">
                        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="cursor:pointer;">
                            <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-gavel"></i></div>
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
            <div class="tab-pane fade" id="lmir" role="tabpanel" aria-labelledby="lmir-tab">
                @foreach($lmir as $info)
                    <a href="{{ route('informasi.index', ['subject' => $info->subject, 'show' => $info->id]) }}" class="text-decoration-none">
                        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="cursor:pointer;">
                            <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-book-open"></i></div>
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
            <div class="tab-pane fade" id="infografis-job-fair" role="tabpanel" aria-labelledby="infografis-job-fair-tab">
                @foreach($infografisJobFair as $info)
                    <a href="{{ route('informasi.index', ['subject' => $info->subject, 'show' => $info->id]) }}" class="text-decoration-none">
                        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" style="cursor:pointer;">
                            <div class="me-3 text-primary" style="font-size:2rem;"><i class="fa fa-calendar-day"></i></div>
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
            <h2 class="fw-bold" style="font-size:2.2rem;">Layanan Pasar Kerja</h2>
        </div>
        <div class="row justify-content-center g-4">
            @foreach($services as $service)
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
 <section class="my-5 py-5 text-white rounded-4 mx-5" style="background: linear-gradient(to right, #388FE8, #4DA4F3);" data-aos="fade-up">
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
                                                    <img src="data:{{ $ad->mime_type }};base64,{{ $ad->image_base64 }}"
                                                        alt="{{ $ad->company_name }}"
                                                        class="rounded" style="height: 50px; object-fit: contain;">
                                                </div>
                                                <h6 class="fw-bold text-center">{{ \Illuminate\Support\Str::limit($ad->job_title, 15, '...') }}</h6>
                                                <p class="text-center mb-1 text-muted small">{{ \Illuminate\Support\Str::limit($ad->company_name, 20, '...') }}</p>
                                                <p class="text-center mb-1 text-secondary small">
                                                    {{ strtoupper($ad->city ?? 'KOTA BELUM DIISI') }},
                                                    <br>
                                                    {{ strtoupper( Illuminate\Support\Str::limit($ad->province, 10, '...') ?? 'PROVINSI BELUM DIISI') }}
                                                </p>
                                                

                                                <div class="text-center mt-2 mb-2">
                                                    <small class="text-muted">Kisaran Gaji</small>
                                                    @if ($ad->secret == 1)
                                                        <div class="fw-semibold text-primary">Dirahasiakan</div>
                                                    @else
                                                        <div class="fw-semibold text-primary">
                                                            Rp{{ number_format($ad->salary_min / 1000000, 0, ',', '.') }}jt - 
                                                            Rp{{ number_format($ad->salary_max / 1000000, 2, ',', '.') }}jt
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
    <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="font-size:2.2rem;">
            <i class="text-primary"></i>
            Dataset Pasar Kerja
        </h2>
    </div>
    @php
        $categoryBg = [
            'Pencari Kerja' => 'dataset-bg-yellow',
            'Pemberi Kerja' => 'dataset-bg-blue',
            'Lowongan Kerja' => 'dataset-bg-green',
        ];
    @endphp
    <div class="row justify-content-center g-4">
        @foreach($datasets as $category => $cards)
            <div class="col-12 col-md-4">
                <div class="mb-3 p-3 rounded shadow {{ $categoryBg[$category] ?? '' }}">
                    <h4 class="fw-bold mb-2" style="color: #187C19;">
                        <span class="text-danger">Dataset</span> {{ $category }}
                        @if($cards->first()->icon)
                            <i class="fa {{ $cards->first()->icon }} ms-2"></i>
                        @endif
                    </h4>
                    @foreach($cards as $card)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="fw-bold mb-1">{{ $card->title }}</div>
                                <div class="mb-1 text-muted" style="font-size:0.95rem;">
                                    <i class="fa fa-map-marker"></i> {{ $card->location ?? '-' }}<br>
                                    <i class="fa fa-calendar"></i> {{ $card->years ?? '-' }}
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    @if($card->csv_url)
                                        <a href="{{ asset($card->csv_url) }}" class="btn btn-warning btn-sm fw-bold" download>CSV</a>
                                    @endif
                                    @if($card->xlsx_url)
                                        <a href="{{ asset($card->xlsx_url) }}" class="btn btn-success btn-sm fw-bold" target="_blank">XLSX</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('datasets.index') }}" class="btn btn-outline-success rounded-pill px-4 py-2">Lihat Semua</a>
    </div>
</section>


    {{-- Topik Data Section --}}
    <!-- <section class="my-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
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
    </section> -->

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
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center">
            @foreach ($mitraKerja->take(15) as $stakeholder)
                <div class="col d-flex align-items-stretch">
                    <div class="p-4 shadow-sm rounded-4 bg-white h-100 stakeholder-card w-100 transition-all">
                        @if($stakeholder->logo)
                            <div class="mb-2 text-center">
                                <img src="{{ asset($stakeholder->logo) }}" alt="Logo" style="max-width: 160px; max-height: 160px; object-fit: contain;">
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ url('/mitra-kerja') }}" class="btn btn-outline-success rounded-pill px-4 py-2">
                Lihat Semua Mitra <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </section>

    {{-- Testimonials Section --}}
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
                    <i class="fa fa-bolt me-2"></i>
                    Kunjungan Hari Ini: {{ number_format($todayVisits ?? 0) }}
                </span>
                <span class="badge bg-success fs-6 p-3 rounded-pill">
                    <i class="fa fa-users me-2"></i>
                    Total Pengunjung: {{ number_format($totalVisitors ?? 0) }}
                </span>
                <span class="badge bg-success fs-6 p-3 rounded-pill">
                    <i class="fa fa-user-check me-2"></i>
                    Pengunjung Hari Ini: {{ number_format($todayVisitors ?? 0) }}
                </span>
                <span class="badge bg-success fs-6 p-3 rounded-pill">
                    <i class="fa fa-eye me-2"></i>
                    Total Kunjungan: {{ number_format($visitCount) }}
                </span>
            </div>
        </div>
    </section>
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
        <a href="https://www.tiktok.com/@paskerid_" target="_blank" class="social-chip" rel="noopener">
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
        // Auto-shrink description text to fit within fixed box height
        function autoshrinkDescriptions(root) {
            const descriptions = (root || document).querySelectorAll('.stat-card .stat-desc');
            descriptions.forEach((el) => {
                let minSize = 12; // px
                let size = parseFloat(window.getComputedStyle(el).fontSize);
                const original = el.innerText;
                // Reset any previous inline style before measuring
                el.style.fontSize = size + 'px';
                // Reduce font size until it fits within max-height or until min size
                const maxHeight = parseFloat(window.getComputedStyle(el).maxHeight || '0');
                if (!maxHeight) return;
                let guard = 0;
                while (el.scrollHeight > el.clientHeight && size > minSize && guard < 20) {
                    size -= 1;
                    el.style.fontSize = size + 'px';
                    guard++;
                }
            });
        }

        // Run after content and layout are ready
        setTimeout(() => autoshrinkDescriptions(document), 0);
        // Re-run on resize for responsiveness
        window.addEventListener('resize', () => autoshrinkDescriptions(document));

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

    // --- Highlight Stat Carousel ---
    document.addEventListener('DOMContentLoaded', function () {
        const row = document.getElementById('highlightStatScrollRow');
        if (!row) return;
        const prev = document.getElementById('highlightStatScrollPrev');
        const next = document.getElementById('highlightStatScrollNext');
        const dots = document.getElementById('highlightStatDots');
        const cards = row.querySelectorAll('.stat-card');
        const cardWidth = 256;
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
        // Apply autoshrink to ensure descriptions fit after any scroll adjustments
        setTimeout(() => {
            const container = document.getElementById('highlightStatScrollRow');
            if (container) {
                const descriptions = container.querySelectorAll('.stat-desc');
                descriptions.forEach((el) => {
                    let minSize = 12;
                    let size = parseFloat(window.getComputedStyle(el).fontSize);
                    el.style.fontSize = size + 'px';
                    const maxHeight = parseFloat(window.getComputedStyle(el).maxHeight || '0');
                    let guard = 0;
                    while (el.scrollHeight > el.clientHeight && size > minSize && guard < 20) {
                        size -= 1;
                        el.style.fontSize = size + 'px';
                        guard++;
                    }
                });
            }
        }, 0);
    });

    // --- Highlight Stat Carousel 2 ---
    document.addEventListener('DOMContentLoaded', function () {
        const row = document.getElementById('highlightStat2ScrollRow');
        if (!row) return;
        const prev = document.getElementById('highlightStat2ScrollPrev');
        const next = document.getElementById('highlightStat2ScrollNext');
        const dots = document.getElementById('highlightStat2Dots');
        const cards = row.querySelectorAll('.stat-card');
        const cardWidth = 256;
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
        // Apply autoshrink for secondary list as well
        setTimeout(() => {
            const container = document.getElementById('highlightStat2ScrollRow');
            if (container) {
                const descriptions = container.querySelectorAll('.stat-desc');
                descriptions.forEach((el) => {
                    let minSize = 12;
                    let size = parseFloat(window.getComputedStyle(el).fontSize);
                    el.style.fontSize = size + 'px';
                    const maxHeight = parseFloat(window.getComputedStyle(el).maxHeight || '0');
                    let guard = 0;
                    while (el.scrollHeight > el.clientHeight && size > minSize && guard < 20) {
                        size -= 1;
                        el.style.fontSize = size + 'px';
                        guard++;
                    }
                });
            }
        }, 0);
    });

    //for ads
    document.addEventListener('DOMContentLoaded', function () {
        const items = document.querySelectorAll('.ad-item');
        let currentIndex = 0;

        setInterval(() => {
            items[currentIndex].classList.add('d-none');
            items[currentIndex].classList.remove('active');

            currentIndex = (currentIndex + 1) % items.length;

            items[currentIndex].classList.remove('d-none');
            items[currentIndex].classList.add('active');
        }, 3000);
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
.dataset-bg-yellow { background: #fffbe6; }
.dataset-bg-blue   { background: #e6f0ff; }
.dataset-bg-green  { background: #e6ffed; }
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

</style>
@endpush 