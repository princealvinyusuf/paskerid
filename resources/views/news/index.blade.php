@extends('layouts.app')

@section('content')
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
    <div class="row">
        @forelse($news as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-4">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" class="card-img-top rounded-top-4" alt="{{ $item->title }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $item->title }}</h5>
                        <p class="card-text">{{ Str::limit($item->content, 120) }}</p>
                        <div class="mt-auto">
                            <small class="text-muted">{{ indo_date($item->date) }} | {{ $item->author }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">Tidak ada berita ditemukan.</div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $news->withQueryString()->links() }}
    </div>
    <div class="mt-4 text-center">
        <a href="{{ url('/') }}" class="btn btn-secondary rounded-pill px-4">Kembali ke Beranda</a>
    </div>
</div>
@endsection
