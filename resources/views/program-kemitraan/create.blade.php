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
    .pk-sticky-submit {
        position: sticky;
        bottom: 0;
        background: #ffffffd9;
        backdrop-filter: blur(6px);
        border-top: 1px solid #e5e7eb;
        padding-top: 0.8rem;
        padding-bottom: 0.2rem;
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

                    @php
                        $activeTab = $tab ?? 'pendaftaran';
                        $scoreOptions = ['1', '2', '3', '4', '5', 'NA'];
                    @endphp

                    <div class="pk-segmented" role="tablist" aria-label="Program Kemitraan tabs">
                        <a href="{{ route('program-kemitraan.create', ['tab' => 'pendaftaran']) }}" class="pk-seg-btn {{ $activeTab === 'pendaftaran' ? 'active' : '' }}" role="tab" aria-selected="{{ $activeTab === 'pendaftaran' ? 'true' : 'false' }}">
                            Pendaftaran Program Kemitraan
                        </a>
                        <a href="{{ route('program-kemitraan.create', ['tab' => 'evaluasi']) }}" class="pk-seg-btn {{ $activeTab === 'evaluasi' ? 'active' : '' }}" role="tab" aria-selected="{{ $activeTab === 'evaluasi' ? 'true' : 'false' }}">
                            Form Evaluasi
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($activeTab === 'pendaftaran')
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

                        <form action="{{ route('program-kemitraan.evaluasi.store') }}" method="POST" id="programKemitraanEvaluasiForm">
                            @csrf
                            <div class="pk-step">
                                <div class="pk-eval-section-title">I. Identitas Kegiatan</div>
                                <div class="pk-eval-grid">
                                    <div>
                                        <label class="form-label">Nama kegiatan<span class="pk-step-required">*</span></label>
                                        <input type="text" class="form-control" name="activity_name" value="{{ old('activity_name') }}" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Tema/topik<span class="pk-step-required">*</span></label>
                                        <input type="text" class="form-control" name="activity_theme" value="{{ old('activity_theme') }}" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Hari/tanggal<span class="pk-step-required">*</span></label>
                                        <input type="date" class="form-control" name="activity_date" value="{{ old('activity_date') }}" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Waktu mulai<span class="pk-step-required">*</span></label>
                                        <input type="time" class="form-control" name="activity_start_time" value="{{ old('activity_start_time') }}" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Waktu selesai<span class="pk-step-required">*</span></label>
                                        <input type="time" class="form-control" name="activity_end_time" value="{{ old('activity_end_time') }}" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Zona waktu<span class="pk-step-required">*</span></label>
                                        <select class="form-select" name="activity_timezone" required>
                                            @foreach (['WIB', 'WITA', 'WIT'] as $zone)
                                                <option value="{{ $zone }}" {{ old('activity_timezone', 'WIB') === $zone ? 'selected' : '' }}>{{ $zone }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Tempat/media<span class="pk-step-required">*</span></label>
                                        <input type="text" class="form-control" name="activity_location" value="{{ old('activity_location') }}" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Penyelenggara<span class="pk-step-required">*</span></label>
                                        <input type="text" class="form-control" name="activity_organizer" value="{{ old('activity_organizer') }}" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Jumlah undangan</label>
                                        <input type="number" min="0" class="form-control" name="participants_invited" value="{{ old('participants_invited') }}">
                                    </div>
                                    <div>
                                        <label class="form-label">Jumlah hadir</label>
                                        <input type="number" min="0" class="form-control" name="participants_attended" value="{{ old('participants_attended') }}">
                                    </div>
                                    <div>
                                        <label class="form-label">Jumlah responden</label>
                                        <input type="number" min="0" class="form-control" name="respondent_count" value="{{ old('respondent_count') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="pk-step">
                                <div class="pk-eval-section-title">II Formulir A - Evaluasi Peserta/Mitra</div>
                                <p class="pk-eval-subtitle">Profil responden dan penilaian peserta/mitra.</p>
                                <div class="pk-eval-grid mb-3">
                                    <div><label class="form-label">Nama (opsional)</label><input type="text" class="form-control" name="respondent_name" value="{{ old('respondent_name') }}"></div>
                                    <div><label class="form-label">Instansi/organisasi</label><input type="text" class="form-control" name="respondent_organization" value="{{ old('respondent_organization') }}"></div>
                                    <div><label class="form-label">Jabatan/peran</label><input type="text" class="form-control" name="respondent_role" value="{{ old('respondent_role') }}"></div>
                                    <div><label class="form-label">Kontak/surel</label><input type="text" class="form-control" name="respondent_contact" value="{{ old('respondent_contact') }}"></div>
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

                                @foreach (($evaluasiQuestionGroups['form_a'] ?? []) as $sectionKey => $sectionConfig)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold">{{ $sectionConfig['title'] }}</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm pk-eval-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 48px;">No</th>
                                                        <th class="pk-item-text">Pernyataan/Indikator</th>
                                                        @foreach ($scoreOptions as $scoreOption)
                                                            <th class="text-center">{{ $scoreOption }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach (($sectionConfig['items'] ?? []) as $itemIndex => $itemText)
                                                        @php
                                                            $itemNumber = $itemIndex + 1;
                                                            $oldAnswer = old("answers.form_a.$sectionKey.$itemNumber");
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $itemNumber }}</td>
                                                            <td class="pk-item-text">{{ $itemText }}</td>
                                                            @foreach ($scoreOptions as $scoreOption)
                                                                <td class="text-center">
                                                                    <input type="radio" name="answers[form_a][{{ $sectionKey }}][{{ $itemNumber }}]" value="{{ $scoreOption }}" {{ $oldAnswer === $scoreOption ? 'checked' : '' }} required>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="pk-eval-grid">
                                    <div><label class="form-label">Catatan khusus tujuan/tema/sasaran</label><textarea class="form-control" name="form_a_special_notes">{{ old('form_a_special_notes') }}</textarea></div>
                                    <div><label class="form-label">Materi paling bermanfaat</label><textarea class="form-control" name="form_a_most_useful_material">{{ old('form_a_most_useful_material') }}</textarea></div>
                                    <div><label class="form-label">Materi perlu diperdalam</label><textarea class="form-control" name="form_a_material_needs">{{ old('form_a_material_needs') }}</textarea></div>
                                    <div><label class="form-label">Catatan narasumber/panitia/fasilitas</label><textarea class="form-control" name="form_a_facility_notes">{{ old('form_a_facility_notes') }}</textarea></div>
                                    <div><label class="form-label">Usulan bentuk kerja sama/tindak lanjut</label><textarea class="form-control" name="form_a_proposed_followup">{{ old('form_a_proposed_followup') }}</textarea></div>
                                </div>
                                <hr>
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
                                                @php
                                                    $selectedChannels = old('preferred_channels', []);
                                                @endphp
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
                                </div>
                            </div>

                            <div class="pk-step">
                                <div class="pk-eval-section-title">III Formulir B - Evaluasi Internal Pelaksana</div>
                                <div class="pk-eval-grid mb-3">
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

                                @foreach (($evaluasiQuestionGroups['form_b'] ?? []) as $sectionKey => $sectionConfig)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold">{{ $sectionConfig['title'] }}</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm pk-eval-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 48px;">No</th>
                                                        <th class="pk-item-text">Pernyataan/Indikator</th>
                                                        @foreach ($scoreOptions as $scoreOption)
                                                            <th class="text-center">{{ $scoreOption }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach (($sectionConfig['items'] ?? []) as $itemIndex => $itemText)
                                                        @php
                                                            $itemNumber = $itemIndex + 1;
                                                            $oldAnswer = old("answers.form_b.$sectionKey.$itemNumber");
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $itemNumber }}</td>
                                                            <td class="pk-item-text">{{ $itemText }}</td>
                                                            @foreach ($scoreOptions as $scoreOption)
                                                                <td class="text-center">
                                                                    <input type="radio" name="answers[form_b][{{ $sectionKey }}][{{ $itemNumber }}]" value="{{ $scoreOption }}" {{ $oldAnswer === $scoreOption ? 'checked' : '' }} required>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="pk-eval-grid">
                                    <div><label class="form-label">Kendala utama perencanaan</label><textarea class="form-control" name="form_b_planning_constraints">{{ old('form_b_planning_constraints') }}</textarea></div>
                                    <div><label class="form-label">Insiden/deviasi jadwal & penanganan</label><textarea class="form-control" name="form_b_incident_notes">{{ old('form_b_incident_notes') }}</textarea></div>
                                    <div><label class="form-label">Praktik baik dipertahankan</label><textarea class="form-control" name="form_b_good_practices">{{ old('form_b_good_practices') }}</textarea></div>
                                    <div><label class="form-label">Akar masalah yang perlu diperbaiki</label><textarea class="form-control" name="form_b_root_issues">{{ old('form_b_root_issues') }}</textarea></div>
                                    <div><label class="form-label">Rekomendasi prioritas</label><textarea class="form-control" name="form_b_priority_recommendations">{{ old('form_b_priority_recommendations') }}</textarea></div>
                                </div>
                            </div>

                            <div class="pk-step">
                                <div class="pk-eval-section-title">IV Formulir C - Rekapitulasi dan Analisis Hasil</div>
                                <div class="pk-eval-grid">
                                    <div><label class="form-label">Jumlah peserta hadir</label><input type="number" min="0" class="form-control" name="recap_participants_present" value="{{ old('recap_participants_present') }}"></div>
                                    <div><label class="form-label">Formulir dibagikan</label><input type="number" min="0" class="form-control" name="recap_forms_distributed" value="{{ old('recap_forms_distributed') }}"></div>
                                    <div><label class="form-label">Formulir diterima</label><input type="number" min="0" class="form-control" name="recap_forms_received" value="{{ old('recap_forms_received') }}"></div>
                                    <div><label class="form-label">Formulir valid</label><input type="number" min="0" class="form-control" name="recap_forms_valid" value="{{ old('recap_forms_valid') }}"></div>
                                    <div><label class="form-label">Tingkat respons (%)</label><input type="number" min="0" max="100" step="0.01" class="form-control" name="recap_response_rate_percent" value="{{ old('recap_response_rate_percent') }}"></div>
                                    <div><label class="form-label">Periode pengumpulan</label><input type="text" class="form-control" name="recap_collection_period" value="{{ old('recap_collection_period') }}"></div>
                                    <div><label class="form-label">Nilai tertinggi (aspek)</label><input type="text" class="form-control" name="recap_highest_aspect" value="{{ old('recap_highest_aspect') }}"></div>
                                    <div><label class="form-label">Nilai tertinggi (angka)</label><input type="number" min="0" max="100" step="0.01" class="form-control" name="recap_highest_value" value="{{ old('recap_highest_value') }}"></div>
                                    <div><label class="form-label">Nilai terendah (aspek)</label><input type="text" class="form-control" name="recap_lowest_aspect" value="{{ old('recap_lowest_aspect') }}"></div>
                                    <div><label class="form-label">Nilai terendah (angka)</label><input type="number" min="0" max="100" step="0.01" class="form-control" name="recap_lowest_value" value="{{ old('recap_lowest_value') }}"></div>
                                    <div><label class="form-label">Nilai keseluruhan /100</label><input type="number" min="0" max="100" step="0.01" class="form-control" name="recap_overall_score" value="{{ old('recap_overall_score') }}"></div>
                                    <div>
                                        <label class="form-label">Kategori hasil</label>
                                        <select class="form-select" name="recap_result_category">
                                            <option value="">-- Pilih kategori --</option>
                                            @foreach ($evaluasiResultCategories as $category)
                                                <option value="{{ $category }}" {{ old('recap_result_category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div><label class="form-label">Target internal /100</label><input type="number" min="0" max="100" step="0.01" class="form-control" name="recap_internal_target" value="{{ old('recap_internal_target') }}"></div>
                                    <div>
                                        <label class="form-label">Status capaian</label>
                                        <select class="form-select" name="recap_achievement_status">
                                            <option value="">-- Pilih status --</option>
                                            @foreach ($evaluasiAchievementStatuses as $status)
                                                <option value="{{ $status }}" {{ old('recap_achievement_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div><label class="form-label">Kesimpulan umum</label><textarea class="form-control" name="recap_general_conclusion">{{ old('recap_general_conclusion') }}</textarea></div>
                                </div>
                                <hr>
                                <h6 class="fw-semibold">Analisis Umpan Balik Kualitatif</h6>
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No.</th>
                                                <th>Tema</th>
                                                <th>Ringkasan Temuan/Contoh Masukan</th>
                                                <th>Frekuensi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 4; $i++)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td><input type="text" class="form-control" name="qualitative_feedback[{{ $i }}][theme]" value="{{ old("qualitative_feedback.$i.theme") }}"></td>
                                                    <td><textarea class="form-control" name="qualitative_feedback[{{ $i }}][summary]">{{ old("qualitative_feedback.$i.summary") }}</textarea></td>
                                                    <td><input type="number" min="0" class="form-control" name="qualitative_feedback[{{ $i }}][frequency]" value="{{ old("qualitative_feedback.$i.frequency") }}"></td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                                <h6 class="fw-semibold">Pencapaian Indikator Kegiatan</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No.</th>
                                                <th>Indikator</th>
                                                <th>Target</th>
                                                <th>Realisasi</th>
                                                <th>Status/Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $defaultIndicators = [
                                                    'Jumlah peserta/mitra yang hadir',
                                                    'Persentase tingkat respons evaluasi',
                                                    'Nilai Evaluasi Kegiatan',
                                                    'Jumlah peluang/komitmen kemitraan',
                                                    'Jangkauan atau interaksi promosi',
                                                    'Persentase tindak lanjut tepat waktu',
                                                ];
                                            @endphp
                                            @foreach ($defaultIndicators as $index => $indicator)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><input type="text" class="form-control" name="indicator_achievements[{{ $index }}][indicator]" value="{{ old("indicator_achievements.$index.indicator", $indicator) }}"></td>
                                                    <td><input type="text" class="form-control" name="indicator_achievements[{{ $index }}][target]" value="{{ old("indicator_achievements.$index.target") }}"></td>
                                                    <td><input type="text" class="form-control" name="indicator_achievements[{{ $index }}][realization]" value="{{ old("indicator_achievements.$index.realization") }}"></td>
                                                    <td><input type="text" class="form-control" name="indicator_achievements[{{ $index }}][status]" value="{{ old("indicator_achievements.$index.status") }}"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="pk-step">
                                <div class="pk-eval-section-title">V Rencana Tindak Lanjut Hasil Evaluasi</div>
                                <div class="pk-eval-grid mb-3">
                                    <div>
                                        <label class="form-label">Penetapan prioritas</label>
                                        <select class="form-select" name="priority_level">
                                            <option value="">-- Pilih prioritas --</option>
                                            @foreach ($evaluasiPriorityOptions as $option)
                                                <option value="{{ $option }}" {{ old('priority_level') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No.</th>
                                                <th>Temuan/Isu</th>
                                                <th>Tindak Lanjut</th>
                                                <th>Penanggung Jawab</th>
                                                <th>Target Waktu</th>
                                                <th>Indikator Selesai</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 0; $i < 7; $i++)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td><textarea class="form-control" name="rtl_items[{{ $i }}][issue]">{{ old("rtl_items.$i.issue") }}</textarea></td>
                                                    <td><textarea class="form-control" name="rtl_items[{{ $i }}][follow_up]">{{ old("rtl_items.$i.follow_up") }}</textarea></td>
                                                    <td><input type="text" class="form-control" name="rtl_items[{{ $i }}][responsible_person]" value="{{ old("rtl_items.$i.responsible_person") }}"></td>
                                                    <td><input type="date" class="form-control" name="rtl_items[{{ $i }}][target_date]" value="{{ old("rtl_items.$i.target_date") }}"></td>
                                                    <td><textarea class="form-control" name="rtl_items[{{ $i }}][completion_indicator]">{{ old("rtl_items.$i.completion_indicator") }}</textarea></td>
                                                    <td><input type="text" class="form-control" name="rtl_items[{{ $i }}][status]" value="{{ old("rtl_items.$i.status") }}"></td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pk-eval-grid">
                                    <div><label class="form-label">Koordinator pemantauan</label><input type="text" class="form-control" name="monitoring_coordinator" value="{{ old('monitoring_coordinator') }}"></div>
                                    <div>
                                        <label class="form-label">Frekuensi pemantauan</label>
                                        <select class="form-select" name="monitoring_frequency">
                                            <option value="">-- Pilih frekuensi --</option>
                                            @foreach ($evaluasiMonitoringFrequencies as $option)
                                                <option value="{{ $option }}" {{ old('monitoring_frequency') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Media pemantauan</label>
                                        <div class="d-flex flex-wrap gap-3">
                                            @php
                                                $selectedMonitoringMedia = old('monitoring_media', []);
                                            @endphp
                                            @foreach ($evaluasiMonitoringMediaOptions as $option)
                                                <label class="form-check-label">
                                                    <input class="form-check-input me-1" type="checkbox" name="monitoring_media[]" value="{{ $option }}" {{ in_array($option, $selectedMonitoringMedia, true) ? 'checked' : '' }}>
                                                    {{ $option }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div><label class="form-label">Media lainnya</label><input type="text" class="form-control" name="monitoring_media_other" value="{{ old('monitoring_media_other') }}"></div>
                                    <div><label class="form-label">Tanggal reviu pertama</label><input type="date" class="form-control" name="first_review_date" value="{{ old('first_review_date') }}"></div>
                                    <div><label class="form-label">Dokumen bukti</label><textarea class="form-control" name="evidence_documents">{{ old('evidence_documents') }}</textarea></div>
                                    <div><label class="form-label">Catatan pimpinan/arahan tambahan</label><textarea class="form-control" name="leader_notes">{{ old('leader_notes') }}</textarea></div>
                                </div>
                            </div>

                            <div class="pk-step">
                                <div class="pk-eval-section-title">VI Lembar Pengesahan dan Pengendalian Dokumen</div>
                                <div class="pk-eval-grid mb-3">
                                    <div>
                                        <label class="form-label">Status pelaksanaan</label>
                                        <select class="form-select" name="execution_status">
                                            <option value="">-- Pilih status --</option>
                                            @foreach ($evaluasiExecutionStatuses as $status)
                                                <option value="{{ $status }}" {{ old('execution_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Rekomendasi</label>
                                        <select class="form-select" name="recommendation_status">
                                            <option value="">-- Pilih rekomendasi --</option>
                                            @foreach ($evaluasiRecommendationStatuses as $status)
                                                <option value="{{ $status }}" {{ old('recommendation_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div><label class="form-label">Rekomendasi 1</label><textarea class="form-control" name="recommendation_1">{{ old('recommendation_1') }}</textarea></div>
                                    <div><label class="form-label">Rekomendasi 2</label><textarea class="form-control" name="recommendation_2">{{ old('recommendation_2') }}</textarea></div>
                                    <div><label class="form-label">Rekomendasi 3</label><textarea class="form-control" name="recommendation_3">{{ old('recommendation_3') }}</textarea></div>
                                </div>
                                <h6 class="fw-semibold">Pengesahan</h6>
                                <div class="pk-eval-grid mb-3">
                                    <div><label class="form-label">Disusun oleh - Nama</label><input type="text" class="form-control" name="prepared_by_name" value="{{ old('prepared_by_name') }}"></div>
                                    <div><label class="form-label">Disusun oleh - NIP</label><input type="text" class="form-control" name="prepared_by_nip" value="{{ old('prepared_by_nip') }}"></div>
                                    <div><label class="form-label">Disusun oleh - Tanggal</label><input type="date" class="form-control" name="prepared_by_date" value="{{ old('prepared_by_date') }}"></div>
                                    <div><label class="form-label">Diverifikasi oleh - Nama</label><input type="text" class="form-control" name="verified_by_name" value="{{ old('verified_by_name') }}"></div>
                                    <div><label class="form-label">Diverifikasi oleh - NIP</label><input type="text" class="form-control" name="verified_by_nip" value="{{ old('verified_by_nip') }}"></div>
                                    <div><label class="form-label">Diverifikasi oleh - Tanggal</label><input type="date" class="form-control" name="verified_by_date" value="{{ old('verified_by_date') }}"></div>
                                    <div><label class="form-label">Disetujui oleh - Nama</label><input type="text" class="form-control" name="approved_by_name" value="{{ old('approved_by_name') }}"></div>
                                    <div><label class="form-label">Disetujui oleh - NIP</label><input type="text" class="form-control" name="approved_by_nip" value="{{ old('approved_by_nip') }}"></div>
                                    <div><label class="form-label">Disetujui oleh - Tanggal</label><input type="date" class="form-control" name="approved_by_date" value="{{ old('approved_by_date') }}"></div>
                                </div>
                                <h6 class="fw-semibold">Pengendalian Dokumen</h6>
                                <div class="pk-eval-grid">
                                    <div><label class="form-label">Kode dokumen</label><input type="text" class="form-control" name="document_code" value="{{ old('document_code', 'KEP-LEK-01') }}"></div>
                                    <div><label class="form-label">Versi</label><input type="text" class="form-control" name="document_version" value="{{ old('document_version', '1.0') }}"></div>
                                    <div><label class="form-label">Tanggal berlaku</label><input type="date" class="form-control" name="document_effective_date" value="{{ old('document_effective_date') }}"></div>
                                    <div>
                                        <label class="form-label">Status dokumen</label>
                                        <select class="form-select" name="document_status">
                                            <option value="">-- Pilih status --</option>
                                            @foreach ($evaluasiDocumentStatuses as $status)
                                                <option value="{{ $status }}" {{ old('document_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div><label class="form-label">Lokasi penyimpanan</label><input type="text" class="form-control" name="document_storage_location" value="{{ old('document_storage_location') }}"></div>
                                    <div><label class="form-label">Masa simpan</label><input type="text" class="form-control" name="document_retention_period" value="{{ old('document_retention_period') }}"></div>
                                    <div>
                                        <label class="form-label">Akses dokumen</label>
                                        <select class="form-select" name="document_access_level">
                                            <option value="">-- Pilih akses --</option>
                                            @foreach ($evaluasiDocumentAccessLevels as $level)
                                                <option value="{{ $level }}" {{ old('document_access_level') === $level ? 'selected' : '' }}>{{ $level }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div><label class="form-label">Penanggung jawab</label><input type="text" class="form-control" name="document_owner" value="{{ old('document_owner') }}"></div>
                                    <div><label class="form-label">Catatan penggunaan</label><textarea class="form-control" name="document_usage_notes">{{ old('document_usage_notes') }}</textarea></div>
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
        var categorySelect = document.getElementById('institution_category');
        var mitraTypeWrapper = document.getElementById('mitraTypeWrapper');
        var mitraTypeSelect = document.getElementById('mitra_pembangunan_type');
        var mitraCategory = 'Mitra Pembangunan (Perusahaan/Swasta/Job Portal)';

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
                evaluasiSubmitBtn.disabled = true;
                evaluasiSubmitBtn.innerHTML = 'Mengirim form evaluasi...';
            });
        }
    })();
</script>
@endsection
