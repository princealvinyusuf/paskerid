@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="cf-hero d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="cf-hero-icon"><i class="fa-solid fa-chart-line"></i></span>
            <div class="cf-section-title mb-1">Insights</div>
            <h1 class="h4 fw-bold mb-1">CF Trend Analytics</h1>
            <p class="text-muted mb-0">Ringkasan tren diskusi pasar kerja untuk {{ $period }} hari terakhir.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cf.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Forum</a>
            <a href="{{ route('cf.admin.reports.index') }}" class="btn btn-outline-danger btn-sm">Moderation Center</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body d-flex gap-2 flex-wrap align-items-center">
            <span class="small text-muted">Periode:</span>
            <a href="{{ route('cf.admin.trends.index', ['period' => 7]) }}" class="btn btn-sm {{ (int) $period === 7 ? 'btn-info' : 'btn-outline-info' }}">
                7 Hari
            </a>
            <a href="{{ route('cf.admin.trends.index', ['period' => 30]) }}" class="btn btn-sm {{ (int) $period === 30 ? 'btn-info' : 'btn-outline-info' }}">
                30 Hari
            </a>
            <span class="small text-muted ms-auto">Mulai: {{ $periodStart?->format('d M Y') }}</span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="small text-muted">Total Threads</div>
                    <div class="h4 fw-bold mb-0">{{ (int) ($totals['threads'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="small text-muted">Total Replies</div>
                    <div class="h4 fw-bold mb-0">{{ (int) ($totals['replies'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="small text-muted">Total Views</div>
                    <div class="h4 fw-bold mb-0">{{ number_format((int) ($totals['views'] ?? 0)) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="small text-muted">Hot Keywords</div>
                    <div class="h4 fw-bold mb-0">{{ (int) ($totals['keywords'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">Top Categories</h2>
                    @forelse($topCategories as $name => $count)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <span>{{ $name }}</span>
                            <strong>{{ (int) $count }}</strong>
                        </div>
                    @empty
                        <div class="cf-empty-state">
                            <i class="fa-regular fa-folder-open"></i>
                            <div class="small">Belum ada data kategori untuk periode ini.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">Top Keywords (Title)</h2>
                    <div class="d-flex gap-2 flex-wrap">
                        @forelse($topKeywords as $keyword => $count)
                            <span class="badge text-bg-light border text-dark">
                                {{ $keyword }} ({{ (int) $count }})
                            </span>
                        @empty
                            <div class="cf-empty-state w-100">
                                <i class="fa-solid fa-hashtag"></i>
                                <div class="small">Belum ada keyword dominan.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">Top Job Roles</h2>
                    @forelse($topJobRoles as $name => $count)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <span>{{ $name }}</span>
                            <strong>{{ (int) $count }}</strong>
                        </div>
                    @empty
                        <div class="cf-empty-state">
                            <i class="fa-regular fa-address-card"></i>
                            <div class="small">Belum ada data jabatan.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">Top Work Type & Location</h2>
                    <div class="mb-3">
                        <div class="small text-muted mb-1">Work Type</div>
                        @forelse($topWorkTypes as $name => $count)
                            <div class="d-flex justify-content-between border-bottom py-1 small">
                                <span>{{ $name }}</span>
                                <strong>{{ (int) $count }}</strong>
                            </div>
                        @empty
                            <div class="cf-empty-state">
                                <i class="fa-solid fa-briefcase"></i>
                                <div class="small">Belum ada data tipe kerja.</div>
                            </div>
                        @endforelse
                    </div>
                    <div>
                        <div class="small text-muted mb-1">Location</div>
                        @forelse($topLocations as $name => $count)
                            <div class="d-flex justify-content-between border-bottom py-1 small">
                                <span>{{ $name }}</span>
                                <strong>{{ (int) $count }}</strong>
                            </div>
                        @empty
                            <div class="cf-empty-state">
                                <i class="fa-solid fa-location-dot"></i>
                                <div class="small">Belum ada data lokasi.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h3 class="h6 fw-bold mb-0">Top Threads by Engagement</h3>
                <span class="small text-muted">{{ $topThreads->count() }} thread</span>
            </div>
            @forelse($topThreads as $thread)
                <div class="p-3 border-bottom cf-list-item">
                    <a href="{{ route('cf.threads.show', $thread->id) }}" class="fw-semibold text-decoration-none cf-link-lift">
                        {{ $thread->title }}
                    </a>
                    <div class="small text-muted">
                        {{ $thread->category->name ?? '-' }} |
                        Oleh: {{ $thread->user->name ?? 'Anonim' }} |
                        Balasan: {{ (int) ($thread->replies_count ?? 0) }} |
                        Views: {{ number_format((int) ($thread->views_count ?? 0)) }}
                    </div>
                </div>
            @empty
                <div class="p-4">
                    <div class="cf-empty-state">
                        <i class="fa-regular fa-chart-bar"></i>
                        <div>Belum ada thread untuk periode ini.</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
