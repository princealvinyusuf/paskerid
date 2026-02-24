@extends('layouts.app')

@section('content')
<section class="minijobi-hero">
    <div class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h1 class="fw-bold text-white mb-1">{{ $job->title }}</h1>
                <p class="text-white mb-0">{{ $job->company_name }}</p>
            </div>
            <a href="{{ route('minijobi.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke miniJobi
            </a>
        </div>
    </div>
</section>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5 fw-bold mb-3">Informasi Lowongan</h2>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($job->location)
                            <span class="badge text-bg-light border">{{ $job->location }}</span>
                        @endif
                        @if($job->employment_type)
                            <span class="badge text-bg-light border">{{ $job->employment_type }}</span>
                        @endif
                        @if($job->category)
                            <span class="badge text-bg-light border">{{ $job->category }}</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <div class="fw-semibold mb-2">Deskripsi Pekerjaan</div>
                        <p class="text-secondary mb-0 preserve-lines">{{ $job->description }}</p>
                    </div>

                    @if($job->requirements)
                        <div class="mb-2">
                            <div class="fw-semibold mb-2">Persyaratan</div>
                            <p class="text-secondary mb-0 preserve-lines">{{ $job->requirements }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-3">Ringkasan</h3>

                    @if($job->salary_range)
                        <div class="small mb-2"><strong>Gaji:</strong> {{ $job->salary_range }}</div>
                    @endif
                    <div class="small mb-2"><strong>Perusahaan:</strong> {{ $job->company_name }}</div>
                    @if($job->location)
                        <div class="small mb-2"><strong>Lokasi:</strong> {{ $job->location }}</div>
                    @endif
                    @if($job->employment_type)
                        <div class="small mb-2"><strong>Tipe Kerja:</strong> {{ $job->employment_type }}</div>
                    @endif
                    @if($job->deadline_date)
                        <div class="small mb-3"><strong>Batas Lamar:</strong> {{ $job->deadline_date->format('d M Y') }}</div>
                    @endif

                    @if($job->apply_url)
                        <a href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-success w-100">
                            Lamar Sekarang
                        </a>
                    @else
                        <button class="btn btn-outline-secondary w-100" disabled>Link lamaran belum tersedia</button>
                    @endif
                </div>
            </div>

            @if($relatedJobs->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-3">Lowongan Terkait</h3>
                        <div class="d-flex flex-column gap-3">
                            @foreach($relatedJobs as $relatedJob)
                                <div>
                                    <a href="{{ route('minijobi.show', $relatedJob->id) }}" class="fw-semibold text-decoration-none">{{ $relatedJob->title }}</a>
                                    <div class="small text-muted">{{ $relatedJob->company_name }} - {{ $relatedJob->location }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('head')
<style>
    .minijobi-hero {
        background: linear-gradient(135deg, #187c19 0%, #00a38a 100%);
    }

    .preserve-lines {
        white-space: pre-line;
    }
</style>
@endpush

