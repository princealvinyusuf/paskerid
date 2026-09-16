@extends('layouts.app')

@section('content')
@php
    $satisfactionQuestions = [
        'service_requirements_ease' => 'Bagaimana pendapat Saudara tentang kemudahan persyaratan untuk memperoleh pelayanan?',
        'system_procedure_ease' => 'Bagaimana kemudahan sistem, mekanisme, dan prosedur dalam mengakses layanan/laman Karirhub?',
        'service_speed' => 'Bagaimana kecepatan/kesesuaian waktu operasional hingga Saudara memperoleh pelayanan?',
        'fee_compliance' => 'Apakah Saudara pernah dimintakan biaya atau tarif lain di luar ketentuan persyaratan yang ada?',
        'product_quality' => 'Bagaimana pendapat Saudara tentang kualitas produk/jasa/tindakan administratif yang diberikan oleh unit layanan?',
        'officer_competence' => 'Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas yang memberikan pelayanan?',
        'officer_behavior' => 'Bagaimana pendapat Saudara terhadap perilaku petugas yang memberikan pelayanan?',
        'facility_quality' => 'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana yang ada di ruang pelayanan?',
        'complaint_media_completeness' => 'Bagaimana pendapat Saudara tentang kelengkapan media penanganan pengaduan atau saran/masukan yang ada dalam unit pelayanan?',
    ];
    $qualityQuestions = [
        'karirhub_procedure_ease' => 'Kemudahan memahami prosedur/tata cara menggunakan Karirhub.',
        'procedure_information_fit' => 'Kesesuaian antara informasi prosedur/panduan penggunaan dengan pengalaman Anda menggunakan Karirhub.',
        'admin_service_hours_fit' => 'Kesesuaian waktu pelayanan Admin dengan kesibukan aktivitas perusahaan Anda (Senin–Jumat, 09.00–15.00 waktu setempat).',
        'candidate_fit' => 'Kesesuaian karakteristik pencari kerja dengan kebutuhan perusahaan Anda.',
    ];
    $scaleLabels = [1 => 'Tidak Mudah', 2 => 'Kurang Mudah', 3 => 'Mudah', 4 => 'Sangat Mudah'];
    $customScaleLabels = [
        'service_speed' => [1 => 'Sangat Lama', 2 => 'Lama', 3 => 'Cepat', 4 => 'Sangat Cepat'],
        'fee_compliance' => [1 => 'Pernah', 2 => 'Tidak Pernah'],
        'product_quality' => [1 => 'Tidak Berkualitas', 2 => 'Kurang Berkualitas', 3 => 'Berkualitas', 4 => 'Sangat Berkualitas'],
        'officer_competence' => [1 => 'Tidak Kompeten', 2 => 'Kurang Kompeten', 3 => 'Kompeten', 4 => 'Sangat Kompeten'],
        'officer_behavior' => [1 => 'Tidak Sopan dan Ramah', 2 => 'Kurang Sopan dan Ramah', 3 => 'Sopan dan Ramah', 4 => 'Sangat Sopan dan Ramah'],
        'facility_quality' => [1 => 'Tidak Nyaman', 2 => 'Cukup Nyaman', 3 => 'Nyaman', 4 => 'Sangat Nyaman'],
        'complaint_media_completeness' => [1 => 'Tidak Ada', 2 => 'Kurang Lengkap', 3 => 'Lengkap', 4 => 'Sangat Lengkap'],
    ];
@endphp

