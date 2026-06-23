@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h3 class="mb-1">Program Kemitraan</h3>
                    <p class="text-muted mb-4">Form pendaftaran kemitraan Pusat Pasar Kerja.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-semibold mb-1">Mohon periksa kembali data Anda:</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('program-kemitraan.store') }}" method="POST" enctype="multipart/form-data" id="programKemitraanForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">1. Nama Penanggung Jawab (PIC)</label>
                            <small class="d-block text-muted mb-2">(Masukkan nama lengkap pihak penanggung jawab)</small>
                            <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">2. Jabatan Penanggung Jawab</label>
                            <small class="d-block text-muted mb-2">(Tuliskan jabatan PIC pada instansi)</small>
                            <input type="text" name="pic_position" class="form-control" value="{{ old('pic_position') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">3. Alamat Email Instansi</label>
                            <small class="d-block text-muted mb-2">(Pastikan email aktif untuk keperluan komunikasi)</small>
                            <input type="email" name="pic_email" class="form-control" value="{{ old('pic_email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">4. Nomor WhatsApp Aktif</label>
                            <small class="d-block text-muted mb-2">(Pastikan nomor WhatsApp aktif untuk keperluan komunikasi)</small>
                            <input type="text" name="pic_whatsapp" class="form-control" value="{{ old('pic_whatsapp') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">5. Kategori/Sektor Instansi</label>
                            <small class="d-block text-muted mb-2">(Pilih salah satu yang sesuai)</small>
                            <select name="institution_category" id="institution_category" class="form-select" required>
                                <option value="">-- Pilih Kategori/Sektor Instansi --</option>
                                @foreach ($institutionCategories as $category)
                                    <option value="{{ $category }}" {{ old('institution_category') === $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">6. Nama Instansi/Lembaga (Kementerian/Lembaga/Pemerintah Daerah)</label>
                            <small class="d-block text-muted mb-2">(Masukkan nama lengkap instansi/lembaga)</small>
                            <input type="text" name="instansi_lembaga_name" class="form-control" value="{{ old('instansi_lembaga_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">7. Nama Instansi</label>
                            <small class="d-block text-muted mb-2">(Masukkan nama lengkap instansi)</small>
                            <input type="text" name="institution_name" class="form-control" value="{{ old('institution_name') }}" required>
                        </div>

                        <div class="mb-3" id="businessSectorWrapper" style="{{ old('institution_category') === 'Mitra Pembangunan (Perusahaan/Swasta/Job Portal)' ? '' : 'display:none;' }}">
                            <label class="form-label fw-semibold">8. Sektor Lapangan Usaha</label>
                            <small class="d-block text-muted mb-2">(Muncul untuk Mitra Pembangunan)</small>
                            <select name="business_sector" class="form-select" id="business_sector">
                                <option value="">-- Pilih Sektor Lapangan Usaha --</option>
                                @foreach ($businessSectors as $sector)
                                    <option value="{{ $sector }}" {{ old('business_sector') === $sector ? 'selected' : '' }}>
                                        {{ $sector }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">9. Alamat Instansi/Lembaga/Perusahaan</label>
                            <small class="d-block text-muted mb-2">(Cantumkan alamat lengkap atau wilayah domisili kegiatan)</small>
                            <textarea name="institution_address" class="form-control" rows="3" required>{{ old('institution_address') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">10. Jenis Kegiatan yang Diajukan</label>
                            <small class="d-block text-muted mb-2">(Pilih salah satu jenis kegiatan yang ingin diajukan)</small>
                            <select name="proposed_activity_type" class="form-select" required>
                                <option value="">-- Pilih Jenis Kegiatan --</option>
                                @foreach ($activityTypes as $type)
                                    <option value="{{ $type }}" {{ old('proposed_activity_type') === $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">11. Surat Permohonan Kemitraan Pusat Pasar Kerja</label>
                            <small class="d-block text-muted mb-2">(Unduh template surat permohonan, lalu unggah surat yang telah disesuaikan)</small>
                            <div class="mb-2">
                                <a href="https://drive.google.com/drive/folders/1wZm4htxHuuTynLJXQ__-XiIW_omKrr7S?usp=sharing" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener noreferrer">
                                    Download Template Surat Permohonan
                                </a>
                            </div>
                            <input type="file" name="request_letter" class="form-control" accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Format file: PDF/DOC/DOCX (maksimal 5MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Kirim Pengajuan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var categorySelect = document.getElementById('institution_category');
        var businessSectorWrapper = document.getElementById('businessSectorWrapper');
        var businessSectorInput = document.getElementById('business_sector');
        var mitraPembangunan = 'Mitra Pembangunan (Perusahaan/Swasta/Job Portal)';

        if (!categorySelect || !businessSectorWrapper || !businessSectorInput) {
            return;
        }

        function toggleBusinessSector() {
            var shouldShow = categorySelect.value === mitraPembangunan;
            businessSectorWrapper.style.display = shouldShow ? '' : 'none';
            if (!shouldShow) {
                businessSectorInput.value = '';
            }
        }

        categorySelect.addEventListener('change', toggleBusinessSector);
        toggleBusinessSector();
    })();
</script>
@endsection
