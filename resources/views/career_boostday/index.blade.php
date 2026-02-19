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
                        Form Konsultasi Karir (Pencari Kerja)
                    </a>
                    <a href="{{ route('career-boostday.index', ['tab' => 'jadwal']) }}"
                       class="btn {{ $tab === 'jadwal' ? 'btn-success' : 'btn-outline-success' }}">
                        Jadwal Konsultasi
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
            @endif

            @if($tab === 'jadwal')
                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h3 class="h6 fw-bold mb-2"><i class="fa-solid fa-user-check me-2 text-success"></i>Jadwal Konsultasi Terbooking</h3>
                        <div class="text-muted mb-3">Berikut jadwal konsultasi yang sudah di-accept.</div>

                        @if(!$bookedFeatureAvailable)
                            <div class="alert alert-warning mb-0">
                                Fitur “Terbooking” belum aktif (tabel/kolom belum tersedia di database).
                            </div>
                        @elseif($bookedKonsultasi->count() === 0)
                            <div class="text-muted">Belum ada jadwal konsultasi yang terbooking.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 160px;">Tanggal</th>
                                            <th style="width: 140px;">Waktu</th>
                                            <th>Nama (disamarkan)</th>
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
                                                <td class="fw-semibold">{{ $b->konselor_name }}</td>
                                                <td>{{ $b->jenis_konseling }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h5 fw-bold mb-1">Form Konsultasi Karir (Pencari Kerja)</h2>
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
                                    <label class="form-label fw-semibold">Apakah Saudara/i :</label>
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
                                    <label class="form-label fw-semibold">Jenis Konseling</label>
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
                                    <label class="form-label fw-semibold" for="jadwal_konseling">Jadwal Konseling</label>
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
                                        $pendidikanOptions = ['SD', 'SMP', 'SMA', 'SMK', 'D1/D2', 'D3', 'D4', 'S1/D4', 'S2', 'S3', 'Lainnya'];
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


