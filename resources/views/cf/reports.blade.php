@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
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
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            @forelse($reports as $report)
                @php
                    $target = $targetMap[$report->id] ?? ['exists' => false, 'label' => '-', 'url' => null];
                @endphp
                <div class="p-3 border-bottom">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-1">
                                <span class="badge text-bg-{{ $report->status === 'open' ? 'danger' : ($report->status === 'resolved' ? 'success' : 'secondary') }}">
                                    {{ strtoupper($report->status) }}
                                </span>
                                <span class="badge text-bg-light border">{{ strtoupper($report->reportable_type) }}</span>
                                <span class="small text-muted">Report ID #{{ $report->id }}</span>
                            </div>

                            <div class="small text-muted mb-1">
                                Pelapor: {{ $report->reporter->name ?? 'Unknown' }} ({{ $report->reporter->email ?? '-' }})
                                | Dibuat: {{ $report->created_at?->format('d M Y H:i') }}
                            </div>

                            <div class="mb-1">
                                <strong>Target:</strong>
                                @if($target['url'])
                                    <a href="{{ $target['url'] }}" class="text-decoration-none">{{ $target['label'] }}</a>
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
                <div class="p-4 text-center text-muted">
                    Belum ada laporan untuk filter ini.
                </div>
            @endforelse

            <div class="p-3">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
