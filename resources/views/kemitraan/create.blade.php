@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pendaftaran Kemitraan Pusat Pasar Kerja</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('kemitraan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="pic_name" class="form-label">Nama Penanggung Jawab (PIC)</label>
            <input type="text" class="form-control" id="pic_name" name="pic_name" required>
        </div>
        <div class="mb-3">
            <label for="pic_position" class="form-label">Jabatan Penanggung Jawab</label>
            <input type="text" class="form-control" id="pic_position" name="pic_position" required>
        </div>
        <div class="mb-3">
            <label for="pic_email" class="form-label">Alamat Email Penanggung Jawab</label>
            <input type="email" class="form-control" id="pic_email" name="pic_email" required>
        </div>
        <div class="mb-3">
            <label for="pic_whatsapp" class="form-label">Nomor WhatsApp Aktif</label>
            <input type="text" class="form-control" id="pic_whatsapp" name="pic_whatsapp" required>
        </div>
        <div class="mb-3">
            <label for="sector_category" class="form-label">Kategori/Sektor Instansi</label>
            <select class="form-select" id="sector_category" name="sector_category" required>
                <option value="">Pilih salah satu</option>
                <option value="Kementerian/Lembaga">Kementerian/Lembaga</option>
                <option value="Pemerintah Daerah">Pemerintah Daerah (Kabupaten/Kota)</option>
                <option value="Mitra Pembangunan">Mitra Pembangunan (Perusahaan/Swasta/Job Portal)</option>
                <option value="Lembaga Pendidikan">Lembaga Pendidikan</option>
                <option value="Lembaga Non-Pemerintah">Lembaga Non-Pemerintah (Yayasan/Asosiasi/Organisasi)</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="institution_name" class="form-label">Nama Instansi</label>
            <input type="text" class="form-control" id="institution_name" name="institution_name" required>
        </div>
        <div class="mb-3">
            <label for="business_sector" class="form-label">Sektor Lapangan Usaha</label>
            <input type="text" class="form-control" id="business_sector" name="business_sector">
        </div>
        <div class="mb-3">
            <label for="institution_address" class="form-label">Alamat Instansi</label>
            <input type="text" class="form-control" id="institution_address" name="institution_address" required>
        </div>
        <div class="mb-3">
            <label for="partnership_type" class="form-label">Jenis Kemitraan yang Diajukan</label>
            <select class="form-select" id="partnership_type" name="partnership_type" required>
                <option value="">Pilih salah satu</option>
                <option value="Walk-in Interview">Walk-in Interview</option>
                <option value="Pendidikan Pasar Kerja">Pendidikan Pasar Kerja (Seminar/Webinar/Workshop)</option>
                <option value="Talenta Muda">Talenta Muda</option>
                <option value="Job Fair">Job Fair</option>
                <option value="Konsultasi Informasi Pasar Kerja">Konsultasi Informasi Pasar Kerja</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="needs" class="form-label">Kebutuhan yang Diajukan</label>
            <textarea class="form-control" id="needs" name="needs" rows="2"></textarea>
        </div>
        <div class="mb-3">
            <label for="schedule" class="form-label">Usulan Jadwal Kegiatan</label>
            <input type="text" class="form-control" id="schedule" name="schedule">
        </div>
        <div class="mb-3">
            <label for="request_letter" class="form-label">Surat Permohonan Kemitraan (PDF/DOC/DOCX)</label>
            <input type="file" class="form-control" id="request_letter" name="request_letter" accept=".pdf,.doc,.docx">
        </div>
        <button type="submit" class="btn btn-primary">Kirim Pendaftaran</button>
    </form>
</div>

@if(session('success'))
<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-5">
        <div class="mb-3">
          <svg width="64" height="64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="32" cy="32" r="32" fill="#28a745"/>
            <path d="M20 34l8 8 16-16" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h4 class="mb-2">Pengajuan Sukses</h4>
        <p>Pendaftaran kemitraan Anda telah berhasil dikirim.</p>
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script>
    var myModal = new bootstrap.Modal(document.getElementById('successModal'));
    window.onload = function() {
        myModal.show();
    };
</script>
@endif

@endsection 