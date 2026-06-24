@extends('layouts.app')

@section('head')
<style>
    .pk-page {
        background:
            radial-gradient(1200px 520px at -8% -10%, rgba(16, 185, 129, 0.12), transparent 55%),
            radial-gradient(900px 480px at 110% 0%, rgba(37, 99, 235, 0.12), transparent 58%),
            linear-gradient(180deg, #f8fbff 0%, #eef7f1 100%);
    }
    .pk-shell {
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 20px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(6px);
    }
    .pk-hero {
        padding: 1.2rem 1.3rem;
        border: 1px solid rgba(37, 99, 235, 0.16);
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(16, 185, 129, 0.08));
        margin-bottom: 1rem;
    }
    .pk-step {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 0.95rem 1rem;
        margin-bottom: 0.95rem;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }
    .pk-step:hover {
        border-color: rgba(37, 99, 235, 0.35);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        transform: translateY(-1px);
    }
    .pk-step-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    .pk-step-index {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.84rem;
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #059669);
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.35);
    }
    .pk-step small {
        color: #6b7280;
        margin-bottom: 0.5rem;
    }
    .pk-step .form-control,
    .pk-step .form-select,
    .pk-step textarea {
        border-radius: 12px;
        border-color: #d1d5db;
        min-height: 46px;
    }
    .pk-step textarea {
        min-height: 110px;
    }
    .pk-step .form-control:focus,
    .pk-step .form-select:focus,
    .pk-step textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.14);
    }
    .pk-submit {
        border-radius: 12px;
        min-height: 48px;
        font-weight: 700;
    }
    .pk-hint-list {
        margin: 0;
        padding-left: 1.1rem;
        color: #4b5563;
    }
</style>
@endsection

@section('content')
<div class="container py-4 pk-page">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card pk-shell border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="pk-hero">
                        <h3 class="mb-1">Program Kemitraan</h3>
                        <p class="text-muted mb-2">Form pendaftaran kemitraan Pusat Pasar Kerja.</p>
                        <ul class="pk-hint-list small">
                            <li>Isi data dengan lengkap dan benar agar proses verifikasi lebih cepat.</li>
                            <li>Pastikan email dan WhatsApp aktif untuk kebutuhan komunikasi tim.</li>
                        </ul>
                    </div>

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

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">1</span>Nama Penanggung Jawab (PIC)</label>
                            <small class="d-block text-muted mb-2">(Masukkan nama lengkap pihak penanggung jawab)</small>
                            <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name') }}" required>
                        </div>

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">2</span>Jabatan Penanggung Jawab</label>
                            <small class="d-block text-muted mb-2">(Tuliskan jabatan PIC pada instansi)</small>
                            <input type="text" name="pic_position" class="form-control" value="{{ old('pic_position') }}" required>
                        </div>

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">3</span>Alamat Email Instansi</label>
                            <small class="d-block text-muted mb-2">(Pastikan email aktif untuk keperluan komunikasi)</small>
                            <input type="email" name="pic_email" class="form-control" value="{{ old('pic_email') }}" required>
                        </div>

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">4</span>Nomor WhatsApp Aktif</label>
                            <small class="d-block text-muted mb-2">(Pastikan nomor WhatsApp aktif untuk keperluan komunikasi)</small>
                            <input type="text" name="pic_whatsapp" class="form-control" value="{{ old('pic_whatsapp') }}" required>
                        </div>

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">5</span>Kategori/Sektor Instansi</label>
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

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">6</span>Nama Instansi/Lembaga (Kementerian/Lembaga/Pemerintah Daerah)</label>
                            <small class="d-block text-muted mb-2">(Masukkan nama lengkap instansi/lembaga)</small>
                            <input type="text" name="instansi_lembaga_name" class="form-control" value="{{ old('instansi_lembaga_name') }}" required>
                        </div>

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">7</span>Nama Instansi</label>
                            <small class="d-block text-muted mb-2">(Masukkan nama lengkap instansi)</small>
                            <input type="text" name="institution_name" class="form-control" value="{{ old('institution_name') }}" required>
                        </div>

                        <div class="pk-step" id="businessSectorWrapper" style="{{ old('institution_category') === 'Mitra Pembangunan (Perusahaan/Swasta/Job Portal)' ? '' : 'display:none;' }}">
                            <label class="form-label pk-step-title"><span class="pk-step-index">8</span>Sektor Lapangan Usaha</label>
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

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">9</span>Alamat Instansi/Lembaga/Perusahaan</label>
                            <small class="d-block text-muted mb-2">(Cantumkan alamat lengkap atau wilayah domisili kegiatan)</small>
                            <textarea name="institution_address" class="form-control" rows="3" required>{{ old('institution_address') }}</textarea>
                        </div>

                        <div class="pk-step">
                            <label class="form-label pk-step-title"><span class="pk-step-index">10</span>Jenis Kegiatan yang Diajukan</label>
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

                        <div class="pk-step mb-4">
                            <label class="form-label pk-step-title"><span class="pk-step-index">11</span>Surat Permohonan Kemitraan Pusat Pasar Kerja</label>
                            <small class="d-block text-muted mb-2">(Unduh template surat permohonan, lalu unggah surat yang telah disesuaikan)</small>
                            <div class="mb-2">
                                <a href="https://drive.google.com/drive/folders/1N82-qAOrGsttTc_Pkcdz2tc_5txoUrXr?usp=sharing" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener noreferrer">
                                    Download Template Surat Permohonan
                                </a>
                            </div>
                            <input type="file" name="request_letter" class="form-control" accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Format file: PDF/DOC/DOCX (maksimal 5MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 pk-submit">Kirim Pengajuan</button>
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
