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
</style>
<div class="container-fluid p-0" style="background: #edf8e9;">
    
    <section class="mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Karakteristik Lowongan Kerja</h3>
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
                    </div>
                </div>
            @endforeach
        </div>
        @if($total > 4)
            <div class="text-center mt-3">
                <button id="lihatLebihBanyakBtn" class="btn btn-success px-4">Lihat lebih banyak</button>
            </div>
        @endif
    </section>
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Karakteristik Pencari Kerja</h3>
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
                    </div>
                </div>
            @endforeach
        </div>
        @if($total2 > 4)
            <div class="text-center mt-3">
                <button id="lihatLebihBanyakBtn2" class="btn btn-success px-4">Lihat lebih banyak</button>
            </div>
        @endif
    </section>
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
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
    </section>
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
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
    </section>

    {{-- Publikasi Cards Carousel (Floating over Banner) --}}
    <section class="stat-carousel-section position-relative mt-5" style="z-index: 10; margin-top: -90px;">
        <div class="container position-relative" style="max-width:1200px;">
            <h3 class="text-center mb-4">Publikasi</h3>
            <div class="d-flex align-items-center position-relative">
                <button id="publikasiScrollPrev" class="btn btn-light shadow rounded-circle position-absolute start-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <div id="publikasiScrollRow" class="d-flex px-7" style="scroll-behavior:smooth; gap:16px; width:100%; overflow-x:hidden;">
                    @foreach($publikasi as $pub)
                        <a href="{{ route('informasi.index', ['type' => 'publikasi', 'search' => $pub->title]) }}" class="text-decoration-none">
                            <div class="card shadow-sm stat-card text-center flex-shrink-0 position-relative overflow-hidden" style="max-width:260px; min-width:180px; cursor:pointer; padding:0; border:none; min-height:220px;">
                                @if($pub->image_url)
                                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('{{ $pub->image_url }}') center center/cover no-repeat; z-index:1;"></div>
                                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,0.45); z-index:2;"></div>
                                @endif
                                <div class="card-body d-flex flex-column align-items-center justify-content-center position-relative" style="z-index:3; min-height:220px;">
                                    <div class="stat-icon mb-3 bg-white bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                                        <i class="fa fa-book fa-2x text-success"></i>
                                    </div>
                                    <div class="stat-title fw-bold mb-1 text-white" style="font-size:1.1rem;">{{ $pub->title }}</div>
                                    <div class="stat-value fw-bold mb-1 text-white" style="font-size:1.1rem;">{{ $pub->date ? indo_date($pub->date) : '' }}</div>
                                    @if($pub->description)
                                        <div class="stat-desc mt-1 text-white-50" style="font-size:0.95rem;">{{ $pub->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button id="publikasiScrollNext" class="btn btn-light shadow rounded-circle position-absolute end-0 translate-middle-y" style="top:50%; z-index:2; width:40px; height:40px;">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
            <div class="d-flex justify-content-center mt-3" id="publikasiDots"></div>
        </div>
    </section>

    
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