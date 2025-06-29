@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Informasi Terbaru</h2>
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header fw-bold">Subject</div>
                <ul class="list-group list-group-flush">
                    @foreach($subjects as $subject)
                        <li class="list-group-item p-0">
                            <a href="?subject={{ urlencode($subject) }}&type={{ request('type', 'statistik') }}" class="d-block px-3 py-2 @if($selectedSubject == $subject) bg-primary text-white @else text-dark @endif" style="text-decoration:none;">
                                {{ $subject }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <ul class="nav nav-tabs mb-4" id="infoTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if(request('type', 'statistik') == 'statistik') active @endif" href="?subject={{ urlencode($selectedSubject) }}&type=statistik">Tabel Statistik</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if(request('type') == 'publikasi') active @endif" href="?subject={{ urlencode($selectedSubject) }}&type=publikasi">Publikasi</a>
                </li>
            </ul>
            <form method="GET" class="row g-3 mb-4">
                <input type="hidden" name="type" value="{{ request('type', 'statistik') }}">
                <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                <div class="col-md-7">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
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
    </div>
</div>
@endsection 