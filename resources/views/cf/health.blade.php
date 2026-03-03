@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="cf-hero d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="cf-section-title mb-1">Operations</div>
            <h1 class="h4 fw-bold mb-1">CF Health Monitor</h1>
            <p class="text-muted mb-0">Ringkasan operasional queue, moderasi, dan aktivitas terbaru.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cf.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Forum</a>
            <a href="{{ route('cf.admin.reports.index') }}" class="btn btn-outline-danger btn-sm">Moderation Center</a>
        </div>
    </div>

    <div class="alert alert-light border mb-4">
        Queue connection: <strong>{{ $queueConnection }}</strong>
        <span class="text-muted">| Generated: {{ $generatedAt?->format('d M Y H:i:s') }}</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="small text-muted">Jobs Pending</div>
                    <div class="h4 fw-bold mb-0">{{ (int) $jobsPending }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="small text-muted">Failed Jobs</div>
                    <div class="h4 fw-bold mb-0">{{ (int) $failedJobsCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="small text-muted">Open Reports</div>
                    <div class="h4 fw-bold mb-0">{{ (int) $openReports }}</div>
                    <div class="small text-muted">Escalated: {{ (int) $openEscalated }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="small text-muted">Hidden Content</div>
                    <div class="h4 fw-bold mb-0">{{ (int) $hiddenThreads + (int) $hiddenReplies }}</div>
                    <div class="small text-muted">Threads: {{ (int) $hiddenThreads }} | Replies: {{ (int) $hiddenReplies }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">Activity (24 Jam)</h2>
                    <div class="d-flex justify-content-between border-bottom py-2 small">
                        <span>Threads dibuat</span>
                        <strong>{{ (int) ($activity24h['threads_created'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2 small">
                        <span>Replies dibuat</span>
                        <strong>{{ (int) ($activity24h['replies_created'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2 small">
                        <span>Reports masuk</span>
                        <strong>{{ (int) ($activity24h['reports_created'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2 small">
                        <span>Audit entries</span>
                        <strong>{{ (int) ($activity24h['audits_logged'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 small">
                        <span>Unread notifications</span>
                        <strong>{{ (int) $unreadNotifications }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <h2 class="h6 fw-bold mb-0">Recent Failed Jobs</h2>
                    </div>
                    @forelse($failedRecent as $row)
                        <div class="p-3 border-bottom">
                            <div class="small mb-1">
                                <strong>#{{ $row['id'] }}</strong> |
                                Queue: {{ $row['queue'] }} |
                                {{ $row['failed_at'] }}
                            </div>
                            <div class="small text-muted">{{ $row['summary'] }}</div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            Tidak ada failed job terbaru.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
