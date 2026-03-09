@extends('layouts.app')

@section('content')
<div class="news-ocean-wrap news-page-ocean">
<section class="section-berita">
    <img src="{{ asset('images/logo_siapkerja_white.svg') }}" class="section-bg" alt="background">
    <div class="section-content">
        <h2 style="color: white; font-weight: bold; margin-left: 80px;">Berita</h2>
    <p style="color: white; margin-left: 80px; font-size: 20px">Temukan Berita Terkini Terkait Dengan Ketenagakerjaan.</p>
    </div>
</section>






<div class="container my-5">
    <h2 class="mb-4 text-center">Semua Berita</h2>
    <form method="GET" class="row g-3 mb-4 justify-content-center">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Cari berita..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Cari</button>
        </div>
    </form>
  <div class="row row-cols-1 row-cols-md-3 g-4 align-items-stretch">
  @forelse($news as $item)
    <div class="col-md-4 mb-4">
      <a href="{{ route('news.DetailBerita', $item->id)}}" class="text-decoration-none text-dark d-block h-100">
        <div class="card h-100 shadow-sm" style="border-radius: 15px">
          @if($item->image_url)
            <img src="{{ $item->image_url }}" class="card-img-top rounded-top-4" alt="{{ $item->title }}">
          @endif

          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold line-2">{{ $item->title }}</h5>
            <p class="card-text flex-grow-1 line-3">{{ Str::limit($item->content, 160) }}</p>
          </div>

          <div class="card-footer bg-white d-flex justify-content-between text-muted text-sm mt-auto">
            <small>{{ indo_date($item->date) }}</small>
            <small>{{ $item->author }}</small>
          </div>
        </div>
      </a>
    </div>
  @empty
    <div class="col-12 text-center text-muted">Tidak ada berita ditemukan.</div>
  @endforelse
</div>

    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $news->withQueryString()->links() }}
    </div>
    {{-- <div class="mt-4 text-center">
        <a href="{{ url('/') }}" class="btn btn-secondary rounded-pill px-4">Kembali ke Beranda</a>
    </div> --}}
</div>
@endsection

<style>

/* Tombol normal */
.page-link {
    color: #0f5fa8 !important;
}

/* Tombol hover */
.page-link:hover {
    color: #fff;
    background-color: #0f5fa8;
    border-color: #0f5fa8;
}

/* Tombol fokus/klik */
.page-link:focus {
    box-shadow: 0 0 0 0.2rem rgba(15, 95, 168, 0.25);
}

/* Tombol aktif (halaman saat ini) */
.page-item.active .page-link {
    background-color: #0f5fa8;
    border-color: #0f5fa8;
    color: #fff !important;
}

/* Nonaktif (disable), misalnya tombol panah di page 1 */
.page-item.disabled .page-link {
    color: #ccc;
    background-color: #fff;
    border-color: #ddd;
}

.section-berita {
    position: relative;
    background: linear-gradient(120deg, #0f5fa8 0%, #2f9fe8 48%, #00a38a 100%);
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

.card-img-top {
    height: 220px;         /* or any height you prefer */
    object-fit: cover;
    width: 100%;
}
/* Bikin card “kolom” supaya footer bisa nempel bawah */
.card {
  display: flex;
  flex-direction: column;
}

.news-ocean-wrap {
    background: radial-gradient(980px 520px at -10% -10%, rgba(37, 99, 235, 0.2), transparent 58%),
        radial-gradient(920px 520px at 110% -6%, rgba(16, 185, 129, 0.16), transparent 60%),
        radial-gradient(760px 430px at 50% 105%, rgba(47, 159, 232, 0.13), transparent 62%),
        #f2f7ff;
    padding-bottom: 2rem;
}

.news-page-ocean .card {
    position: relative;
    overflow: hidden;
    border-radius: 18px !important;
    border: 1px solid rgba(15, 95, 168, 0.2) !important;
    box-shadow: 0 12px 30px rgba(12, 50, 82, 0.1) !important;
    background: linear-gradient(165deg, rgba(255, 255, 255, 0.97), rgba(244, 251, 255, 0.94)) !important;
    transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
}

.news-page-ocean .card::before {
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

.news-page-ocean .card > * {
    position: relative;
    z-index: 1;
}

.news-page-ocean .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 38px rgba(12, 50, 82, 0.14) !important;
    border-color: rgba(15, 95, 168, 0.3) !important;
}

.news-page-ocean .card-footer {
    background: transparent !important;
}

.news-page-ocean .btn-success {
    background: linear-gradient(120deg, #0f5fa8 0%, #2f9fe8 48%, #00a38a 100%) !important;
    border: none !important;
}

/* Pastikan body mengisi ruang tersisa */
.card-body {
  flex: 1 1 auto;
  display: flex;
  flex-direction: column;
}

/* Tinggi gambar konsisten */
.card-img-top {
  height: 220px;
  object-fit: cover;
  width: 100%;
}

/* (Opsional) Rapikan ketinggian judul & isi pakai line clamp */
.line-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;    /* maksimal 2 baris */
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.line-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;    /* maksimal 3 baris */
  -webkit-box-orient: vertical;
  overflow: hidden;
}



</style>