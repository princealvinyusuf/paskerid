@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center mt-5 mb-5">
    <div class="card shadow-lg w-100" style="max-width: 700px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-people-fill" style="font-size: 2.5rem; color: #0d6efd;"></i>
                <h2 class="mt-2 mb-0">Pendaftaran Kemitraan</h2>
                <p class="text-muted">Pusat Pasar Kerja</p>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <hr class="my-4">
            <h5 class="mb-3"><i class="bi bi-link-45deg me-2"></i>Detail Kemitraan</h5>
            <!-- GET form for partnership type (for calendar functionality, hidden initially) -->
            <form method="GET" id="typeForm" class="mb-3" style="display:none;">
                <label for="partnership_type" class="form-label">Jenis Kemitraan yang Diajukan</label>
                <select class="form-select" id="partnership_type" name="partnership_type" onchange="document.getElementById('typeForm').submit()" required>
                    <option value="Walk-in Interview" {{ $selectedType == 'Walk-in Interview' ? 'selected' : '' }}>Walk-in Interview</option>
                    <option value="Pendidikan Pasar Kerja" {{ $selectedType == 'Pendidikan Pasar Kerja' ? 'selected' : '' }}>Pendidikan Pasar Kerja</option>
                    <option value="Talenta Muda" {{ $selectedType == 'Talenta Muda' ? 'selected' : '' }}>Talenta Muda</option>
                    <option value="Job Fair" {{ $selectedType == 'Job Fair' ? 'selected' : '' }}>Job Fair</option>
                    <option value="Konsultasi Pasar Kerja" {{ $selectedType == 'Konsultasi Pasar Kerja' ? 'selected' : '' }}>Konsultasi Pasar Kerja</option>
                    <option value="Konsultasi Informasi Pasar Kerja" {{ $selectedType == 'Konsultasi Informasi Pasar Kerja' ? 'selected' : '' }}>Konsultasi Informasi Pasar Kerja</option>
                </select>
            </form>
            <!-- End Partnership Type Selector -->
            <form action="{{ route('kemitraan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="partnership_type" value="{{ $selectedType }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="pic_name" class="form-label">Nama Penanggung Jawab (PIC)</label>
                        <input type="text" class="form-control" id="pic_name" name="pic_name" placeholder="Masukkan nama lengkap" value="{{ $formData['pic_name'] ?? old('pic_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pic_position" class="form-label">Jabatan Penanggung Jawab</label>
                        <input type="text" class="form-control" id="pic_position" name="pic_position" placeholder="Masukkan jabatan" value="{{ $formData['pic_position'] ?? old('pic_position') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pic_email" class="form-label">Alamat Email Penanggung Jawab</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="pic_email" name="pic_email" placeholder="email@domain.com" value="{{ $formData['pic_email'] ?? old('pic_email') }}" required>
                        </div>
                        <div class="form-text">Pastikan email aktif untuk komunikasi.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="pic_whatsapp" class="form-label">Nomor WhatsApp Aktif</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" class="form-control" id="pic_whatsapp" name="pic_whatsapp" placeholder="08xxxxxxxxxx" value="{{ $formData['pic_whatsapp'] ?? old('pic_whatsapp') }}" required>
                        </div>
                        <div class="form-text">Nomor aktif untuk keperluan komunikasi.</div>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3"><i class="bi bi-building me-2"></i>Data Instansi</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="sector_category" class="form-label">Kategori/Sektor Instansi</label>
                        <select class="form-select" id="sector_category" name="sector_category" required>
                            <option value="">Pilih salah satu</option>
                            <option value="Kementerian/Lembaga" {{ ($formData['sector_category'] ?? old('sector_category')) == 'Kementerian/Lembaga' ? 'selected' : '' }}>Kementerian/Lembaga</option>
                            <option value="Pemerintah Daerah" {{ ($formData['sector_category'] ?? old('sector_category')) == 'Pemerintah Daerah' ? 'selected' : '' }}>Pemerintah Daerah (Kabupaten/Kota)</option>
                            <option value="Mitra Pembangunan" {{ ($formData['sector_category'] ?? old('sector_category')) == 'Mitra Pembangunan' ? 'selected' : '' }}>Mitra Pembangunan (Perusahaan/Swasta/Job Portal)</option>
                            <option value="Lembaga Pendidikan" {{ ($formData['sector_category'] ?? old('sector_category')) == 'Lembaga Pendidikan' ? 'selected' : '' }}>Lembaga Pendidikan</option>
                            <option value="Lembaga Non-Pemerintah" {{ ($formData['sector_category'] ?? old('sector_category')) == 'Lembaga Non-Pemerintah' ? 'selected' : '' }}>Lembaga Non-Pemerintah (Yayasan/Asosiasi/Organisasi)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="institution_name" class="form-label">Nama Instansi</label>
                        <input type="text" class="form-control" id="institution_name" name="institution_name" placeholder="Masukkan nama instansi" value="{{ $formData['institution_name'] ?? old('institution_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="business_sector" class="form-label">Sektor Lapangan Usaha</label>
                        <input type="text" class="form-control" id="business_sector" name="business_sector" placeholder="Contoh: manufaktur, teknologi, dsb" value="{{ $formData['business_sector'] ?? old('business_sector') }}" required>
                        <div class="form-text">Bidang usaha/sektor yang menjadi fokus utama instansi.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="institution_address" class="form-label">Alamat Instansi</label>
                        <input type="text" class="form-control" id="institution_address" name="institution_address" placeholder="Masukkan alamat lengkap" value="{{ $formData['institution_address'] ?? old('institution_address') }}" required>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3"><i class="bi bi-link-45deg me-2"></i>Detail Kemitraan</h5>
                <!-- Placeholder for the type selector -->
                <div id="typeSelectorPlaceholder"></div>
                <form action="{{ route('kemitraan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="partnership_type" value="{{ $selectedType }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="needs" class="form-label">Kebutuhan yang Diajukan</label>
                            <textarea class="form-control" id="needs" name="needs" rows="2" placeholder="Jelaskan kebutuhan atau bentuk dukungan yang diharapkan" required>{{ $formData['needs'] ?? old('needs') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="schedule" class="form-label">Usulan Jadwal Kegiatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="text" class="form-control" id="schedule" name="schedule" placeholder="Pilih rentang tanggal" autocomplete="off" value="{{ $formData['schedule'] ?? old('schedule') }}" required>
                            </div>
                            <div class="form-text">Pilih rentang tanggal pelaksanaan kegiatan.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="request_letter" class="form-label">Surat Permohonan Kemitraan</label>
                            <div class="mb-2">
                                <a href="http://www.psid.run.place/paskerid/public/documents/template-surat-permohonan.docx" class="btn btn-outline-secondary btn-sm" download>
                                    <i class="bi bi-download me-1"></i>Download Template Surat Permohonan
                                </a>
                            </div>
                            <input type="file" class="form-control" id="request_letter" name="request_letter" accept=".pdf,.doc,.docx" value="{{ old('request_letter') }}" required>
                            <div class="form-text">Unggah surat permohonan (PDF/DOC/DOCX, max 2MB).</div>
                        </div>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send-check me-2"></i>Kirim Pendaftaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-body text-center p-5">
        <div class="mb-3 animate__animated animate__bounceIn">
          <svg width="80" height="80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="40" cy="40" r="40" fill="#28a745"/>
            <path d="M25 43l11 11 19-19" stroke="#fff" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3 class="mb-2">Pengajuan Sukses</h3>
        <p class="mb-4">Pendaftaran kemitraan Anda telah berhasil dikirim.<br>Tim kami akan segera meninjau pengajuan Anda.</p>
        <button type="button" class="btn btn-success btn-lg px-4" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('successModal'));
        myModal.show();
    });
</script>
@endif

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Move the GET form visually into the placeholder
    document.addEventListener('DOMContentLoaded', function() {
        var typeForm = document.getElementById('typeForm');
        var placeholder = document.getElementById('typeSelectorPlaceholder');
        if (typeForm && placeholder) {
            typeForm.style.display = '';
            placeholder.appendChild(typeForm);
        }
    });
    var fullyBookedDates = @json($fullyBookedDates ?? []);
    flatpickr("#schedule", {
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: fullyBookedDates,
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
            },
            months: {
                shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var typeForm = document.getElementById('typeForm');
        var mainForm = document.querySelector('form[action="{{ route('kemitraan.store') }}"]');
        var typeSelect = document.getElementById('partnership_type');

        if (typeForm && mainForm && typeSelect) {
            typeSelect.addEventListener('change', function() {
                // Remove old hidden fields except partnership_type
                Array.from(typeForm.querySelectorAll('input[type=hidden]:not([name=partnership_type])')).forEach(function(el) {
                    el.remove();
                });
                // Copy all POST form values as hidden fields into GET form
                Array.from(mainForm.elements).forEach(function(el) {
                    if (el.name && el.value && el.type !== 'submit' && el.type !== 'button' && el.name !== 'partnership_type') {
                        var hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = el.name;
                        hidden.value = el.value;
                        typeForm.appendChild(hidden);
                    }
                });
                typeForm.submit();
            });
        }
    });
</script>
@endpush

@endsection 