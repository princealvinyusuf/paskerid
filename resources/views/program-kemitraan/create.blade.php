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
    .pk-step .form-control::placeholder,
    .pk-step textarea::placeholder {
        color: #94a3b8;
        font-style: italic;
    }
    .pk-step-required {
        color: #dc2626;
        font-weight: 700;
        margin-left: 0.2rem;
    }
    .pk-submit[disabled] {
        opacity: 0.8;
        cursor: wait;
    }
    .pk-segmented {
        display: inline-flex;
        width: 100%;
        background: #eef2f7;
        border-radius: 999px;
        padding: 6px;
        gap: 6px;
        margin-bottom: 1rem;
    }
    .pk-seg-btn {
        flex: 1 1 50%;
        border: 0;
        background: transparent;
        border-radius: 999px;
        padding: 0.65rem 1rem;
        text-align: center;
        text-decoration: none;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .pk-seg-btn.active {
        background: #fff;
        color: #111827;
        box-shadow: 0 6px 16px rgba(2, 6, 23, 0.10);
    }
    .pk-eval-segmented {
        display: inline-flex;
        width: 100%;
        background: #eef2f7;
        border-radius: 999px;
        padding: 6px;
        gap: 6px;
        margin-bottom: 1rem;
    }
    .pk-eval-seg-btn {
        flex: 1 1 50%;
        border: 0;
        background: transparent;
        border-radius: 999px;
        padding: 0.65rem 1rem;
        text-align: center;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .pk-eval-seg-btn.active {
        background: #fff;
        color: #111827;
        box-shadow: 0 6px 16px rgba(2, 6, 23, 0.10);
    }
    .pk-eval-subpanel {
        display: none;
    }
    .pk-eval-subpanel.active {
        display: block;
    }
    .pk-eval-section-title {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }
    .pk-eval-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.75rem;
    }
    .pk-eval-table th,
    .pk-eval-table td {
        vertical-align: middle;
    }
    .pk-eval-table th {
        white-space: nowrap;
    }
    .pk-eval-table .pk-item-text {
        min-width: 420px;
        white-space: normal;
    }
    .pk-eval-subtitle {
        color: #475569;
        margin-bottom: 0.8rem;
    }
    .pk-eval-intro-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 999px;
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #1d4ed8;
        background: #dbeafe;
        border: 1px solid #bfdbfe;
        margin-bottom: 0.85rem;
    }
    .pk-eval-card {
        border: 1px solid #dbe3ee;
        border-radius: 14px;
        background: #fff;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }
    .pk-eval-card-head {
        width: 100%;
        border: 0;
        text-align: left;
        padding: 0.9rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(16, 185, 129, 0.08));
        color: #0f172a;
        font-weight: 700;
    }
    .pk-eval-card-head:focus {
        outline: 2px solid #93c5fd;
        outline-offset: -2px;
    }
    .pk-eval-card-sub {
        font-size: 0.82rem;
        color: #475569;
        font-weight: 500;
    }
    .pk-eval-card-body {
        padding: 0.95rem 1rem 1rem;
        background: #fff;
    }
    .pk-eval-profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.75rem;
    }
    .pk-eval-score-note {
        border-left: 4px solid #2563eb;
        background: #eff6ff;
        color: #1e3a8a;
        border-radius: 10px;
        padding: 0.7rem 0.85rem;
        font-size: 0.86rem;
        margin-bottom: 0.95rem;
    }
    .pk-sticky-submit {
        position: sticky;
        bottom: 0;
        background: #ffffffd9;
        backdrop-filter: blur(6px);
        border-top: 1px solid #e5e7eb;
        padding-top: 0.8rem;
        padding-bottom: 0.2rem;
    }
    .pk-wizard {
        border: 1px solid #dbe3ee;
        border-radius: 16px;
        background: #f8fbff;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
        padding: 1rem;
    }
    .pk-wizard-header {
        position: sticky;
        top: 0.4rem;
        z-index: 4;
        background: #f8fbff;
        border-radius: 12px;
        padding: 0.25rem 0.2rem 0.6rem;
        margin-bottom: 0.6rem;
    }
    .pk-wizard-progress-track {
        height: 10px;
        width: 100%;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }
    .pk-wizard-progress-fill {
        height: 100%;
        width: 0;
        border-radius: 999px;
        background: linear-gradient(90deg, #2563eb, #0ea5e9);
        transition: width 0.25s ease;
    }
    .pk-wizard-step {
        display: none;
        animation: pkFadeSlide 0.2s ease;
    }
    .pk-wizard-step.active {
        display: block;
    }
    .pk-wizard-question {
        border: 1px solid #d7e2f0;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        margin-bottom: 0.85rem;
        padding: 0.9rem;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }
    .pk-wizard-question:hover {
        transform: translateY(-1px);
        border-color: #bfdbfe;
        box-shadow: 0 12px 26px rgba(37, 99, 235, 0.12);
    }
    .pk-wizard-question-label {
        display: flex;
        gap: 0.7rem;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        color: #0f172a;
        font-weight: 600;
    }
    .pk-wizard-question-index {
        min-width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .pk-score-choice-row {
        display: grid;
        gap: 0.5rem;
        grid-template-columns: repeat(5, minmax(40px, 1fr));
    }
    .pk-score-choice {
        margin: 0;
    }
    .pk-score-choice input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .pk-score-choice > span {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        min-height: 42px;
        font-weight: 700;
        color: #1e293b;
        background: #f8fafc;
        transition: all 0.18s ease;
        cursor: pointer;
    }
    .pk-score-emoji {
        font-size: 2.05rem;
        line-height: 1;
    }
    .pk-score-number {
        font-size: 1.2rem;
        line-height: 1;
        font-weight: 800;
    }
    .pk-score-choice > span:hover {
        border-color: #60a5fa;
        background: #eff6ff;
    }
    .pk-score-choice input:checked + span {
        border-color: #2563eb;
        background: #2563eb;
        color: #fff;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.28);
    }
    .pk-score-choice input:focus-visible + span {
        outline: 2px solid #93c5fd;
        outline-offset: 2px;
    }
    .pk-wizard-nav {
        margin-top: 0.75rem;
        display: flex;
        justify-content: space-between;
        gap: 0.7rem;
    }
    .pk-wizard-nav .btn {
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }
    .pk-wizard-nav .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.14);
    }
    .pk-icon-badge {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e0ecff;
        color: #1d4ed8;
        flex: 0 0 auto;
    }
    .pk-icon-badge svg {
        width: 16px;
        height: 16px;
    }
    .pk-inline-icon {
        width: 16px;
        height: 16px;
        margin-right: 0.35rem;
        vertical-align: text-bottom;
        color: currentColor;
    }
    .pk-wizard-section-chip {
        display: inline-flex;
        align-items: center;
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        color: #1e40af;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        font-size: 0.78rem;
        font-weight: 600;
        margin-bottom: 0.7rem;
    }
    .pk-step-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.28rem;
        margin-left: 0.45rem;
        border-radius: 999px;
        padding: 0.16rem 0.46rem;
        font-size: 0.72rem;
        font-weight: 700;
        border: 1px solid #cbd5e1;
        color: #64748b;
        background: #f8fafc;
    }
    .pk-step-status-badge.is-complete {
        border-color: #86efac;
        color: #166534;
        background: #dcfce7;
    }
    @keyframes pkFadeSlide {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @media (max-width: 767.98px) {
        .pk-wizard {
            padding: 0.8rem;
        }
        .pk-score-choice-row {
            grid-template-columns: repeat(5, minmax(28px, 1fr));
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4 pk-page">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card pk-shell border-0">
                <div class="card-body p-4 p-md-5">
                    @php
                        $activeTab = $tab ?? 'pendaftaran';
                        $scoreOptions = ['1', '2', '3', '4', '5'];
                        $activeEvaluasiSubTab = old('evaluasi_inner_tab', 'peserta');
                        $selectedEvaluasiActivityId = (string) old('activity_master_id', '');
                        if (!in_array($activeEvaluasiSubTab, ['peserta', 'penyelenggara'], true)) {
                            $activeEvaluasiSubTab = 'peserta';
                        }
                    @endphp

                    <div class="pk-segmented" role="tablist" aria-label="Program Kemitraan tabs">
                        <a href="{{ route('program-kemitraan.create', ['tab' => 'pendaftaran']) }}" class="pk-seg-btn {{ $activeTab === 'pendaftaran' ? 'active' : '' }}" role="tab" aria-selected="{{ $activeTab === 'pendaftaran' ? 'true' : 'false' }}">
                            Pendaftaran Program Kemitraan
                        </a>
                        <a href="{{ route('program-kemitraan.create', ['tab' => 'evaluasi']) }}" class="pk-seg-btn {{ $activeTab === 'evaluasi' ? 'active' : '' }}" role="tab" aria-selected="{{ $activeTab === 'evaluasi' ? 'true' : 'false' }}">
                            Form Evaluasi
                        </a>
                    </div>

                    @if ($activeTab === 'pendaftaran')
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
                                <label class="form-label pk-step-title"><span class="pk-step-index">1</span>Nama Penanggung Jawab (PIC)<span class="pk-step-required">*</span></label>
                                <small class="d-block text-muted mb-2">(Masukkan nama lengkap pihak penanggung jawab)</small>
                                <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name') }}" placeholder="Contoh: Budi Santoso" required>
                            </div>

                            <div class="pk-step">
                                <label class="form-label pk-step-title"><span class="pk-step-index">2</span>Jabatan Penanggung Jawab<span class="pk-step-required">*</span></label>
                                <small class="d-block text-muted mb-2">(Tuliskan jabatan PIC pada instansi)</small>
                                <input type="text" name="pic_position" class="form-control" value="{{ old('pic_position') }}" placeholder="Contoh: Manajer HRD" required>
                            </div>

                            <div class="pk-step">
                                <label class="form-label pk-step-title"><span class="pk-step-index">3</span>Alamat Email Instansi<span class="pk-step-required">*</span></label>
                                <small class="d-block text-muted mb-2">(Pastikan email aktif untuk keperluan komunikasi)</small>
                                <input type="email" name="pic_email" class="form-control" value="{{ old('pic_email') }}" placeholder="Contoh: hrd@instansi.go.id" required>
                            </div>

                            <div class="pk-step">
                                <label class="form-label pk-step-title"><span class="pk-step-index">4</span>Nomor WhatsApp Aktif<span class="pk-step-required">*</span></label>
                                <small class="d-block text-muted mb-2">(Pastikan nomor WhatsApp aktif untuk keperluan komunikasi)</small>
                                <input type="text" name="pic_whatsapp" class="form-control" value="{{ old('pic_whatsapp') }}" placeholder="Contoh: 081234567890" required>
                            </div>

                            <div class="pk-step">
                                <label class="form-label pk-step-title"><span class="pk-step-index">5</span>Kategori/Sektor Instansi<span class="pk-step-required">*</span></label>
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

                            <div class="pk-step" id="mitraTypeWrapper" style="{{ old('institution_category') === 'Mitra Pembangunan (Perusahaan/Swasta/Job Portal)' ? '' : 'display:none;' }}">
                                <label class="form-label pk-step-title"><span class="pk-step-index">6</span>Jenis Mitra Pembangunan<span class="pk-step-required">*</span></label>
                                <small class="d-block text-muted mb-2">(Wajib dipilih jika kategori adalah Mitra Pembangunan)</small>
                                <select name="mitra_pembangunan_type" id="mitra_pembangunan_type" class="form-select">
                                    <option value="">-- Pilih Jenis Mitra --</option>
                                    @foreach ($mitraPembangunanTypes as $mitraType)
                                        <option value="{{ $mitraType }}" {{ old('mitra_pembangunan_type') === $mitraType ? 'selected' : '' }}>
                                            {{ $mitraType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pk-step">
                                <label class="form-label pk-step-title"><span class="pk-step-index">7</span>Nama Instansi/Lembaga (Kementerian/Lembaga/Pemerintah Daerah/Mitra Pembangunan)<span class="pk-step-required">*</span></label>
                                <small class="d-block text-muted mb-2">(Masukkan nama lengkap instansi/lembaga)</small>
                                <input type="text" name="instansi_lembaga_name" class="form-control" value="{{ old('instansi_lembaga_name') }}" placeholder="Contoh: Dinas Ketenagakerjaan Kota Bandung" required>
                            </div>

                            <div class="pk-step" id="businessSectorWrapper">
                                <label class="form-label pk-step-title"><span class="pk-step-index">8</span>Sektor Lapangan Usaha</label>
                                <small class="d-block text-muted mb-2">(Pilih sektor yang paling sesuai)</small>
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
                                <label class="form-label pk-step-title"><span class="pk-step-index">9</span>Alamat Instansi/Lembaga/Perusahaan/Mitra Pembangunan<span class="pk-step-required">*</span></label>
                                <small class="d-block text-muted mb-2">(Cantumkan alamat lengkap atau wilayah domisili kegiatan)</small>
                                <textarea name="institution_address" class="form-control" rows="3" placeholder="Contoh: Jl. Gatot Subroto No. 44, Jakarta Selatan" required>{{ old('institution_address') }}</textarea>
                            </div>

                            <div class="pk-step">
                                <label class="form-label pk-step-title"><span class="pk-step-index">10</span>Jenis Kegiatan yang Diajukan<span class="pk-step-required">*</span></label>
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
                                <label class="form-label pk-step-title"><span class="pk-step-index">11</span>Surat Permohonan Kemitraan Pusat Pasar Kerja<span class="pk-step-required">*</span></label>
                                <small class="d-block text-muted mb-2">(Unduh template surat permohonan, lalu unggah surat yang telah disesuaikan)</small>
                                <div class="mb-2">
                                    <a href="https://drive.google.com/drive/folders/1N82-qAOrGsttTc_Pkcdz2tc_5txoUrXr?usp=sharing" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener noreferrer">
                                        Download Template Surat Permohonan
                                    </a>
                                </div>
                                <input type="file" name="request_letter" class="form-control" accept=".pdf,.doc,.docx" required>
                                <small class="text-muted">Format file: PDF/DOC/DOCX (maksimal 5MB)</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 pk-submit" id="pkSubmitBtn">Kirim Pengajuan</button>
                        </form>
                    @else
                        @if (session('evaluasi_success'))
                            <div class="alert alert-success">{{ session('evaluasi_success') }}</div>
                        @endif

                        @if ($errors->evaluasi->any())
                            <div class="alert alert-danger">
                                <div class="fw-semibold mb-1">Mohon periksa kembali Form Evaluasi:</div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->evaluasi->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('program-kemitraan.evaluasi.store') }}" method="POST" id="programKemitraanEvaluasiForm" novalidate>
                            @csrf
                            <input type="hidden" name="evaluasi_inner_tab" id="evaluasiInnerTabInput" value="{{ $activeEvaluasiSubTab }}">
                            <div class="pk-eval-segmented" role="tablist" aria-label="Form Evaluasi section tabs">
                                <button type="button" class="pk-eval-seg-btn {{ $activeEvaluasiSubTab === 'peserta' ? 'active' : '' }}" data-eval-subtab="peserta" aria-selected="{{ $activeEvaluasiSubTab === 'peserta' ? 'true' : 'false' }}">
                                    Peserta
                                </button>
                                <button type="button" class="pk-eval-seg-btn {{ $activeEvaluasiSubTab === 'penyelenggara' ? 'active' : '' }}" data-eval-subtab="penyelenggara" aria-selected="{{ $activeEvaluasiSubTab === 'penyelenggara' ? 'true' : 'false' }}">
                                    Penyelenggara
                                </button>
                            </div>

                            <div class="pk-eval-subpanel {{ $activeEvaluasiSubTab === 'peserta' ? 'active' : '' }}" data-eval-panel="peserta">
                                @php
                                    $pesertaWizardInitialStep = (int) old('evaluasi_peserta_step', 1);
                                    if ($pesertaWizardInitialStep < 1) {
                                        $pesertaWizardInitialStep = 1;
                                    }
                                @endphp

                                <div class="pk-hero">
                                    <h3 class="mb-1">Form Evaluasi Program Kemitraan</h3>
                                    <p class="text-muted mb-2">Isi instrumen evaluasi kegiatan secara lengkap sesuai pelaksanaan.</p>
                                    <ul class="pk-hint-list small mb-0">
                                        <li>Format ini dirancang seperti survei berurutan agar pengisian lebih fokus.</li>
                                    </ul>
                                </div>

                                <div class="pk-wizard" id="pesertaWizard" data-initial-step="{{ $pesertaWizardInitialStep }}">
                                    <input type="hidden" name="evaluasi_peserta_step" id="evaluasiPesertaStepInput" value="{{ $pesertaWizardInitialStep }}">
                                    <div class="pk-wizard-header">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <div class="fw-semibold text-dark d-flex align-items-center gap-2">
                                                <span class="pk-icon-badge" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M4 18V6"></path>
                                                        <path d="M10 18V10"></path>
                                                        <path d="M16 18V13"></path>
                                                        <path d="M22 18V4"></path>
                                                    </svg>
                                                </span>
                                                <span id="pesertaWizardTitle">Langkah</span>
                                            </div>
                                            <div class="small text-muted" id="pesertaWizardCounter"></div>
                                        </div>
                                        <div class="pk-wizard-progress-track">
                                            <div class="pk-wizard-progress-fill" id="pesertaWizardProgress"></div>
                                        </div>
                                    </div>

                                    <div class="pk-wizard-step active" data-step-index="1" data-step-title="Profil Responden">
                                        <div class="pk-eval-card">
                                            <div class="pk-eval-card-head">
                                                <span>
                                                    <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <circle cx="12" cy="8" r="4"></circle>
                                                        <path d="M4 20c1.8-3.7 5-5.5 8-5.5s6.2 1.8 8 5.5"></path>
                                                    </svg>
                                                    Profil Responden
                                                </span>
                                                <span class="pk-eval-card-sub">
                                                    Isi data dasar terlebih dahulu
                                                    <span class="pk-step-status-badge" data-step-status>
                                                        <span aria-hidden="true">○</span> Belum
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="pk-eval-card-body">
                                                <div class="pk-eval-profile-grid mb-0">
                                                    <div>
                                                        <label class="form-label">Nama Kegiatan<span class="pk-step-required">*</span></label>
                                                        <select class="form-select" name="activity_master_id" data-shared-field="activity_master_id" required>
                                                            <option value="">-- Pilih nama kegiatan --</option>
                                                            @foreach (($evaluasiActivities ?? []) as $activityOption)
                                                                @php $activityId = (string) ($activityOption['id'] ?? ''); @endphp
                                                                <option value="{{ $activityId }}" {{ $selectedEvaluasiActivityId === $activityId ? 'selected' : '' }}>
                                                                    {{ $activityOption['activity_name'] ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div><label class="form-label">Nama<span class="pk-step-required">*</span></label><input type="text" class="form-control" name="respondent_name" value="{{ old('respondent_name') }}" required></div>
                                                    <div><label class="form-label">Instansi/organisasi<span class="pk-step-required">*</span></label><input type="text" class="form-control" name="respondent_organization" value="{{ old('respondent_organization') }}" required></div>
                                                    <div><label class="form-label">Jabatan/peran<span class="pk-step-required">*</span></label><input type="text" class="form-control" name="respondent_role" value="{{ old('respondent_role') }}" required></div>
                                                    <div><label class="form-label">Kontak/surel<span class="pk-step-required">*</span></label><input type="text" class="form-control" name="respondent_contact" value="{{ old('respondent_contact') }}" required></div>
                                                    <div>
                                                        <label class="form-label">Kategori responden<span class="pk-step-required">*</span></label>
                                                        <select class="form-select" name="respondent_category" required>
                                                            <option value="">-- Pilih kategori --</option>
                                                            @foreach ($evaluasiRespondentCategories as $category)
                                                                <option value="{{ $category }}" {{ old('respondent_category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Kategori lainnya</label>
                                                        <input type="text" class="form-control" name="respondent_category_other" value="{{ old('respondent_category_other') }}">
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Moda keikutsertaan<span class="pk-step-required">*</span></label>
                                                        <select class="form-select" name="participation_mode" required>
                                                            <option value="">-- Pilih moda --</option>
                                                            @foreach ($evaluasiParticipationModes as $mode)
                                                                <option value="{{ $mode }}" {{ old('participation_mode') === $mode ? 'selected' : '' }}>{{ $mode }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach (($evaluasiQuestionGroups['form_a'] ?? []) as $sectionKey => $sectionConfig)
                                        <div class="pk-wizard-step" data-step-index="{{ $loop->iteration + 1 }}" data-step-title="{{ $sectionConfig['title'] }}">
                                            <div class="pk-wizard-section-chip">
                                                <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 2l2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 16.3 6.4 19.2l1.1-6.2L3 8.6l6.2-.9L12 2z"></path>
                                                </svg>
                                                {{ $sectionConfig['title'] }}
                                                <span class="pk-step-status-badge" data-step-status>
                                                    <span aria-hidden="true">○</span> Belum
                                                </span>
                                            </div>
                                            <div class="pk-eval-score-note">
                                                Beri nilai pada setiap pernyataan. Pilih satu skor 1-5 yang paling sesuai.
                                            </div>
                                            @foreach (($sectionConfig['items'] ?? []) as $itemIndex => $itemText)
                                                @php
                                                    $itemNumber = $itemIndex + 1;
                                                    $oldAnswer = old("answers.form_a.$sectionKey.$itemNumber");
                                                    $questionName = "answers[form_a][$sectionKey][$itemNumber]";
                                                @endphp
                                                <div class="pk-wizard-question">
                                                    <div class="pk-wizard-question-label">
                                                        <span class="pk-wizard-question-index">{{ $itemNumber }}</span>
                                                        <span>{{ $itemText }}</span>
                                                    </div>
                                                    <div class="pk-score-choice-row">
                                                        @foreach ($scoreOptions as $scoreOption)
                                                            @php
                                                                $scoreEmoji = match ($scoreOption) {
                                                                    '1' => '😞',
                                                                    '2' => '🙁',
                                                                    '3' => '😐',
                                                                    '4' => '🙂',
                                                                    '5' => '😄',
                                                                    default => '🙂',
                                                                };
                                                            @endphp
                                                            <label class="pk-score-choice">
                                                                <input
                                                                    type="radio"
                                                                    name="{{ $questionName }}"
                                                                    value="{{ $scoreOption }}"
                                                                    {{ $oldAnswer === $scoreOption ? 'checked' : '' }}
                                                                    required
                                                                >
                                                                <span>
                                                                    <span class="pk-score-emoji" aria-hidden="true">{{ $scoreEmoji }}</span>
                                                                    <span class="pk-score-number">{{ $scoreOption }}</span>
                                                                </span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach

                                    <div class="pk-wizard-step" data-step-index="{{ count($evaluasiQuestionGroups['form_a'] ?? []) + 2 }}" data-step-title="Penilaian Akhir Peserta">
                                        <div class="pk-eval-card">
                                            <div class="pk-eval-card-head">
                                                <span>
                                                    <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M4 12h16"></path>
                                                        <path d="M4 6h12"></path>
                                                        <path d="M4 18h10"></path>
                                                        <path d="M19 6l1.5 1.5L22 6"></path>
                                                        <path d="M17 18l2 2 4-4"></path>
                                                    </svg>
                                                    Penilaian Akhir Peserta
                                                </span>
                                                <span class="pk-eval-card-sub">
                                                    Kepuasan dan tindak lanjut
                                                    <span class="pk-step-status-badge" data-step-status>
                                                        <span aria-hidden="true">○</span> Belum
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="pk-eval-card-body">
                                                <div class="pk-eval-grid">
                                                    <div>
                                                        <label class="form-label">Tingkat kepuasan umum<span class="pk-step-required">*</span></label>
                                                        <select class="form-select" name="overall_satisfaction" required>
                                                            <option value="">-- Pilih tingkat kepuasan --</option>
                                                            @foreach ($evaluasiSatisfactionOptions as $option)
                                                                <option value="{{ $option }}" {{ old('overall_satisfaction') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Bersedia dihubungi tindak lanjut<span class="pk-step-required">*</span></label>
                                                        <select class="form-select" name="willing_to_follow_up" required>
                                                            <option value="">-- Pilih jawaban --</option>
                                                            @foreach ($evaluasiWillingFollowupOptions as $option)
                                                                <option value="{{ $option }}" {{ old('willing_to_follow_up') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label">Kanal komunikasi disukai<span class="pk-step-required">*</span></label>
                                                        <div class="d-flex flex-wrap gap-3">
                                                            @foreach ($evaluasiCommunicationChannels as $channel)
                                                                @php $selectedChannels = old('preferred_channels', []); @endphp
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input me-1" type="checkbox" name="preferred_channels[]" value="{{ $channel }}" {{ in_array($channel, $selectedChannels, true) ? 'checked' : '' }}>
                                                                    {{ $channel }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div><label class="form-label">Hal terbaik dari kegiatan<span class="pk-step-required">*</span></label><textarea class="form-control" name="best_part" required>{{ old('best_part') }}</textarea></div>
                                                    <div><label class="form-label">Hal yang perlu diperbaiki<span class="pk-step-required">*</span></label><textarea class="form-control" name="needs_improvement" required>{{ old('needs_improvement') }}</textarea></div>
                                                    <div><label class="form-label">Topik/kegiatan lanjutan dibutuhkan<span class="pk-step-required">*</span></label><textarea class="form-control" name="needed_topics" required>{{ old('needed_topics') }}</textarea></div>
                                                    <div><label class="form-label">Saran lainnya</label><textarea class="form-control" name="additional_suggestions">{{ old('additional_suggestions') }}</textarea></div>
                                                    <div><label class="form-label">Catatan khusus tujuan/tema/sasaran</label><textarea class="form-control" name="form_a_special_notes">{{ old('form_a_special_notes') }}</textarea></div>
                                                    <div><label class="form-label">Materi paling bermanfaat</label><textarea class="form-control" name="form_a_most_useful_material">{{ old('form_a_most_useful_material') }}</textarea></div>
                                                    <div><label class="form-label">Materi perlu diperdalam</label><textarea class="form-control" name="form_a_material_needs">{{ old('form_a_material_needs') }}</textarea></div>
                                                    <div><label class="form-label">Catatan narasumber/panitia/fasilitas</label><textarea class="form-control" name="form_a_facility_notes">{{ old('form_a_facility_notes') }}</textarea></div>
                                                    <div><label class="form-label">Usulan bentuk kerja sama/tindak lanjut</label><textarea class="form-control" name="form_a_proposed_followup">{{ old('form_a_proposed_followup') }}</textarea></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pk-wizard-nav">
                                        <button type="button" class="btn btn-outline-secondary" id="pesertaWizardPrevBtn">
                                            <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M15 18l-6-6 6-6"></path>
                                            </svg>
                                            Sebelumnya
                                        </button>
                                        <button type="button" class="btn btn-primary" id="pesertaWizardNextBtn">
                                            Lanjut
                                            <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M9 18l6-6-6-6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pk-eval-subpanel {{ $activeEvaluasiSubTab === 'penyelenggara' ? 'active' : '' }}" data-eval-panel="penyelenggara">
                                @php
                                    $penyelenggaraWizardInitialStep = (int) old('evaluasi_penyelenggara_step', 1);
                                    if ($penyelenggaraWizardInitialStep < 1) {
                                        $penyelenggaraWizardInitialStep = 1;
                                    }
                                @endphp
                                <div class="pk-eval-intro-chip">Interaktif - Isi profil evaluator terlebih dahulu</div>
                                <div class="pk-wizard" id="penyelenggaraWizard" data-initial-step="{{ $penyelenggaraWizardInitialStep }}">
                                    <input type="hidden" name="evaluasi_penyelenggara_step" id="evaluasiPenyelenggaraStepInput" value="{{ $penyelenggaraWizardInitialStep }}">
                                    <div class="pk-wizard-header">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <div class="fw-semibold text-dark d-flex align-items-center gap-2">
                                                <span class="pk-icon-badge" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M4 18V6"></path>
                                                        <path d="M10 18V10"></path>
                                                        <path d="M16 18V13"></path>
                                                        <path d="M22 18V4"></path>
                                                    </svg>
                                                </span>
                                                <span id="penyelenggaraWizardTitle">Langkah</span>
                                            </div>
                                            <div class="small text-muted" id="penyelenggaraWizardCounter"></div>
                                        </div>
                                        <div class="pk-wizard-progress-track">
                                            <div class="pk-wizard-progress-fill" id="penyelenggaraWizardProgress"></div>
                                        </div>
                                    </div>

                                    <div class="pk-wizard-step active" data-step-index="1" data-step-title="Profil Evaluator">
                                        <div class="pk-eval-card">
                                            <div class="pk-eval-card-head">
                                                <span>
                                                    <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <circle cx="12" cy="8" r="4"></circle>
                                                        <path d="M4 20c1.8-3.7 5-5.5 8-5.5s6.2 1.8 8 5.5"></path>
                                                    </svg>
                                                    Profil Evaluator
                                                </span>
                                                <span class="pk-eval-card-sub">
                                                    Isi data evaluator terlebih dahulu
                                                    <span class="pk-step-status-badge" data-step-status>
                                                        <span aria-hidden="true">○</span> Belum
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="pk-eval-card-body">
                                                <div class="pk-eval-profile-grid mb-0">
                                                    <div>
                                                        <label class="form-label">Nama Kegiatan<span class="pk-step-required">*</span></label>
                                                        <select class="form-select" name="activity_master_id" data-shared-field="activity_master_id" required>
                                                            <option value="">-- Pilih nama kegiatan --</option>
                                                            @foreach (($evaluasiActivities ?? []) as $activityOption)
                                                                @php
                                                                    $activityId = (string) ($activityOption['id'] ?? '');
                                                                @endphp
                                                                <option value="{{ $activityId }}" {{ $selectedEvaluasiActivityId === $activityId ? 'selected' : '' }}>
                                                                    {{ $activityOption['activity_name'] ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div><label class="form-label">Nama evaluator<span class="pk-step-required">*</span></label><input type="text" class="form-control" name="evaluator_name" value="{{ old('evaluator_name') }}" required></div>
                                                    <div><label class="form-label">Jabatan/peran<span class="pk-step-required">*</span></label><input type="text" class="form-control" name="evaluator_position" value="{{ old('evaluator_position') }}" required></div>
                                                    <div><label class="form-label">Unit kerja<span class="pk-step-required">*</span></label><input type="text" class="form-control" name="evaluator_unit" value="{{ old('evaluator_unit') }}" required></div>
                                                    <div><label class="form-label">Tanggal evaluasi<span class="pk-step-required">*</span></label><input type="date" class="form-control" name="evaluation_date" value="{{ old('evaluation_date') }}" required></div>
                                                    <div>
                                                        <label class="form-label">Peran dalam kegiatan<span class="pk-step-required">*</span></label>
                                                        <select class="form-select" name="evaluator_role" required>
                                                            <option value="">-- Pilih peran --</option>
                                                            @foreach ($evaluasiEvaluatorRoles as $role)
                                                                <option value="{{ $role }}" {{ old('evaluator_role') === $role ? 'selected' : '' }}>{{ $role }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div><label class="form-label">Peran lainnya</label><input type="text" class="form-control" name="evaluator_role_other" value="{{ old('evaluator_role_other') }}"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach (($evaluasiQuestionGroups['form_b'] ?? []) as $sectionKey => $sectionConfig)
                                        <div class="pk-wizard-step" data-step-index="{{ $loop->iteration + 1 }}" data-step-title="{{ $sectionConfig['title'] }}">
                                            <div class="pk-wizard-section-chip">
                                                <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 2l2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 16.3 6.4 19.2l1.1-6.2L3 8.6l6.2-.9L12 2z"></path>
                                                </svg>
                                                {{ $sectionConfig['title'] }}
                                                <span class="pk-step-status-badge" data-step-status>
                                                    <span aria-hidden="true">○</span> Belum
                                                </span>
                                            </div>
                                            <div class="pk-eval-score-note">
                                                Beri nilai pada setiap pernyataan. Pilih satu skor 1-5 yang paling sesuai.
                                            </div>
                                            @foreach (($sectionConfig['items'] ?? []) as $itemIndex => $itemText)
                                                @php
                                                    $itemNumber = $itemIndex + 1;
                                                    $oldAnswer = old("answers.form_b.$sectionKey.$itemNumber");
                                                    $questionName = "answers[form_b][$sectionKey][$itemNumber]";
                                                @endphp
                                                <div class="pk-wizard-question">
                                                    <div class="pk-wizard-question-label">
                                                        <span class="pk-wizard-question-index">{{ $itemNumber }}</span>
                                                        <span>{{ $itemText }}</span>
                                                    </div>
                                                    <div class="pk-score-choice-row">
                                                        @foreach ($scoreOptions as $scoreOption)
                                                            @php
                                                                $scoreEmoji = match ($scoreOption) {
                                                                    '1' => '😞',
                                                                    '2' => '🙁',
                                                                    '3' => '😐',
                                                                    '4' => '🙂',
                                                                    '5' => '😄',
                                                                    default => '🙂',
                                                                };
                                                            @endphp
                                                            <label class="pk-score-choice">
                                                                <input
                                                                    type="radio"
                                                                    name="{{ $questionName }}"
                                                                    value="{{ $scoreOption }}"
                                                                    {{ $oldAnswer === $scoreOption ? 'checked' : '' }}
                                                                    required
                                                                >
                                                                <span>
                                                                    <span class="pk-score-emoji" aria-hidden="true">{{ $scoreEmoji }}</span>
                                                                    <span class="pk-score-number">{{ $scoreOption }}</span>
                                                                </span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach

                                    <div class="pk-wizard-step" data-step-index="{{ count($evaluasiQuestionGroups['form_b'] ?? []) + 2 }}" data-step-title="Catatan Internal Pelaksana">
                                        <div class="pk-eval-card">
                                            <div class="pk-eval-card-head">
                                                <span>
                                                    <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M4 12h16"></path>
                                                        <path d="M4 6h12"></path>
                                                        <path d="M4 18h10"></path>
                                                        <path d="M19 6l1.5 1.5L22 6"></path>
                                                        <path d="M17 18l2 2 4-4"></path>
                                                    </svg>
                                                    Catatan Internal Pelaksana
                                                </span>
                                                <span class="pk-eval-card-sub">
                                                    Ringkasan pelaksanaan dan rekomendasi
                                                    <span class="pk-step-status-badge" data-step-status>
                                                        <span aria-hidden="true">○</span> Belum
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="pk-eval-card-body">
                                                <div class="pk-eval-grid mb-0">
                                                    <div><label class="form-label">Kendala utama perencanaan</label><textarea class="form-control" name="form_b_planning_constraints">{{ old('form_b_planning_constraints') }}</textarea></div>
                                                    <div><label class="form-label">Insiden/deviasi jadwal & penanganan</label><textarea class="form-control" name="form_b_incident_notes">{{ old('form_b_incident_notes') }}</textarea></div>
                                                    <div><label class="form-label">Praktik baik dipertahankan</label><textarea class="form-control" name="form_b_good_practices">{{ old('form_b_good_practices') }}</textarea></div>
                                                    <div><label class="form-label">Akar masalah yang perlu diperbaiki</label><textarea class="form-control" name="form_b_root_issues">{{ old('form_b_root_issues') }}</textarea></div>
                                                    <div><label class="form-label">Rekomendasi prioritas</label><textarea class="form-control" name="form_b_priority_recommendations">{{ old('form_b_priority_recommendations') }}</textarea></div>
                                                    <div>
                                                        <label class="form-label">Password submit Penyelenggara<span class="pk-step-required">*</span></label>
                                                        <input type="password" class="form-control" name="penyelenggara_submit_password" autocomplete="off" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pk-wizard-nav">
                                        <button type="button" class="btn btn-outline-secondary" id="penyelenggaraWizardPrevBtn">
                                            <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M15 18l-6-6 6-6"></path>
                                            </svg>
                                            Sebelumnya
                                        </button>
                                        <button type="button" class="btn btn-primary" id="penyelenggaraWizardNextBtn">
                                            Lanjut
                                            <svg class="pk-inline-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M9 18l6-6-6-6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pk-sticky-submit">
                                <button type="submit" class="btn btn-primary w-100 pk-submit" id="pkEvaluasiSubmitBtn">Kirim Form Evaluasi</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var form = document.getElementById('programKemitraanForm');
        var submitBtn = document.getElementById('pkSubmitBtn');
        var evaluasiForm = document.getElementById('programKemitraanEvaluasiForm');
        var evaluasiSubmitBtn = document.getElementById('pkEvaluasiSubmitBtn');
        var evaluasiSubTabInput = document.getElementById('evaluasiInnerTabInput');
        var evaluasiSubTabButtons = document.querySelectorAll('[data-eval-subtab]');
        var evaluasiSubPanels = document.querySelectorAll('[data-eval-panel]');
        var sharedIdentitasFields = document.querySelectorAll('[data-shared-field]');
        var pesertaWizard = document.getElementById('pesertaWizard');
        var pesertaWizardSteps = pesertaWizard ? pesertaWizard.querySelectorAll('.pk-wizard-step') : [];
        var pesertaWizardPrevBtn = document.getElementById('pesertaWizardPrevBtn');
        var pesertaWizardNextBtn = document.getElementById('pesertaWizardNextBtn');
        var pesertaWizardCounter = document.getElementById('pesertaWizardCounter');
        var pesertaWizardTitle = document.getElementById('pesertaWizardTitle');
        var pesertaWizardProgress = document.getElementById('pesertaWizardProgress');
        var pesertaWizardStepInput = document.getElementById('evaluasiPesertaStepInput');
        var penyelenggaraWizard = document.getElementById('penyelenggaraWizard');
        var penyelenggaraWizardSteps = penyelenggaraWizard ? penyelenggaraWizard.querySelectorAll('.pk-wizard-step') : [];
        var penyelenggaraWizardPrevBtn = document.getElementById('penyelenggaraWizardPrevBtn');
        var penyelenggaraWizardNextBtn = document.getElementById('penyelenggaraWizardNextBtn');
        var penyelenggaraWizardCounter = document.getElementById('penyelenggaraWizardCounter');
        var penyelenggaraWizardTitle = document.getElementById('penyelenggaraWizardTitle');
        var penyelenggaraWizardProgress = document.getElementById('penyelenggaraWizardProgress');
        var penyelenggaraWizardStepInput = document.getElementById('evaluasiPenyelenggaraStepInput');
        var evaluasiErrorKeys = @json($errors->evaluasi->keys());
        var categorySelect = document.getElementById('institution_category');
        var mitraTypeWrapper = document.getElementById('mitraTypeWrapper');
        var mitraTypeSelect = document.getElementById('mitra_pembangunan_type');
        var mitraCategory = 'Mitra Pembangunan (Perusahaan/Swasta/Job Portal)';
        var activePesertaStep = 1;
        var activePenyelenggaraStep = 1;

        function syncMitraTypeVisibility() {
            if (!categorySelect || !mitraTypeWrapper || !mitraTypeSelect) {
                return;
            }
            var isMitra = categorySelect.value === mitraCategory;
            mitraTypeWrapper.style.display = isMitra ? '' : 'none';
            mitraTypeSelect.required = isMitra;
            if (!isMitra) {
                mitraTypeSelect.value = '';
            }
            renumberVisibleSteps();
        }

        function renumberVisibleSteps() {
            var visibleSteps = Array.prototype.filter.call(
                document.querySelectorAll('.pk-step'),
                function (step) {
                    return step.style.display !== 'none';
                }
            );

            visibleSteps.forEach(function (step, idx) {
                var badge = step.querySelector('.pk-step-index');
                if (badge) {
                    badge.textContent = String(idx + 1);
                }
            });
        }

        if (categorySelect) {
            categorySelect.addEventListener('change', syncMitraTypeVisibility);
            syncMitraTypeVisibility();
        }

        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Mengirim pengajuan...';
            });
        }

        if (evaluasiForm && evaluasiSubmitBtn) {
            evaluasiForm.addEventListener('submit', function () {
                if (pesertaWizardStepInput) {
                    pesertaWizardStepInput.value = String(activePesertaStep);
                }
                if (penyelenggaraWizardStepInput) {
                    penyelenggaraWizardStepInput.value = String(activePenyelenggaraStep);
                }
                evaluasiSubmitBtn.disabled = true;
                evaluasiSubmitBtn.innerHTML = 'Mengirim form evaluasi...';
            });
        }

        function setEvaluasiSubTab(tabName) {
            evaluasiSubTabButtons.forEach(function (button) {
                var isActive = button.getAttribute('data-eval-subtab') === tabName;
                button.classList.toggle('active', isActive);
                button.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
            evaluasiSubPanels.forEach(function (panel) {
                var isActive = panel.getAttribute('data-eval-panel') === tabName;
                panel.classList.toggle('active', isActive);
            });
            if (evaluasiSubTabInput) {
                evaluasiSubTabInput.value = tabName;
            }
            evaluasiSubPanels.forEach(function (panel) {
                var isActivePanel = panel.getAttribute('data-eval-panel') === tabName;
                panel.querySelectorAll('input, select, textarea').forEach(function (field) {
                    field.disabled = !isActivePanel;
                });
            });
        }

        function syncSharedField(sourceField) {
            var key = sourceField.getAttribute('data-shared-field');
            if (!key) {
                return;
            }
            var value = sourceField.value;
            document.querySelectorAll('[data-shared-field="' + key + '"]').forEach(function (targetField) {
                if (targetField === sourceField) {
                    return;
                }
                targetField.value = value;
            });
        }

        function normalizeFieldName(fieldName) {
            if (!fieldName) {
                return '';
            }
            return fieldName
                .replace(/\[\]/g, '')
                .replace(/\]/g, '')
                .replace(/\[/g, '.')
                .replace(/^\./, '');
        }

        function isErrorMatch(fieldKey, errorKey) {
            if (!fieldKey || !errorKey) {
                return false;
            }
            return (
                fieldKey === errorKey ||
                fieldKey.indexOf(errorKey + '.') === 0 ||
                errorKey.indexOf(fieldKey + '.') === 0
            );
        }

        function getStepFieldNames(stepElement) {
            var names = [];
            stepElement.querySelectorAll('input[name], select[name], textarea[name]').forEach(function (field) {
                names.push(normalizeFieldName(field.name));
            });
            return names;
        }

        function getFirstStepFromErrors(wizardSteps) {
            if (!Array.isArray(wizardSteps) || wizardSteps.length === 0 || !Array.isArray(evaluasiErrorKeys) || evaluasiErrorKeys.length === 0) {
                return null;
            }
            for (var i = 0; i < wizardSteps.length; i++) {
                var names = getStepFieldNames(wizardSteps[i]);
                for (var e = 0; e < evaluasiErrorKeys.length; e++) {
                    var errorKey = evaluasiErrorKeys[e];
                    for (var n = 0; n < names.length; n++) {
                        if (isErrorMatch(names[n], errorKey)) {
                            return i + 1;
                        }
                    }
                }
            }
            return null;
        }

        function refreshWizardHeader(stepNumber, totalSteps, stepTitle) {
            var pct = Math.round((stepNumber / totalSteps) * 100);
            if (pesertaWizardCounter) {
                pesertaWizardCounter.textContent = 'Langkah ' + stepNumber + ' dari ' + totalSteps + ' (' + pct + '%)';
            }
            if (pesertaWizardTitle) {
                pesertaWizardTitle.textContent = stepTitle || 'Langkah';
            }
            if (pesertaWizardProgress) {
                pesertaWizardProgress.style.width = pct + '%';
            }
            if (pesertaWizardPrevBtn) {
                pesertaWizardPrevBtn.disabled = stepNumber === 1;
            }
            if (pesertaWizardNextBtn) {
                var isLast = stepNumber === totalSteps;
                pesertaWizardNextBtn.style.visibility = isLast ? 'hidden' : 'visible';
                pesertaWizardNextBtn.disabled = isLast;
            }
        }

        function isStepComplete(stepElement) {
            var requiredGroups = {};
            var fields = stepElement.querySelectorAll('input, select, textarea');

            for (var i = 0; i < fields.length; i++) {
                var field = fields[i];
                if (field.disabled || !field.hasAttribute('required')) {
                    continue;
                }

                var type = (field.getAttribute('type') || '').toLowerCase();
                if (type === 'radio') {
                    if (requiredGroups[field.name]) {
                        continue;
                    }
                    requiredGroups[field.name] = true;
                    if (!stepElement.querySelector('input[type="radio"][name="' + field.name + '"]:checked')) {
                        return false;
                    }
                    continue;
                }

                if (type === 'checkbox') {
                    if (requiredGroups[field.name]) {
                        continue;
                    }
                    requiredGroups[field.name] = true;
                    if (!stepElement.querySelector('input[type="checkbox"][name="' + field.name + '"]:checked')) {
                        return false;
                    }
                    continue;
                }

                if (!field.value || !field.value.trim()) {
                    return false;
                }
            }

            return true;
        }

        function refreshStepCompletionBadges() {
            if (!pesertaWizard || pesertaWizardSteps.length === 0) {
                return;
            }
            pesertaWizardSteps.forEach(function (step) {
                var badge = step.querySelector('[data-step-status]');
                if (!badge) {
                    return;
                }
                var done = isStepComplete(step);
                badge.classList.toggle('is-complete', done);
                badge.innerHTML = done ? '<span aria-hidden="true">\u2713</span> Selesai' : '<span aria-hidden="true">\u25cb</span> Belum';
            });
        }

        function validateStep(stepElement) {
            var firstInvalid = null;
            var checkedGroups = {};
            var fields = stepElement.querySelectorAll('input, select, textarea');

            function hasCheckedInGroup(groupName, typeName) {
                var groupFields = stepElement.querySelectorAll('input[type="' + typeName + '"]');
                for (var i = 0; i < groupFields.length; i++) {
                    if (groupFields[i].name === groupName && groupFields[i].checked) {
                        return true;
                    }
                }
                return false;
            }

            fields.forEach(function (field) {
                if (field.disabled || !field.hasAttribute('required')) {
                    return;
                }

                var type = (field.getAttribute('type') || '').toLowerCase();
                if (type === 'radio') {
                    if (checkedGroups[field.name]) {
                        return;
                    }
                    checkedGroups[field.name] = true;
                    var isChecked = hasCheckedInGroup(field.name, 'radio');
                    if (!isChecked && !firstInvalid) {
                        firstInvalid = field;
                    }
                    return;
                }

                if (type === 'checkbox') {
                    var isTicked = hasCheckedInGroup(field.name, 'checkbox');
                    if (!isTicked && !firstInvalid) {
                        firstInvalid = field;
                    }
                    return;
                }

                if (!field.value || !field.value.trim()) {
                    if (!firstInvalid) {
                        firstInvalid = field;
                    }
                }
            });

            if (firstInvalid) {
                firstInvalid.focus();
                return false;
            }
            return true;
        }

        function setPesertaStep(nextStep) {
            if (!pesertaWizard || pesertaWizardSteps.length === 0) {
                return;
            }
            var totalSteps = pesertaWizardSteps.length;
            var safeStep = Math.min(Math.max(nextStep, 1), totalSteps);
            activePesertaStep = safeStep;
            pesertaWizardSteps.forEach(function (step, idx) {
                var isActive = idx + 1 === safeStep;
                step.classList.toggle('active', isActive);
            });
            var activeStepElement = pesertaWizardSteps[safeStep - 1];
            refreshWizardHeader(
                safeStep,
                totalSteps,
                activeStepElement ? activeStepElement.getAttribute('data-step-title') : 'Langkah'
            );
            if (pesertaWizardStepInput) {
                pesertaWizardStepInput.value = String(safeStep);
            }
            if (evaluasiSubTabInput) {
                evaluasiSubTabInput.value = 'peserta';
            }
        }

        function initPesertaWizard() {
            if (!pesertaWizard || pesertaWizardSteps.length === 0) {
                return;
            }
            var totalSteps = pesertaWizardSteps.length;
            var initialTab = evaluasiSubTabInput && evaluasiSubTabInput.value ? evaluasiSubTabInput.value : 'peserta';
            var initialStep = parseInt(pesertaWizard.getAttribute('data-initial-step') || '1', 10);
            if (Number.isNaN(initialStep)) {
                initialStep = 1;
            }
            var errorStep = getFirstStepFromErrors(Array.prototype.slice.call(pesertaWizardSteps));
            if (errorStep !== null && initialTab === 'peserta') {
                initialStep = errorStep;
                setEvaluasiSubTab('peserta');
            }
            setPesertaStep(Math.min(Math.max(initialStep, 1), totalSteps));
            refreshStepCompletionBadges();

            if (pesertaWizardPrevBtn) {
                pesertaWizardPrevBtn.addEventListener('click', function () {
                    setPesertaStep(activePesertaStep - 1);
                });
            }
            if (pesertaWizardNextBtn) {
                pesertaWizardNextBtn.addEventListener('click', function () {
                    var currentStep = pesertaWizardSteps[activePesertaStep - 1];
                    if (!currentStep) {
                        return;
                    }
                    if (!validateStep(currentStep)) {
                        return;
                    }
                    refreshStepCompletionBadges();
                    setPesertaStep(activePesertaStep + 1);
                });
            }

            pesertaWizard.addEventListener('input', refreshStepCompletionBadges);
            pesertaWizard.addEventListener('change', refreshStepCompletionBadges);
        }

        function refreshPenyelenggaraWizardHeader(stepNumber, totalSteps, stepTitle) {
            var pct = Math.round((stepNumber / totalSteps) * 100);
            if (penyelenggaraWizardCounter) {
                penyelenggaraWizardCounter.textContent = 'Langkah ' + stepNumber + ' dari ' + totalSteps + ' (' + pct + '%)';
            }
            if (penyelenggaraWizardTitle) {
                penyelenggaraWizardTitle.textContent = stepTitle || 'Langkah';
            }
            if (penyelenggaraWizardProgress) {
                penyelenggaraWizardProgress.style.width = pct + '%';
            }
            if (penyelenggaraWizardPrevBtn) {
                penyelenggaraWizardPrevBtn.disabled = stepNumber === 1;
            }
            if (penyelenggaraWizardNextBtn) {
                var isLast = stepNumber === totalSteps;
                penyelenggaraWizardNextBtn.style.visibility = isLast ? 'hidden' : 'visible';
                penyelenggaraWizardNextBtn.disabled = isLast;
            }
        }

        function refreshPenyelenggaraStepCompletionBadges() {
            if (!penyelenggaraWizard || penyelenggaraWizardSteps.length === 0) {
                return;
            }
            penyelenggaraWizardSteps.forEach(function (step) {
                var badge = step.querySelector('[data-step-status]');
                if (!badge) {
                    return;
                }
                var done = isStepComplete(step);
                badge.classList.toggle('is-complete', done);
                badge.innerHTML = done ? '<span aria-hidden="true">\u2713</span> Selesai' : '<span aria-hidden="true">\u25cb</span> Belum';
            });
        }

        function setPenyelenggaraStep(nextStep) {
            if (!penyelenggaraWizard || penyelenggaraWizardSteps.length === 0) {
                return;
            }
            var totalSteps = penyelenggaraWizardSteps.length;
            var safeStep = Math.min(Math.max(nextStep, 1), totalSteps);
            activePenyelenggaraStep = safeStep;
            penyelenggaraWizardSteps.forEach(function (step, idx) {
                var isActive = idx + 1 === safeStep;
                step.classList.toggle('active', isActive);
            });
            var activeStepElement = penyelenggaraWizardSteps[safeStep - 1];
            refreshPenyelenggaraWizardHeader(
                safeStep,
                totalSteps,
                activeStepElement ? activeStepElement.getAttribute('data-step-title') : 'Langkah'
            );
            if (penyelenggaraWizardStepInput) {
                penyelenggaraWizardStepInput.value = String(safeStep);
            }
            if (evaluasiSubTabInput) {
                evaluasiSubTabInput.value = 'penyelenggara';
            }
        }

        function initPenyelenggaraWizard() {
            if (!penyelenggaraWizard || penyelenggaraWizardSteps.length === 0) {
                return;
            }
            var totalSteps = penyelenggaraWizardSteps.length;
            var initialTab = evaluasiSubTabInput && evaluasiSubTabInput.value ? evaluasiSubTabInput.value : 'peserta';
            var initialStep = parseInt(penyelenggaraWizard.getAttribute('data-initial-step') || '1', 10);
            if (Number.isNaN(initialStep)) {
                initialStep = 1;
            }
            var errorStep = getFirstStepFromErrors(Array.prototype.slice.call(penyelenggaraWizardSteps));
            if (errorStep !== null && initialTab === 'penyelenggara') {
                initialStep = errorStep;
                setEvaluasiSubTab('penyelenggara');
            }
            setPenyelenggaraStep(Math.min(Math.max(initialStep, 1), totalSteps));
            refreshPenyelenggaraStepCompletionBadges();

            if (penyelenggaraWizardPrevBtn) {
                penyelenggaraWizardPrevBtn.addEventListener('click', function () {
                    setPenyelenggaraStep(activePenyelenggaraStep - 1);
                });
            }
            if (penyelenggaraWizardNextBtn) {
                penyelenggaraWizardNextBtn.addEventListener('click', function () {
                    var currentStep = penyelenggaraWizardSteps[activePenyelenggaraStep - 1];
                    if (!currentStep) {
                        return;
                    }
                    if (!validateStep(currentStep)) {
                        return;
                    }
                    refreshPenyelenggaraStepCompletionBadges();
                    setPenyelenggaraStep(activePenyelenggaraStep + 1);
                });
            }

            penyelenggaraWizard.addEventListener('input', refreshPenyelenggaraStepCompletionBadges);
            penyelenggaraWizard.addEventListener('change', refreshPenyelenggaraStepCompletionBadges);
        }

        if (evaluasiSubTabButtons.length > 0 && evaluasiSubPanels.length > 0) {
            evaluasiSubTabButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var tabName = button.getAttribute('data-eval-subtab');
                    if (tabName) {
                        setEvaluasiSubTab(tabName);
                    }
                });
            });

            var initialTab = evaluasiSubTabInput && evaluasiSubTabInput.value ? evaluasiSubTabInput.value : 'peserta';
            setEvaluasiSubTab(initialTab);
        }

        initPesertaWizard();
        initPenyelenggaraWizard();

        if (sharedIdentitasFields.length > 0) {
            sharedIdentitasFields.forEach(function (field) {
                field.addEventListener('input', function () {
                    syncSharedField(field);
                });
                field.addEventListener('change', function () {
                    syncSharedField(field);
                });
            });
        }
    })();
</script>
@endsection
