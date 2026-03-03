@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Verifikasi Profil CF</h1>
            <p class="text-muted mb-0">Ajukan badge verifikasi sebagai employer atau jobseeker.</p>
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
        <div class="card-body p-4">
            <div class="mb-3">
                <strong>Status Saat Ini:</strong>
                @if($user->cf_verified_role === 'employer')
                    <span class="badge text-bg-primary">Verified Employer</span>
                @elseif($user->cf_verified_role === 'jobseeker')
                    <span class="badge text-bg-primary">Verified Jobseeker</span>
                @else
                    <span class="badge text-bg-secondary">Belum terverifikasi</span>
                @endif
            </div>

            <form method="POST" action="{{ route('cf.verification.store') }}" class="row g-3">
                @csrf
                <div class="col-12 col-md-4">
                    <label for="requested_role" class="form-label">Role Verifikasi</label>
                    <select id="requested_role" name="requested_role" class="form-select" required>
                        <option value="">Pilih role</option>
                        <option value="employer" @selected(old('requested_role') === 'employer')>Employer</option>
                        <option value="jobseeker" @selected(old('requested_role') === 'jobseeker')>Jobseeker</option>
                    </select>
                </div>

                <div class="col-12 col-md-8">
                    <label for="organization_name" class="form-label">Nama Perusahaan/Instansi (opsional)</label>
                    <input id="organization_name" name="organization_name" type="text" class="form-control" maxlength="160" value="{{ old('organization_name') }}">
                </div>

                <div class="col-12">
                    <label for="evidence_url" class="form-label">Link Bukti (opsional)</label>
                    <input id="evidence_url" name="evidence_url" type="url" class="form-control" maxlength="500" value="{{ old('evidence_url') }}" placeholder="https://...">
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label">Catatan Pengajuan</label>
                    <textarea id="notes" name="notes" rows="4" class="form-control" maxlength="3000">{{ old('notes') }}</textarea>
                </div>

                <div class="col-12 d-grid d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="p-3 border-bottom">
                <h2 class="h6 fw-bold mb-0">Riwayat Pengajuan</h2>
            </div>
            @forelse($requests as $requestItem)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="small text-muted">
                            {{ strtoupper($requestItem->requested_role) }} | {{ $requestItem->created_at?->format('d M Y H:i') }}
                        </div>
                        <span class="badge text-bg-{{ $requestItem->status === 'pending' ? 'warning' : ($requestItem->status === 'approved' ? 'success' : 'secondary') }}">
                            {{ strtoupper($requestItem->status) }}
                        </span>
                    </div>
                    @if($requestItem->organization_name)
                        <div><strong>Instansi:</strong> {{ $requestItem->organization_name }}</div>
                    @endif
                    @if($requestItem->evidence_url)
                        <div><strong>Bukti:</strong> <a href="{{ $requestItem->evidence_url }}" target="_blank">{{ $requestItem->evidence_url }}</a></div>
                    @endif
                    @if($requestItem->notes)
                        <div><strong>Catatan:</strong> {{ $requestItem->notes }}</div>
                    @endif
                    @if($requestItem->review_note)
                        <div class="small text-muted mt-1"><strong>Review admin:</strong> {{ $requestItem->review_note }}</div>
                    @endif
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
