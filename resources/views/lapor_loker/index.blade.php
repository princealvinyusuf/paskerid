@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h1 class="h3 fw-bold mb-2">Lapor Loker</h1>
                            <p class="text-muted mb-0">Pusat Informasi Loker Palsu dan Pelaporan Kerja Palsu</p>
                        </div>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button class="btn btn-outline-success btn-lg" data-bs-toggle="modal" data-bs-target="#laporLokerBulkModal">
                                <i class="fa-solid fa-file-arrow-up me-2"></i>Lapor Loker (Bulking)
                            </button>
                            <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#laporLokerModal">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>Laporkan Loker Hoax
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->getBag('default')->any())
                <div class="alert alert-danger">
                    <div class="fw-bold mb-2">Mohon periksa kembali data laporan:</div>
                    <ul class="mb-0">
                        @foreach ($errors->getBag('default')->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($errors->getBag('bulkReport')->any())
                <div class="alert alert-danger">
                    <div class="fw-bold mb-2">Mohon periksa kembali data bulk report:</div>
                    <ul class="mb-0">
                        @foreach ($errors->getBag('bulkReport')->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Email Terduga Pelaku</th>
                                    <th>Tanggal Terdeteksi</th>
                                    <th>Nama Perusahaan Yang Digunakan</th>
                                    <th>Nama HR Yang Digunakan</th>
                                    <th>Provinsi</th>
                                    <th>Kota</th>
                                    <th>Kontak Terduga</th>
                                    <th>Platform Sumber</th>
                                    <th>Tautan Informasi</th>
                                    <th>Tindak Lanjut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>{{ $report->email_terduga_pelaku }}</td>
                                        <td>{{ optional($report->tanggal_terdeteksi)->format('d M Y') }}</td>
                                        <td>{{ $report->nama_perusahaan_digunakan }}</td>
                                        <td>{{ $report->nama_hr_digunakan }}</td>
                                        <td>{{ $report->provinsi }}</td>
                                        <td>{{ $report->kota }}</td>
                                        <td>{{ $report->nomor_kontak_terduga ?: '-' }}</td>
                                        <td>{{ $report->platform_sumber ?: '-' }}</td>
                                        <td>
                                            @if(!empty($report->tautan_informasi))
                                                <a href="{{ $report->tautan_informasi }}" target="_blank" rel="noopener noreferrer">Lihat</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $publicFollowUps = [];
                                                if ((int)($report->tindak_lanjut_tutup_lowongan ?? 0) === 1) {
                                                    $publicFollowUps[] = 'Menutup Lowongan Kerja';
                                                }
                                                if ((int)($report->tindak_lanjut_tutup_akun_perusahaan ?? 0) === 1) {
                                                    $publicFollowUps[] = 'Menutup Akun Perusahaan';
                                                }
                                                if ((int)($report->tindak_lanjut_lainnya_checked ?? 0) === 1) {
                                                    $otherPublic = trim((string)($report->tindak_lanjut_lainnya_text ?? ''));
                                                    $publicFollowUps[] = $otherPublic !== '' ? ('Lainnya: ' . $otherPublic) : 'Lainnya';
                                                }
                                            @endphp
                                            @if(empty($publicFollowUps))
                                                -
                                            @else
                                                <ul class="mb-0 ps-3">
                                                    @foreach($publicFollowUps as $publicFollowUp)
                                                        <li>{{ $publicFollowUp }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            Belum ada laporan yang sudah diverifikasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $reports->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="laporLokerBulkModal" tabindex="-1" aria-labelledby="laporLokerBulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <form method="POST" action="{{ route('lapor-loker.bulk-store') }}" enctype="multipart/form-data" class="d-flex flex-column h-100">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="laporLokerBulkModalLabel">Lapor Loker (Bulking)</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Passcode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="bulk_passcode" value="{{ old('bulk_passcode') }}" required placeholder="Isi Portal Code (unique)">
                            <div class="form-text">Passcode harus sama dengan <strong>Portal Code (unique)</strong>.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">File Excel/CSV <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="bulk_file" required accept=".xlsx,.csv">
                            <div class="form-text">
                                Format file didukung: XLSX, CSV (maks. 10MB). Header kolom wajib:
                                <code>Email Terduga Pelaku</code>, <code>Tanggal Terdeteksi</code>, <code>Nama Perusahaan Yang Digunakan</code>, <code>Nama HR Yang Digunakan</code>,
                                <code>Provinsi</code>, <code>Kota</code>, <code>Nomor Kontak Terduga</code>, <code>Platform Sumber</code>, <code>Tautan Informasi</code>,
                                <code>Nama Pelapor</code>, <code>Email Pelapor</code>.
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('lapor-loker.bulk-template') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-download me-1"></i>Download Template CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import Bulk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="laporLokerModal" tabindex="-1" aria-labelledby="laporLokerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <form method="POST" action="{{ route('lapor-loker.store') }}" enctype="multipart/form-data" class="d-flex flex-column h-100">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="laporLokerModalLabel">Form Laporan Loker Hoax</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Terduga Pelaku <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email_terduga_pelaku" value="{{ old('email_terduga_pelaku') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Terdeteksi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_terdeteksi" value="{{ old('tanggal_terdeteksi') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan Yang Digunakan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_perusahaan_digunakan" value="{{ old('nama_perusahaan_digunakan') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama HR Yang Digunakan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_hr_digunakan" value="{{ old('nama_hr_digunakan') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="provinsi" value="{{ old('provinsi') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kota" value="{{ old('kota') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Kontak Terduga <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nomor_kontak_terduga" value="{{ old('nomor_kontak_terduga') }}" placeholder="Contoh: +62..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Platform Sumber <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="platform_sumber" value="{{ old('platform_sumber') }}" placeholder="Contoh: WhatsApp, Telegram, Email" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tautan Informasi <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" name="tautan_informasi" value="{{ old('tautan_informasi') }}" placeholder="https://..." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Lampirkan Bukti Pendukung</label>
                            <input type="file" class="form-control" name="bukti_pendukung" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">
                            <div class="form-text">Format yang didukung: JPG, PNG, WEBP, PDF, DOC, DOCX (maks. 10MB).</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Kronologi Singkat</label>
                            <textarea class="form-control" name="kronologi" rows="4" placeholder="Jelaskan detail kronologi penipuan yang diketahui">{{ old('kronologi') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Pelapor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pelapor_nama" value="{{ old('pelapor_nama') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Pelapor <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="pelapor_email" value="{{ old('pelapor_email') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('head')
<style>
    #laporLokerBulkModal .modal-dialog,
    #laporLokerModal .modal-dialog {
        height: calc(100% - 1rem);
        margin: 0.5rem auto;
    }

    #laporLokerBulkModal .modal-content,
    #laporLokerModal .modal-content {
        height: 100%;
        max-height: 100%;
        overflow: hidden;
    }

    #laporLokerBulkModal form,
    #laporLokerModal form {
        min-height: 0;
    }

    #laporLokerBulkModal .modal-body,
    #laporLokerModal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 575.98px) {
        #laporLokerBulkModal .modal-dialog,
        #laporLokerModal .modal-dialog {
            height: 100%;
            margin: 0;
            max-width: 100%;
        }

        #laporLokerBulkModal .modal-content,
        #laporLokerModal .modal-content {
            max-height: 100vh;
            border-radius: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        var shouldOpenSingleModal = {{ $errors->getBag('default')->any() ? 'true' : 'false' }};
        var shouldOpenBulkModal = {{ $errors->getBag('bulkReport')->any() ? 'true' : 'false' }};

        if (!shouldOpenSingleModal && !shouldOpenBulkModal) {
            return;
        }

        var modalId = shouldOpenBulkModal ? 'laporLokerBulkModal' : 'laporLokerModal';
        var modalEl = document.getElementById(modalId);
        if (!modalEl || !window.bootstrap || !window.bootstrap.Modal) {
            return;
        }

        var modal = new window.bootstrap.Modal(modalEl);
        modal.show();
    })();
</script>
@endpush
