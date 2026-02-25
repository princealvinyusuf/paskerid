@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="p-4 p-md-5 rounded-4 shadow-sm text-white mb-4" style="background: linear-gradient(135deg, #187C19 0%, #00A38A 100%);">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h1 class="h3 fw-bold mb-2">Career Boost Day : Curhat Peluang Kerja</h1>
                        <div class="opacity-90">Unlock peluang kerja dan insight pasar kerja yang relevan buat kamu</div>
                    </div>
                    <div class="text-md-end small opacity-90">
                        <div><i class="fa-solid fa-comments me-1"></i> Konsultasi</div>
                        <div><i class="fa-solid fa-calendar-days me-1"></i> Jadwal</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mb-4">
                <div class="btn-group" role="group" aria-label="Career Boost Day toggle">
                    <a href="{{ route('career-boostday.index', ['tab' => 'form']) }}"
                       class="btn {{ $tab === 'form' ? 'btn-success' : 'btn-outline-success' }}">
                        Form Curhat Peluang Kerja
                    </a>
                    <a href="{{ route('career-boostday.index', ['tab' => 'jadwal']) }}"
                       class="btn {{ $tab === 'jadwal' ? 'btn-success' : 'btn-outline-success' }}">
                        Jadwal Curhat
                    </a>
                    <a href="{{ route('career-boostday.index', ['tab' => 'statistik']) }}"
                       class="btn {{ $tab === 'statistik' ? 'btn-success' : 'btn-outline-success' }}">
                        Statistik
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
            @endif

            @if($tab === 'jadwal')
                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h3 class="h6 fw-bold mb-2"><i class="fa-solid fa-user-check me-2 text-success"></i>Jadwal Curhat Terbooking</h3>
                        <div class="text-muted mb-3">Berikut jadwal curhat yang sudah di-accept.</div>

                        @if(!$bookedFeatureAvailable)
                            <div class="alert alert-warning mb-0">
                                Fitur “Terbooking” belum aktif (tabel/kolom belum tersedia di database).
                            </div>
                        @elseif($bookedKonsultasi->count() === 0)
                            <div class="text-muted">Belum ada jadwal curhat yang terbooking.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 160px;">Tanggal</th>
                                            <th style="width: 140px;">Waktu</th>
                                            <th>Nama (disamarkan)</th>
                                            <th style="width: 180px;">Kontak (disamarkan)</th>
                                            <th style="width: 160px;">Konselor</th>
                                            <th style="width: 220px;">Jenis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookedKonsultasi as $b)
                                            @php $date = \Carbon\Carbon::parse($b->booked_date); @endphp
                                            <tr>
                                                <td class="fw-semibold">{{ $date->format('d M Y') }}</td>
                                                <td>{{ $b->time ?: '-' }}</td>
                                                <td class="fw-semibold">{{ $b->masked_name }}</td>
                                                <td class="fw-semibold">{{ $b->masked_contact ?: '-' }}</td>
                                                <td class="fw-semibold">{{ $b->konselor_name }}</td>
                                                <td>{{ $b->jenis_konseling }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3 d-flex justify-content-end">
                                <button
                                    type="button"
                                    class="btn btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#attendanceConfirmModal"
                                >
                                    Isi Form Konfirmasi Kehadiran
                                </button>
                            </div>

                            <div class="modal fade" id="attendanceConfirmModal" tabindex="-1" aria-labelledby="attendanceConfirmModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('career-boostday.confirm-attendance') }}" id="attendance-confirm-form">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="attendanceConfirmModalLabel">Konfirmasi Kehadiran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($errors->confirmAttendance->any())
                                                    <div class="alert alert-danger">
                                                        <ul class="mb-0">
                                                            @foreach($errors->confirmAttendance->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold" for="consultation_id">Nama (disamarkan)</label>
                                                    <select class="form-select @error('consultation_id', 'confirmAttendance') is-invalid @enderror" id="consultation_id" name="consultation_id" required>
                                                        <option value="" selected disabled>Pilih nama</option>
                                                        @foreach($bookedKonsultasi as $b)
                                                            <option value="{{ $b->id }}" {{ (string) old('consultation_id') === (string) $b->id ? 'selected' : '' }}>
                                                                {{ $b->masked_name }} — {{ \Carbon\Carbon::parse($b->booked_date)->format('d M Y') }}{{ $b->time ? ' (' . $b->time . ')' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-1">
                                                    <label class="form-label fw-semibold" for="phone_confirmation">Konfirmasi Nomor Handphone</label>
                                                    <input
                                                        type="text"
                                                        class="form-control @error('phone_confirmation', 'confirmAttendance') is-invalid @enderror"
                                                        id="phone_confirmation"
                                                        name="phone_confirmation"
                                                        value="{{ old('phone_confirmation') }}"
                                                        placeholder="Masukkan nomor sesuai data pendaftaran"
                                                        required
                                                    >
                                                    <div class="form-text">Nomor harus sama dengan nomor yang terdaftar di database.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success" id="attendance-confirm-submit" disabled>Konfirmasi Hadir</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                                (function () {
                                    var consultationSelect = document.getElementById('consultation_id');
                                    var phoneInput = document.getElementById('phone_confirmation');
                                    var submitBtn = document.getElementById('attendance-confirm-submit');
                                    var modalEl = document.getElementById('attendanceConfirmModal');
                                    var hasConfirmErrors = {{ $errors->confirmAttendance->any() ? 'true' : 'false' }};
                                    var phoneMismatchMessage = @json($errors->confirmAttendance->first('phone_confirmation'));

                                    function syncAttendanceSubmitState() {
                                        if (!submitBtn || !consultationSelect || !phoneInput) return;
                                        var hasConsultation = !!consultationSelect.value;
                                        var hasPhone = String(phoneInput.value || '').trim() !== '';
                                        submitBtn.disabled = !(hasConsultation && hasPhone);
                                    }

                                    function forceShowModalFallback() {
                                        // Fallback when bootstrap JS loads later than this script block.
                                        if (!modalEl) return;
                                        modalEl.classList.add('show');
                                        modalEl.style.display = 'block';
                                        modalEl.removeAttribute('aria-hidden');
                                        modalEl.setAttribute('aria-modal', 'true');
                                        document.body.classList.add('modal-open');
                                    }

                                    function showConfirmModalIfError() {
                                        if (!hasConfirmErrors || !modalEl) return;

                                        if (window.bootstrap && bootstrap.Modal) {
                                            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                                            modal.show();
                                        } else {
                                            forceShowModalFallback();
                                        }

                                        if (phoneMismatchMessage) {
                                            alert(phoneMismatchMessage);
                                        }
                                    }

                                    document.addEventListener('DOMContentLoaded', function () {
                                        if (consultationSelect) consultationSelect.addEventListener('change', syncAttendanceSubmitState);
                                        if (phoneInput) phoneInput.addEventListener('input', syncAttendanceSubmitState);
                                        syncAttendanceSubmitState();
                                        showConfirmModalIfError();
                                    });
                                })();
                            </script>
                        @endif
                    </div>
                </div>
            @elseif($tab === 'statistik')
                @php
                    $total = (int)($stats['totals']['total'] ?? 0);
                    $booked = (int)($stats['totals']['booked'] ?? 0);
                    $withCv = (int)($stats['totals']['with_cv'] ?? 0);
                    $withJurusan = (int)($stats['totals']['with_jurusan'] ?? 0);

                    $cvRate = $total > 0 ? round(($withCv / $total) * 100, 1) : 0;
                    $jurusanRate = $total > 0 ? round(($withJurusan / $total) * 100, 1) : 0;
                @endphp

                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                            <h2 class="h5 fw-bold mb-0">
                                <i class="fa-solid fa-chart-line me-2 text-success"></i>
                                Statistik Career Boost Day
                            </h2>
                            <div class="small text-muted">
                                Data diperbarui otomatis dari tabel konsultasi.
                            </div>
                        </div>

                        @if(!($stats['available'] ?? false))
                            <div class="alert alert-warning mb-0">
                                Data statistik belum tersedia karena tabel konsultasi belum ditemukan.
                            </div>
                        @else
                            <div class="row g-3 mb-1">
                                <div class="col-6 col-lg-4">
                                    <div class="border rounded-3 p-3 h-100 bg-light">
                                        <div class="small text-muted mb-1">Total Pendaftar</div>
                                        <div class="h4 fw-bold mb-0">{{ number_format($total) }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="small text-muted mb-1">Sudah Terbooking</div>
                                        <div class="h5 fw-bold mb-0">{{ number_format($booked) }}</div>
                                        <div class="small text-muted">Jadwal curhat yang sudah dipastikan.</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="small text-muted mb-1">Upload CV</div>
                                        <div class="h5 fw-bold mb-0">{{ number_format($withCv) }}</div>
                                        <div class="small text-muted">{{ $cvRate }}% dari total pendaftar</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="small text-muted mb-1">Isi Jurusan</div>
                                        <div class="h5 fw-bold mb-0">{{ number_format($withJurusan) }}</div>
                                        <div class="small text-muted">{{ $jurusanRate }}% dari total pendaftar</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($stats['available'] ?? false)
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm rounded-4">
                                <div class="card-body p-4">
                                    <h3 class="h6 fw-bold mb-3">Tren Pendaftar per Bulan</h3>
                                    <div style="position: relative; height: 280px;">
                                        <canvas id="cbd-monthly-trend"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card shadow-sm rounded-4 h-100">
                                <div class="card-body p-4">
                                    <h3 class="h6 fw-bold mb-3">Metode Curhat</h3>
                                    <div style="position: relative; height: 260px;">
                                        <canvas id="cbd-jenis-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card shadow-sm rounded-4 h-100">
                                <div class="card-body p-4">
                                    <h3 class="h6 fw-bold mb-3">Pendidikan Terakhir</h3>
                                    <div style="position: relative; height: 260px;">
                                        <canvas id="cbd-pendidikan-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                                <h3 class="h6 fw-bold mb-0">Distribusi Jurusan (Interaktif)</h3>
                                <div class="d-flex flex-column flex-sm-row gap-2">
                                    <input
                                        type="text"
                                        id="jurusan-search"
                                        class="form-control form-control-sm"
                                        style="min-width: 240px;"
                                        placeholder="Cari jurusan..."
                                    >
                                    <select id="jurusan-limit" class="form-select form-select-sm">
                                        <option value="5">Top 5</option>
                                        <option value="10" selected>Top 10</option>
                                        <option value="15">Top 15</option>
                                        <option value="25">Top 25</option>
                                        <option value="0">Semua</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-12 col-xl-7">
                                    <div style="position: relative; height: 320px;">
                                        <canvas id="cbd-jurusan-chart"></canvas>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-5">
                                    <div class="table-responsive" style="max-height: 320px;">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead class="table-light position-sticky top-0">
                                                <tr>
                                                    <th>Jurusan</th>
                                                    <th class="text-end">Total</th>
                                                    <th class="text-end">% Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="jurusan-table-body"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-12 col-lg-6">
                            <div class="card shadow-sm rounded-4 h-100">
                                <div class="card-body p-4">
                                    <h3 class="h6 fw-bold mb-3">Status Karir Peserta</h3>
                                    @if(empty($stats['statusBreakdown']))
                                        <div class="text-muted">Belum ada data status peserta.</div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Status</th>
                                                        <th class="text-end">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($stats['statusBreakdown'] as $row)
                                                        <tr>
                                                            <td>{{ $row['label'] }}</td>
                                                            <td class="text-end fw-semibold">{{ number_format($row['total']) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card shadow-sm rounded-4 h-100">
                                <div class="card-body p-4">
                                    <h3 class="h6 fw-bold mb-3">Jadwal Curhat Paling Diminati</h3>
                                    @if(empty($stats['jadwalBreakdown']))
                                        <div class="text-muted">Belum ada data jadwal.</div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Jadwal</th>
                                                        <th class="text-end">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($stats['jadwalBreakdown'] as $row)
                                                        <tr>
                                                            <td>{{ $row['label'] }}</td>
                                                            <td class="text-end fw-semibold">{{ number_format($row['total']) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        (function () {
                            var stats = @json($stats);
                            var total = Number((stats.totals && stats.totals.total) || 0);

                            function toLabels(rows) {
                                return (rows || []).map(function (r) { return r.label; });
                            }

                            function toValues(rows) {
                                return (rows || []).map(function (r) { return Number(r.total || 0); });
                            }

                            function renderBarChart(id, rows, color) {
                                var el = document.getElementById(id);
                                if (!el || !rows || rows.length === 0) return null;
                                return new Chart(el, {
                                    type: 'bar',
                                    data: {
                                        labels: toLabels(rows),
                                        datasets: [{
                                            data: toValues(rows),
                                            backgroundColor: color
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        animation: false,
                                        plugins: {
                                            legend: { display: false }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: { precision: 0 }
                                            }
                                        }
                                    }
                                });
                            }

                            function renderDoughnutChart(id, rows, colors) {
                                var el = document.getElementById(id);
                                if (!el || !rows || rows.length === 0) return null;
                                return new Chart(el, {
                                    type: 'doughnut',
                                    data: {
                                        labels: toLabels(rows),
                                        datasets: [{
                                            data: toValues(rows),
                                            backgroundColor: colors
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        animation: false,
                                        plugins: {
                                            legend: { position: 'bottom' }
                                        }
                                    }
                                });
                            }

                            var monthlyTrend = stats.monthlyTrend || [];
                            var monthlyEl = document.getElementById('cbd-monthly-trend');
                            if (monthlyEl && monthlyTrend.length > 0) {
                                new Chart(monthlyEl, {
                                    type: 'line',
                                    data: {
                                        labels: toLabels(monthlyTrend),
                                        datasets: [{
                                            label: 'Pendaftar',
                                            data: toValues(monthlyTrend),
                                            borderColor: '#198754',
                                            backgroundColor: 'rgba(25, 135, 84, 0.15)',
                                            fill: true,
                                            tension: 0.25,
                                            pointRadius: 3
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        animation: false,
                                        plugins: { legend: { display: false } },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: { precision: 0 }
                                            }
                                        }
                                    }
                                });
                            }

                            renderBarChart('cbd-jenis-chart', stats.jenisBreakdown || [], '#20c997');
                            renderBarChart('cbd-pendidikan-chart', stats.pendidikanBreakdown || [], '#0dcaf0');

                            var jurusanRows = (stats.jurusanBreakdown || []).slice();
                            var jurusanTableBody = document.getElementById('jurusan-table-body');
                            var jurusanSearch = document.getElementById('jurusan-search');
                            var jurusanLimit = document.getElementById('jurusan-limit');
                            var jurusanChart = null;

                            function destroyJurusanChart() {
                                if (jurusanChart) {
                                    jurusanChart.destroy();
                                    jurusanChart = null;
                                }
                            }

                            function drawJurusanChart(rows) {
                                destroyJurusanChart();
                                var el = document.getElementById('cbd-jurusan-chart');
                                if (!el || rows.length === 0) return;
                                jurusanChart = new Chart(el, {
                                    type: 'bar',
                                    data: {
                                        labels: rows.map(function (r) { return r.label; }),
                                        datasets: [{
                                            label: 'Jumlah',
                                            data: rows.map(function (r) { return Number(r.total || 0); }),
                                            backgroundColor: '#6610f2'
                                        }]
                                    },
                                    options: {
                                        indexAxis: 'y',
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        animation: false,
                                        plugins: { legend: { display: false } },
                                        scales: {
                                            x: { beginAtZero: true, ticks: { precision: 0 } }
                                        }
                                    }
                                });
                            }

                            function renderJurusanTable(rows) {
                                if (!jurusanTableBody) return;
                                if (!rows.length) {
                                    jurusanTableBody.innerHTML = '<tr><td colspan="3" class="text-muted">Data jurusan tidak ditemukan.</td></tr>';
                                    return;
                                }
                                jurusanTableBody.innerHTML = rows.map(function (r) {
                                    var val = Number(r.total || 0);
                                    var pct = total > 0 ? ((val / total) * 100).toFixed(1) : '0.0';
                                    return '<tr>' +
                                        '<td>' + (r.label || '-') + '</td>' +
                                        '<td class="text-end fw-semibold">' + val.toLocaleString('id-ID') + '</td>' +
                                        '<td class="text-end text-muted">' + pct + '%</td>' +
                                        '</tr>';
                                }).join('');
                            }

                            function getFilteredJurusan() {
                                var keyword = (jurusanSearch && jurusanSearch.value ? jurusanSearch.value : '').toLowerCase().trim();
                                var limit = Number(jurusanLimit && jurusanLimit.value ? jurusanLimit.value : 10);

                                var rows = jurusanRows.filter(function (r) {
                                    if (!keyword) return true;
                                    return String(r.label || '').toLowerCase().indexOf(keyword) !== -1;
                                });

                                if (limit > 0) {
                                    rows = rows.slice(0, limit);
                                }
                                return rows;
                            }

                            function renderJurusanWidgets() {
                                var rows = getFilteredJurusan();
                                renderJurusanTable(rows);
                                drawJurusanChart(rows);
                            }

                            if (jurusanSearch) {
                                jurusanSearch.addEventListener('input', renderJurusanWidgets);
                            }
                            if (jurusanLimit) {
                                jurusanLimit.addEventListener('change', renderJurusanWidgets);
                            }
                            renderJurusanWidgets();
                        })();
                    </script>
                @endif
            @else
                <div class="card shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h5 fw-bold mb-1">Form Curhat Peluang Kerja</h2>
                        <div class="text-muted mb-4">Silakan isi data berikut. Tim kami akan menghubungi Anda melalui WhatsApp.</div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <div class="fw-bold mb-2">Mohon periksa kembali isian Anda:</div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('career-boostday.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold" for="name">Nama</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold" for="whatsapp">Nomor WhatsApp</label>
                                    <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}" placeholder="Contoh: 0822xxxx atau +62822xxxx" required>
                                    <div class="form-text">Pastikan nomor aktif dan dapat dihubungi.</div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Apakah Jobers :</label>
                                    @php
                                        $statusChoiceOld = old('status_choice');
                                        $statusOtherOld = old('status_other');
                                    @endphp

                                    <div class="d-flex flex-column gap-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_choice" id="status_choice_fresh" value="fresh" {{ $statusChoiceOld === 'fresh' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="status_choice_fresh">Fresh Graduate</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_choice" id="status_choice_pindah" value="pindah" {{ $statusChoiceOld === 'pindah' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="status_choice_pindah">Sudah bekerja &amp; ingin pindah bekerja</label>
                                        </div>

                                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-2">
                                            <div class="form-check m-0">
                                                <input class="form-check-input" type="radio" name="status_choice" id="status_choice_other" value="lainnya" {{ $statusChoiceOld === 'lainnya' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="status_choice_other">Other:</label>
                                            </div>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="status_other"
                                                name="status_other"
                                                value="{{ $statusOtherOld }}"
                                                placeholder="Tulis jawaban lainnya"
                                                style="max-width: 520px;"
                                            >
                                        </div>
                                        <div class="form-text" id="status_other_help">Isi field Other hanya jika memilih Lainnya.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Metode Curhat</label>
                                    @php
                                        $jenisOld = old('jenis_konseling', 'Online (Zoom)');
                                        $jenisOnline = 'Online (Zoom)';
                                        $jenisOffline = 'Offline (datang langsung ke kantor Pusat Pasar Kerja, Jl. Gatot Subroto No.44, Jakarta Selatan)';
                                    @endphp
                                    <div class="d-flex flex-column gap-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_konseling" id="jenis_online" value="{{ $jenisOnline }}" {{ $jenisOld === $jenisOnline ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="jenis_online">{{ $jenisOnline }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_konseling" id="jenis_offline" value="{{ $jenisOffline }}" {{ $jenisOld === $jenisOffline ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="jenis_offline">{{ $jenisOffline }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold" for="jadwal_konseling">Jadwal Curhat</label>
                                    <select id="jadwal_konseling" name="jadwal_konseling" class="form-select" required>
                                        <option value="" disabled {{ old('jadwal_konseling') ? '' : 'selected' }}>Pilih jadwal</option>
                                        @foreach($konsultasiSlots as $slot)
                                            <option value="{{ $slot }}" {{ old('jadwal_konseling') === $slot ? 'selected' : '' }}>{{ $slot }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold" for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                    @php
                                        $pendidikanOptions = ['SD', 'SMP', 'SMA', 'SMK', 'D1/D2', 'D3', 'S1/D4', 'S2', 'S3', 'Lainnya'];
                                        $pendidikanChoiceOld = old('pendidikan_choice');
                                        $pendidikanOtherOld = old('pendidikan_other');
                                    @endphp
                                    <select id="pendidikan_choice" name="pendidikan_choice" class="form-select">
                                        @php
                                            $pendidikanOld = $pendidikanChoiceOld;
                                        @endphp
                                        <option value="" {{ $pendidikanOld ? '' : 'selected' }}>Pilih (opsional)</option>
                                        @foreach($pendidikanOptions as $opt)
                                            <option value="{{ $opt }}" {{ $pendidikanOld === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="pendidikan_other"
                                            name="pendidikan_other"
                                            value="{{ $pendidikanOtherOld }}"
                                            placeholder="Jika Lainnya, tulis pendidikan terakhir"
                                        >
                                        <div class="form-text" id="pendidikan_other_help">Isi field ini hanya jika memilih Lainnya.</div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold" for="jurusan">Jurusan <span class="text-muted fw-normal">(opsional)</span></label>
                                    <input
                                        type="text"
                                        id="jurusan"
                                        name="jurusan"
                                        class="form-control"
                                        value="{{ old('jurusan') }}"
                                        placeholder="Contoh: Teknik Informatika / Akuntansi / IPA"
                                    >
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold" for="cv">Upload CV</label>
                                    <input type="file" id="cv" name="cv" class="form-control" accept=".pdf,.doc,.docx">
                                    <div class="form-text">Format: PDF/DOC/DOCX, maksimal 5MB.</div>
                                </div>
                            </div>

                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-4">
                                <div class="text-muted small">
                                    Dengan mengirim form ini, Anda menyetujui data digunakan untuk proses konsultasi karir.
                                </div>
                                <button class="btn btn-success btn-lg px-4" type="submit">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Kirim
                                </button>
                            </div>
                        </form>

                        <script>
                            (function () {
                                function syncStatusOther() {
                                    var otherRadio = document.getElementById('status_choice_other');
                                    var otherInput = document.getElementById('status_other');
                                    var help = document.getElementById('status_other_help');
                                    if (!otherRadio || !otherInput) return;

                                    var enabled = !!otherRadio.checked;
                                    otherInput.disabled = !enabled;
                                    otherInput.required = enabled;
                                    if (!enabled) otherInput.value = '';
                                    if (help) help.style.opacity = enabled ? '1' : '0.6';
                                }

                                document.addEventListener('change', function (e) {
                                    if (e.target && e.target.name === 'status_choice') syncStatusOther();
                                });
                                document.addEventListener('DOMContentLoaded', syncStatusOther);
                                syncStatusOther();
                            })();
                        </script>

                        <script>
                            (function () {
                                function syncPendidikanOther() {
                                    var sel = document.getElementById('pendidikan_choice');
                                    var otherInput = document.getElementById('pendidikan_other');
                                    var help = document.getElementById('pendidikan_other_help');
                                    if (!sel || !otherInput) return;

                                    var enabled = (sel.value === 'Lainnya');
                                    otherInput.disabled = !enabled;
                                    otherInput.required = enabled;
                                    if (!enabled) otherInput.value = '';
                                    if (help) help.style.opacity = enabled ? '1' : '0.6';
                                }

                                document.addEventListener('change', function (e) {
                                    if (e.target && e.target.id === 'pendidikan_choice') syncPendidikanOther();
                                });
                                document.addEventListener('DOMContentLoaded', syncPendidikanOther);
                                syncPendidikanOther();
                            })();
                        </script>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


