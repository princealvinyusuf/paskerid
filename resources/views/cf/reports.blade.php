@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="cf-hero d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="cf-hero-icon"><i class="fa-solid fa-shield-halved"></i></span>
            <div class="cf-section-title mb-1">Admin Workspace</div>
            <h1 class="h4 fw-bold mb-1">CF Moderation Center</h1>
            <p class="text-muted mb-0">Kelola laporan thread dan balasan komunitas.</p>
        </div>
        <a href="{{ route('cf.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Forum</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(($autoClosedCount ?? 0) > 0)
        <div class="alert alert-info">
            {{ $autoClosedCount }} laporan open telah di-auto-close sesuai kebijakan stale report.
            Anda tetap dapat membuka kembali kasus melalui pengaturan status (admin override).
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="small text-muted">Open Reports</div>
                    <div class="h4 fw-bold mb-0">{{ (int) ($summary['open_total'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="small text-muted">Prioritas Open</div>
                    <div class="small">
                        High: <strong>{{ (int) ($summary['open_high'] ?? 0) }}</strong> |
                        Medium: <strong>{{ (int) ($summary['open_medium'] ?? 0) }}</strong> |
                        Low: <strong>{{ (int) ($summary['open_low'] ?? 0) }}</strong>
                    </div>
                    <div class="small mt-1">
                        Escalated: <strong>{{ (int) ($summary['open_escalated'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="small text-muted">Aging Open</div>
                    <div class="small">
                        7+ hari: <strong>{{ (int) ($summary['open_age_7_plus'] ?? 0) }}</strong> |
                        14+ hari: <strong>{{ (int) ($summary['open_age_14_plus'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="small text-muted">Aktivitas Hari Ini</div>
                    <div class="small">
                        Baru: <strong>{{ (int) ($summary['new_today'] ?? 0) }}</strong> |
                        Resolved: <strong>{{ (int) ($summary['resolved_today'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('cf.admin.reports.index') }}" class="btn btn-sm {{ $status === '' ? 'btn-success' : 'btn-outline-success' }}">
                    Semua ({{ array_sum($statusCounts) }})
                </a>
                <a href="{{ route('cf.admin.reports.index', ['status' => 'open']) }}" class="btn btn-sm {{ $status === 'open' ? 'btn-success' : 'btn-outline-success' }}">
                    Open ({{ $statusCounts['open'] }})
                </a>
                <a href="{{ route('cf.admin.reports.index', ['status' => 'resolved']) }}" class="btn btn-sm {{ $status === 'resolved' ? 'btn-success' : 'btn-outline-success' }}">
                    Resolved ({{ $statusCounts['resolved'] }})
                </a>
                <a href="{{ route('cf.admin.reports.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-success' : 'btn-outline-success' }}">
                    Rejected ({{ $statusCounts['rejected'] }})
                </a>
                <a href="{{ route('cf.admin.reports.export-csv', ['status' => $status]) }}" class="btn btn-sm btn-outline-primary ms-auto">
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            @forelse($reports as $report)
                @php
                    $target = $targetMap[$report->id] ?? ['exists' => false, 'label' => '-', 'url' => null];
                    $audits = $auditMap[$report->id] ?? [];
                @endphp
                <div class="p-3 border-bottom cf-list-item">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-1">
                                <span class="badge text-bg-{{ $report->status === 'open' ? 'danger' : ($report->status === 'resolved' ? 'success' : 'secondary') }}">
                                    {{ strtoupper($report->status) }}
                                </span>
                                <span class="badge text-bg-{{ ($report->priority_level ?? 'low') === 'high' ? 'danger' : (($report->priority_level ?? 'low') === 'medium' ? 'warning' : 'secondary') }}">
                                    PRIORITY {{ strtoupper((string) ($report->priority_level ?? 'low')) }}
                                </span>
                                @if(($report->escalation_level ?? 'none') !== 'none')
                                    <span class="badge text-bg-{{ in_array((string) $report->escalation_level, ['critical', 'urgent'], true) ? 'dark' : 'info' }}">
                                        ESCALATION {{ strtoupper((string) $report->escalation_level) }}
                                    </span>
                                @endif
                                <span class="badge text-bg-light border">{{ strtoupper($report->reportable_type) }}</span>
                                <span class="small text-muted">Report ID #{{ $report->id }}</span>
                            </div>

                            <div class="small text-muted mb-1">
                                Pelapor: {{ $report->reporter->name ?? 'Unknown' }} ({{ $report->reporter->email ?? '-' }})
                                | Dibuat: {{ $report->created_at?->format('d M Y H:i') }}
                                | Skor Prioritas: {{ (int) ($report->priority_score ?? 0) }}
                            </div>

                            <div class="mb-1">
                                <strong>Target:</strong>
                                @if($target['url'])
                                    <a href="{{ $target['url'] }}" class="text-decoration-none cf-link-lift">{{ $target['label'] }}</a>
                                @else
                                    <span class="text-muted">{{ $target['label'] }}</span>
                                @endif
                            </div>

                            <div class="mb-2">
                                <strong>Alasan laporan:</strong>
                                <div class="text-muted" style="white-space: pre-line;">{{ $report->reason }}</div>
                            </div>

                            @if($report->reviewed_by_user_id)
                                <div class="small text-muted">
                                    Ditinjau oleh: {{ $report->reviewer->name ?? '-' }}
                                    @if($report->reviewed_at)
                                        pada {{ $report->reviewed_at->format('d M Y H:i') }}
                                    @endif
                                </div>
                            @endif

                            @if(!empty($audits))
                                <div class="small text-muted mt-2">
                                    <strong>Audit Trail:</strong>
                                </div>
                                <div class="small">
                                    @foreach($audits as $audit)
                                        <div class="text-muted">
                                            {{ $audit->created_at?->format('d M Y H:i') }} -
                                            {{ strtoupper((string) $audit->action) }}
                                            @if($audit->from_status || $audit->to_status)
                                                ({{ strtoupper((string) ($audit->from_status ?? '-')) }} -> {{ strtoupper((string) ($audit->to_status ?? '-')) }})
                                            @endif
                                            @if($audit->escalation_level)
                                                | Esc: {{ strtoupper((string) $audit->escalation_level) }}
                                            @endif
                                            @if($audit->actor)
                                                | by {{ $audit->actor->name ?? '-' }}
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div style="min-width: 280px;">
                            <form method="POST" action="{{ route('cf.admin.reports.update-status', ['id' => $report->id, 'status' => $status]) }}" class="d-grid gap-2">
                                @csrf
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="open" @selected($report->status === 'open')>Open</option>
                                    <option value="resolved" @selected($report->status === 'resolved')>Resolved</option>
                                    <option value="rejected" @selected($report->status === 'rejected')>Rejected</option>
                                </select>
                                <textarea name="review_note" rows="3" class="form-control form-control-sm" placeholder="Catatan moderator (opsional)">{{ old('review_note', $report->review_note) }}</textarea>
                                <button type="submit" class="btn btn-sm btn-primary">Simpan Status</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4">
                    <div class="cf-empty-state">
                        <i class="fa-regular fa-folder-open"></i>
                        <div>Belum ada laporan untuk filter ini.</div>
                    </div>
                </div>
            @endforelse

            <div class="p-3">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
