@extends('layouts.app')

@section('content')
<section class="market-info-hero">
    <span class="market-info-hero-grid" aria-hidden="true"></span>
    <span class="market-info-hero-orb market-info-hero-orb-one" aria-hidden="true"></span>
    <span class="market-info-hero-orb market-info-hero-orb-two" aria-hidden="true"></span>
    <img src="{{ asset('images/logo_siapkerja_white.svg') }}" class="market-info-hero-mark" alt="" aria-hidden="true">
    <div class="market-info-hero-content">
        <span class="market-info-eyebrow"><i class="fa-solid fa-chart-line"></i> Data & Analitik</span>
        <h1>Informasi Pasar Kerja</h1>
        <p>Temukan data, tren, dan analisis terbaru seputar pasar kerja di Indonesia.</p>
    </div>
</section>

<div class="container-fluid p-0 info-ocean-theme">
    @php $forceDesktop = request()->cookie('force_desktop') === '1'; @endphp
    @php
        $marketSections = [
            [
                'key' => '3',
                'title' => 'Struktur Ketenagakerjaan',
                'items' => $informasiSection3,
                'detailRoute' => route('dashboard.performance'),
                'icon' => 'fa-chart-pie',
                'tone' => 'purple',
            ],
            [
                'key' => '1',
                'title' => 'Kebutuhan Tenaga Kerja',
                'items' => $informasiSection1,
                'detailRoute' => route('dashboard.trend'),
                'icon' => 'fa-arrow-trend-up',
                'tone' => 'blue',
            ],
            [
                'key' => '2',
                'title' => 'Persediaan Tenaga Kerja',
                'items' => $informasiSection2,
                'detailRoute' => route('dashboard.distribution'),
                'icon' => 'fa-users',
                'tone' => 'green',
            ],
        ];
    @endphp

    <nav class="market-info-nav" aria-label="Navigasi Informasi Pasar Kerja">
        <div class="market-info-nav-inner">
            @foreach($marketSections as $marketSection)
                <a href="#market-section-{{ $marketSection['key'] }}" class="market-info-nav-link market-tone-{{ $marketSection['tone'] }}">
                    <span><i class="fa-solid {{ $marketSection['icon'] }}"></i></span>
                    {{ $marketSection['title'] }}
                </a>
            @endforeach
        </div>
    </nav>
    <main class="market-info-content">
        @foreach($marketSections as $marketSection)
            @php
                $sectionKey = $marketSection['key'];
                $sectionItems = $marketSection['items'];
                $sectionTotal = count($sectionItems);
                $buttonSuffix = $sectionKey === '1' ? '' : $sectionKey;
            @endphp
            <section id="market-section-{{ $sectionKey }}"
                class="market-analytics-section market-tone-{{ $marketSection['tone'] }}"
                data-aos="fade-up">
                <span class="market-section-decoration" aria-hidden="true"></span>
                <header class="market-section-header">
                    <div class="market-section-heading">
                        <span class="market-section-icon"><i class="fa-solid {{ $marketSection['icon'] }}"></i></span>
                        <div>
                            <span class="market-section-index">0{{ $loop->iteration }}</span>
                            <h2>{{ $marketSection['title'] }}</h2>
                        </div>
                    </div>
                    <span class="market-section-count">{{ str_pad($sectionTotal, 2, '0', STR_PAD_LEFT) }}</span>
                </header>

                <div class="row g-4" id="section{{ $sectionKey }}Cards">
                    @foreach($sectionItems as $info)
                        @php
                            $embedCode = $info->tableau_embed_code;
                            if ($forceDesktop) {
                                $embedCode = str_replace([
                                    ':device=phone',
                                    ':device=tablet',
                                    'name="device" value="phone"',
                                    'name="device" value="tablet"'
                                ], [
                                    ':device=desktop',
                                    ':device=desktop',
                                    'name="device" value="desktop"',
                                    'name="device" value="desktop"'
                                ], $embedCode);
                            }
                            $embedCode = str_replace([
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
                            ], $embedCode);
                        @endphp
                        <div class="col-12 col-xl-6 d-flex align-items-stretch section{{ $sectionKey }}-card{{ $loop->iteration > 4 ? ' d-none' : '' }}">
                            <article class="market-dashboard-card">
                                <header class="market-dashboard-card-header">
                                    <span class="market-dashboard-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <h3>{{ $info->title }}</h3>
                                        @if($info->description)
                                            <p>{{ $info->description }}</p>
                                        @endif
                                    </div>
                                </header>
                                <div class="market-dashboard-viewer">
                                    <div class="tableau-embed-wrapper">
                                        <div class="market-dashboard-embed">
                                            {!! $embedCode !!}
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ $marketSection['detailRoute'] }}" class="d-none">Lihat Detail</a>
                            </article>
                        </div>
                    @endforeach
                </div>

                @if($sectionTotal > 4)
                    <div class="market-section-actions">
                        <button id="lihatLebihBanyakBtn{{ $buttonSuffix }}" class="market-more-button" type="button">
                            Lihat lebih banyak <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <a href="{{ $marketSection['detailRoute'] }}"
                            id="section{{ $sectionKey }}LihatDetailBtn"
                            class="market-detail-button d-none">
                            Lihat Detail <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                @endif
            </section>
        @endforeach
    </main>
    
    <!-- <section class="my-5 mb-5 px-2 px-md-4 px-lg-5 pt-custom" data-aos="fade-up">
        <div class="section-green-card">
            <h3 class="text-center mb-4">Informasi Pasar Kerja 4</h3>
            <div class="row gx-3 gy-4 justify-content-center" id="section4Cards">
                @php $count4 = 0; $total4 = count($informasiSection4); @endphp
                @foreach($informasiSection4 as $info)
                    @php $count4++; @endphp
                    <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch section4-card{{ $count4 > 4 ? ' d-none' : '' }}">
                        <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <h5 class="fw-bold mb-2">{{ $info->title }}</h5>
                            <p class="mb-2 text-muted">{{ $info->description }}</p>
                            <div class="w-100" style="min-height: 350px;">
                                <div class="tableau-embed-wrapper w-100" style="min-height: 350px; overflow-x: auto;">
                                    <div style="min-width: 400px;">
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
                                        ], $info->tableau_embed_code) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($total4 > 4)
                <div class="text-center mt-3">
                    <button id="lihatLebihBanyakBtn4" class="btn btn-success px-4">Lihat lebih banyak</button>
                </div>
            @endif
        </div>
    </section> -->

    {{-- Publikasi Cards Carousel (Floating over Banner) --}}
    <!-- <section class="stat-carousel-section position-relative mt-5 pt-custom" style="z-index: 10; margin-top: -90px;">
        <div class="section-green-card">
            <div class="container-fluid px-2 px-md-4 px-lg-5 position-relative" style="max-width:1400px;">
                <h3 class="text-center mb-4">Publikasi</h3>
                <div class="d-flex align-items-center position-relative">
                    <div id="publikasiScrollRow"
                         class="d-flex flex-nowrap overflow-auto w-100"
                         style="gap:32px; scrollbar-width:thin; padding-bottom: 8px;">
                        @foreach($publikasi as $pub)
                            <a href="{{ route('informasi.index', ['type' => 'publikasi', 'subject' => $pub->title]) }}" class="text-decoration-none">
                                <div class="card shadow-sm stat-card text-center flex-shrink-0 position-relative overflow-hidden publikasi-card"
                                     style="max-width:340px; min-width:260px; cursor:pointer; padding:0; border:none; min-height:320px;">
                                    @if($pub->image_url)
                                        <div class="position-absolute top-0 start-0 w-100 h-100"
                                             style="background: url('{{ $pub->image_url }}') center center/cover no-repeat; z-index:1;"></div>
                                        <div class="position-absolute top-0 start-0 w-100 h-100"
                                             style="background:rgba(0,0,0,0.35); z-index:2;"></div>
                                    @endif
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center position-relative"
                                         style="z-index:3; min-height:320px;">
                                        <div class="stat-title fw-bold mb-2 text-white" style="font-size:1.35rem; line-height:1.2;">{{ $pub->title }}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3" id="publikasiDots"></div>
            </div>
        </div>
    </section> -->
    <style>
    #publikasiScrollRow {
        gap: 32px;
        overflow-x: auto;
        scrollbar-width: thin;
        padding-bottom: 8px;
    }
    .publikasi-card {
        min-width: 260px;
        max-width: 340px;
        min-height: 320px;
    }
    @media (max-width: 1200px) {
        #publikasiScrollRow { gap: 20px !important; }
        .publikasi-card { min-width: 200px !important; max-width: 240px !important; }
    }
    @media (max-width: 900px) {
        #publikasiScrollRow { gap: 12px !important; }
        .publikasi-card { min-width: 160px !important; max-width: 180px !important; min-height: 180px !important; }
    }
    @media (max-width: 600px) {
        #publikasiScrollRow { gap: 8px !important; }
        .publikasi-card { min-width: 120px !important; max-width: 150px !important; min-height: 120px !important; }
    }
    .floating-desktop-btn {
        position: fixed;
        right: 16px;
        bottom: 16px;
        z-index: 1050;
        background: #0f5fa8;
        color: #fff;
        border: none;
        border-radius: 9999px;
        padding: 12px 16px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    html {
        scroll-behavior: smooth;
    }

    .market-info-hero {
        position: relative;
        min-height: 330px;
        display: flex;
        align-items: center;
        overflow: hidden;
        color: #fff;
        background:
            radial-gradient(circle at 82% 14%, rgba(45, 212, 191, 0.24), transparent 25%),
            radial-gradient(circle at 12% 95%, rgba(59, 130, 246, 0.24), transparent 30%),
            linear-gradient(125deg, #052b49 0%, #0b5c7a 52%, #078475 100%);
    }

    .market-info-hero::after {
        content: "";
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 100px;
        background: linear-gradient(to top, rgba(3, 33, 51, 0.32), transparent);
    }

    .market-info-hero-grid {
        position: absolute;
        inset: 0;
        opacity: 0.13;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.42) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.42) 1px, transparent 1px);
        background-size: 52px 52px;
        mask-image: linear-gradient(90deg, #000, transparent 75%);
    }

    .market-info-hero-orb {
        position: absolute;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 50%;
    }

    .market-info-hero-orb-one {
        width: 270px;
        height: 270px;
        top: -175px;
        left: 38%;
    }

    .market-info-hero-orb-two {
        width: 130px;
        height: 130px;
        right: 11%;
        bottom: -55px;
    }

    .market-info-hero-mark {
        position: absolute;
        top: 50%;
        right: clamp(1rem, 7vw, 7rem);
        width: min(380px, 34vw);
        height: 76%;
        object-fit: contain;
        opacity: 0.14;
        transform: translateY(-50%);
    }

    .market-info-hero-content {
        position: relative;
        z-index: 2;
        width: min(1380px, 100%);
        margin: 0 auto;
        padding: 3.6rem clamp(1.25rem, 6vw, 6rem);
    }

    .market-info-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        margin-bottom: 0.8rem;
        color: #82f1df;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.13em;
        text-transform: uppercase;
    }

    .market-info-hero h1 {
        max-width: 850px;
        margin: 0 0 0.8rem;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.7rem);
        font-weight: 850;
        line-height: 1;
        letter-spacing: -0.05em;
    }

    .market-info-hero p {
        max-width: 690px;
        margin: 0;
        color: rgba(255, 255, 255, 0.76);
        font-size: clamp(0.95rem, 1.7vw, 1.15rem);
        line-height: 1.65;
    }

    .info-ocean-theme {
        min-height: 100vh;
        background:
            radial-gradient(850px 480px at -8% 5%, rgba(37, 99, 235, 0.13), transparent 58%),
            radial-gradient(780px 460px at 108% 45%, rgba(16, 185, 129, 0.11), transparent 60%),
            #eff6fb;
    }

    .market-info-nav {
        position: relative;
        z-index: 5;
        padding: 0 clamp(1rem, 4vw, 3rem);
        transform: translateY(-28px);
    }

    .market-info-nav-inner {
        display: flex;
        width: min(1120px, 100%);
        gap: 0.75rem;
        margin: 0 auto;
        padding: 0.6rem;
        overflow-x: auto;
        border: 1px solid rgba(15, 95, 168, 0.12);
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 16px 38px rgba(12, 50, 82, 0.13);
        backdrop-filter: blur(14px);
        scrollbar-width: none;
    }

    .market-info-nav-inner::-webkit-scrollbar {
        display: none;
    }

    .market-info-nav-link {
        --market-accent: #2578d5;
        --market-accent-rgb: 37, 120, 213;
        display: inline-flex;
        min-width: 0;
        flex: 1 0 auto;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 0.7rem 0.9rem;
        border-radius: 14px;
        color: #315266;
        font-size: 0.84rem;
        font-weight: 750;
        text-decoration: none;
        white-space: nowrap;
        transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
    }

    .market-info-nav-link span {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: var(--market-accent);
        background: rgba(var(--market-accent-rgb), 0.11);
    }

    .market-info-nav-link:hover {
        color: var(--market-accent);
        background: rgba(var(--market-accent-rgb), 0.08);
        transform: translateY(-2px);
    }

    .market-info-content {
        width: min(1500px, 100%);
        margin: -0.3rem auto 0;
        padding: 0 clamp(0.75rem, 3.5vw, 3rem) 4rem;
    }

    .market-tone-purple {
        --market-accent: #805ad5;
        --market-accent-rgb: 128, 90, 213;
    }

    .market-tone-blue {
        --market-accent: #2578d5;
        --market-accent-rgb: 37, 120, 213;
    }

    .market-tone-green {
        --market-accent: #079273;
        --market-accent-rgb: 7, 146, 115;
    }

    .market-analytics-section {
        position: relative;
        margin-bottom: 2rem;
        padding: clamp(1rem, 3vw, 2.25rem);
        overflow: hidden;
        border: 1px solid rgba(var(--market-accent-rgb), 0.15);
        border-radius: 30px;
        background:
            linear-gradient(125deg, rgba(var(--market-accent-rgb), 0.08), transparent 26%),
            rgba(255, 255, 255, 0.72);
        box-shadow: 0 16px 40px rgba(12, 50, 82, 0.085);
        scroll-margin-top: 90px;
        backdrop-filter: blur(10px);
    }

    .market-section-decoration {
        position: absolute;
        width: 220px;
        height: 220px;
        top: -150px;
        right: -95px;
        border: 32px solid rgba(var(--market-accent-rgb), 0.07);
        border-radius: 50%;
        pointer-events: none;
    }

    .market-section-header {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .market-section-heading {
        display: flex;
        align-items: center;
        gap: 0.9rem;
    }

    .market-section-icon {
        display: inline-flex;
        width: 54px;
        height: 54px;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        border-radius: 17px;
        color: #fff;
        background: var(--market-accent);
        box-shadow: 0 10px 23px rgba(var(--market-accent-rgb), 0.25);
        font-size: 1.15rem;
    }

    .market-section-index {
        display: block;
        margin-bottom: 0.08rem;
        color: var(--market-accent);
        font-size: 0.7rem;
        font-weight: 850;
        letter-spacing: 0.12em;
    }

    .market-section-heading h2 {
        margin: 0;
        color: #153c56;
        font-size: clamp(1.35rem, 2.3vw, 2rem);
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .market-section-count {
        display: inline-flex;
        min-width: 44px;
        height: 38px;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(var(--market-accent-rgb), 0.2);
        border-radius: 11px;
        color: var(--market-accent);
        background: rgba(255, 255, 255, 0.7);
        font-size: 0.76rem;
        font-weight: 850;
        letter-spacing: 0.08em;
    }

    .market-dashboard-card {
        position: relative;
        display: flex;
        width: 100%;
        min-width: 0;
        flex-direction: column;
        padding: 1rem;
        overflow: hidden;
        border: 1px solid rgba(var(--market-accent-rgb), 0.14);
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 10px 26px rgba(12, 50, 82, 0.075);
        transition: border-color 0.22s ease, box-shadow 0.22s ease, transform 0.22s ease;
    }

    .market-dashboard-card:hover {
        border-color: rgba(var(--market-accent-rgb), 0.38);
        box-shadow: 0 16px 32px rgba(12, 50, 82, 0.13);
        transform: translateY(-3px);
    }

    .market-dashboard-card-header {
        display: grid;
        min-height: 94px;
        grid-template-columns: auto minmax(0, 1fr);
        align-items: start;
        gap: 0.8rem;
        padding: 0.25rem 0.2rem 1rem;
    }

    .market-dashboard-number {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: var(--market-accent);
        background: rgba(var(--market-accent-rgb), 0.1);
        font-size: 0.68rem;
        font-weight: 850;
        letter-spacing: 0.06em;
    }

    .market-dashboard-card h3 {
        margin: 0 0 0.35rem;
        color: #163b52;
        font-size: 1rem;
        font-weight: 800;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .market-dashboard-card p {
        margin: 0;
        color: #657c8b;
        font-size: 0.82rem;
        line-height: 1.55;
        overflow-wrap: anywhere;
    }

    .market-dashboard-viewer {
        position: relative;
        min-width: 0;
        overflow: hidden;
        border: 1px solid #dfe9ef;
        border-radius: 15px;
        background: #eef4f7;
        isolation: isolate;
    }

    .market-dashboard-viewer::before {
        content: "";
        display: block;
        height: 8px;
        background: linear-gradient(90deg, var(--market-accent), rgba(var(--market-accent-rgb), 0.25));
    }

    .tableau-embed-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .market-dashboard-embed {
        min-width: 400px;
        min-height: 427px;
    }

    .market-dashboard-viewer .tableauPlaceholder,
    .market-dashboard-viewer .tableauPlaceholder object {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        height: 427px !important;
    }

    .market-section-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .market-more-button,
    .market-detail-button {
        display: inline-flex;
        min-height: 44px;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.65rem 1rem;
        border: 1px solid rgba(var(--market-accent-rgb), 0.3);
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 750;
        text-decoration: none;
        transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
    }

    .market-more-button {
        color: var(--market-accent);
        background: rgba(255, 255, 255, 0.78);
    }

    .market-detail-button {
        color: #fff;
        background: var(--market-accent);
    }

    .market-more-button:hover,
    .market-detail-button:hover {
        color: #fff;
        background: var(--market-accent);
        transform: translateY(-2px);
    }

    @media (max-width: 767.98px) {
        .market-info-hero {
            min-height: 300px;
        }

        .market-info-hero-mark {
            width: 62vw;
            opacity: 0.09;
        }

        .market-info-nav {
            padding: 0 0.75rem;
        }

        .market-info-nav-inner {
            justify-content: flex-start;
        }

        .market-info-nav-link {
            flex: 0 0 auto;
        }

        .market-analytics-section {
            border-radius: 22px;
        }

        .market-dashboard-card {
            border-radius: 18px;
        }
    }

    @media (max-width: 575.98px) {
        .market-info-hero-content {
            padding-right: 1rem;
            padding-left: 1rem;
        }

        .market-info-content {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }

        .market-section-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
        }

        .market-section-count {
            display: none;
        }

        .market-dashboard-card {
            padding: 0.75rem;
        }
    }

    html.dark-mode .info-ocean-theme {
        background:
            radial-gradient(850px 480px at -8% 5%, rgba(37, 99, 235, 0.11), transparent 58%),
            radial-gradient(780px 460px at 108% 45%, rgba(16, 185, 129, 0.09), transparent 60%),
            var(--theme-page);
        color: var(--theme-text);
    }

    html.dark-mode .market-info-nav-inner,
    html.dark-mode .market-analytics-section,
    html.dark-mode .market-dashboard-card {
        border-color: var(--theme-border);
        background-color: #111b2d;
    }

    html.dark-mode .market-info-nav-inner {
        background: rgba(17, 27, 45, 0.92);
    }

    html.dark-mode .market-analytics-section {
        background:
            linear-gradient(125deg, rgba(var(--market-accent-rgb), 0.11), transparent 26%),
            rgba(17, 27, 45, 0.82);
    }

    html.dark-mode .market-dashboard-card {
        background: #17243a;
    }

    html.dark-mode .market-info-nav-link,
    html.dark-mode .market-section-heading h2,
    html.dark-mode .market-dashboard-card h3 {
        color: var(--theme-text);
    }

    html.dark-mode .market-dashboard-card p {
        color: var(--theme-text-muted);
    }

    html.dark-mode .market-section-count,
    html.dark-mode .market-more-button {
        background: rgba(15, 23, 42, 0.7);
    }

    html.dark-mode .market-dashboard-viewer {
        border-color: var(--theme-border);
        background: #0f172a;
    }
    </style>

    
</div>
<button id="requestDesktopBtn" type="button" class="floating-desktop-btn d-none">Request Desktop Site</button>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Publikasi Carousel Logic
    const pubRow = document.getElementById('publikasiScrollRow');
    const pubPrev = document.getElementById('publikasiScrollPrev');
    const pubNext = document.getElementById('publikasiScrollNext');
    const pubDots = document.getElementById('publikasiDots');
    if(pubRow && pubPrev && pubNext && pubDots) {
        const pubCards = pubRow.querySelectorAll('.stat-card');
        const pubCardWidth = 256; // width + gap
        let pubVisible = Math.floor(pubRow.offsetWidth / pubCardWidth) || 1;
        const pubTotal = pubCards.length;
        let pubCurrent = 0;

        function updatePubDots() {
            pubDots.innerHTML = '';
            pubVisible = Math.floor(pubRow.offsetWidth / pubCardWidth) || 1;
            const dotCount = Math.ceil(pubTotal / pubVisible);
            for (let i = 0; i < dotCount; i++) {
                const dot = document.createElement('span');
                dot.className = 'stat-dot' + (i === Math.floor(pubCurrent / pubVisible) ? ' active' : '');
                dot.addEventListener('click', () => {
                    pubRow.scrollTo({ left: i * pubCardWidth * pubVisible, behavior: 'smooth' });
                    pubCurrent = i * pubVisible;
                    updatePubDots();
                });
                pubDots.appendChild(dot);
            }
        }

        function scrollToPubCurrent() {
            pubRow.scrollTo({ left: pubCurrent * pubCardWidth, behavior: 'smooth' });
            updatePubDots();
        }

        pubPrev.addEventListener('click', () => {
            pubCurrent = Math.max(0, pubCurrent - pubVisible);
            scrollToPubCurrent();
        });
        pubNext.addEventListener('click', () => {
            pubCurrent = Math.min(pubTotal - pubVisible, pubCurrent + pubVisible);
            scrollToPubCurrent();
        });

        pubRow.addEventListener('scroll', () => {
            pubCurrent = Math.round(pubRow.scrollLeft / pubCardWidth);
            updatePubDots();
        });

        window.addEventListener('resize', () => {
            updatePubDots();
        });

        updatePubDots();
    }

    // Lihat lebih banyak logic for Section 1
    const lihatLebihBanyakBtn = document.getElementById('lihatLebihBanyakBtn');
    const section1LihatDetailBtn = document.getElementById('section1LihatDetailBtn');
    if (lihatLebihBanyakBtn) {
        lihatLebihBanyakBtn.addEventListener('click', function() {
            document.querySelectorAll('.section1-card.d-none').forEach(function(card) {
                card.classList.remove('d-none');
            });
            lihatLebihBanyakBtn.style.display = 'none';
            if (section1LihatDetailBtn) {
                section1LihatDetailBtn.classList.remove('d-none');
            }
        });
    }
    // Lihat lebih banyak logic for Section 2
    const lihatLebihBanyakBtn2 = document.getElementById('lihatLebihBanyakBtn2');
    const section2LihatDetailBtn = document.getElementById('section2LihatDetailBtn');
    if (lihatLebihBanyakBtn2) {
        lihatLebihBanyakBtn2.addEventListener('click', function() {
            document.querySelectorAll('.section2-card.d-none').forEach(function(card) {
                card.classList.remove('d-none');
            });
            lihatLebihBanyakBtn2.style.display = 'none';
            if (section2LihatDetailBtn) {
                section2LihatDetailBtn.classList.remove('d-none');
            }
        });
    }
    // Lihat lebih banyak logic for Section 3
    const lihatLebihBanyakBtn3 = document.getElementById('lihatLebihBanyakBtn3');
    const section3LihatDetailBtn = document.getElementById('section3LihatDetailBtn');
    if (lihatLebihBanyakBtn3) {
        lihatLebihBanyakBtn3.addEventListener('click', function() {
            document.querySelectorAll('.section3-card.d-none').forEach(function(card) {
                card.classList.remove('d-none');
            });
            lihatLebihBanyakBtn3.style.display = 'none';
            if (section3LihatDetailBtn) {
                section3LihatDetailBtn.classList.remove('d-none');
            }
        });
    }
    // Lihat lebih banyak logic for Section 4
    const lihatLebihBanyakBtn4 = document.getElementById('lihatLebihBanyakBtn4');
    if (lihatLebihBanyakBtn4) {
        lihatLebihBanyakBtn4.addEventListener('click', function() {
            document.querySelectorAll('.section4-card.d-none').forEach(function(card) {
                card.classList.remove('d-none');
            });
            lihatLebihBanyakBtn4.style.display = 'none';
        });
    }
    // Request Desktop Site floating button (mobile only) with forced desktop viewport
    const requestDesktopBtn = document.getElementById('requestDesktopBtn');
    if (requestDesktopBtn) {
        const forceCookieName = 'force_desktop';
        const widthCookieName = 'force_desktop_width';

        function getCookie(name) {
            const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()\[\]\\\/+^])/g, '\\$1') + '=([^;]*)'));
            return match ? decodeURIComponent(match[1]) : null;
        }

        function setCookie(name, value, days) {
            const maxAge = days ? days * 24 * 60 * 60 : 0;
            const parts = [name + '=' + encodeURIComponent(value), 'path=/'];
            if (maxAge > 0) parts.push('max-age=' + maxAge);
            document.cookie = parts.join('; ');
        }

        // Viewport meta is now set in layout head server-side when cookie is present
        function ensureViewportMeta() { return document.querySelector('meta[name="viewport"]'); }

        function computeTargetWidth() {
            // Mimic Chrome's "Desktop site" default layout viewport ~980 CSS px
            return 980;
        }

        function applyForcedDesktopViewport(targetWidth) {
            const viewportMeta = ensureViewportMeta();
            if (viewportMeta) {
                const deviceWidth = window.innerWidth || document.documentElement.clientWidth || 375;
                const scale = Math.min(1, deviceWidth / targetWidth);
                viewportMeta.setAttribute('content', 'width=' + targetWidth + ', initial-scale=' + scale + ', minimum-scale=' + scale + ', maximum-scale=' + scale + ', user-scalable=no');
            }
        }

        const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
        const isForcedDesktop = getCookie(forceCookieName) === '1';
        const storedWidth = parseInt(getCookie(widthCookieName) || '', 10);

        if (isForcedDesktop) {
            const targetWidth = isNaN(storedWidth) ? computeTargetWidth() : storedWidth;
            applyForcedDesktopViewport(targetWidth);
            requestDesktopBtn.classList.add('d-none');
        } else if (isMobileDevice) {
            requestDesktopBtn.classList.remove('d-none');
        }

        requestDesktopBtn.addEventListener('click', () => {
            const targetWidth = computeTargetWidth();
            setCookie(forceCookieName, '1', 30);
            setCookie(widthCookieName, String(targetWidth), 30);
            window.location.reload();
        });

        window.addEventListener('resize', () => {
            const isNowMobile = window.innerWidth <= 768;
            const forced = getCookie(forceCookieName) === '1';
            requestDesktopBtn.classList.toggle('d-none', !isNowMobile || forced);
            // No dynamic re-application after load to prevent flicker
        });
    }
});
</script>
@endpush