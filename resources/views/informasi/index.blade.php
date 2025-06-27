@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Daftar Informasi</h2>
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">Semua Tipe</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @if(request('type') == $type) selected @endif>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
    <ul class="list-group mb-4">
        @forelse($information as $info)
            <li class="list-group-item">
                <strong>{{ $info->title }}</strong> <br>
                <small>{{ $info->date }} | {{ ucfirst($info->type) }}</small>
                <p>{{ $info->description }}</p>
                @if($info->file_url)
                    <a href="{{ $info->file_url }}" target="_blank">Download</a>
                @endif
            </li>
        @empty
            <li class="list-group-item text-center">Tidak ada informasi ditemukan.</li>
        @endforelse
    </ul>
    <div class="d-flex justify-content-center">
        {{ $information->withQueryString()->links() }}
    </div>
    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-secondary">Kembali ke Beranda</a>
    </div>
</div>
@endsection 