@extends('layouts.app')

@section('content')
<section class="section-berita">
    <img src="{{ asset('images/logo_siapkerja_white.svg') }}" class="section-bg" alt="background">
    <div class="section-content">
        <h2 style="color: white; font-weight: bold; margin-left: 80px;">Informasi Pasar Kerja</h2>
        <p style="color: white; margin-left: 80px; font-size: 20px">Temukan data, tren, dan analisis terbaru seputar pasar kerja di Indonesia.</p>
    </div>
</section>
<style>
.section-berita {
    position: relative;
    background-color: #00a78e;
    padding: 30px 40px;
    overflow: hidden;
    margin-bottom: 0 !important;
}
.section-bg {
    position: absolute;
    top: 0;
    right: 0;
    width: 400px;
    height: 100%;
    object-fit: cover;
    opacity: 0.4;
}
.section-content {
    position: relative;
    z-index: 1;
    max-width: 1200px;
}
.pt-custom {
    padding-top: 60px;
}
.section-green-card {
    background: rgba(255, 255, 255, 0.13); /* very transparent white */
    border-radius: 24px;
    padding: 40px 32px 32px 32px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.08);
    color: #fff;
    margin-bottom: 32px;
    border: 2px solid rgba(255,255,255,0.45); /* brighter, more visible */
    backdrop-filter: blur(24px); /* stronger blur */
    -webkit-backdrop-filter: blur(24px);
}
.section-green-card h3 {
    color: #111 !important;
}
.section-green-card p {
    color: #fff;
}
.section-green-card:last-of-type {
    margin-bottom: 0 !important;
}
section:last-of-type {
    margin-bottom: 0 !important;
}
</style>
<div class="container-fluid p-0" style="background: #edf8e9;">
    
