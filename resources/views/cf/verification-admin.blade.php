@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Verification Admin</h1>
            <p class="text-muted mb-0">Review pengajuan badge employer/jobseeker.</p>
        </div>
        <a href="{{ route('cf.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Forum</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body d-flex gap-2 flex-wrap">
            <a href="{{ route('cf.admin.verifications.index') }}" class="btn btn-sm {{ $status === '' ? 'btn-success' : 'btn-outline-success' }}">
                Semua ({{ array_sum($statusCounts) }})
            </a>
            <a href="{{ route('cf.admin.verifications.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-success' : 'btn-outline-success' }}">
                Pending ({{ $statusCounts['pending'] }})
            </a>
            <a href="{{ route('cf.admin.verifications.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                Approved ({{ $statusCounts['approved'] }})
            </a>
            <a href="{{ route('cf.admin.verifications.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-success' : 'btn-outline-success' }}">
                Rejected ({{ $statusCounts['rejected'] }})
            </a>
            <a href="{{ route('cf.admin.verifications.export-csv', ['status' => $status]) }}" class="btn btn-sm btn-outline-primary ms-auto">
                Export CSV
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            @forelse($requests as $req)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <strong>{{ $req->user->name ?? '-' }}</strong>
                            <span class="small text-muted">({{ $req->user->email ?? '-' }})</span>
                        </div>
                        <span class="badge text-bg-{{ $req->status === 'pending' ? 'warning' : ($req->status === 'approved' ? 'success' : 'secondary') }}">
                            {{ strtoupper($req->status) }}
                        </span>
                    </div>
                    <div class="small text-muted mb-2">
                        Role diajukan: {{ strtoupper($req->requested_role) }} | {{ $req->created_at?->format('d M Y H:i') }}
                    </div>
                    @if($req->organization_name)
                        <div><strong>Instansi:</strong> {{ $req->organization_name }}</div>
                    @endif
                    @if($req->evidence_url)
                        <div><strong>Bukti:</strong> <a href="{{ $req->evidence_url }}" target="_blank">{{ $req->evidence_url }}</a></div>
                    @endif
                    @if($req->notes)
                        <div><strong>Catatan User:</strong> {{ $req->notes }}</div>
                    @endif
                    @if($req->review_note)
                        <div class="small text-muted"><strong>Review Sebelumnya:</strong> {{ $req->review_note }}</div>
                    @endif

                    <form method="POST" action="{{ route('cf.admin.verifications.update-status', ['id' => $req->id, 'status' => $status]) }}" class="row g-2 mt-2">
                        @csrf
                        <div class="col-12 col-md-3">
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="pending" @selected($req->status === 'pending')>Pending</option>
                                <option value="approved" @selected($req->status === 'approved')>Approved</option>
                                <option value="rejected" @selected($req->status === 'rejected')>Rejected</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-7">
                            <input name="review_note" type="text" class="form-control form-control-sm" value="{{ old('review_note', $req->review_note) }}" placeholder="Catatan admin (opsional)">
                        </div>
                        <div class="col-12 col-md-2 d-grid">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="p-4 text-center text-muted">Belum ada pengajuan verifikasi.</div>
            @endforelse
            <div class="p-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
