@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Informasi Terbaru</h2>
    <ul class="nav nav-tabs justify-content-center mb-4" id="infoTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link @if(request('type', 'statistik') == 'statistik') active @endif" href="?type=statistik">Tabel Statistik</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link @if(request('type') == 'publikasi') active @endif" href="?type=publikasi">Publikasi</a>
        </li>
    </ul>
    <form method="GET" class="row g-3 mb-4 justify-content-center">
        <input type="hidden" name="type" value="{{ request('type', 'statistik') }}">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Cari</button>
        </div>
    </form>
    @forelse($information as $info)
        <div class="card mb-3 shadow-sm rounded-pill px-4 py-3 d-flex flex-row align-items-center">
            <div class="me-3 text-primary" style="font-size:2rem;">
                <i class="fa {{ $info->type == 'publikasi' ? 'fa-book' : 'fa-clipboard' }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold">{{ $info->title }}</div>
                <div class="text-muted small">{{ indo_date($info->date) }}</div>
            </div>
            <div>
                <a href="{{ $info->file_url ?? '#' }}" class="btn btn-link text-success p-0" target="_blank"><i class="fa fa-arrow-right fa-lg"></i></a>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">Tidak ada informasi ditemukan.</div>
    @endforelse
    <div class="d-flex justify-content-center mt-4">
        {{ $information->withQueryString()->links() }}
    </div>
    <div class="text-center mt-4">
        <a href="{{ url('/') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">Kembali ke Beranda <i class="fa fa-arrow-left"></i></a>
    </div>
</div>
@endsection 