<style>
    .company-survey-page { background: #f4f8f6; min-height: 70vh; }
    .company-survey-page .survey-card { border: 0; border-radius: 1.25rem; box-shadow: 0 12px 36px rgba(20, 83, 45, .10); }
    .company-survey-page .survey-header { background: linear-gradient(135deg, #08783f, #24a967); color: #fff; border-radius: 1.25rem 1.25rem 0 0; }
    .company-survey-page .section-title { color: #08783f; border-bottom: 2px solid #dcefe5; padding-bottom: .65rem; }
    .company-survey-page .question-card { border: 1px solid #dce9e1; border-radius: .85rem; padding: 1rem; }
    .company-survey-page .scale-option { min-width: 8rem; }
    @media (max-width: 575.98px) { .company-survey-page .scale-option { min-width: calc(50% - .5rem); } }
</style>

<div class="company-survey-page py-4 py-md-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-9">
                <div class="card survey-card">
                    <div class="survey-header p-4 p-md-5">
                        <div class="small text-uppercase fw-semibold opacity-75 mb-2">Kuesioner Pemberi Kerja</div>
                        <h1 class="h3 fw-bold mb-2">Survei Evaluasi Perusahaan</h1>
                        <p class="mb-0">Survei Kepuasan Pengguna Layanan, Kualitas Layanan, dan Kemanfaatan Data Karirhub</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <div class="fw-semibold mb-1">Mohon periksa kembali isian berikut:</div>
                                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('company-evaluation-survey.store') }}">
                            @csrf

                            <h2 class="h5 fw-bold section-title mb-4">Data Umum Responden</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="company_name">Nama Perusahaan <span class="text-danger">*</span></label>
                                    <input class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" maxlength="255" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="respondent_name">Nama Responden <span class="text-danger">*</span></label>
                                    <input class="form-control" id="respondent_name" name="respondent_name" value="{{ old('respondent_name') }}" maxlength="255" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="position">Jabatan <span class="text-danger">*</span></label>
                                    <select class="form-select" id="position" name="position" required>
                                        <option value="">Pilih jabatan</option>
                                        @foreach(['Human Resources (HR)', 'Management Level', 'Direktur', 'Lainnya'] as $option)
                                            <option value="{{ $option }}" @selected(old('position') === $option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" id="position_other_wrap">
                                    <label class="form-label fw-semibold" for="position_other">Jabatan lainnya</label>
                                    <input class="form-control" id="position_other" name="position_other" value="{{ old('position_other') }}" maxlength="255">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="company_email">Email Perusahaan <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="company_email" name="company_email" value="{{ old('company_email') }}" maxlength="255" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="phone">No. Handphone <span class="text-danger">*</span></label>
                                    <input type="tel" inputmode="numeric" pattern="[0-9]+" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" maxlength="30" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold" for="province">Domisili Provinsi Perusahaan <span class="text-danger">*</span></label>
                                    <select class="form-select" id="province" name="province" required>
                                        <option value="">Pilih provinsi</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province }}" @selected(old('province') === $province)>{{ $province }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <h2 class="h5 fw-bold section-title mt-5 mb-4">Penggunaan Karirhub / Job Fair</h2>
                            <div class="mb-4">
                                <div class="form-label fw-semibold">Apakah perusahaan Anda mendapatkan fasilitas pendampingan saat menginput lowongan pada laman/aplikasi Karirhub? <span class="text-danger">*</span></div>
                                @foreach(['Ya, dibantu', 'Tidak, secara mandiri'] as $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" id="input_assistance_{{ $loop->index }}" name="input_assistance" value="{{ $option }}" @checked(old('input_assistance') === $option) required>
                                        <label class="form-check-label" for="input_assistance_{{ $loop->index }}">{{ $option }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mb-4">
                                <div class="form-label fw-semibold">Apakah perusahaan Anda memperoleh tenaga kerja melalui Karirhub? <span class="text-danger">*</span></div>
                                @foreach(['Ya', 'Tidak'] as $option)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input obtained-worker" type="radio" id="obtained_worker_{{ strtolower($option) }}" name="obtained_worker" value="{{ $option }}" @checked(old('obtained_worker') === $option) required>
                                        <label class="form-check-label" for="obtained_worker_{{ strtolower($option) }}">{{ $option }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mb-4" id="waiting_time_wrap">
                                <label class="form-label fw-semibold" for="waiting_time">Berapa lama waktu tunggu sejak mengunggah pekerjaan melalui Karirhub/Job Fair hingga mendapat kandidat? <span class="text-danger">*</span></label>
                                <select class="form-select" id="waiting_time" name="waiting_time">
                                    <option value="">Pilih waktu tunggu</option>
                                    @foreach(['Kurang dari 1 bulan', 'Antara 1-2 bulan', 'Antara 2-3 bulan', 'Lebih dari 3 bulan'] as $option)
                                        <option value="{{ $option }}" @selected(old('waiting_time') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <div class="form-label fw-semibold">Dari mana Anda mengetahui laman/aplikasi pencari kerja Karirhub? <span class="text-danger">*</span> <span class="text-muted fw-normal">(boleh lebih dari satu)</span></div>
                                @foreach(['Sosial media', 'Dinas Ketenagakerjaan', 'Teman/kolega', 'Lainnya'] as $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input information-source" type="checkbox" id="source_{{ $loop->index }}" name="information_sources[]" value="{{ $option }}" @checked(in_array($option, old('information_sources', []), true))>
                                        <label class="form-check-label" for="source_{{ $loop->index }}">{{ $option }}</label>
                                    </div>
                                @endforeach
                                <input class="form-control mt-2" id="information_source_other" name="information_source_other" value="{{ old('information_source_other') }}" maxlength="255" placeholder="Sebutkan sumber lainnya">
                            </div>

                            <h2 class="h5 fw-bold section-title mt-5 mb-2">Kepuasan Pengguna</h2>
                            <p class="text-muted mb-4">Pilih jawaban yang paling sesuai pada setiap pertanyaan.</p>
                            <div class="d-grid gap-3">
                                @foreach($satisfactionQuestions as $field => $question)
                                    @php $questionScaleLabels = $customScaleLabels[$field] ?? $scaleLabels; @endphp
                                    <div class="question-card">
                                        <div class="fw-semibold mb-3">{{ $loop->iteration }}. {{ $question }} <span class="text-danger">*</span></div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($questionScaleLabels as $score => $label)
                                                <div class="form-check scale-option">
                                                    <input class="form-check-input" type="radio" id="{{ $field }}_{{ $score }}" name="{{ $field }}" value="{{ $score }}" @checked((string) old($field) === (string) $score) required>
                                                    <label class="form-check-label" for="{{ $field }}_{{ $score }}"><strong>{{ $score }}</strong> — {{ $label }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <h2 class="h5 fw-bold section-title mt-5 mb-2">Tingkat Kualitas Layanan & Kepuasan Pengguna</h2>
                            <p class="text-muted mb-4">Gunakan skala penilaian 1–4 yang sama.</p>
                            <div class="d-grid gap-3">
                                @foreach($qualityQuestions as $field => $question)
                                    <div class="question-card">
                                        <div class="fw-semibold mb-3">{{ $loop->iteration }}. {{ $question }} <span class="text-danger">*</span></div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($scaleLabels as $score => $label)
                                                <div class="form-check scale-option">
                                                    <input class="form-check-input" type="radio" id="{{ $field }}_{{ $score }}" name="{{ $field }}" value="{{ $score }}" @checked((string) old($field) === (string) $score) required>
                                                    <label class="form-check-label" for="{{ $field }}_{{ $score }}"><strong>{{ $score }}</strong> — {{ $label }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <button type="submit" class="btn btn-success btn-lg px-4">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Kirim Survei
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const position = document.getElementById('position');
        const positionOtherWrap = document.getElementById('position_other_wrap');
        const positionOther = document.getElementById('position_other');
        const workerChoices = document.querySelectorAll('.obtained-worker');
        const waitingWrap = document.getElementById('waiting_time_wrap');
        const waitingTime = document.getElementById('waiting_time');
        const sourceChoices = document.querySelectorAll('.information-source');
        const sourceOther = document.getElementById('information_source_other');

        function updateConditionalFields() {
            const usesOtherPosition = position.value === 'Lainnya';
            positionOtherWrap.classList.toggle('d-none', !usesOtherPosition);
            positionOther.required = usesOtherPosition;

            const obtainedWorker = document.querySelector('.obtained-worker:checked')?.value === 'Ya';
            waitingWrap.classList.toggle('d-none', !obtainedWorker);
            waitingTime.required = obtainedWorker;

            const usesOtherSource = Array.from(sourceChoices).some((item) => item.checked && item.value === 'Lainnya');
            sourceOther.classList.toggle('d-none', !usesOtherSource);
            sourceOther.required = usesOtherSource;

            const hasSource = Array.from(sourceChoices).some((item) => item.checked);
            sourceChoices.forEach((item) => item.required = !hasSource);
        }

        position.addEventListener('change', updateConditionalFields);
        workerChoices.forEach((item) => item.addEventListener('change', updateConditionalFields));
        sourceChoices.forEach((item) => item.addEventListener('change', updateConditionalFields));
        updateConditionalFields();
    })();
</script>
@endsection
