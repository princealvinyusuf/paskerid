@extends('layouts.app')

@section('content')
<section class="minijobi-hero">
    <div class="container py-5">
        <h1 class="fw-bold text-white mb-2">miniJobi</h1>
        <p class="text-white mb-0">Portal mini lowongan kerja Pasker ID. Jelajahi semua peluang kerja terbaru.</p>
    </div>
</section>

<div class="container my-5">
    <form method="GET" class="card shadow-sm border-0 mb-4">
        <div class="card-body row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label fw-semibold">Kata kunci</label>
                <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Jabatan / perusahaan / lokasi">
            </div>
            <div class="col-md-2">
                <label for="location" class="form-label fw-semibold">Lokasi</label>
                <select id="location" name="location" class="form-select">
                    <option value="">Semua</option>
                    @foreach($locations as $location)
                        <option value="{{ $location }}" @selected(request('location') === $location)>{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="employment_type" class="form-label fw-semibold">Tipe Kerja</label>
                <select id="employment_type" name="employment_type" class="form-select">
                    <option value="">Semua</option>
                    @foreach($employmentTypes as $employmentType)
                        <option value="{{ $employmentType }}" @selected(request('employment_type') === $employmentType)>{{ $employmentType }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label fw-semibold">Kategori</label>
                <select id="category" name="category" class="form-select">
                    <option value="">Semua</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-success">Cari</button>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 fw-bold mb-0">Semua Lowongan</h2>
        <span class="text-muted">{{ $jobs->total() }} lowongan ditemukan</span>
    </div>

    <div class="row g-4">
        @forelse($jobs as $job)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h3 class="h6 fw-bold mb-2">{{ $job->title }}</h3>
                        <div class="small text-muted mb-3">{{ $job->company_name }}</div>

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

                        <p class="mb-3 text-secondary">{{ \Illuminate\Support\Str::limit($job->description, 140) }}</p>

                        @if($job->salary_range)
                            <div class="small mb-2"><strong>Gaji:</strong> {{ $job->salary_range }}</div>
                        @endif

                        @if($job->deadline_date)
                            <div class="small mb-3"><strong>Batas Lamar:</strong> {{ $job->deadline_date->format('d M Y') }}</div>
                        @endif

                        <div class="mt-auto">
                            <a href="{{ route('minijobi.show', $job->id) }}" class="btn btn-outline-success btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center mb-0">
                    Belum ada lowongan aktif di miniJobi.
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $jobs->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('head')
<style>
    .minijobi-hero {
        background: linear-gradient(135deg, #187c19 0%, #00a38a 100%);
    }

    .page-link {
        color: #42bba8 !important;
    }

    .page-link:hover {
        color: #fff !important;
        background-color: #42bba8;
        border-color: #42bba8;
    }

    .page-item.active .page-link {
        background-color: #42bba8;
        border-color: #42bba8;
        color: #fff !important;
    }
</style>
@endpush

