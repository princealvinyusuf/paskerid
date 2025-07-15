@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="background: #edf8e9;">
    
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Karakteristik Lowongan Kerja</h3>
        <div class="row gx-3 gy-4 justify-content-center">
            @foreach($informasiSection1 as $info)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                    <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                        <h5 class="fw-bold mb-2">{{ $info->title }}</h5>
                        <p class="mb-2 text-muted">{{ $info->description }}</p>
                        <div class="w-100" style="min-height: 350px;">
                            <div class="tableau-embed-wrapper w-100" style="min-height: 350px; overflow-x: auto;">
                                <div style="min-width: 400px;">
                                    {!! $info->tableau_embed_code !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Karakteristik Pencari Kerja</h3>
        <div class="row gx-3 gy-4 justify-content-center">
            @foreach($informasiSection2 as $info)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                    <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                        <h5 class="fw-bold mb-2">{{ $info->title }}</h5>
                        <p class="mb-2 text-muted">{{ $info->description }}</p>
                        <div class="w-100" style="min-height: 350px;">
                            <div class="tableau-embed-wrapper w-100" style="min-height: 350px; overflow-x: auto;">
                                <div style="min-width: 400px;">
                                    {!! $info->tableau_embed_code !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Informasi Pasar Kerja 3</h3>
        <div class="row gx-3 gy-4 justify-content-center">
            @foreach($informasiSection3 as $info)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                    <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                        <h5 class="fw-bold mb-2">{{ $info->title }}</h5>
                        <p class="mb-2 text-muted">{{ $info->description }}</p>
                        <div class="w-100" style="min-height: 350px;">
                            <div class="tableau-embed-wrapper w-100" style="min-height: 350px; overflow-x: auto;">
                                <div style="min-width: 400px;">
                                    {!! $info->tableau_embed_code !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <section class="my-5 mb-5 px-2 px-md-4 px-lg-5" data-aos="fade-up">
        <h3 class="text-center mb-4">Informasi Pasar Kerja 4</h3>
        <div class="row gx-3 gy-4 justify-content-center">
            @foreach($informasiSection4 as $info)
                <div class="col-12 col-md-6 col-lg-3 d-flex align-items-stretch">
                    <div class="card stat-card shadow-sm border-0 w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center text-center">
                        <h5 class="fw-bold mb-2">{{ $info->title }}</h5>
                        <p class="mb-2 text-muted">{{ $info->description }}</p>
                        <div class="w-100" style="min-height: 350px;">
                            <div class="tableau-embed-wrapper w-100" style="min-height: 350px; overflow-x: auto;">
                                <div style="min-width: 400px;">
                                    {!! $info->tableau_embed_code !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
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
                            <div class="card shadow-sm stat-card text-center flex-shrink-0" style="max-width:260px; min-width:180px; cursor:pointer;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <div class="stat-icon mb-3">
                                        <i class="fa fa-book fa-2x text-success"></i>
                                    </div>
                                    <div class="stat-title fw-bold mb-1" style="font-size:1.1rem; color:#187C19;">{{ $pub->title }}</div>
                                    <div class="stat-value fw-bold mb-1" style="font-size:1.1rem; color:#222;">{{ $pub->date ? indo_date($pub->date) : '' }}</div>
                                    @if($pub->description)
                                        <div class="stat-desc text-muted mt-1" style="font-size:0.95rem;">{{ $pub->description }}</div>
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
});
</script>
@endpush 