<section class="mb-5 px-2 px-md-4 px-lg-5 pt-custom" data-aos="fade-up">
        <div class="section-green-card">
            <h3 class="text-center mb-4">Struktur Ketenagakerjaan</h3>
            <div class="row gx-3 gy-4 justify-content-center" id="section3Cards">
                @php $count3 = 0; $total3 = count($informasiSection3); @endphp
                @foreach($informasiSection3 as $info)
                    @php $count3++; @endphp
                    <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch section3-card{{ $count3 > 4 ? ' d-none' : '' }}">
                        <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <h5 class="fw-bold mb-2">{{ $info->title }}</h5>
                            <p class="mb-2 text-muted">{{ $info->description }}</p>
                            <div class="w-100" style="min-height: 350px;">
                                <div class="tableau-embed-wrapper w-100" style="min-height: 350px; width: 100%; overflow: visible;">
                                    <div style="width: 100%; min-width: 0;">
                                        {!! str_replace([
                                            "vizElement.style.width='400px';",
                                            'vizElement.style.width = \"400px\";',
                                            'vizElement.style.width = \"100%\";',
                                            'vizElement.style.height=\'427px\';',
                                            'vizElement.style.height = \"427px\";',
                                            "vizElement.style.width='800px';",
                                            'vizElement.style.width = \"800px\";',
                                            "vizElement.style.width='600px';",
                                            'vizElement.style.width = \"600px\";',
                                            "vizElement.style.width='500px';",
                                            'vizElement.style.width = \"500px\";',
                                            "vizElement.style.width='900px';",
                                            'vizElement.style.width = \"900px\";',
                                            "vizElement.style.width='700px';",
                                            'vizElement.style.width = \"700px\";'
                                        ], [
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.height='427px';",
                                            'vizElement.style.height = "427px";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";'
                                        ], $info->tableau_embed_code) !!}
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('dashboard.performance') }}" class="btn btn-primary mt-3 w-100 d-none">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($total3 > 4)
                <div class="text-center mt-3">
                    <button id="lihatLebihBanyakBtn3" class="btn btn-success px-4">Lihat lebih banyak</button>
                    <a href="{{ route('dashboard.performance') }}" id="section3LihatDetailBtn" class="btn btn-primary px-4 mt-3 d-none">Lihat Detail</a>
                </div>
            @endif
        </div>
    </section>


    <section class="mb-5 px-2 px-md-4 px-lg-5 pt-custom" data-aos="fade-up">
        <div class="section-green-card">
            <h3 class="text-center mb-4">Kebutuhan Tenaga Kerja</h3>
            <style>
                .tableauPlaceholder,
                .tableauPlaceholder object,
                .tableauPlaceholder iframe {
                    width: 100% !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    height: 427px !important;
                    overflow: visible !important;
                }
                .tableau-embed-wrapper {
                    width: 100% !important;
                    overflow: visible !important;
                    max-width: 100% !important;
                }
                .tableau-embed-wrapper > div {
                    width: 100% !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    overflow: visible !important;
                }
                .tableauViz {
                    width: 100% !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    overflow: visible !important;
                }
                /* Force all Tableau-related elements to full width */
                [class*="tableau"],
                [id*="viz"] {
                    width: 100% !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    overflow: visible !important;
                }
                /* Ensure parent containers don't constrain width */
                .card.stat-card {
                    overflow: visible !important;
                }
                .card.stat-card .w-100 {
                    overflow: visible !important;
                }
                /* Support for scaled Tableau visualizations */
                .tableau-embed-wrapper {
                    position: relative;
                }
                .tableauPlaceholder {
                    transition: transform 0.3s ease;
                }
                /* Ensure scaled content doesn't cause horizontal scroll */
                .tableau-embed-wrapper.scaled {
                    overflow: hidden !important;
                }
            </style>
            <div class="row gx-3 gy-4 justify-content-center" id="section1Cards">
                @php $count = 0; $total = count($informasiSection1); @endphp
                @foreach($informasiSection1 as $info)
                    @php $count++; @endphp
                    <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch section1-card{{ $count > 4 ? ' d-none' : '' }}">
                        <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <h5 class="fw-bold mb-2">{{ $info->title }}</h5>
                            <p class="mb-2 text-muted">{{ $info->description }}</p>
                            <div class="w-100" style="min-height: 350px;">
                                <div class="tableau-embed-wrapper w-100" style="min-height: 350px; width: 100%; overflow: visible;">
                                    <div style="width: 100%; min-width: 0;">
                                        {!! str_replace([
                                            "vizElement.style.width='400px';",
                                            'vizElement.style.width = \"400px\";',
                                            'vizElement.style.width = \"100%\";',
                                            'vizElement.style.height=\'427px\';',
                                            'vizElement.style.height = \"427px\";',
                                            "vizElement.style.width='800px';",
                                            'vizElement.style.width = \"800px\";',
                                            "vizElement.style.width='600px';",
                                            'vizElement.style.width = \"600px\";',
                                            "vizElement.style.width='500px';",
                                            'vizElement.style.width = \"500px\";',
                                            "vizElement.style.width='900px';",
                                            'vizElement.style.width = \"900px\";',
                                            "vizElement.style.width='700px';",
                                            'vizElement.style.width = \"700px\";'
                                        ], [
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.height='427px';",
                                            'vizElement.style.height = "427px";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";'
                                        ], $info->tableau_embed_code) !!}
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('dashboard.trend') }}" class="btn btn-primary mt-3 w-100 d-none">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($total > 4)
                <div class="text-center mt-3">
                    <button id="lihatLebihBanyakBtn" class="btn btn-success px-4">Lihat lebih banyak</button>
                    <a href="{{ route('dashboard.trend') }}" id="section1LihatDetailBtn" class="btn btn-primary px-4 mt-3 d-none">Lihat Detail</a>
                </div>
            @endif
        </div>
    </section>
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5 pt-custom" data-aos="fade-up">
        <div class="section-green-card">
            <h3 class="text-center mb-4">Persediaan Tenaga Kerja</h3>
            <div class="row gx-3 gy-4 justify-content-center" id="section2Cards">
                @php $count2 = 0; $total2 = count($informasiSection2); @endphp
                @foreach($informasiSection2 as $info)
                    @php $count2++; @endphp
                    <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch section2-card{{ $count2 > 4 ? ' d-none' : '' }}">
                        <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <h5 class="fw-bold mb-2">{{ $info->title }}</h5>
                            <p class="mb-2 text-muted">{{ $info->description }}</p>
                            <div class="w-100" style="min-height: 350px;">
                                <div class="tableau-embed-wrapper w-100" style="min-height: 350px; width: 100%; overflow: visible;">
                                    <div style="width: 100%; min-width: 0;">
                                        {!! str_replace([
                                            "vizElement.style.width='400px';",
                                            'vizElement.style.width = \"400px\";',
                                            'vizElement.style.width = \"100%\";',
                                            'vizElement.style.height=\'427px\';',
                                            'vizElement.style.height = \"427px\";',
                                            "vizElement.style.width='800px';",
                                            'vizElement.style.width = \"800px\";',
                                            "vizElement.style.width='600px';",
                                            'vizElement.style.width = \"600px\";',
                                            "vizElement.style.width='500px';",
                                            'vizElement.style.width = \"500px\";',
                                            "vizElement.style.width='900px';",
                                            'vizElement.style.width = \"900px\";',
                                            "vizElement.style.width='700px';",
                                            'vizElement.style.width = \"700px\";'
                                        ], [
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.height='427px';",
                                            'vizElement.style.height = "427px";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";'
                                        ], $info->tableau_embed_code) !!}
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('dashboard.distribution') }}" class="btn btn-primary mt-3 w-100 d-none">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($total2 > 4)
                <div class="text-center mt-3">
                    <button id="lihatLebihBanyakBtn2" class="btn btn-success px-4">Lihat lebih banyak</button>
                    <a href="{{ route('dashboard.distribution') }}" id="section2LihatDetailBtn" class="btn btn-primary px-4 mt-3 d-none">Lihat Detail</a>
                </div>
            @endif
        </div>
    </section>
    
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
                                <div class="tableau-embed-wrapper w-100" style="min-height: 350px; width: 100%; overflow: visible;">
                                    <div style="width: 100%; min-width: 0;">
                                        {!! str_replace([
                                            "vizElement.style.width='400px';",
                                            'vizElement.style.width = \"400px\";',
                                            'vizElement.style.width = \"100%\";',
                                            'vizElement.style.height=\'427px\';',
                                            'vizElement.style.height = \"427px\";',
                                            "vizElement.style.width='800px';",
                                            'vizElement.style.width = \"800px\";',
                                            "vizElement.style.width='600px';",
                                            'vizElement.style.width = \"600px\";',
                                            "vizElement.style.width='500px';",
                                            'vizElement.style.width = \"500px\";',
                                            "vizElement.style.width='900px';",
                                            'vizElement.style.width = \"900px\";',
                                            "vizElement.style.width='700px';",
                                            'vizElement.style.width = \"700px\";'
                                        ], [
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.height='427px';",
                                            'vizElement.style.height = "427px";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";',
                                            "vizElement.style.width='100%';vizElement.style.minWidth='0';vizElement.style.maxWidth='100%';",
                                            'vizElement.style.width = "100%";vizElement.style.minWidth = "0";vizElement.style.maxWidth = "100%";'
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
    
    /* Responsive Tableau Styles */
    @media (max-width: 768px) {
        .tableau-embed-wrapper {
            min-height: 300px !important;
        }
        .tableauPlaceholder,
        .tableauPlaceholder object {
            height: 300px !important;
        }
    }
    
    @media (max-width: 576px) {
        .tableau-embed-wrapper {
            min-height: 250px !important;
        }
        .tableauPlaceholder,
        .tableauPlaceholder object {
            height: 250px !important;
        }
    }
    </style>

    
</div>
@endsection

@push('scripts')
<script>
// Function to scale Tableau visualizations to fit container
function scaleTableauVisualizations() {
    const tableauPlaceholders = document.querySelectorAll('.tableauPlaceholder');
    
    tableauPlaceholders.forEach(function(placeholder) {
        const container = placeholder.closest('.tableau-embed-wrapper');
        if (!container) return;
        
        const containerWidth = container.offsetWidth;
        const tableauObject = placeholder.querySelector('object.tableauViz');
        
        if (tableauObject && containerWidth > 0) {
            // Wait a bit for Tableau to render if needed
            setTimeout(function() {
                // Get the natural width of the Tableau visualization
                let tableauWidth = tableauObject.offsetWidth;
                
                // If width is still 0, try to get it from the object's natural dimensions
                if (tableauWidth === 0) {
                    tableauWidth = tableauObject.getAttribute('width') || 800;
                }
                
                // Convert to number if it's a string
                tableauWidth = parseInt(tableauWidth) || 800;
                
                // Calculate scale factor (similar to 67% zoom)
                let scaleFactor = containerWidth / tableauWidth;
                
                // Ensure scale factor is reasonable (between 0.4 and 1.0)
                scaleFactor = Math.max(0.4, Math.min(1.0, scaleFactor));
                
                // Only apply scaling if it's significantly different from 1.0
                if (scaleFactor < 0.95) {
                    // Apply transform scale
                    tableauObject.style.transform = `scale(${scaleFactor})`;
                    tableauObject.style.transformOrigin = 'top left';
                    
                    // Also scale any iframes that might be created by Tableau
                    const iframes = placeholder.querySelectorAll('iframe');
                    iframes.forEach(function(iframe) {
                        iframe.style.transform = `scale(${scaleFactor})`;
                        iframe.style.transformOrigin = 'top left';
                    });
                    
                    // Adjust container height to accommodate scaled content
                    const scaledHeight = (427 * scaleFactor) + 'px';
                    container.style.height = scaledHeight;
                    placeholder.style.height = scaledHeight;
                    
                    // Mark as scaled and ensure no overflow
                    container.classList.add('scaled');
                    container.style.overflow = 'hidden';
                    
                    console.log(`Scaled Tableau visualization: ${scaleFactor.toFixed(2)}x (${tableauWidth}px -> ${containerWidth}px)`);
                } else {
                    // Reset scaling if not needed
                    tableauObject.style.transform = '';
                    const iframes = placeholder.querySelectorAll('iframe');
                    iframes.forEach(function(iframe) {
                        iframe.style.transform = '';
                    });
                    container.classList.remove('scaled');
                    container.style.overflow = 'visible';
                }
            }, 100);
        }
    });
}

// Function to resize Tableau visualizations
function resizeTableauVisualizations() {
    // Find all Tableau placeholder elements
    const tableauPlaceholders = document.querySelectorAll('.tableauPlaceholder');
    
    tableauPlaceholders.forEach(function(placeholder) {
        // Get the Tableau object element
        const tableauObject = placeholder.querySelector('object.tableauViz');
        if (tableauObject) {
            // Force width to 100%
            tableauObject.style.width = '100%';
            tableauObject.style.minWidth = '0';
            tableauObject.style.maxWidth = '100%';
            
            // Also set the placeholder width
            placeholder.style.width = '100%';
            placeholder.style.minWidth = '0';
            placeholder.style.maxWidth = '100%';
            placeholder.style.overflow = 'visible';
        }
        
        // Handle any iframe elements that might be created by Tableau
        const iframes = placeholder.querySelectorAll('iframe');
        iframes.forEach(function(iframe) {
            iframe.style.width = '100%';
            iframe.style.minWidth = '0';
            iframe.style.maxWidth = '100%';
        });
    });
    
    // Also handle any elements with tableau-specific classes
    const tableauElements = document.querySelectorAll('.tableauViz, [class*="tableau"]');
    tableauElements.forEach(function(element) {
        element.style.width = '100%';
        element.style.minWidth = '0';
        element.style.maxWidth = '100%';
    });
}

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

    // Scale Tableau visualizations on page load
    scaleTableauVisualizations();
    
    // Scale again after a short delay to ensure Tableau has loaded
    setTimeout(scaleTableauVisualizations, 1000);
    
    // Scale on window resize
    window.addEventListener('resize', function() {
        setTimeout(scaleTableauVisualizations, 100);
    });
    
    // Also run the original resize function as backup
    resizeTableauVisualizations();
    setTimeout(resizeTableauVisualizations, 1000);
    
    // Watch for dynamically added Tableau content
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        if (node.classList && (node.classList.contains('tableauPlaceholder') || node.classList.contains('tableauViz'))) {
                            setTimeout(scaleTableauVisualizations, 100);
                            setTimeout(resizeTableauVisualizations, 100);
                        }
                        // Check for Tableau elements in added nodes
                        const tableauElements = node.querySelectorAll && node.querySelectorAll('.tableauPlaceholder, .tableauViz');
                        if (tableauElements && tableauElements.length > 0) {
                            setTimeout(scaleTableauVisualizations, 100);
                            setTimeout(resizeTableauVisualizations, 100);
                        }
                    }
                });
            }
        });
    });
    
    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

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
});
</script>
@endpush 