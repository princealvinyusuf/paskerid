@extends('layouts.app') {{-- Pastikan punya file layouts.app berisi navbar + footer --}}

@section('content')
<section class="py-4 bg-light">
    <section class="section-berita position-relative">
        <img src="{{ asset('images/logo_siapkerja_white.svg') }}" class="section-bg" alt="background">
        <div class="section-content text-center py-5">
            <h2 class="fw-bold text-white mb-2" style="font-size:2.5rem;">Informasi Mitra Kerja</h2>
            <p class="text-white mb-0" style="font-size: 1.25rem;">Dapatkan informasi mengenai kami dimanapun dan kapanpun, kami siap membantu Anda!</p>
        </div>
    </section>

    <div class="circle-wrapper my-4">
        <a href="?divider=dinas" class="circle-btn {{ $divider === 'dinas' ? 'filled' : 'outlined' }}">Dinas <br> Tenaga <br> Kerja</a>
        <img src="{{ asset('images/logo.png') }}" alt="Maskot" class="bird-image">
        <a href="?divider=mitra" class="circle-btn {{ $divider === 'mitra' ? 'filled' : 'outlined' }}">Mitra <br> Pasker</a>
    </div>

    {{-- Stakeholder Cards --}}
    <div class="bg-green p-4 rounded-4 mb-5">
        <h5 class="fw-bold mb-4 text-center">
            Stakeholder {{ $divider === 'dinas' ? 'Dinas Tenaga Kerja' : 'Mitra Kerja' }}
        </h5>
        <div class="row g-4">
            @foreach ($stakeholders as $stakeholder)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                    <div class="p-4 shadow-sm rounded-4 bg-white h-100 stakeholder-card w-100 transition-all">
                        @if($stakeholder->logo)
                            <div class="mb-2 text-center">
                                <img src="{{ asset($stakeholder->logo) }}" alt="Logo" style="max-width: 80px; max-height: 80px; object-fit: contain;">
                            </div>
                        @endif
                        @if($stakeholder->wilayah || $stakeholder->category)
                            <div class="mb-2 d-flex flex-wrap align-items-center gap-2" style="margin-left:2px;">
                                @if($stakeholder->wilayah)
                                    <span class="badge bg-light text-success border border-success" style="font-weight:500;">{{ $stakeholder->wilayah }}</span>
                                @endif
                                @if($stakeholder->category)
                                    <span class="badge bg-light text-success border border-success" style="font-weight:500;">{{ $stakeholder->category }}</span>
                                @endif
                            </div>
                        @endif
                        <h6 class="fw-bold mb-2">{{ $stakeholder->name }}</h6>
                        @if($stakeholder->pic)
                            <p class="mb-1"><i class="fa fa-user me-2 text-success"></i>PIC: {{ $stakeholder->pic }}</p>
                        @endif
                        @if($stakeholder->address)
                            <p class="mb-1"><i class="fa fa-map-marker-alt me-2 text-success"></i>{{ $stakeholder->address }}</p>
                        @endif
                        @if($stakeholder->contact)
                            <p class="mb-1"><i class="fa fa-phone me-2 text-success"></i>{{ $stakeholder->contact }}</p>
                        @endif
                        @if($stakeholder->email)
                            <p class="mb-1"><i class="fa fa-envelope me-2 text-success"></i>{{ $stakeholder->email }}</p>
                        @endif
                        @if($stakeholder->website_url)
                            <a href="{{ $stakeholder->website_url }}" class="text-success fw-bold" target="_blank"><i class="fa fa-globe me-1"></i>Kunjungi Website</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        {{-- Pagination --}}
        <div class="text-center mt-4">
            {{ $stakeholders->links() }}
        </div>
    </div>

    {{-- Kantor Pusat --}}
    <div class="row justify-content-center my-5 g-4">
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Kantor Pusat</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-2 text-success"></i> Gedung Pasar Kerja</p>
                    <p class="mb-2"><i class="fa fa-phone me-2 text-success"></i> 0811-871-2018</p>
                    <p class="mb-0"><i class="fa fa-envelope me-2 text-success"></i> pusatpasarkerja@kemnaker.go.id</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body p-2">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.211894308172!2d106.82165157499043!3d-6.235776693752427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3a463211d29%3A0xd532fe78919d1589!2sLabor%20Market%20Center!5e0!3m2!1sen!2sid!4v1752117534527!5m2!1sen!2sid" width="100%" height="200" class="rounded-4 border-0 w-100"></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- Hotline --}}
    <div class="bg-light p-4 rounded-4 mt-5">
        <h5 class="fw-bold mb-4 text-center">Layanan Hotline Pusat Pasar Kerja <br> <span class="fw-normal">Jam Pelayanan: 09.00 - 16.00</span></h5>
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="card hotline-card shadow-sm rounded-4 text-center p-3 h-100">
                    <i class="fa fa-phone fa-2x mb-2 text-success"></i>
                    <div class="fw-bold">Pemberi Kerja </div>
                    <div>08118712018</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hotline-card shadow-sm rounded-4 text-center p-3 h-100">
                    <i class="fa fa-phone fa-2x mb-2 text-success"></i>
                    <div class="fw-bold">Pencari Kerja</div>
                    <div>08118712019</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hotline-card shadow-sm rounded-4 text-center p-3 h-100">
                    <i class="fa fa-phone fa-2x mb-2 text-success"></i>
                    <div class="fw-bold">CPMI/P3MI 1</div>
                    <div>08118712016</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hotline-card shadow-sm rounded-4 text-center p-3 h-100">
                    <i class="fa fa-phone fa-2x mb-2 text-success"></i>
                    <div class="fw-bold">CPMI/P3MI 2</div>
                    <div>08118712017</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<style>
    .bg-green{
        background-color: rgba(0, 163, 138, 0.12);
    }
    .section-berita {
        position: relative;
        background: linear-gradient(90deg, #00a78e 60%, #00c6a7 100%);
        padding: 30px 0 60px 0;
        overflow: hidden;
    }
    .section-bg {
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        object-fit: cover;
        opacity: 0.25;
    }
    .section-content {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 0 auto;
    }
    .circle-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 32px;
    }
    .circle-btn {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        font-weight: bold;
        font-size: 16px;
        text-align: center;
        line-height: 1.4;
        padding: 20px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s cubic-bezier(.4,2,.3,1);
        z-index: 1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .circle-btn.filled {
        background-color: #00A38A;
        color: white;
        border: 4px solid #00A38A;
    }
    .circle-btn.outlined {
        background-color: white;
        color: #00A38A;
        border: 4px solid #00A38A;
    }
    .circle-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 6px 24px rgba(0,163,138,0.18);
    }
    .bird-image {
        position: absolute;
        bottom: -20px;
        width: 100px;
        z-index: 2;
    }
    .stakeholder-card {
        transition: box-shadow 0.3s, transform 0.3s;
        border: 1px solid #e0f7f4;
    }
    .stakeholder-card:hover {
        box-shadow: 0 8px 32px rgba(0,163,138,0.18);
        transform: translateY(-6px) scale(1.03);
        border-color: #00a78e;
    }
    .hotline-card {
        transition: box-shadow 0.3s, transform 0.3s;
        border: 1px solid #e0f7f4;
        background: #f8fdfa;
    }
    .hotline-card:hover {
        box-shadow: 0 8px 32px rgba(0,163,138,0.18);
        transform: translateY(-4px) scale(1.03);
        border-color: #00a78e;
    }
    @media (max-width: 767px) {
        .section-content {
            padding: 0 10px;
        }
        .circle-wrapper {
            flex-direction: column;
            gap: 12px;
        }
        .bird-image {
            position: static;
            margin: 0 auto 12px auto;
            display: block;
        }
    }
</style>
