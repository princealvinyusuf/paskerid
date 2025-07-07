@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white px-0">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Layanan</li>
            <li class="breadcrumb-item active" aria-current="page">Virtual Karir</li>
        </ol>
    </nav>
    <h2 class="fw-bold mb-4">Layanan Pusat Pasar Kerja</h2>
    <div class="row mb-5">
        @foreach($services as $service)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-2">
                            @if($service->icon)
                                <i class="{{ $service->icon }} fa-2x"></i>
                            @endif
                        </div>
                        <h5 class="card-title fw-bold">{{ $service->title }}</h5>
                        <p class="card-text">{{ $service->description }}</p>
                        @if($service->link)
                            <a href="{{ $service->link }}" class="text-primary">Selengkapnya &rarr;</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h2 class="fw-bold mb-4">Layanan Job Fair</h2>
    <div class="row mb-5">
        @foreach($jobFairs as $jobFair)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($jobFair->image_url)
                        <img src="{{ $jobFair->image_url }}" class="card-img-top" alt="{{ $jobFair->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $jobFair->title }}</h5>
                        <p class="card-text">{{ $jobFair->description }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">{{ \Carbon\Carbon::parse($jobFair->date)->format('d M Y') }}</small>
                            <small class="text-muted">{{ $jobFair->author }}</small>
                        </div>
                        @if($jobFair->register_url)
                            <a href="{{ $jobFair->register_url }}" class="btn btn-primary btn-sm mt-2">Daftar Sekarang</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h2 class="fw-bold mb-4">Agenda Pusat Pasar Kerja</h2>
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agendas as $agenda)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($agenda->date)->format('d M Y') }}</td>
                            <td>{{ $agenda->title }}</td>
                            <td>{{ $agenda->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 