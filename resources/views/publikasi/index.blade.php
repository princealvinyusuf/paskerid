@extends('layouts.app')

@section('content')
<section class="section-berita mb-0">
    <img src="{{ asset('images/logo_siapkerja_white.svg') }}" class="section-bg" alt="background">
    <div class="section-content">
        <h2 style="color: white; font-weight: bold; margin-left: 80px;">Informasi Pasar Kerja</h2>
        <p style="color: white; margin-left: 80px; font-size: 20px">Temukan data, tren, dan analisis terbaru seputar pasar kerja di Indonesia.</p>
    </div>
</section>


<div class="container-fluid p-0" style="background: #edf8e9;">
    <section class="stat-carousel-section position-relative mt-5 pt-custom" style="z-index: 10; margin-top: -90px;">
        <div class="section-green-card">
            <div class="container-fluid px-2 px-md-4 px-lg-5 position-relative" style="max-width:1400px;">
                <h3 class="text-center mb-4">Publikasi</h3>
                <div class="d-flex align-items-center position-relative">
                    <div id="publikasiScrollRow"
                         class="d-flex flex-nowrap overflow-auto w-100"
                         style="gap:32px; scrollbar-width:thin; padding-bottom: 8px;">
                        @foreach($publikasi as $pub)
                            <a href="{{ route('informasi.index', ['type' => 'publikasi', 'subject' => $pub->title, 'search' => $pub->title]) }}" class="text-decoration-none">
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
                                        @if($pub->description)
                                            <div class="text-white small mb-2">{{ $pub->description }}</div>
                                        @endif
                                        @if($pub->date)
                                            <div class="text-white-50 small mb-2">{{ \Carbon\Carbon::parse($pub->date)->translatedFormat('d F Y') }}</div>
                                        @endif
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
    const pubDots = document.getElementById('publikasiDots');
    if(pubRow && pubDots) {
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

        pubRow.addEventListener('scroll', () => {
            pubCurrent = Math.round(pubRow.scrollLeft / pubCardWidth);
            updatePubDots();
        });

        window.addEventListener('resize', () => {
            updatePubDots();
        });

        updatePubDots();
    }
});
</script>
@endpush 