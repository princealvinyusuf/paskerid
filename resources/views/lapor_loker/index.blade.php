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
                        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#laporLokerModal">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>Laporkan Loker Hoax
                        </button>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-bold mb-2">Mohon periksa kembali data laporan:</div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
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

<div class="modal fade" id="laporLokerModal" tabindex="-1" aria-labelledby="laporLokerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('lapor-loker.store') }}">
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
                            <label class="form-label">Nomor Kontak Terduga</label>
                            <input type="text" class="form-control" name="nomor_kontak_terduga" value="{{ old('nomor_kontak_terduga') }}" placeholder="Contoh: +62...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Platform Sumber</label>
                            <input type="text" class="form-control" name="platform_sumber" value="{{ old('platform_sumber') }}" placeholder="Contoh: WhatsApp, Telegram, Email">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tautan Informasi (opsional)</label>
                            <input type="url" class="form-control" name="tautan_informasi" value="{{ old('tautan_informasi') }}" placeholder="https://...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Kronologi Singkat</label>
                            <textarea class="form-control" name="kronologi" rows="4" placeholder="Jelaskan detail kronologi penipuan yang diketahui">{{ old('kronologi') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Pelapor (opsional)</label>
                            <input type="text" class="form-control" name="pelapor_nama" value="{{ old('pelapor_nama') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Pelapor (opsional)</label>
                            <input type="email" class="form-control" name="pelapor_email" value="{{ old('pelapor_email') }}">
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

@push('scripts')
<script>
    (function () {
        var shouldOpenModal = {{ $errors->any() ? 'true' : 'false' }};
        if (!shouldOpenModal) {
            return;
        }

        var modalEl = document.getElementById('laporLokerModal');
        if (!modalEl || !window.bootstrap || !window.bootstrap.Modal) {
            return;
        }

        var modal = new window.bootstrap.Modal(modalEl);
        modal.show();
    })();
</script>
@endpush
