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
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-success">Filter</button>
                </div>
            </form>
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
