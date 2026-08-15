@section('meta_title', 'Bedah CV Gratis - AI CV ATS Checker | Cari Loker')
@section('meta_description', 'Bedah CV Gratis dengan AI CV ATS Checker. Upload CV Anda, dapatkan analisis 12 aspek, action points, keyword, dan rekomendasi karier dalam hitungan detik.')
@section('meta_keywords', 'bedah cv gratis, cv ats checker, ai cv reviewer, review cv indonesia, optimasi cv')
@section('canonical_url', route('cv.reviewer'))
@section('og_type', 'website')

@extends('layouts.app')

@section('head')
    <script src="https://js.puter.com/v2/"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://unpkg.com/mammoth@1.8.0/mammoth.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <style>
        .score-ring-track {
            stroke: rgba(148, 163, 184, 0.25);
        }

        .score-ring-progress {
            stroke: #2563eb;
            transition: stroke-dashoffset 0.9s ease;
            stroke-linecap: round;
        }

        .result-tabs-sticky {
            position: sticky;
            top: 5.25rem;
            z-index: 20;
            backdrop-filter: blur(8px);
        }

        @keyframes fade-slide-up {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-animate {
            animation: fade-slide-up 0.45s ease both;
        }

        @keyframes shimmer {
            0% { background-position: -300px 0; }
            100% { background-position: 300px 0; }
        }

        .skeleton-line {
            background: linear-gradient(90deg, rgba(226,232,240,0.5) 25%, rgba(203,213,225,0.65) 50%, rgba(226,232,240,0.5) 75%);
            background-size: 600px 100%;
            animation: shimmer 1.5s infinite linear;
        }

        .tab-scroll {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .tab-scroll::-webkit-scrollbar {
            display: none;
        }

        .aspect-panel {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.35s ease, opacity 0.25s ease;
        }

        .aspect-panel.open {
            opacity: 1;
        }
    </style>
@endsection

@section('content')
    <section class="position-relative overflow-hidden border-bottom border-light bg-white">
        <div class="pointer-events-none position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 10%, rgba(31,123,255,0.1), transparent 40%), radial-gradient(circle at 90% 30%, rgba(16,185,129,0.1), transparent 38%);"></div>
        <div class="container position-relative py-5">
            <div class="mx-auto text-center" style="max-width: 800px;">
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 mb-3">
                    AI CV ATS Checker
                </span>
                <h1 class="display-5 fw-bolder text-dark mb-4">
                    Tingkatkan Peluang Lolos Screening dengan
                    <span class="text-primary">Bedah CV Gratis</span>
                </h1>
                <p class="lead text-secondary mb-4">
                    Dapatkan analisis CV mendalam berbasis AI seperti platform profesional: skor ATS, analisis 12 aspek penting, action points, keyword rekomendasi, dan saran karier.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2 small text-secondary">
                    <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill">4.9/5 review kepuasan</span>
                    <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill">12 aspek evaluasi</span>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 p-md-5 mb-4 rounded-4">
                    <h2 class="h4 fw-bold text-dark">Review CV Sekarang</h2>
                    <p class="text-muted small">Upload CV Anda atau tempel teks CV, lalu klik tombol review untuk mendapatkan hasil yang detail.</p>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="cv-file" class="form-label fw-bold text-dark">CV / Resume</label>
                            <input id="cv-file" type="file" accept=".txt,.md,.rtf,.pdf,.doc,.docx,.png,.jpg,.jpeg,.webp" class="form-control">
                            <div id="file-help" class="form-text small">
                                File teks, PDF, DOCX, dan gambar CV akan dicoba dibaca otomatis (termasuk OCR untuk scan). Jika gagal, paste isi CV di kolom bawah.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="review-language" class="form-label fw-bold text-dark">Select Language</label>
                            <select id="review-language" class="form-select mb-3">
                                <option value="id">Bahasa Indonesia</option>
                                <option value="en">English</option>
                            </select>
                            <label for="review-purpose" class="form-label fw-bold text-dark">Review Purpose</label>
                            <select id="review-purpose" class="form-select">
                                <option value="job">Job Seeking</option>
                                <option value="job-scholarship">Job-seeking & Scholarship</option>
                                <option value="internship">Internship</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="cv-text" class="form-label fw-bold text-dark">CV Text</label>
                        <textarea id="cv-text" rows="12" class="form-control" placeholder="Paste CV Anda di sini jika file tidak terbaca..."></textarea>
                    </div>

                    <div class="mt-4">
                        <label for="job-description" class="form-label fw-bold text-dark">Target Job Description (Opsional)</label>
                        <textarea id="job-description" rows="8" class="form-control" placeholder="Paste job description untuk mendapatkan CV-job match score dan gap analysis."></textarea>
                    </div>

                    <div class="mt-4 d-flex flex-wrap align-items-center gap-2">
                        <button id="review-btn" type="button" class="btn btn-primary px-4 py-2 fw-bold">
                            <span id="review-btn-text">Review Sekarang</span>
                            <span id="review-btn-loading" class="d-none">
                                <i class="fa-solid fa-spinner fa-spin me-1"></i> Menganalisis...
                            </span>
                        </button>
                        <button id="clear-btn" type="button" class="btn btn-outline-secondary px-4 py-2 fw-bold">Reset</button>
                        <span id="status-text" class="small text-muted ms-2"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h3 class="h5 fw-bold text-dark">Apa yang dianalisis?</h3>
                    <ul class="list-unstyled mt-3 mb-0 text-secondary small" style="line-height: 1.8;">
                        <li><i class="fa-solid fa-check text-success me-2"></i> Overall Impression</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Contact Information</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Relevant Skill</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Professional Summary</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Work Experience</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Achievement</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Education & Certification</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Organizational Activity</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Writing Quality</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Additional Section</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Keywords</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Career Recommendation</li>
                    </ul>
                </div>
            </div>
        </div>

        @php
            $userReviews = [
                ['name' => 'Worksfess (@worksfess)', 'role' => 'Komunitas Karir', 'text' => 'Gw kira CV udah bagus, ternyata masih banyak yang kurang. Gara-gara fitur ini jadi tau kalau CV harus ada kata kunci yang relevan sesuai posisi.'],
                ['name' => 'Rio (@vicarioreinaldo)', 'role' => 'Career Development Content Creator', 'text' => 'Step 1 dapet kerja impian & di notice HRD: update CV di sini. Analisis lengkap dan action item-nya super detail.'],
                ['name' => 'Andini Saras (@ustadchen)', 'role' => 'Content Creator', 'text' => 'Jujur ini ngebantu banget sih buat jobseekers. Langsung keliatan mana yang harus gue tambahin dan benerin.'],
                ['name' => 'Putri (@chocostudy_)', 'role' => 'Undergraduate Student', 'text' => 'Berkat webnya aku lolos beberapa paid internship padahal masih kuliah semester 5.'],
                ['name' => 'Theresa Naevys (@ninanotsleep)', 'role' => 'Freshgraduate', 'text' => 'Skeptis awalnya, tapi ternyata fiturnya beneran bantu banget buat kasih keywords mana yang harus di-highlight di CV.'],
                ['name' => 'Karirspace (@karirspace)', 'role' => 'Media & Komunitas Karir', 'text' => 'Buat yang serius cari kerja, plis benahin CV dari sekarang. Dieval bagian A-Z dan poin perbaikannya lengkap.'],
                ['name' => 'Nana (@nanadybs)', 'role' => 'Content Creator Karir', 'text' => 'Solusi canggih buat freshgrad. Nggak sampe 1 menit dapet semua perbaikan CV biar lebih di-notice HR plus bonus personality test.'],
                ['name' => 'Imam Vishal (@imamprdn_)', 'role' => 'Undergraduate Student', 'text' => 'Jujur baru coba fitur yang bisa bikin CV lebih menarik di mata HR. Penting banget buat mahasiswa di-review secara detail gini.'],
                ['name' => 'Indrawan Sadewa (@indraaaaaeee_)', 'role' => 'Undergraduate Student', 'text' => 'Fitur ini beneran bantu saya benerin CV non-ATS jadi format ATS dan ubah bahasa non-formal jadi lebih profesional.'],
                ['name' => 'Gerald Rombelayuk (@ger_obakbaso)', 'role' => 'Social Media Specialist', 'text' => 'Iseng nyobain buat update CV dan hasilnya se in-depth itu. Helpful buat gw yang males baca tips di internet.'],
                ['name' => 'Jessica F. (@izzermcglizzer)', 'role' => 'Legal Consultant', 'text' => 'Thanks to this tool, CV aku jadi lebih clean dan eye-catching. Recommended banget buat yang lagi job hunting.'],
                ['name' => 'Eza Hazami', 'role' => 'Tech HR Business Partner', 'text' => 'Jangan suka nyalahin diri sendiri kalau nggak dipanggil HRD. Better sebelum kirim CV minta review dulu. Gratis kalau mau coba.']
            ];
        @endphp

        <section class="mt-5">
            <div class="d-flex align-items-end justify-content-between mb-4">
                <div>
                    <p class="text-primary fw-bold text-uppercase small mb-1" style="letter-spacing: 0.2em;">Testimonial</p>
                    <h2 class="h3 fw-bold text-dark mb-0">Review Pengguna</h2>
                </div>
                <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill">4.9/5 review kepuasan</span>
            </div>

            <div class="row g-4">
                @foreach($userReviews as $review)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
                            <h3 class="h6 fw-bold text-dark mb-1">{{ $review['name'] }}</h3>
                            <p class="small text-muted mb-2">{{ $review['role'] }}</p>
                            <p class="small text-secondary mb-0">"{{ $review['text'] }}"</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div id="result-wrapper" class="mt-5 d-none">
            <div class="row g-4">
                <aside class="col-lg-3">
                    <div class="card border-0 shadow-sm p-3 rounded-4 sticky-top" style="top: 100px; z-index: 10;">
                        <div class="d-flex flex-row flex-lg-column gap-2 overflow-auto pb-2 pb-lg-0 result-tabs-sticky tab-scroll">
                            <button type="button" data-tab-target="tab-overview" class="result-tab-btn btn btn-primary text-start fw-bold rounded-3 active"><i class="fa-regular fa-eye me-2"></i>Overview</button>
                            <button type="button" data-tab-target="tab-aspects" class="result-tab-btn btn btn-outline-secondary border-0 text-start fw-bold rounded-3 text-dark"><i class="fa-regular fa-square-check me-2"></i>12 Aspek</button>
                            <button type="button" data-tab-target="tab-job-match" class="result-tab-btn btn btn-outline-secondary border-0 text-start fw-bold rounded-3 text-dark"><i class="fa-solid fa-bullseye me-2"></i>Job Match</button>
                            <button type="button" data-tab-target="tab-keywords" class="result-tab-btn btn btn-outline-secondary border-0 text-start fw-bold rounded-3 text-dark"><i class="fa-solid fa-key me-2"></i>Keywords</button>
                            <button type="button" data-tab-target="tab-career" class="result-tab-btn btn btn-outline-secondary border-0 text-start fw-bold rounded-3 text-dark"><i class="fa-solid fa-briefcase me-2"></i>Career Fit</button>
                            <hr class="my-2 d-none d-lg-block">
                            <button id="download-pdf-btn" type="button" class="btn btn-light text-primary border border-primary border-opacity-25 text-start fw-bold rounded-3"><i class="fa-regular fa-file-pdf me-2"></i>Download (PDF)</button>
                            <button id="download-ats-btn" type="button" class="btn btn-light text-success border border-success border-opacity-25 text-start fw-bold rounded-3"><i class="fa-solid fa-file-lines me-2"></i>Download CV ATS Builder</button>
                        </div>
                    </div>
                </aside>

                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm p-4 p-md-5 mb-4 rounded-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <p class="text-primary fw-bold text-uppercase small mb-1" style="letter-spacing: 0.2em;">AI CV Reviewer Results</p>
                                <h2 class="h3 fw-bold text-dark mb-0">Hasil Analisis CV</h2>
                            </div>
                            <div id="score-chip" class="d-flex align-items-center gap-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 p-3">
                                <div class="position-relative" style="width: 80px; height: 80px;">
                                    <svg class="w-100 h-100" viewBox="0 0 120 120" style="transform: rotate(-90deg);">
                                        <circle class="score-ring-track" cx="60" cy="60" r="52" stroke-width="12" fill="none"></circle>
                                        <circle id="score-ring-progress" class="score-ring-progress" cx="60" cy="60" r="52" stroke-width="12" fill="none"></circle>
                                    </svg>
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                        <span id="overall-score" class="fs-4 fw-bolder text-primary">0%</span>
                                    </div>
                                </div>
                                <div>
                                    <p id="ats-label" class="fw-bold text-primary text-uppercase mb-0 small" style="letter-spacing: 0.1em;">ATS Readiness</p>
                                    <p id="ats-caption" class="small text-muted mb-0">Skor keseluruhan CV Anda</p>
                                </div>
                            </div>
                        </div>
                        <p id="overall-impression" class="mt-4 mb-0 text-secondary"></p>
                    </div>

                    <div id="tab-overview" class="result-tab-panel">
                        <div class="row g-3">
                            <div class="col-md-6 col-xl-3">
                                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                                    <p class="text-muted text-uppercase small fw-bold mb-2" style="letter-spacing: 0.1em;">Top Strength</p>
                                    <p id="top-strength" class="fw-bold text-dark mb-0">-</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                                    <p class="text-muted text-uppercase small fw-bold mb-2" style="letter-spacing: 0.1em;">Main Gap</p>
                                    <p id="main-gap" class="fw-bold text-dark mb-0">-</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                                    <p class="text-muted text-uppercase small fw-bold mb-2" style="letter-spacing: 0.1em;">Aspect Count</p>
                                    <p id="aspect-count" class="fw-bold text-dark mb-0">0 aspek</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border-0 shadow-sm p-4 h-100 rounded-4">
                                    <p class="text-muted text-uppercase small fw-bold mb-2" style="letter-spacing: 0.1em;">Recommended Action</p>
                                    <p id="priority-action" class="fw-bold text-dark mb-0">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-aspects" class="result-tab-panel d-none">
                        <div id="aspects-grid" class="row g-3"></div>
                    </div>

                    <div id="tab-job-match" class="result-tab-panel d-none">
                        <div class="card border-0 shadow-sm p-4 rounded-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                                <h3 class="h5 fw-bold text-dark mb-0">Job-targeted CV Scoring</h3>
                                <span id="job-match-score-badge" class="badge bg-light border text-dark">Belum dianalisis</span>
                            </div>
                            <p id="job-match-summary" class="text-secondary small">Tambahkan job description untuk melihat kecocokan CV terhadap target role.</p>
                            <div class="row g-4 mt-1">
                                <div class="col-md-6">
                                    <p class="text-muted text-uppercase small fw-bold mb-2" style="letter-spacing: 0.1em;">Key Gaps</p>
                                    <ul id="job-match-gaps" class="text-secondary small ps-3 mb-0"></ul>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted text-uppercase small fw-bold mb-2" style="letter-spacing: 0.1em;">Suggested Keywords</p>
                                    <ul id="job-match-keywords" class="text-secondary small ps-3 mb-0"></ul>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-muted text-uppercase small fw-bold mb-2" style="letter-spacing: 0.1em;">Priority Improvements</p>
                                <ul id="job-match-improvements" class="text-secondary small ps-3 mb-0"></ul>
                            </div>
                        </div>
                    </div>

                    <div id="tab-keywords" class="result-tab-panel d-none">
                        <div class="card border-0 shadow-sm p-4 rounded-4">
                            <h3 class="h5 fw-bold text-dark">Keywords Recommendation</h3>
                            <ul id="keywords-list" class="text-secondary small ps-3 mt-3 mb-0"></ul>
                        </div>
                    </div>

                    <div id="tab-career" class="result-tab-panel d-none">
                        <div class="card border-0 shadow-sm p-4 rounded-4">
                            <h3 class="h5 fw-bold text-dark">Career Recommendation</h3>
                            <p id="career-recommendation" class="text-secondary small mt-3 mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sample-state" class="mt-5">
            <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4">
                <p class="text-primary fw-bold text-uppercase small mb-1" style="letter-spacing: 0.2em;">Contoh Hasil Analisis</p>
                <h2 class="h3 fw-bold text-dark">Preview sebelum review</h2>
                <p class="text-secondary small mb-4">Setelah Anda klik <strong>Review Sekarang</strong>, hasil akan tampil seperti struktur di bawah ini.</p>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-4 bg-light rounded-4">
                            <div class="skeleton-line rounded" style="height: 16px; width: 120px;"></div>
                            <div class="skeleton-line rounded mt-3" style="height: 12px; width: 100%;"></div>
                            <div class="skeleton-line rounded mt-2" style="height: 12px; width: 90%;"></div>
                            <div class="skeleton-line rounded mt-2" style="height: 12px; width: 75%;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-4 bg-light rounded-4">
                            <div class="skeleton-line rounded" style="height: 16px; width: 150px;"></div>
                            <div class="skeleton-line rounded mt-3" style="height: 12px; width: 100%;"></div>
                            <div class="skeleton-line rounded mt-2" style="height: 12px; width: 85%;"></div>
                            <div class="skeleton-line rounded mt-2" style="height: 12px; width: 60%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        (() => {
            const fileInput = document.getElementById('cv-file');
            const cvText = document.getElementById('cv-text');
            const jobDescriptionInput = document.getElementById('job-description');
            const language = document.getElementById('review-language');
            const purpose = document.getElementById('review-purpose');
            const reviewBtn = document.getElementById('review-btn');
            const clearBtn = document.getElementById('clear-btn');
            const downloadPdfBtn = document.getElementById('download-pdf-btn');
            const downloadAtsBtn = document.getElementById('download-ats-btn');
            const statusText = document.getElementById('status-text');
            const resultWrapper = document.getElementById('result-wrapper');
            const sampleState = document.getElementById('sample-state');
            const overallScore = document.getElementById('overall-score');
            const scoreRingProgress = document.getElementById('score-ring-progress');
            const scoreChip = document.getElementById('score-chip');
            const atsLabel = document.getElementById('ats-label');
            const atsCaption = document.getElementById('ats-caption');
            const overallImpression = document.getElementById('overall-impression');
            const aspectsGrid = document.getElementById('aspects-grid');
            const keywordsList = document.getElementById('keywords-list');
            const careerRecommendation = document.getElementById('career-recommendation');
            const jobMatchScoreBadge = document.getElementById('job-match-score-badge');
            const jobMatchSummary = document.getElementById('job-match-summary');
            const jobMatchGaps = document.getElementById('job-match-gaps');
            const jobMatchKeywords = document.getElementById('job-match-keywords');
            const jobMatchImprovements = document.getElementById('job-match-improvements');
            const topStrength = document.getElementById('top-strength');
            const mainGap = document.getElementById('main-gap');
            const aspectCount = document.getElementById('aspect-count');
            const priorityAction = document.getElementById('priority-action');
            const tabButtons = document.querySelectorAll('.result-tab-btn');
            const tabPanels = document.querySelectorAll('.result-tab-panel');
            const reviewBtnText = document.getElementById('review-btn-text');
            const reviewBtnLoading = document.getElementById('review-btn-loading');
            const fileHelp = document.getElementById('file-help');

            const expectedAspects = [
                'Overall Impression',
                'Contact Information',
                'Relevant Skill',
                'Professional Summary',
                'Work Experience',
                'Achievement',
                'Education and Certification',
                'Organizational Activity',
                'Consistent and Error-free Writing',
                'Additional Section',
                'Keywords',
                'Career Recommendation'
            ];
            const textLikeExtensions = ['.txt', '.md', '.rtf'];
            const imageExtensions = ['.png', '.jpg', '.jpeg', '.webp'];
            let extractedFromFile = '';
            let extractedFileName = '';
            let latestAnalysis = null;
            let latestCvText = '';

            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#39;');

            const setLoading = (loading) => {
                reviewBtn.disabled = loading;
                reviewBtn.classList.toggle('opacity-75', loading);
                reviewBtnText.classList.toggle('d-none', loading);
                reviewBtnLoading.classList.toggle('d-none', !loading);
            };

            const ringRadius = 52;
            const ringCircumference = 2 * Math.PI * ringRadius;
            scoreRingProgress.style.strokeDasharray = `${ringCircumference}`;
            scoreRingProgress.style.strokeDashoffset = `${ringCircumference}`;

            const getScorePalette = (score) => {
                if (score >= 80) {
                    return {
                        ring: '#198754', // success
                        text: ['text-success'],
                        chip: ['bg-success', 'bg-opacity-10', 'border-success', 'border-opacity-25'],
                        label: ['text-success'],
                        caption: 'CV Anda sudah kuat dan kompetitif.'
                    };
                }
                if (score >= 65) {
                    return {
                        ring: '#ffc107', // warning
                        text: ['text-warning'],
                        chip: ['bg-warning', 'bg-opacity-10', 'border-warning', 'border-opacity-25'],
                        label: ['text-warning'],
                        caption: 'CV Anda cukup baik, perlu sedikit peningkatan.'
                    };
                }
                return {
                    ring: '#dc3545', // danger
                    text: ['text-danger'],
                    chip: ['bg-danger', 'bg-opacity-10', 'border-danger', 'border-opacity-25'],
                    label: ['text-danger'],
                    caption: 'CV Anda butuh optimasi prioritas sebelum melamar.'
                };
            };

            const applyScorePalette = (score) => {
                const palette = getScorePalette(score);
                scoreRingProgress.style.stroke = palette.ring;

                overallScore.classList.remove('text-primary', 'text-success', 'text-warning', 'text-danger');
                atsLabel.classList.remove('text-primary', 'text-success', 'text-warning', 'text-danger');
                scoreChip.classList.remove('bg-primary', 'bg-success', 'bg-warning', 'bg-danger', 'bg-opacity-10', 'border-primary', 'border-success', 'border-warning', 'border-danger', 'border-opacity-25');

                overallScore.classList.add(...palette.text);
                atsLabel.classList.add(...palette.label);
                scoreChip.classList.add(...palette.chip);
                atsCaption.textContent = palette.caption;
            };

            const resetScoreVisual = () => {
                scoreRingProgress.style.stroke = '';
                overallScore.classList.remove('text-success', 'text-warning', 'text-danger');
                atsLabel.classList.remove('text-success', 'text-warning', 'text-danger');
                scoreChip.classList.remove('bg-success', 'bg-warning', 'bg-danger', 'bg-opacity-10', 'border-success', 'border-warning', 'border-danger', 'border-opacity-25');
                overallScore.classList.add('text-primary');
                atsLabel.classList.add('text-primary');
                scoreChip.classList.add('bg-primary', 'bg-opacity-10', 'border-primary', 'border-opacity-25');
                atsCaption.textContent = 'Skor keseluruhan CV Anda';
            };

            const setActiveTab = (targetId) => {
                tabPanels.forEach((panel) => panel.classList.toggle('d-none', panel.id !== targetId));
                tabButtons.forEach((button) => {
                    const isActive = button.dataset.tabTarget === targetId;
                    button.classList.toggle('btn-primary', isActive);
                    button.classList.toggle('btn-outline-secondary', !isActive);
                    button.classList.toggle('text-dark', !isActive);
                    button.classList.toggle('border-0', !isActive);
                });

                if (targetId === 'tab-aspects') {
                    // Recompute heights when the hidden tab becomes visible.
                    requestAnimationFrame(() => {
                        document.querySelectorAll('#tab-aspects .aspect-panel.open').forEach((panel) => {
                            panel.style.maxHeight = `${panel.scrollHeight}px`;
                        });
                    });
                }

                if (!resultWrapper.classList.contains('d-none')) {
                    animatePanels();
                }
            };

            const animateScore = (score) => {
                const safeScore = Math.max(0, Math.min(100, Number(score) || 0));
                applyScorePalette(safeScore);
                const targetOffset = ringCircumference * (1 - safeScore / 100);
                const start = performance.now();
                const duration = 900;
                const initialValue = Number((overallScore.textContent || '0').replace('%', '')) || 0;
                const initialOffset = ringCircumference * (1 - initialValue / 100);

                const step = (time) => {
                    const progress = Math.min((time - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const currentScore = Math.round(initialValue + (safeScore - initialValue) * eased);
                    const currentOffset = initialOffset + (targetOffset - initialOffset) * eased;
                    overallScore.textContent = `${currentScore}%`;
                    scoreRingProgress.style.strokeDashoffset = `${currentOffset}`;

                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                };

                requestAnimationFrame(step);
            };

            const animatePanels = () => {
                const targets = document.querySelectorAll('#tab-overview .surface-card, #tab-aspects .surface-card, #tab-job-match .surface-card, #tab-keywords .surface-card, #tab-career .surface-card');
                targets.forEach((el, index) => {
                    el.classList.remove('result-animate');
                    el.style.animationDelay = `${Math.min(index * 40, 220)}ms`;
                    // Force reflow for restart animation.
                    void el.offsetWidth;
                    el.classList.add('result-animate');
                });
            };

            const detectExtension = (name) => {
                const lower = String(name || '').toLowerCase();
                if (lower.endsWith('.docx')) return '.docx';
                if (lower.endsWith('.pdf')) return '.pdf';
                if (lower.endsWith('.txt')) return '.txt';
                if (lower.endsWith('.md')) return '.md';
                if (lower.endsWith('.rtf')) return '.rtf';
                if (lower.endsWith('.doc')) return '.doc';
                if (lower.endsWith('.png')) return '.png';
                if (lower.endsWith('.jpg')) return '.jpg';
                if (lower.endsWith('.jpeg')) return '.jpeg';
                if (lower.endsWith('.webp')) return '.webp';
                return '';
            };

            const loadExternalScript = (src) => {
                return new Promise((resolve, reject) => {
                    const existing = document.querySelector(`script[data-dynamic-src="${src}"]`);
                    if (existing) {
                        if (existing.dataset.loaded === 'true') {
                            resolve();
                            return;
                        }
                        existing.addEventListener('load', () => resolve(), { once: true });
                        existing.addEventListener('error', () => reject(new Error(`Gagal load script: ${src}`)), { once: true });
                        return;
                    }

                    const script = document.createElement('script');
                    script.src = src;
                    script.async = true;
                    script.dataset.dynamicSrc = src;
                    script.addEventListener('load', () => {
                        script.dataset.loaded = 'true';
                        resolve();
                    }, { once: true });
                    script.addEventListener('error', () => reject(new Error(`Gagal load script: ${src}`)), { once: true });
                    document.head.appendChild(script);
                });
            };

            let pdfJsLoadPromise = null;
            const ensurePdfJsLoaded = async () => {
                const existingLib = window.pdfjsLib || window['pdfjs-dist/build/pdf'];
                if (existingLib?.GlobalWorkerOptions) {
                    existingLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                    return existingLib;
                }

                if (!pdfJsLoadPromise) {
                    pdfJsLoadPromise = (async () => {
                        const sources = [
                            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
                            'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js',
                        ];

                        for (const source of sources) {
                            try {
                                await loadExternalScript(source);
                                const lib = window.pdfjsLib || window['pdfjs-dist/build/pdf'];
                                if (lib?.GlobalWorkerOptions) {
                                    lib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                                    return lib;
                                }
                            } catch (_) {
                                // Try the next source.
                            }
                        }

                        throw new Error('PDF parser belum tersedia di browser ini. Coba refresh, nonaktifkan adblock, atau paste teks CV manual.');
                    })();
                }

                return await pdfJsLoadPromise;
            };

            const fileToImageElement = async (file) => {
                const dataUrl = await new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = () => reject(new Error('Gagal membaca image file.'));
                    reader.readAsDataURL(file);
                });

                return await new Promise((resolve, reject) => {
                    const image = new Image();
                    image.onload = () => resolve(image);
                    image.onerror = () => reject(new Error('Gagal memuat image file.'));
                    image.src = dataUrl;
                });
            };

            const runOcrOnCanvas = async (canvas, progressPrefix = 'OCR') => {
                if (!window.Tesseract?.recognize) {
                    throw new Error('OCR engine belum tersedia.');
                }

                const language = 'eng+ind';
                const result = await window.Tesseract.recognize(canvas, language, {
                    logger: (message) => {
                        if (message?.status && typeof message.progress === 'number') {
                            const pct = Math.round(message.progress * 100);
                            statusText.textContent = `${progressPrefix}: ${message.status} ${pct}%`;
                        }
                    }
                });
                return (result?.data?.text || '').trim();
            };

            const extractImageTextWithOCR = async (file) => {
                const image = await fileToImageElement(file);
                const canvas = document.createElement('canvas');
                const maxWidth = 1800;
                const ratio = image.width > maxWidth ? (maxWidth / image.width) : 1;
                canvas.width = Math.max(1, Math.floor(image.width * ratio));
                canvas.height = Math.max(1, Math.floor(image.height * ratio));
                const ctx = canvas.getContext('2d');
                ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
                return await runOcrOnCanvas(canvas, 'OCR gambar CV');
            };

            const extractPdfTextWithOCR = async (file) => {
                const pdfjsLib = await ensurePdfJsLoaded();
                const fileBuffer = await file.arrayBuffer();
                const loadingTask = pdfjsLib.getDocument({ data: fileBuffer });
                const pdf = await loadingTask.promise;
                const maxPages = Math.min(pdf.numPages, 5);
                const pageTexts = [];

                for (let pageNo = 1; pageNo <= maxPages; pageNo += 1) {
                    statusText.textContent = `Menjalankan OCR PDF halaman ${pageNo}/${maxPages}...`;
                    const page = await pdf.getPage(pageNo);
                    const viewport = page.getViewport({ scale: 1.8 });
                    const canvas = document.createElement('canvas');
                    canvas.width = Math.floor(viewport.width);
                    canvas.height = Math.floor(viewport.height);
                    const ctx = canvas.getContext('2d');
                    await page.render({ canvasContext: ctx, viewport }).promise;
                    const pageText = await runOcrOnCanvas(canvas, `OCR PDF halaman ${pageNo}/${maxPages}`);
                    if (pageText) {
                        pageTexts.push(pageText);
                    }
                }

                return pageTexts.join('\n\n');
            };

            const extractPdfText = async (file) => {
                const pdfjsLib = await ensurePdfJsLoaded();
                const fileBuffer = await file.arrayBuffer();
                const loadingTask = pdfjsLib.getDocument({ data: fileBuffer });
                const pdf = await loadingTask.promise;
                const pages = [];

                for (let pageNo = 1; pageNo <= pdf.numPages; pageNo += 1) {
                    const page = await pdf.getPage(pageNo);
                    const content = await page.getTextContent();
                    const pageText = content.items.map((item) => item.str).join(' ').trim();
                    if (pageText) {
                        pages.push(pageText);
                    }
                }

                const textLayerResult = pages.join('\n\n').trim();
                if (textLayerResult.length >= 120) {
                    return textLayerResult;
                }

                statusText.textContent = 'PDF terdeteksi sebagai scan/image. Menjalankan OCR...';
                const ocrResult = await extractPdfTextWithOCR(file);
                return ocrResult.trim();
            };

            const extractDocxText = async (file) => {
                if (!window.mammoth?.extractRawText) {
                    throw new Error('DOCX parser belum tersedia.');
                }

                const fileBuffer = await file.arrayBuffer();
                const result = await window.mammoth.extractRawText({ arrayBuffer: fileBuffer });
                return (result.value || '').trim();
            };

            const extractTextFromFile = async (file) => {
                const extension = detectExtension(file?.name);

                if (textLikeExtensions.includes(extension)) {
                    return (await file.text()).trim();
                }

                if (extension === '.pdf') {
                    return (await extractPdfText(file)).trim();
                }

                if (extension === '.docx') {
                    return (await extractDocxText(file)).trim();
                }

                if (imageExtensions.includes(extension)) {
                    return (await extractImageTextWithOCR(file)).trim();
                }

                if (extension === '.doc') {
                    throw new Error('Format .doc belum didukung otomatis. Silakan simpan ke PDF/DOCX atau paste teks CV.');
                }

                throw new Error('Format file belum didukung. Gunakan PDF, DOCX, TXT, MD, RTF, atau gambar (PNG/JPG/WEBP).');
            };

            const summarizeOverview = (aspects) => {
                if (!aspects.length) {
                    topStrength.textContent = '-';
                    mainGap.textContent = '-';
                    aspectCount.textContent = '0 aspek';
                    priorityAction.textContent = '-';
                    return;
                }

                const sorted = [...aspects].sort((a, b) => Number(b.score || 0) - Number(a.score || 0));
                const strongest = sorted[0];
                const weakest = sorted[sorted.length - 1];
                const firstAction = Array.isArray(weakest.action_points) && weakest.action_points.length
                    ? weakest.action_points[0]
                    : weakest.analysis || '-';

                topStrength.textContent = `${strongest.name} (${Number(strongest.score || 0)}%)`;
                mainGap.textContent = `${weakest.name} (${Number(weakest.score || 0)}%)`;
                aspectCount.textContent = `${aspects.length} aspek`;
                priorityAction.textContent = firstAction;
            };

            const renderBulletedList = (target, items, fallback = 'Belum ada data.') => {
                const normalized = Array.isArray(items) ? items.filter(Boolean) : [];
                target.innerHTML = normalized.length
                    ? normalized.map((item) => `<li>- ${escapeHtml(item)}</li>`).join('')
                    : `<li>- ${escapeHtml(fallback)}</li>`;
            };

            const resetJobMatchView = () => {
                jobMatchScoreBadge.textContent = 'Belum dianalisis';
                jobMatchScoreBadge.className = 'badge bg-light border text-dark';
                jobMatchSummary.textContent = 'Tambahkan job description untuk melihat kecocokan CV terhadap target role.';
                renderBulletedList(jobMatchGaps, [], 'Belum ada gap yang dihitung.');
                renderBulletedList(jobMatchKeywords, [], 'Belum ada keyword rekomendasi.');
                renderBulletedList(jobMatchImprovements, [], 'Belum ada prioritas improvement.');
            };

            const renderJobMatch = (jobTargeted) => {
                if (!jobTargeted || Number.isNaN(Number(jobTargeted.match_score))) {
                    resetJobMatchView();
                    return;
                }

                const score = Math.max(0, Math.min(100, Number(jobTargeted.match_score)));
                const tone = scoreTone(score);
                const toneStyles = {
                    emerald: 'badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                    amber: 'badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                    rose: 'badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'
                };

                jobMatchScoreBadge.className = toneStyles[tone];
                jobMatchScoreBadge.textContent = `Match Score ${score}%`;
                jobMatchSummary.textContent = jobTargeted.summary || 'Ringkasan kecocokan belum tersedia.';
                renderBulletedList(jobMatchGaps, jobTargeted.key_gaps, 'Tidak ada gap utama terdeteksi.');
                renderBulletedList(jobMatchKeywords, jobTargeted.suggested_keywords, 'Tidak ada keyword tambahan.');
                renderBulletedList(jobMatchImprovements, jobTargeted.priority_improvements, 'Tidak ada prioritas tambahan.');
            };

            const ensureJsPdfLoaded = () => Boolean(window.jspdf?.jsPDF);

            const wrapTextForPdf = (doc, text, maxWidth) => {
                const safeText = String(text || '-');
                return doc.splitTextToSize(safeText, maxWidth);
            };

            const addPdfParagraph = (doc, text, y, options = {}) => {
                const maxWidth = options.maxWidth || 180;
                const x = options.x || 15;
                const lineHeight = options.lineHeight || 5.2;
                const lines = wrapTextForPdf(doc, text, maxWidth);
                doc.text(lines, x, y);
                return y + (lines.length * lineHeight) + 2;
            };

            const formatDateTime = (date) => {
                try {
                    return new Intl.DateTimeFormat('id-ID', {
                        dateStyle: 'medium',
                        timeStyle: 'short',
                    }).format(date);
                } catch (_) {
                    return date.toLocaleString();
                }
            };

            const formatBulletItems = (items) => {
                const normalized = Array.isArray(items) ? items.filter(Boolean) : [];
                return normalized.length ? normalized : ['-'];
            };

            const slugify = (text) => String(text || '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .slice(0, 40) || 'candidate';

            const inferCandidateName = (rawCvText) => {
                const lines = String(rawCvText || '')
                    .split('\n')
                    .map((line) => line.trim())
                    .filter(Boolean)
                    .slice(0, 18);

                const ignored = /^(curriculum vitae|cv|resume|profil|profile|ringkasan|summary|contact|kontak|experience|pengalaman|education|pendidikan)$/i;
                const emailLike = /@/;
                const phoneLike = /(\+?\d[\d\s().-]{7,})/;

                for (const line of lines) {
                    if (line.length < 3 || line.length > 50) continue;
                    if (ignored.test(line)) continue;
                    if (emailLike.test(line) || phoneLike.test(line)) continue;
                    if (!/^[a-zA-Z][a-zA-Z\s'.-]+$/.test(line)) continue;
                    const words = line.split(/\s+/).filter(Boolean);
                    if (words.length < 2 || words.length > 4) continue;
                    return line.replace(/\s+/g, ' ').trim();
                }

                return 'Candidate';
            };

            let pdfLogoDataUrlPromise = null;
            const getPdfLogoDataUrl = async () => {
                if (pdfLogoDataUrlPromise) {
                    return await pdfLogoDataUrlPromise;
                }

                pdfLogoDataUrlPromise = (async () => {
                    try {
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        const loaded = await new Promise((resolve, reject) => {
                            img.onload = () => resolve(true);
                            img.onerror = () => reject(new Error('Logo load failed'));
                            img.src = '/image/cariloker.png';
                        });
                        if (!loaded) {
                            return null;
                        }

                        const canvas = document.createElement('canvas');
                        canvas.width = 64;
                        canvas.height = 64;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, 64, 64);
                        return canvas.toDataURL('image/png');
                    } catch (_) {
                        return null;
                    }
                })();

                return await pdfLogoDataUrlPromise;
            };

            const addPdfSectionHeading = (doc, title, y) => {
                doc.setFillColor(239, 246, 255);
                doc.roundedRect(12, y - 5, 186, 9, 2, 2, 'F');
                doc.setTextColor(30, 64, 175);
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(11);
                doc.text(String(title || ''), 15, y + 1);
                doc.setTextColor(15, 23, 42);
                return y + 10;
            };

            const addPageHeaderFooter = (doc, pageNumber, totalPages, logoDataUrl = null) => {
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                doc.setFillColor(30, 64, 175);
                doc.rect(0, 0, pageWidth, 9, 'F');
                if (logoDataUrl) {
                    doc.addImage(logoDataUrl, 'PNG', 4.5, 1.4, 5.8, 5.8);
                }
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(9);
                doc.setFont('helvetica', 'bold');
                doc.text('Cari Loker - Bedah CV Gratis', logoDataUrl ? 12 : 14, 6);
                doc.setTextColor(100, 116, 139);
                doc.setFontSize(9);
                doc.setFont('helvetica', 'normal');
                doc.text(`Page ${pageNumber}/${totalPages}`, pageWidth - 28, pageHeight - 6);
                doc.text('Generated by AI CV ATS Checker', 14, pageHeight - 6);
                doc.setTextColor(15, 23, 42);
            };

            const addPageWatermark = (doc, watermarkText) => {
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                const safeText = String(watermarkText || 'Cari Loker').slice(0, 48);
                doc.setTextColor(230, 236, 244);
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(34);
                doc.text(safeText, pageWidth / 2, pageHeight / 2, { align: 'center', angle: 35 });
                doc.setFontSize(22);
                doc.text('CONFIDENTIAL CV REPORT', pageWidth / 2, (pageHeight / 2) + 18, { align: 'center', angle: 35 });
                doc.setTextColor(15, 23, 42);
            };

            const ensurePdfSpace = (doc, y, neededHeight, onNewPage) => {
                const maxY = 274;
                if (y + neededHeight <= maxY) {
                    return y;
                }
                doc.addPage();
                const nextY = 16;
                if (typeof onNewPage === 'function') {
                    onNewPage();
                }
                return nextY;
            };

            const getPdfScoreToneStyle = (score) => {
                const numeric = Number(score || 0);
                if (numeric >= 80) return { bg: [220, 252, 231], text: [22, 101, 52] };
                if (numeric >= 65) return { bg: [254, 243, 199], text: [146, 64, 14] };
                return { bg: [255, 228, 230], text: [159, 18, 57] };
            };

            const downloadAnalysisPdf = async () => {
                if (!latestAnalysis) {
                    statusText.textContent = 'Belum ada hasil analisis untuk diunduh.';
                    return;
                }

                if (!ensureJsPdfLoaded()) {
                    statusText.textContent = 'PDF generator belum siap. Refresh halaman lalu coba lagi.';
                    return;
                }

                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF({ unit: 'mm', format: 'a4' });
                    const logoDataUrl = await getPdfLogoDataUrl();
                    const candidateName = inferCandidateName(latestCvText);
                    const reportTitle = `CV Analysis Report - ${candidateName}`;
                    const watermarkText = `Cari Loker - ${candidateName}`;
                    const now = new Date();
                    const reportDate = formatDateTime(now);
                    let y = 16;
                    statusText.textContent = 'Menyusun laporan PDF...';

                // Executive summary page
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(18);
                doc.text(reportTitle, 15, y);
                y += 8;
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                doc.setTextColor(71, 85, 105);
                doc.text(`Generated: ${reportDate}`, 15, y);
                doc.text(`Overall Score: ${latestAnalysis.overall_score ?? '-'}%`, 120, y);
                doc.setTextColor(15, 23, 42);
                y += 9;

                const aspectsForSummary = Array.isArray(latestAnalysis.aspects) ? latestAnalysis.aspects : [];
                const sortedAspects = [...aspectsForSummary].sort((a, b) => Number(b.score || 0) - Number(a.score || 0));
                const strongest = sortedAspects[0];
                const weakest = sortedAspects[sortedAspects.length - 1];
                const topKeywords = formatBulletItems(latestAnalysis.keywords_recommendation).slice(0, 6);
                const summaryBullets = [
                    strongest ? `Top strength: ${strongest.name} (${Number(strongest.score || 0)}%)` : null,
                    weakest ? `Main gap: ${weakest.name} (${Number(weakest.score || 0)}%)` : null,
                    `Total reviewed aspects: ${aspectsForSummary.length}`,
                ].filter(Boolean);

                y = addPdfSectionHeading(doc, 'Executive Summary', y);
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                for (const bullet of summaryBullets) {
                    y = addPdfParagraph(doc, `- ${bullet}`, y, { maxWidth: 172, x: 17, lineHeight: 5 });
                }

                const jobSummary = latestAnalysis.job_targeted;
                if (jobSummary && Number.isFinite(Number(jobSummary.match_score)) && Number(jobSummary.match_score) >= 0) {
                    y = ensurePdfSpace(doc, y, 16);
                    const jobTone = getPdfScoreToneStyle(Number(jobSummary.match_score));
                    doc.setFillColor(...jobTone.bg);
                    doc.roundedRect(15, y - 4.5, 52, 8, 2, 2, 'F');
                    doc.setTextColor(...jobTone.text);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`Job Match ${Number(jobSummary.match_score)}%`, 18, y + 0.8);
                    doc.setTextColor(15, 23, 42);
                    y += 8;
                    doc.setFont('helvetica', 'normal');
                    y = addPdfParagraph(doc, `- ${jobSummary.summary || 'Ringkasan job match belum tersedia.'}`, y, { maxWidth: 172, x: 17, lineHeight: 5 });
                }

                y = ensurePdfSpace(doc, y, 16);
                y = addPdfSectionHeading(doc, 'Priority Keywords', y);
                doc.setFont('helvetica', 'normal');
                topKeywords.forEach((keyword) => {
                    y = addPdfParagraph(doc, `- ${keyword}`, y, { maxWidth: 172, x: 17, lineHeight: 5 });
                });

                // Detailed report starts on new page
                doc.addPage();
                y = 16;
                y = addPdfSectionHeading(doc, 'Overall Impression', y);
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                y = addPdfParagraph(doc, latestAnalysis.overall_impression, y, { maxWidth: 176, x: 15, lineHeight: 5.1 });

                const job = latestAnalysis.job_targeted;
                if (job && Number.isFinite(Number(job.match_score)) && Number(job.match_score) >= 0) {
                    y = ensurePdfSpace(doc, y, 42);
                    y = addPdfSectionHeading(doc, 'Job-targeted Match', y);
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(10);
                    doc.text(`Match Score: ${Number(job.match_score)}%`, 15, y);
                    y += 5;
                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(10);
                    y = addPdfParagraph(doc, `Summary: ${job.summary || '-'}`, y, { maxWidth: 176, x: 15, lineHeight: 5.1 });

                    const gaps = formatBulletItems(job.key_gaps);
                    const keywords = formatBulletItems(job.suggested_keywords);
                    const improvements = formatBulletItems(job.priority_improvements);
                    const grouped = [
                        ['Key Gaps', gaps],
                        ['Suggested Keywords', keywords],
                        ['Priority Improvements', improvements],
                    ];

                    for (const [title, items] of grouped) {
                        y = ensurePdfSpace(doc, y, 18);
                        doc.setFont('helvetica', 'bold');
                        doc.text(String(title), 15, y);
                        y += 5;
                        doc.setFont('helvetica', 'normal');
                        for (const item of items) {
                            y = ensurePdfSpace(doc, y, 8);
                            y = addPdfParagraph(doc, `- ${item}`, y, { maxWidth: 172, x: 17, lineHeight: 5 });
                        }
                    }
                }

                const aspects = Array.isArray(latestAnalysis.aspects) ? latestAnalysis.aspects : [];
                y = ensurePdfSpace(doc, y, 14);
                y = addPdfSectionHeading(doc, `Detailed Aspect Review (${aspects.length})`, y);

                for (const aspect of aspects) {
                    y = ensurePdfSpace(doc, y, 34);
                    doc.setDrawColor(226, 232, 240);
                    doc.roundedRect(14, y - 4, 182, 28, 2, 2, 'S');
                    doc.setFontSize(11);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`${aspect.name || 'Aspect'}`, 15, y);
                    const score = Number(aspect.score || 0);
                    const toneStyle = getPdfScoreToneStyle(score);
                    doc.setFillColor(...toneStyle.bg);
                    doc.roundedRect(160, y - 3.7, 30, 7, 2, 2, 'F');
                    doc.setTextColor(...toneStyle.text);
                    doc.setFontSize(10);
                    doc.text(`${score}%`, 171, y + 1);
                    doc.setTextColor(15, 23, 42);
                    y += 5;
                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'normal');
                    y = addPdfParagraph(doc, aspect.analysis || '-', y, { maxWidth: 176, x: 15, lineHeight: 5.1 });

                    const actionPoints = formatBulletItems(aspect.action_points);
                    y = ensurePdfSpace(doc, y, 10);
                    doc.setFont('helvetica', 'bold');
                    doc.text('Action Points:', 15, y);
                    y += 5;
                    doc.setFont('helvetica', 'normal');
                    for (const point of actionPoints.slice(0, 3)) {
                        y = ensurePdfSpace(doc, y, 7);
                        y = addPdfParagraph(doc, `- ${point}`, y, { maxWidth: 172, x: 17, lineHeight: 4.8 });
                    }
                    y += 2;
                }

                    const totalPages = doc.getNumberOfPages();
                    for (let page = 1; page <= totalPages; page += 1) {
                        doc.setPage(page);
                        addPageHeaderFooter(doc, page, totalPages, logoDataUrl);
                    }

                    const fileName = `cv-analysis-${slugify(candidateName)}-${now.toISOString().slice(0, 10)}.pdf`;
                    doc.save(fileName);
                    statusText.textContent = 'Laporan PDF berhasil diunduh.';
                } catch (error) {
                    statusText.textContent = `Gagal membuat PDF: ${error?.message || 'unknown error'}`;
                }
            };

            const parseModelJson = (raw) => {
                if (!raw) {
                    return null;
                }

                if (typeof raw === 'object') {
                    if (typeof raw.message?.content === 'string') {
                        raw = raw.message.content;
                    } else if (Array.isArray(raw.message?.content)) {
                        raw = raw.message.content
                            .map((part) => part?.text || part?.content || '')
                            .join('\n')
                            .trim();
                    } else if (typeof raw.text === 'string') {
                        raw = raw.text;
                    } else {
                        raw = JSON.stringify(raw);
                    }
                }

                const text = String(raw).trim();

                try {
                    return JSON.parse(text);
                } catch (_) {
                    const match = text.match(/\{[\s\S]*\}/);
                    if (!match) {
                        return null;
                    }
                    try {
                        return JSON.parse(match[0]);
                    } catch (_) {
                        return null;
                    }
                }
            };

            const getReadableErrorMessage = (error) => {
                if (!error) {
                    return 'unknown error';
                }

                if (typeof error === 'string') {
                    return error;
                }

                if (typeof error.message === 'string' && error.message.trim()) {
                    return error.message;
                }

                if (typeof error.error?.message === 'string' && error.error.message.trim()) {
                    return error.error.message;
                }

                if (typeof error.response?.data?.error?.message === 'string' && error.response.data.error.message.trim()) {
                    return error.response.data.error.message;
                }

                try {
                    const serialized = JSON.stringify(error);
                    return serialized && serialized !== '{}' ? serialized : 'unknown error';
                } catch (_) {
                    return 'unknown error';
                }
            };

            const scoreTone = (score) => {
                if (score >= 80) return 'emerald';
                if (score >= 65) return 'amber';
                return 'rose';
            };

            tabButtons.forEach((button) => {
                button.addEventListener('click', () => setActiveTab(button.dataset.tabTarget));
            });

            downloadPdfBtn.addEventListener('click', downloadAnalysisPdf);

            // ── ATS CV Builder PDF ────────────────────────────────────────────
            const parseCvSections = (rawText) => {
                const sectionKeywords = [
                    'summary', 'ringkasan', 'profil', 'profile', 'objective', 'tujuan',
                    'experience', 'pengalaman', 'work history', 'riwayat kerja', 'employment',
                    'education', 'pendidikan', 'riwayat pendidikan',
                    'skill', 'keahlian', 'kemampuan', 'kompetensi', 'technical',
                    'achievement', 'pencapaian', 'prestasi',
                    'certification', 'sertifikasi', 'sertifikat', 'license',
                    'organization', 'organisasi', 'aktivitas', 'activity',
                    'language', 'bahasa',
                    'project', 'proyek',
                    'award', 'penghargaan',
                    'interest', 'hobi', 'hobby',
                    'contact', 'kontak', 'informasi',
                    'volunteer', 'sukarela',
                    'reference', 'referensi',
                ];
                const lines = rawText.split('\n').map(l => l.trimEnd()).filter(l => l.trim());
                const sections = [];
                let current = null;

                for (const line of lines) {
                    const stripped = line.trim();
                    if (!stripped) continue;
                    const lower = stripped.toLowerCase();
                    const isHeading = stripped.length < 60 &&
                        sectionKeywords.some(kw => lower.startsWith(kw) || lower === kw) &&
                        !/^[-•*\d]/.test(stripped);

                    if (isHeading && current !== null) {
                        sections.push({ heading: current.heading, lines: current.lines });
                        current = { heading: stripped, lines: [] };
                    } else if (isHeading) {
                        current = { heading: stripped, lines: [] };
                    } else if (current) {
                        current.lines.push(stripped);
                    } else {
                        if (!sections.length) sections.push({ heading: '__HEADER__', lines: [] });
                        sections[0].lines.push(stripped);
                    }
                }
                if (current) sections.push({ heading: current.heading, lines: current.lines });
                return sections;
            };

            const downloadAtsCvPdf = async () => {
                if (!latestAnalysis || !latestCvText) {
                    statusText.textContent = 'Harap analisis CV terlebih dahulu sebelum mengunduh ATS Builder.';
                    return;
                }
                if (!ensureJsPdfLoaded()) {
                    statusText.textContent = 'PDF generator belum siap. Refresh halaman lalu coba lagi.';
                    return;
                }

                try {
                    statusText.textContent = 'Menyusun CV ATS Builder PDF…';
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF({ unit: 'mm', format: 'a4' });
                    const pageW = doc.internal.pageSize.getWidth();
                    const pageH = doc.internal.pageSize.getHeight();
                    const marginL = 18;
                    const marginR = 18;
                    const contentW = pageW - marginL - marginR;
                    const maxY = pageH - 18;
                    let y = 20;

                    const checkPage = (needed = 8) => {
                        if (y + needed > maxY) {
                            doc.addPage();
                            y = 20;
                        }
                    };

                    const writeLine = (text, opts = {}) => {
                        const { fontSize = 10, bold = false, color = [20, 20, 20], indent = 0 } = opts;
                        doc.setFontSize(fontSize);
                        doc.setFont('helvetica', bold ? 'bold' : 'normal');
                        doc.setTextColor(...color);
                        const lines = doc.splitTextToSize(String(text || ''), contentW - indent);
                        checkPage(lines.length * (fontSize * 0.4) + 2);
                        doc.text(lines, marginL + indent, y);
                        y += lines.length * (fontSize * 0.38) + 2;
                    };

                    const writeSection = (heading) => {
                        checkPage(14);
                        y += 3;
                        // Accent bar
                        doc.setFillColor(13, 110, 253);
                        doc.rect(marginL, y - 4.5, 3.5, 7.5, 'F');
                        doc.setFontSize(11.5);
                        doc.setFont('helvetica', 'bold');
                        doc.setTextColor(13, 110, 253);
                        doc.text(String(heading).toUpperCase(), marginL + 6, y + 0.5);
                        y += 5;
                        doc.setDrawColor(190, 210, 255);
                        doc.setLineWidth(0.4);
                        doc.line(marginL, y, pageW - marginR, y);
                        y += 4;
                        doc.setTextColor(20, 20, 20);
                    };

                    const candidateName = inferCandidateName(latestCvText);
                    const keywords = Array.isArray(latestAnalysis.keywords_recommendation)
                        ? latestAnalysis.keywords_recommendation.filter(Boolean)
                        : [];
                    const careerRec = latestAnalysis.career_recommendation || '';

                    // ── Header block ─────────────────────────────────────────
                    doc.setFillColor(13, 110, 253);
                    doc.rect(0, 0, pageW, 28, 'F');
                    doc.setFontSize(20);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(255, 255, 255);
                    doc.text(candidateName, marginL, 14);
                    if (careerRec) {
                        const roleLines = doc.splitTextToSize(careerRec, contentW);
                        doc.setFontSize(9.5);
                        doc.setFont('helvetica', 'normal');
                        doc.setTextColor(190, 220, 255);
                        doc.text(roleLines[0], marginL, 22);
                    }
                    y = 36;

                    // ── ATS note ─────────────────────────────────────────────
                    doc.setFontSize(7.5);
                    doc.setFont('helvetica', 'italic');
                    doc.setTextColor(140, 140, 140);
                    doc.text('ATS-Optimized CV  |  Generated by PaskerID CV Reviewer', marginL, y);
                    y += 7;

                    // ── Parse sections from raw CV text ──────────────────────
                    const sections = parseCvSections(latestCvText);

                    for (const section of sections) {
                        if (section.heading === '__HEADER__') {
                            // First lines before any section heading — treat as contact/intro
                            for (const line of section.lines.slice(0, 8)) {
                                writeLine(line, { fontSize: 9.5, color: [55, 55, 55] });
                            }
                            y += 2;
                        } else {
                            writeSection(section.heading);
                            for (const line of section.lines) {
                                const isBullet = /^[-•*]/.test(line);
                                writeLine(
                                    isBullet ? line : line,
                                    { fontSize: 9.8, indent: isBullet ? 3 : 0 }
                                );
                            }
                        }
                    }

                    // ── ATS Keywords section ─────────────────────────────────
                    if (keywords.length) {
                        writeSection('ATS Keywords (Recommended)');
                        const kwText = keywords.join('  •  ');
                        const kwLines = doc.splitTextToSize(kwText, contentW);
                        doc.setFontSize(9.5);
                        doc.setFont('helvetica', 'normal');
                        doc.setTextColor(20, 20, 20);
                        checkPage(kwLines.length * 5 + 4);
                        doc.text(kwLines, marginL, y);
                        y += kwLines.length * 5 + 4;
                    }

                    // ── Footer on all pages ──────────────────────────────────
                    const totalPages = doc.getNumberOfPages();
                    for (let p = 1; p <= totalPages; p++) {
                        doc.setPage(p);
                        doc.setFontSize(8);
                        doc.setFont('helvetica', 'normal');
                        doc.setTextColor(160, 160, 160);
                        doc.text(`Page ${p} / ${totalPages}`, pageW - marginR, pageH - 8, { align: 'right' });
                        doc.text('PaskerID CV ATS Builder', marginL, pageH - 8);
                    }

                    const now = new Date();
                    const fileName = `ats-cv-${slugify(candidateName)}-${now.toISOString().slice(0, 10)}.pdf`;
                    doc.save(fileName);
                    statusText.textContent = 'CV ATS Builder berhasil diunduh!';
                } catch (err) {
                    statusText.textContent = `Gagal membuat ATS PDF: ${err?.message || 'unknown error'}`;
                }
            };

            downloadAtsBtn.addEventListener('click', downloadAtsCvPdf);

            resetScoreVisual();
            resetJobMatchView();
            setActiveTab('tab-overview');

            const renderAspect = (aspect, index) => {
                const score = Number(aspect.score || 0);
                const tone = scoreTone(score);
                const toneStyles = {
                    emerald: 'bg-success bg-opacity-10 text-success border-success border-opacity-25',
                    amber: 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25',
                    rose: 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25'
                };
                const toneBarStyles = {
                    emerald: 'bg-success',
                    amber: 'bg-warning',
                    rose: 'bg-danger'
                };
                const actionPoints = Array.isArray(aspect.action_points) ? aspect.action_points : [];
                const aspectId = `${String(aspect.name || 'aspect').toLowerCase().replaceAll(/[^a-z0-9]+/g, '-')}-${index}`;

                return `
                    <div class="col-12">
                        <article class="card border-0 shadow-sm p-4 rounded-4 h-100">
                            <button type="button" class="aspect-toggle btn btn-link text-decoration-none p-0 d-flex w-100 align-items-start justify-content-between gap-3 text-start border-0 shadow-none" data-target="${escapeHtml(aspectId)}">
                                <h3 class="h6 fw-bold text-dark mb-0">${escapeHtml(aspect.name || 'Aspect')}</h3>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill px-3 py-2 border ${toneStyles[tone]}">${escapeHtml(score)}%</span>
                                    <i class="fa-solid fa-chevron-down small text-muted transition-transform aspect-icon"></i>
                                </div>
                            </button>
                            <div class="mt-3 progress" style="height: 8px;">
                                <div class="progress-bar ${toneBarStyles[tone]}" role="progressbar" style="width: ${Math.max(0, Math.min(100, score))}%"></div>
                            </div>
                            <div id="${escapeHtml(aspectId)}" class="aspect-panel mt-3">
                                <p class="small text-secondary mb-0">${escapeHtml(aspect.analysis || '')}</p>
                                ${actionPoints.length ? `<div class="mt-3">
                                <p class="small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.12em;">Action Points</p>
                                <ul class="small text-secondary ps-3 mb-0">
                                    ${actionPoints.map((point) => `<li class="mb-1">${escapeHtml(point)}</li>`).join('')}
                                </ul>
                                </div>` : ''}
                                ${aspect.why_important ? `<p class="mt-3 small text-muted mb-0"><strong class="text-dark">Why it matters:</strong> ${escapeHtml(aspect.why_important)}</p>` : ''}
                            </div>
                        </article>
                    </div>
                `;
            };

            const initExpandableCards = () => {
                const toggles = aspectsGrid.querySelectorAll('.aspect-toggle');
                toggles.forEach((toggle) => {
                    const targetId = toggle.dataset.target;
                    const panel = document.getElementById(targetId);
                    const icon = toggle.querySelector('.aspect-icon');
                    if (!panel || !icon) {
                        return;
                    }

                    const openPanel = () => {
                        panel.classList.add('open');
                        panel.style.maxHeight = `${panel.scrollHeight}px`;
                        icon.classList.add('rotate-180');
                    };

                    const closePanel = () => {
                        panel.style.maxHeight = '0px';
                        panel.classList.remove('open');
                        icon.classList.remove('rotate-180');
                    };

                    // Default to expanded for better result visibility.
                    openPanel();

                    toggle.addEventListener('click', () => {
                        const isOpen = panel.classList.contains('open');
                        if (isOpen) {
                            closePanel();
                        } else {
                            openPanel();
                        }
                    });
                });
            };

            window.addEventListener('resize', () => {
                document.querySelectorAll('.aspect-panel.open').forEach((panel) => {
                    panel.style.maxHeight = `${panel.scrollHeight}px`;
                });
            });

            fileInput.addEventListener('change', async (event) => {
                const file = event.target.files?.[0];
                if (!file) {
                    return;
                }

                try {
                    statusText.textContent = 'Membaca file CV...';
                    fileHelp.textContent = 'Sistem sedang mengekstrak teks CV dari file Anda.';
                    const extractedText = await extractTextFromFile(file);

                    extractedFromFile = extractedText;
                    extractedFileName = file.name;

                    if (extractedText.length < 80) {
                        statusText.textContent = 'Teks dari file terlalu sedikit. Silakan cek file atau paste isi CV manual.';
                        fileHelp.textContent = 'Teks berhasil diekstrak namun sangat singkat. Paste isi CV jika hasil belum lengkap.';
                        return;
                    }

                    cvText.value = extractedText;
                    statusText.textContent = `Teks CV berhasil dibaca dari ${file.name}.`;
                    fileHelp.textContent = 'Teks CV sudah terisi otomatis. Anda tetap bisa edit sebelum review.';
                } catch (error) {
                    extractedFromFile = '';
                    extractedFileName = '';
                    statusText.textContent = error.message || 'Gagal membaca file. Silakan paste isi CV secara manual.';
                    fileHelp.textContent = 'Ekstraksi otomatis gagal. Paste isi CV di kolom teks untuk lanjut review.';
                }
            });

            clearBtn.addEventListener('click', () => {
                fileInput.value = '';
                cvText.value = '';
                jobDescriptionInput.value = '';
                extractedFromFile = '';
                extractedFileName = '';
                latestAnalysis = null;
                latestCvText = '';
                statusText.textContent = '';
                resultWrapper.classList.add('d-none');
                sampleState.classList.remove('d-none');
                aspectsGrid.innerHTML = '';
                keywordsList.innerHTML = '';
                overallImpression.textContent = '';
                overallScore.textContent = '0%';
                scoreRingProgress.style.strokeDashoffset = `${ringCircumference}`;
                resetScoreVisual();
                careerRecommendation.textContent = '';
                topStrength.textContent = '-';
                mainGap.textContent = '-';
                aspectCount.textContent = '0 aspek';
                priorityAction.textContent = '-';
                resetJobMatchView();
                fileHelp.textContent = 'File teks, PDF, DOCX, dan gambar CV akan dicoba dibaca otomatis (termasuk OCR untuk scan). Jika gagal, paste isi CV di kolom bawah.';
                setActiveTab('tab-overview');
            });

            reviewBtn.addEventListener('click', async () => {
                if (!window.puter?.ai?.chat) {
                    statusText.textContent = 'Puter API belum siap. Refresh halaman lalu coba lagi.';
                    return;
                }

                setLoading(true);
                statusText.textContent = 'Menyiapkan CV untuk analisis...';

                let text = cvText.value.trim();
                const currentFile = fileInput.files?.[0];

                if (!text && currentFile) {
                    try {
                        if (!extractedFromFile || extractedFileName !== currentFile.name) {
                            extractedFromFile = await extractTextFromFile(currentFile);
                            extractedFileName = currentFile.name;
                        }
                        text = extractedFromFile.trim();
                        if (text) {
                            cvText.value = text;
                        }
                    } catch (error) {
                        statusText.textContent = error.message || 'Gagal membaca isi file CV. Silakan paste teks CV manual.';
                        setLoading(false);
                        return;
                    }
                }

                if (!text || text.length < 80) {
                    statusText.textContent = 'Isi CV terlalu singkat atau belum terbaca. Tambahkan detail CV sebelum dianalisis.';
                    setLoading(false);
                    return;
                }

                const jobDescription = jobDescriptionInput.value.trim();
                statusText.textContent = 'Mengirim CV ke AI untuk dianalisis...';

                const prompt = `
You are an expert ATS CV reviewer.
Language for response: ${language.value === 'id' ? 'Bahasa Indonesia' : 'English'}.
Review purpose: ${purpose.value}.
Job description is ${jobDescription ? 'provided' : 'not provided'}.

Analyze the following CV text and return STRICT JSON only with this schema:
{
  "overall_score": number (0-100),
  "overall_impression": "string",
  "aspects": [
    {
      "name": "Overall Impression | Contact Information | Relevant Skill | Professional Summary | Work Experience | Achievement | Education and Certification | Organizational Activity | Consistent and Error-free Writing | Additional Section | Keywords | Career Recommendation",
      "score": number (0-100),
      "analysis": "string",
      "action_points": ["string", "string"],
      "why_important": "string"
    }
  ],
  "keywords_recommendation": ["string", "string"],
  "career_recommendation": "string",
  "job_targeted": {
    "match_score": number (0-100),
    "summary": "string",
    "key_gaps": ["string", "string"],
    "suggested_keywords": ["string", "string"],
    "priority_improvements": ["string", "string"]
  }
}

Important rules:
- Return exactly 12 aspects, matching all names listed above.
- Give practical, concrete action points.
- Keep analysis concise but insightful.
- JSON only, no markdown.
- If job description is missing, return job_targeted with match_score = -1 and short explanation in summary.

CV TEXT:
${text}

JOB DESCRIPTION:
${jobDescription || '(Not provided)'}
                `.trim();

                try {
                    let response;
                    try {
                        response = await window.puter.ai.chat(prompt, {
                            model: 'gpt-4o',
                            temperature: 0.2,
                            max_tokens: 2600
                        });
                    } catch (firstError) {
                        // Retry once for transient provider-side failures.
                        statusText.textContent = 'Permintaan pertama gagal, mencoba ulang...';
                        response = await window.puter.ai.chat(prompt, {
                            model: 'gpt-4o',
                            temperature: 0.2,
                            max_tokens: 2200
                        });
                    }

                    const parsed = parseModelJson(response);
                    if (!parsed) {
                        throw new Error('Model response is not valid JSON.');
                    }

                    latestAnalysis = parsed;
                    latestCvText = text;

                    const totalScore = Number(parsed.overall_score || 0);
                    animateScore(totalScore);
                    overallImpression.textContent = parsed.overall_impression || '-';

                    const aspectMap = new Map((Array.isArray(parsed.aspects) ? parsed.aspects : []).map((item) => [item.name, item]));
                    const orderedAspects = expectedAspects.map((name) => aspectMap.get(name)).filter(Boolean);
                    const finalAspects = orderedAspects.length ? orderedAspects : (Array.isArray(parsed.aspects) ? parsed.aspects : []);

                    aspectsGrid.innerHTML = finalAspects.map(renderAspect).join('');
                    initExpandableCards();
                    summarizeOverview(finalAspects);

                    const keywords = Array.isArray(parsed.keywords_recommendation) ? parsed.keywords_recommendation : [];
                    keywordsList.innerHTML = keywords.length
                        ? keywords.map((item) => `<li>- ${escapeHtml(item)}</li>`).join('')
                        : '<li>- Tidak ada keyword rekomendasi.</li>';

                    careerRecommendation.textContent = parsed.career_recommendation || '-';
                    renderJobMatch(parsed.job_targeted);

                    resultWrapper.classList.remove('d-none');
                    sampleState.classList.add('d-none');
                    setActiveTab('tab-overview');
                    animatePanels();
                    statusText.textContent = 'Analisis selesai. Silakan cek hasil di bawah.';
                } catch (error) {
                    const readable = getReadableErrorMessage(error);
                    statusText.textContent = `Gagal menganalisis CV: ${readable}`;
                    try {
                        console.error('CV analysis error', error);
                    } catch (_) {
                        // no-op
                    }
                } finally {
                    setLoading(false);
                }
            });
        })();
    </script>
@endsection
