@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">CF (Underconstruction)</h1>
            <p class="text-muted mb-0">
                Forum komunitas pasar kerja untuk pelaku usaha dan pencari kerja.
            </p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge text-bg-warning">Under Construction</span>
            @auth
                <a href="{{ route('cf.notifications.index') }}" class="btn btn-outline-primary btn-sm">
                    Notifikasi
                    @if(($unreadNotificationsCount ?? 0) > 0)
                        <span class="badge text-bg-danger">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>
            @endauth
            @if($isCfAdmin ?? false)
                <a href="{{ route('cf.admin.reports.index') }}" class="btn btn-outline-danger btn-sm">Moderation Center</a>
            @endif
            <a href="{{ route('cf.threads.create') }}" class="btn btn-success btn-sm">+ Buat Thread</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('cf.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="category" class="form-label mb-1">Kategori</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="author_type" class="form-label mb-1">Tipe Penulis</label>
                    <select name="author_type" id="author_type" class="form-select">
                        <option value="">Semua</option>
                        <option value="employer" @selected(($filters['author_type'] ?? '') === 'employer')>Perusahaan</option>
                        <option value="jobseeker" @selected(($filters['author_type'] ?? '') === 'jobseeker')>Pencari Kerja</option>
                        <option value="community" @selected(($filters['author_type'] ?? '') === 'community')>Komunitas</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="q" class="form-label mb-1">Cari</label>
                    <input type="text" id="q" name="q" class="form-control" placeholder="Kata kunci..." value="{{ $filters['q'] ?? '' }}">
                </div>
                <div class="col-12 col-md-2">
                    <label for="work_type" class="form-label mb-1">Tipe Kerja</label>
                    <select name="work_type" id="work_type" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['Onsite', 'Hybrid', 'Remote', 'Freelance', 'Project Based'] as $type)
                            <option value="{{ $type }}" @selected(($filters['work_type'] ?? '') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label for="experience_level" class="form-label mb-1">Pengalaman</label>
                    <select name="experience_level" id="experience_level" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['Fresh Graduate', 'Junior', 'Mid', 'Senior', 'Lead'] as $level)
                            <option value="{{ $level }}" @selected(($filters['experience_level'] ?? '') === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label for="province" class="form-label mb-1">Provinsi</label>
                    <input type="text" id="province" name="province" class="form-control" value="{{ $filters['province'] ?? '' }}">
                </div>
                <div class="col-12 col-md-2">
                    <label for="city" class="form-label mb-1">Kota</label>
                    <input type="text" id="city" name="city" class="form-control" value="{{ $filters['city'] ?? '' }}">
                </div>
                <div class="col-12 col-md-2">
                    <label for="job_role" class="form-label mb-1">Jabatan</label>
                    <input type="text" id="job_role" name="job_role" class="form-control" value="{{ $filters['job_role'] ?? '' }}">
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-success">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h6 fw-bold mb-0">Matching Discussions</h2>
                <span class="small text-muted">
                    @if(($matchingSource ?? 'default') === 'filter')
                        Berdasarkan filter Anda
                    @elseif(($matchingSource ?? 'default') === 'activity')
                        Berdasarkan aktivitas Anda
                    @else
                        Belum cukup data preferensi
                    @endif
                </span>
            </div>
            @if(!empty($matchingThreads))
                <div class="row g-3">
                    @foreach($matchingThreads as $match)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100">
                                <a href="{{ route('cf.threads.show', $match->id) }}" class="fw-semibold text-decoration-none d-block mb-1">
                                    {{ $match->title }}
                                </a>
                                <div class="small text-muted">
                                    {{ $match->category->name ?? '-' }} |
                                    {{ $match->work_type ?? 'Tipe kerja belum diisi' }}
                                    @if(!empty($match->city) || !empty($match->province))
                                        | {{ $match->city ?? '-' }}{{ (!empty($match->city) && !empty($match->province)) ? ', ' : '' }}{{ $match->province ?? '' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="small text-muted">
                    Belum ada rekomendasi yang cocok. Isi metadata jabatan/lokasi/tipe kerja untuk meningkatkan relevansi.
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">Hot Threads</h2>
                    @forelse($hotThreads as $hotThread)
                        <div class="mb-2 pb-2 border-bottom">
                            <a href="{{ route('cf.threads.show', $hotThread->id) }}" class="text-decoration-none fw-semibold">
                                {{ $hotThread->title }}
                            </a>
                            <div class="small text-muted">
                                Balasan: {{ (int) ($hotThread->replies_count ?? 0) }} | Views: {{ number_format($hotThread->views_count) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small">Belum ada hot thread.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-12 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-body p-3">
                        <h2 class="h6 fw-bold mb-1">{{ $category->name }}</h2>
                        <p class="text-muted small mb-0">{{ $category->description }}</p>
                    </div>
                </div>
            </div>
        @endforeach
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h3 class="h5 fw-bold mb-0">Thread Terbaru</h3>
                <span class="text-muted small">{{ $threads->total() }} thread</span>
            </div>

            @forelse($threads as $thread)
                <div class="p-3 border-bottom">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <a href="{{ route('cf.threads.show', $thread->id) }}" class="fw-semibold text-decoration-none">
                            {{ $thread->title }}
                        </a>
                        @if($thread->is_pinned)
                            <span class="badge text-bg-info">Pinned</span>
                        @endif
                        @if($thread->status === 'closed')
                            <span class="badge text-bg-secondary">Closed</span>
                        @endif
                    </div>
                    <div class="small text-muted">
                        {{ $thread->category->name ?? '-' }} |
                        Oleh: {{ $thread->user->name ?? 'Anonim' }}
                        @php $authorReputation = $reputationMap[$thread->user_id] ?? null; @endphp
                        @if($authorReputation)
                            <span class="badge text-bg-light border">{{ $authorReputation['badge'] }}</span>
                        @endif
                        |
                        Tipe:
                        @if($thread->author_type === 'employer')
                            Perusahaan
                        @elseif($thread->author_type === 'jobseeker')
                            Pencari Kerja
                        @else
                            Komunitas
                        @endif
                        |
                        Balasan: {{ (int) ($thread->replies_count ?? 0) }} |
                        Views: {{ number_format($thread->views_count) }}
                        @if($thread->location)
                            | Lokasi: {{ $thread->location }}
                        @endif
                        @if($thread->sector)
                            | Sektor: {{ $thread->sector }}
                        @endif
                        @if($thread->job_role)
                            | Jabatan: {{ $thread->job_role }}
                        @endif
                        @if($thread->work_type)
                            | Tipe Kerja: {{ $thread->work_type }}
                        @endif
                        @if($thread->experience_level)
                            | Pengalaman: {{ $thread->experience_level }}
                        @endif
                        @if($thread->province || $thread->city)
                            | Area:
                            @if($thread->city){{ $thread->city }}@endif
                            @if($thread->city && $thread->province), @endif
                            @if($thread->province){{ $thread->province }}@endif
                        @endif
                        @if($thread->salary_range)
                            | Gaji: {{ $thread->salary_range }}
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">
                    Belum ada thread. Mulai diskusi pertama untuk komunitas pasar kerja.
                </div>
            @endforelse

            <div class="p-3">
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
