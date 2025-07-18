@extends('layouts.app')

@section('content')
<section class="section-berita mb-0">
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
            <h3 class="text-center mb-4">Kebutuhan Tenaga Kerja</h3>
            <style>
                .tableauPlaceholder,
                .tableauPlaceholder object {
                    width: 100% !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    height: 427px !important;
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
                            <a href="{{ route('dashboard.trend') }}" class="btn btn-primary mt-3 w-100">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($total > 4)
                <div class="text-center mt-3">
                    <button id="lihatLebihBanyakBtn" class="btn btn-success px-4">Lihat lebih banyak</button>
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
                            <a href="{{ route('dashboard.distribution') }}" class="btn btn-primary mt-3 w-100">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($total2 > 4)
                <div class="text-center mt-3">
                    <button id="lihatLebihBanyakBtn2" class="btn btn-success px-4">Lihat lebih banyak</button>
                </div>
            @endif
        </div>
    </section>
    <!-- <section class="my-5 mb-5 px-2 px-md-4 px-lg-5 pt-custom" data-aos="fade-up">
        <div class="section-green-card">
            <h3 class="text-center mb-4">Informasi Pasar Kerja 3</h3>
            <div class="row gx-3 gy-4 justify-content-center" id="section3Cards">
                @php $count3 = 0; $total3 = count($informasiSection3); @endphp
                @foreach($informasiSection3 as $info)
                    @php $count3++; @endphp
                    <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch section3-card{{ $count3 > 4 ? ' d-none' : '' }}">
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
            @if($total3 > 4)
                <div class="text-center mt-3">
                    <button id="lihatLebihBanyakBtn3" class="btn btn-success px-4">Lihat lebih banyak</button>
                </div>
            @endif
        </div>
    </section> -->
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
    <section class="stat-carousel-section position-relative mt-5 pt-custom" style="z-index: 10; margin-top: -90px;">
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
    </section>
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
    </style>

    
</div>
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
    if (lihatLebihBanyakBtn) {
        lihatLebihBanyakBtn.addEventListener('click', function() {
            document.querySelectorAll('.section1-card.d-none').forEach(function(card) {
                card.classList.remove('d-none');
            });
            lihatLebihBanyakBtn.style.display = 'none';
        });
    }
    // Lihat lebih banyak logic for Section 2
    const lihatLebihBanyakBtn2 = document.getElementById('lihatLebihBanyakBtn2');
    if (lihatLebihBanyakBtn2) {
        lihatLebihBanyakBtn2.addEventListener('click', function() {
            document.querySelectorAll('.section2-card.d-none').forEach(function(card) {
                card.classList.remove('d-none');
            });
            lihatLebihBanyakBtn2.style.display = 'none';
        });
    }
    // Lihat lebih banyak logic for Section 3
    const lihatLebihBanyakBtn3 = document.getElementById('lihatLebihBanyakBtn3');
    if (lihatLebihBanyakBtn3) {
        lihatLebihBanyakBtn3.addEventListener('click', function() {
            document.querySelectorAll('.section3-card.d-none').forEach(function(card) {
                card.classList.remove('d-none');
            });
            lihatLebihBanyakBtn3.style.display = 'none';
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