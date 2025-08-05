@extends('layouts.app')

@section('content')
<div class="ribbon-section">
    <button id="likeBtn" class="ribbon-btn">
        <i class="fa fa-thumbs-up"></i>
        <span id="likeCount">{{ $news->likes }}</span>
    </button>
    <button id="shareBtn" class="ribbon-btn">
        <i class="fa fa-share"></i>
    </button>
</div>
<section class="section-berita">
    <div class="section-content">
        <h2 style="color: white; font-weight: bold; margin-left: 80px;">Berita</h2>
    <p style="color: white; margin-left: 80px; font-size: 20px">Temukan Berita Terkini Terkait Dengan Ketenagakerjaan.</p>
    </div>
</section>

<div class="container my-5">
    <div class="row">
        <!-- Konten Berita -->
        <div class=" detailberita col-md-8" style="background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,0.07); border-radius: 18px; padding: 32px 0 32px 0;">
            <h3 class="fw-bold TitleBerita" style="margin-bottom: 18px;">
                {{ $news->title }}
            </h3>
            <div class="text-muted mb-3 timestampBerita" style="margin-bottom: 22px !important;">
                <i class="fa fa-clock" style="font-size:13px"></i> <span style="font-weight: bold; font-size: 13px">{{ \Carbon\Carbon::parse($news->date)->format('d M Y') }} &nbsp; </span>
                <i class="fa fa-circle-user" style="font-size:13px"></i> <span style="font-weight: bold; font-size: 13px">{{ $news->author}}</span>
            </div>
            
            <img src="{{ asset($news->image_url) }}" alt="{{ $news->title }}" class="img-fluid rounded-3 mb-2 gambarBerita" style="margin-bottom: 28px;">

            <div class="contentBerita" style="font-size: 1.18rem; line-height: 1.85; color: #222; margin-bottom: 10px;">
                {!! nl2br(e($news->content)) !!}
            </div>
        </div>

        

        <!-- Berita Populer -->
        <div class="col-md-4 mt-1">
            <div class="popular-card p-3">
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
        <!-- Kembali Button: match width with news container -->
        <div class="col-md-8 mt-4">
            <a href="{{ route('news.index')}}" class="btn btn-success rounded-pill w-100">Kembali</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const likeBtn = document.getElementById('likeBtn');
    const likeCount = document.getElementById('likeCount');
    likeBtn.addEventListener('click', function() {
        fetch("{{ route('news.like', $news->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            likeCount.textContent = data.likes;
        });
    });

    const shareBtn = document.getElementById('shareBtn');
    shareBtn.addEventListener('click', function() {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: window.location.href
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('Link copied to clipboard!');
        }
    });
});
</script>
@endpush

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
    background-color: #DFF4F0 !important; /* legacy, not used for card anymore */
}

.detailberita {
    background-color: #fff;
    border-radius: 18px;
    box-shadow: 0 6px 32px 0 rgba(0,0,0,0.16), 0 1.5px 6px 0 rgba(0,0,0,0.10);
    padding: 32px 0 32px 0;
}

/* Popular News Card */
.popular-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 6px 32px 0 rgba(0,0,0,0.16), 0 1.5px 6px 0 rgba(0,0,0,0.10);
    padding: 24px 18px 18px 18px;
}

.section-berita {
    position: relative;
    background-color: #00a78e;
    padding: 30px 40px;
    overflow: hidden;
    background-image: url('{{ asset('images/logo_siapkerja.svg') }}');
    background-position: top right;
    background-repeat: no-repeat;
    background-size: 400px auto;
    opacity: 1;
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
    margin-bottom: 28px;
}

.contentBerita {
    padding-left: 30px;
    padding-right: 30px;
    text-align: justify;
    font-size: 1.18rem;
    line-height: 1.85;
    color: #222;
    margin-bottom: 10px;
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
.ribbon-section {
    position: fixed;
    top: 120px;
    left: 30px;
    display: flex;
    flex-direction: column;
    gap: 18px;
    z-index: 1000;
}
.ribbon-btn {
    background: #333;
    color: #fff;
    border: none;
    border-radius: 14px;
    width: 48px;
    height: 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    cursor: pointer;
    position: relative;
    transition: background 0.2s;
}
.ribbon-btn:hover {
    background: #00a78e;
}
#likeCount {
    font-size: 13px;
    background: #0099ff;
    color: #fff;
    border-radius: 50%;
    padding: 2px 7px;
    position: absolute;
    top: 4px;
    right: 4px;
}
@media (max-width: 900px) {
    .ribbon-section {
        position: static;
        flex-direction: row;
        gap: 12px;
        margin-bottom: 18px;
        left: unset;
        top: unset;
        justify-content: flex-start;
    }
}
</style>

<style>
@media (max-width: 768px) {
    .section-berita {
        padding: 20px 10px;
        background-size: 200px auto;
    }
    .section-content {
        max-width: 100%;
        margin-left: 0;
    }
    .TitleBerita,
    .timestampBerita,
    .gambarBerita,
    .contentBerita {
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 20px;
    }
    .TitleBerita {
        font-size: 1.2rem;
    }
    .contentBerita {
        font-size: 1.05rem;
        line-height: 1.7;
        padding-left: 10px;
        padding-right: 10px;
    }
    .section-title {
        font-size: 1.1rem;
    }
    .section-title::after {
        max-width: 100px;
    }
    .detailberita {
        border-radius: 10px;
        padding: 18px 0 18px 0;
    }
    .popular-card {
        border-radius: 10px;
        padding: 12px 6px 10px 6px;
    }
    .gambarBerita {
        width: 100%;
        height: auto;
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 18px;
    }
    h2, p {
        margin-left: 0 !important;
        text-align: left;
    }
    .container {
        padding-left: 5px;
        padding-right: 5px;
    }
}

@media (max-width: 480px) {
    .section-berita {
        background-size: 100px auto;
    }
    .TitleBerita {
        font-size: 1rem;
    }
    .contentBerita {
        font-size: 0.98rem;
        line-height: 1.6;
    }
    .section-title {
        font-size: 1rem;
    }
}
</style>