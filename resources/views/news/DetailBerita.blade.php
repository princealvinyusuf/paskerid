@extends('layouts.app')

@section('content')
<section class="section-berita">
    <img src="/images/logo_siapkerja.svg" class="section-bg" alt="background">
    <div class="section-content">
        <h2 style="color: white; font-weight: bold; margin-left: 80px;">Berita</h2>
    <p style="color: white; margin-left: 80px; font-size: 20px">Temukan Berita Terkini Terkait Dengan Ketenagakerjaan.</p>
    </div>
</section>

<div class="container my-5">
    <div class="row">
        <!-- Konten Berita -->
        <div class=" detailberita col-md-8">
            <h3 class="fw-bold TitleBerita">
                {{ $news->title }}
            </h3>
            <div class="text-muted mb-3 timestampBerita">
                <i class="fa fa-clock" style="font-size:13px"></i> <span style="font-weight: bold; font-size: 13px">{{ \Carbon\Carbon::parse($news->date)->format('d M Y') }} &nbsp; </span>
                <i class="fa fa-circle-user" style="font-size:13px"></i> <span style="font-weight: bold; font-size: 13px">{{ $news->author}}</span>
            </div>
            
            <img src="{{ asset($news->image_url) }}" alt="{{ $news->title }}" class="img-fluid rounded-3 mb-2 gambarBerita">
            
            {{-- <p class="text-muted fst-italic">Pembukaan Acara Job Fair</p> --}}

            <p class="contentBerita">{{ $news->content}}</p>

            
        </div>

        

        <!-- Berita Populer -->
        <div class="col-md-4 mt-1">
            <div class="p-3 bg-light2 rounded-3">
                <h5 class="fw-bold mb-3 section-title">Berita Populer</h5>
                @foreach($popularNews as $item)
                <div class="mb-3">
                    <a href=" {{ route('news.DetailBerita', $item->id)}}" class="text-dark fw-semibold" style="text-decoration: none;">{{Str::limit($item->title, 70)}}</a>
                    <div class="text-muted small">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</div>
                </div>
                @endforeach


                <!-- Tambahkan berita populer lainnya sesuai kebutuhan -->
            </div>
        </div>
        <div class="mt-4 d-flex justify-content-center">
                <a href="{{ route('news.index')}}" class="btn btn-success rounded-pill w-100">Kembali</a>
            </div>
    </div>
</div>
@endsection

<style>
    body {
    font-family: Arial, sans-serif;
}

.fw-bold {
    font-weight: bold;
}

.rounded-pill {
    border-radius: 50px;
}

.text-muted {
    color: #6c757d;
}

.bg-light2 {
    background-color: #DFF4F0 !important;
}

.detailberita {
    background-color: #DFF4F0;
    border-radius: 15px;
}

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
    width: 400px; /* Perbesar sesuai kebutuhan */
    height: 100%;
    object-fit: cover;
    opacity: 0.4; /* Sesuaikan jika ingin transparan atau hapus jika full color */
}

.section-content {
    position: relative; /* Agar teks tidak ketimpa gambar */
    z-index: 1;
    max-width: 1200px;
}

.section-breadcrumb {
    font-size: 18px;
    margin-bottom: 10px;
}

.section-subtitle {
    font-weight: bold;
    font-size: 18px;
    color: white;
}

.text-primary {
    color: white;
}

.text-white {
    color: white;
}

.fw-bold {
    font-weight: bold;
}

.TitleBerita {
    padding-left: 30px;
    padding-top : 40px;
    padding-right: 30px;
}

.timestampBerita {
    padding-left: 30px;
    padding-right: 30px;
}

.gambarBerita {
    padding-left: 30px;
    padding-right: 30px;
}

.contentBerita {
    padding-left: 30px;
    padding-right: 30px;
    text-align: justify;
}
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 8px;
    margin-bottom: 16px;
}

.section-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;  /*panjang garis, bisa disesuaikan*/
    max-width: 400px;
    height: 3px;  /* tebal garis */
    background-color: #00a78e; /* warna garis */
}
</style>