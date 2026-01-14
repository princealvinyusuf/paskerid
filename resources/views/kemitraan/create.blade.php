@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center mt-5 mb-5">
    <div class="card shadow-lg w-100" style="max-width: 700px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-people-fill" style="font-size: 2.5rem; color: #0d6efd;"></i>
                <h2 class="mt-2 mb-0">Pendaftaran Walk In Interview</h2>
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
           
            {{-- <form method="GET" id="typeForm" class="mb-3" style="display:none;">
                <label for="partnership_type" class="form-label">Jenis Kemitraan yang Diajukan</label>
                <select class="form-select" id="partnership_type" name="partnership_type" onchange="document.getElementById('typeForm').submit()" required>
                    <option value="Walk-in Interview" {{ $selectedType == 'Walk-in Interview' ? 'selected' : '' }}>Walk-in Interview</option>
                    <option value="Pendidikan Pasar Kerja" {{ $selectedType == 'Pendidikan Pasar Kerja' ? 'selected' : '' }}>Pendidikan Pasar Kerja</option>
                    <option value="Talenta Muda" {{ $selectedType == 'Talenta Muda' ? 'selected' : '' }}>Talenta Muda</option>
                    <option value="Job Fair" {{ $selectedType == 'Job Fair' ? 'selected' : '' }}>Job Fair</option>
                    <option value="Konsultasi Pasar Kerja" {{ $selectedType == 'Konsultasi Pasar Kerja' ? 'selected' : '' }}>Konsultasi Pasar Kerja</option>
                    <option value="Konsultasi Informasi Pasar Kerja" {{ $selectedType == 'Konsultasi Informasi Pasar Kerja' ? 'selected' : '' }}>Konsultasi Informasi Pasar Kerja</option>
                </select>
            </form> --}}
            <!-- End Partnership Type Selector -->
            <form action="{{ route('kemitraan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- <input type="hidden" name="partnership_type" value="{{ $selectedType }}"> --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="pic_name" class="form-label">Nama Penanggung Jawab (PIC)</label>
                        <input type="text" class="form-control" id="pic_name" name="pic_name" placeholder="Masukkan nama lengkap" value="{{ old('pic_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pic_position" class="form-label">Jabatan Penanggung Jawab</label>
                        <input type="text" class="form-control" id="pic_position" name="pic_position" placeholder="Masukkan jabatan" value="{{ old('pic_position') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pic_email" class="form-label">Alamat Email Penanggung Jawab</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="pic_email" name="pic_email" placeholder="email@domain.com" value="{{ old('pic_email') }}" required>
                        </div>
                        <div class="form-text">Pastikan email aktif untuk komunikasi.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="pic_whatsapp" class="form-label">Nomor WhatsApp Aktif</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" class="form-control" id="pic_whatsapp" name="pic_whatsapp" placeholder="+62xxxxxxxxxx" value="{{ old('pic_whatsapp') }}" required>
                        </div>
                        <div class="form-text">Nomor aktif untuk keperluan komunikasi.</div>
                    </div>
                    <div class="col-md-12">
                        <label for="foto_kartu_pegawai_pic" class="form-label">Upload Foto Kartu Pegawai (PIC)</label>
                        <input type="file" class="form-control" id="foto_kartu_pegawai_pic" name="foto_kartu_pegawai_pic" accept="image/png,image/jpeg">
                        <div class="form-text">Format gambar (PNG, JPG/JPEG). Maksimal 2MB.</div>
                        @error('foto_kartu_pegawai_pic')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3"><i class="bi bi-building me-2"></i>Data Perusahaan</h5>
                <div class="row g-3">
                    {{-- <div class="col-md-6">
                        <label for="sector_category" class="form-label">Kategori/Sektor Instansi</label>
                        <select class="form-select" id="sector_category" name="sector_category" required>
                            <option value="">Pilih salah satu</option>
                            <option value="Kementerian/Lembaga" {{ (old('sector_category')) == 'Kementerian/Lembaga' ? 'selected' : '' }}>Kementerian/Lembaga</option>
                            <option value="Pemerintah Daerah" {{ (old('sector_category')) == 'Pemerintah Daerah' ? 'selected' : '' }}>Pemerintah Daerah (Kabupaten/Kota)</option>
                            <option value="Mitra Pembangunan" {{ (old('sector_category')) == 'Mitra Pembangunan' ? 'selected' : '' }}>Mitra Pembangunan (Perusahaan/Swasta/Job Portal)</option>
                            <option value="Lembaga Pendidikan" {{ (old('sector_category')) == 'Lembaga Pendidikan' ? 'selected' : '' }}>Lembaga Pendidikan</option>
                            <option value="Lembaga Non-Pemerintah" {{ (old('sector_category')) == 'Lembaga Non-Pemerintah' ? 'selected' : '' }}>Lembaga Non-Pemerintah (Yayasan/Asosiasi/Organisasi)</option>
                        </select>
                    </div> --}}
                    <div class="col-md-6">
                        <label for="comapny_sectors" class="form-label">Kategori/Sektor Perusahaan</label>
                        <select class="form-select" id="company_sectors_id" name="company_sectors_id" required>
                            <option value="">-- Pilih Kategori / Sektor Perusahaan --</option>
                        @foreach ($dropdownCompanySectors as $sectors)
                            <option value="{{ $sectors->id }}" {{ old('company_sectors_id') == $sectors->id ? 'selected' : '' }}>{{ $sectors->sector_name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="institution_name" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="institution_name" name="institution_name" placeholder="Masukkan nama perusahaan" value="{{ old('institution_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="business_sector" class="form-label">Sektor Lapangan Usaha</label>
                        <input type="text" class="form-control" id="business_sector" name="business_sector" placeholder="Contoh: manufaktur, teknologi, dsb" value="{{ old('business_sector') }}" required>
                        <div class="form-text">Bidang usaha/sektor yang menjadi fokus utama Perusahaan.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="institution_address" class="form-label">Alamat Perusahaan</label>
                        <input type="text" class="form-control" id="institution_address" name="institution_address" placeholder="Masukkan alamat lengkap" value="{{ old('institution_address') }}" required>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3"><i class="bi bi-link-45deg me-2"></i>Detail Kemitraan</h5>
                <!-- Placeholder for the type selector -->
                <div id="typeSelectorPlaceholder"></div>
                {{-- Removed nested form start --}}
                    {{-- <input type="hidden" name="partnership_type_id"> --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="partnership_type_id" class="form-label">Jenis Kemitraan yang Diajukan</label>
                            <select name="type_of_partnership_id" id="type_of_partnership_id" class="form-select">
                                <option value="">-- Pilih Jenis Kemitraan --</option>
                                @foreach ($dropdownPartnership as $type)
                                    <option value="{{ $type->id }}" {{ (old('type_of_partnership_id', $defaultTypeId ?? 1) == $type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tipe_penyelenggara" class="form-label">Tipe Penyelenggara</label>
                            <select name="tipe_penyelenggara" id="tipe_penyelenggara" class="form-select" required>
                                <option value="">-- Pilih Tipe Penyelenggara --</option>
                                <option value="Job Portal" {{ old('tipe_penyelenggara') == 'Job Portal' ? 'selected' : '' }}>Job Portal</option>
                                <option value="Perusahaan" {{ old('tipe_penyelenggara') == 'Perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                            </select>
                        </div>
                    </div>
                        <div class="col-12">
                            <label for="pasker_room" class="form-label">Ruangan yang akan dipakai</label>
                            <div class="grid-container">
                                @foreach($imagePaskerRoom as $ruangan)
                            <div class="ruangan-card">
                                @if($ruangan->image_base64)
                                    <img src="data:{{ $ruangan->mime_type }};base64,{{ $ruangan->image_base64 }}" alt="{{ $ruangan->title }}">
                                @endif

                            <div class="form-check">
                             <input class="form-check-input" type="checkbox" name="pasker_room_ids[]" value="{{ $ruangan->id }}" id="ruangan{{ $ruangan->id }}" {{ collect(old('pasker_room_ids', []))->contains($ruangan->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="ruangan{{ $ruangan->id }}">
                                {{ $ruangan->room_name }}
                            </label>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>

                        <div class="form-check align-items-center d-flex" style="padding-top: 10px">
                            <input class="form-check-input mt-0" type="checkbox" id="raunganlainnyaCheckbox" onchange="toggleOtherroomText(this)">
                            <label class="form-check-label ms-2 mb-0" for="raunganlainnyaCheckbox"><strong>Lainnya</strong></label>
                        </div>
                            <input type="text" id="ruanganOtherText" class="form-control mt-2" placeholder="Tulis nama ruangan..." name="other_pasker_room" style="display: none" value="{{ old('other_pasker_room') }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="needs" class="form-label">Kebutuhan yang Diajukan</label>
                            @foreach ($paskerFacility as $facility)
                           <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="pasker_facility_ids[]" value="{{ $facility->id }}" id="facility{{ $facility->id }}" {{ collect(old('pasker_facility_ids', []))->contains($facility->id) ? 'checked' : '' }}>
                                 <label class="form-check-label" for="facility{{ $facility->id }}">
                                    {{ $facility->facility_name }}
                                </label>
                            </div>
                            @endforeach
                            <div class="checkbox-label-align">
                                <input class="form-check-input mt-0" type="checkbox" id="fasilitaslainnyaCheckbox" onchange="toggleOtherfacilityText(this)">
                            <label class="form-check-label ms-1 mb-0" for="fasilitaslainnyaCheckbox">Lainnya</label>
                            </div>
                            <input type="text" id="facilityOtherText" class="form-control mt-2" placeholder="Tulis nama fasilitas..." name="other_pasker_facility" style="display: none" value="{{ old('other_pasker_facility') }}">
                            @error('pasker_facility_ids')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label for="schedule" class="form-label">Usulan Jadwal Kegiatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="text" class="form-control" id="schedule" name="schedule" placeholder="Pilih rentang tanggal" autocomplete="off" value="{{ old('schedule') }}" required>
                            </div>
                            <div class="form-text">Pilih rentang tanggal pelaksanaan kegiatan.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="schedule" class="form-label">Usulan Waktu Kegiatan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="text" class="form-control" id="scheduletimestart" name="scheduletimestart" placeholder="Pilih waktu mulai" autocomplete="off" value="{{ old('scheduletimestart') }}">
                            </div>
                            <div class="form-text">Pilih rentang waktu mulai pelaksanaan kegiatan.</div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="text" class="form-control" id="scheduletimefinish" name="scheduletimefinish" placeholder="Pilih waktu selesai" autocomplete="off" value="{{ old('scheduletimefinish') }}">
                            </div>
                            <div class="form-text">Pilih rentang waktu selesai pelaksanaan kegiatan.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="request_letter" class="form-label">Surat Permohonan Kemitraan</label>
                            <div class="mb-2">
                                <a href="https://drive.google.com/drive/folders/1wZm4htxHuuTynLJXQ__-XiIW_omKrr7S?usp=sharing" class="btn btn-outline-secondary btn-sm" download target="_blank">
                                    <i class="bi bi-download me-1"></i>Download Template Surat Permohonan
                                </a>
                            </div>
                            <input type="file" class="form-control" id="request_letter" name="request_letter" accept=".pdf,.doc,.docx" required>
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
    var schedulePicker = flatpickr("#schedule", {
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

    // Refresh disabled dates when partnership type changes
    const typeSelectDynamic = document.getElementById('type_of_partnership_id');
    if (typeSelectDynamic) {
        typeSelectDynamic.addEventListener('change', function() {
            const typeId = this.value;
            if (!typeId) { return; }
            fetch(`{{ route('kemitraan.fullyBookedDates') }}?type_id=${encodeURIComponent(typeId)}`)
                .then(r => r.json())
                .then(disabled => {
                    if (Array.isArray(disabled)) {
                        schedulePicker.set('disable', disabled);
                        schedulePicker.clear();
                    }
                })
                .catch(() => {});
        });
    }

    flatpickr("#scheduletimestart", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        minuteIncrement: 30,
        
    });
    flatpickr("#scheduletimefinish", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        minuteIncrement: 30,
    });

    const whatsappInput = document.getElementById('pic_whatsapp');

    whatsappInput.addEventListener('input', function () {
        let input = this.value;

        // Hapus semua karakter kecuali angka dan +
        input = input.replace(/[^0-9+]/g, '');

        // Pastikan hanya satu '+' di awal
        if (input.indexOf('+') > 0) {
            input = input.replace(/\+/g, ''); // hapus semua '+'
            input = '+' + input;
        }

        // Pastikan diawali dengan +62
        if (!input.startsWith('+62')) {
            input = '+62' + input.replace(/^\+*/, '').replace(/^62*/, '');
        }

        // Ambil hanya digit setelah +62
        const digits = input.slice(3).replace(/[^0-9]/g, '');

        // Batasi maksimal 13 digit setelah +62
        const limitedDigits = digits.substring(0, 13);

        // Gabungkan kembali
        this.value = '+62' + limitedDigits;
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

        // Custom validation for schedule field
        if (mainForm) {
            mainForm.addEventListener('submit', function(e) {
                var schedule = document.getElementById('schedule');
                if (!schedule.value.trim()) {
                    alert('Field "Usulan Jadwal Kegiatan" wajib diisi.');
                    schedule.focus();
                    e.preventDefault();
                }
            });
        }
    });

    function toggleOtherroomText(input) {
        const field = document.getElementById('ruanganOtherText');
        const isChecked = input && input.checked;
        field.style.display = isChecked ? 'block' : 'none';
    }

    function toggleOtherfacilityText(input) {
        const field = document.getElementById('facilityOtherText');
        const isChecked = input && input.checked;
        field.style.display = isChecked ? 'block' : 'none';
    }

    document.querySelector('form').addEventListener('submit', function (e) {
        const selectedFacility = document.querySelectorAll('input[name="pasker_facility_ids[]"]:checked');
        const otherFacilityText = document.getElementById('facilityOtherText');
        const hasOther = otherFacilityText && otherFacilityText.style.display !== 'none' && otherFacilityText.value.trim().length > 0;

        if (selectedFacility.length === 0 && !hasOther) {
            e.preventDefault();
            alert('Silakan pilih minimal satu fasilitas atau isi Lainnya.');
        }
    });
</script>
@endpush
<style>
    .grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 2 kolom tetap */
    gap: 1.5rem; /* jarak antar card */
}

.ruangan-card {
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    background-color: #fff;
}

.ruangan-card img {
    width: 100%;
    height: 180px; /* Tetapkan tinggi tetap */
    object-fit: cover; /* Potong gambar agar pas di dalam */
    border-radius: 10px;
    margin-bottom: 0.75rem;
}


.form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-right: 0.5em;
        vertical-align: middle;
    }

    .form-check-label {
        font-weight: 500;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .grid-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }
    .ruang-card-lainnya {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.checkbox-label-align {
        display: flex;
        align-items: center;
        gap: 8px; /* jarak antara checkbox dan label */
    }
</style>

@endsection 
