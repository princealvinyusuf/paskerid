@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5" style="max-width: 1200px;">
    <div class="text-center mb-4">
        <i class="bi bi-people-fill" style="font-size: 2.5rem; color: #0d6efd;"></i>
        <h2 class="mt-2 mb-0">Walk In Interview</h2>
        <p class="text-muted mb-0">Pusat Pasar Kerja</p>
    </div>

    <!-- Segmented toggle (top) -->
    <div class="mb-3">
        <div class="walkin-segmented" role="tablist" aria-label="Pilih tampilan">
            <button type="button" class="walkin-seg-btn active" id="btnPanelGallery" aria-selected="true">Galeri Walk In</button>
            <button type="button" class="walkin-seg-btn" id="btnPanelForm" aria-selected="false">Form Pendaftaran Walk In</button>
            <button type="button" class="walkin-seg-btn" id="btnPanelSchedule" aria-selected="false">Jadwal Walk In</button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Schedule -->
        <div class="col-12 d-none" id="panelSchedule">
            <div class="card shadow-lg w-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="mb-0"><i class="fa-solid fa-calendar-days me-2"></i>Jadwal Walk In</h5>
                            <div class="text-muted small">Informasi jadwal kegiatan Walk-in Interview yang akan datang.</div>
                        </div>
                    </div>

                    <div class="walkin-panel p-3 p-md-4">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle walkin-schedule-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 160px;">Tanggal</th>
                                        <th style="min-width: 360px;">Kegiatan</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($walkinAgendas) && $walkinAgendas->count() > 0)
                                        @php
                                            $nextIdx = null;
                                            foreach($walkinAgendas as $idx => $agenda) {
                                                if ($nextIdx === null && \Carbon\Carbon::parse($agenda->date)->isFuture()) {
                                                    $nextIdx = $idx;
                                                }
                                            }
                                        @endphp
                                        @foreach($walkinAgendas as $idx => $agenda)
                                            @php
                                                $date = \Carbon\Carbon::parse($agenda->date);
                                                $isUpcoming = $idx === $nextIdx;
                                                $rowClass = $isUpcoming ? 'walkin-schedule-upcoming' : ($idx % 2 === 1 ? 'walkin-schedule-odd' : '');
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td class="fw-semibold">
                                                    <div class="walkin-schedule-date">{{ $date->format('d M') }}</div>
                                                    <div class="text-muted small">{{ $date->format('Y') }}</div>
                                                </td>
                                                <td><i class="fas fa-user-tie walkin-schedule-icon"></i>{{ $agenda->title }}</td>
                                                <td>
                                                    <div class="walkin-2line">{{ $agenda->description }}</div>
                                                    <button
                                                        class="btn btn-outline-primary btn-sm ms-2 mt-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#agendaDetailModal"
                                                        data-title="{{ e($agenda->title) }}"
                                                        data-organizer="{{ e($agenda->organizer) }}"
                                                        data-date="{{ $date->format('d M Y') }}"
                                                        data-location="{{ e($agenda->location) }}"
                                                        data-image="{{ $agenda->image_url }}"
                                                        data-registration="{{ $agenda->registration_url }}"
                                                        data-description="{{ e($agenda->description) }}"
                                                    >Detail</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                                                Belum ada jadwal Walk In yang tersedia.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form (Big View) -->
        <div class="col-12 d-none" id="panelForm">
            <div class="card shadow-lg w-100">
                <div class="card-body p-4">
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
                        <label for="foto_kartu_pegawai_pic" class="form-label">Upload Foto Kartu Pegawai (PIC) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="foto_kartu_pegawai_pic" name="foto_kartu_pegawai_pic" accept="image/png,image/jpeg" required>
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
                <h5 class="mb-3"><i class="bi bi-briefcase me-2"></i>Detail Lowongan</h5>
                @php
                    $detailLowonganOld = old('detail_lowongan');
                    if (!is_array($detailLowonganOld) || count($detailLowonganOld) < 1) {
                        $detailLowonganOld = [[]];
                    }
                @endphp
                <div id="detailLowonganList">
                    @foreach ($detailLowonganOld as $i => $dl)
                        <div class="card mb-3 detail-lowongan-item" data-index="{{ $i }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="fw-semibold">Lowongan #<span class="detail-lowongan-number">{{ $i + 1 }}</span></div>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-lowongan">Hapus</button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" data-for-template="dl___INDEX___jabatan_yang_dibuka" for="dl_{{ $i }}_jabatan_yang_dibuka">Jabatan Yang Dibuka</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="dl_{{ $i }}_jabatan_yang_dibuka"
                                            data-id-template="dl___INDEX___jabatan_yang_dibuka"
                                            name="detail_lowongan[{{ $i }}][jabatan_yang_dibuka]"
                                            data-name-template="detail_lowongan[__INDEX__][jabatan_yang_dibuka]"
                                            placeholder="Contoh: Admin, Operator Produksi, Software Engineer"
                                            value="{{ $dl['jabatan_yang_dibuka'] ?? '' }}"
                                            required
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" data-for-template="dl___INDEX___jumlah_kebutuhan" for="dl_{{ $i }}_jumlah_kebutuhan">Jumlah Kebutuhan</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="dl_{{ $i }}_jumlah_kebutuhan"
                                            data-id-template="dl___INDEX___jumlah_kebutuhan"
                                            name="detail_lowongan[{{ $i }}][jumlah_kebutuhan]"
                                            data-name-template="detail_lowongan[__INDEX__][jumlah_kebutuhan]"
                                            placeholder="Contoh: 10"
                                            value="{{ $dl['jumlah_kebutuhan'] ?? '' }}"
                                            min="1"
                                            required
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" data-for-template="dl___INDEX___gender" for="dl_{{ $i }}_gender">Gender</label>
                                        <select
                                            class="form-select"
                                            id="dl_{{ $i }}_gender"
                                            data-id-template="dl___INDEX___gender"
                                            name="detail_lowongan[{{ $i }}][gender]"
                                            data-name-template="detail_lowongan[__INDEX__][gender]"
                                        >
                                            <option value="">-- Pilih Gender --</option>
                                            @php $gender = $dl['gender'] ?? '' @endphp
                                            <option value="Laki-laki" {{ $gender === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ $gender === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                            <option value="Laki-laki/Perempuan" {{ $gender === 'Laki-laki/Perempuan' ? 'selected' : '' }}>Laki-laki / Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" data-for-template="dl___INDEX___pendidikan_terakhir" for="dl_{{ $i }}_pendidikan_terakhir">Pendidikan Terakhir</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="dl_{{ $i }}_pendidikan_terakhir"
                                            data-id-template="dl___INDEX___pendidikan_terakhir"
                                            name="detail_lowongan[{{ $i }}][pendidikan_terakhir]"
                                            data-name-template="detail_lowongan[__INDEX__][pendidikan_terakhir]"
                                            placeholder="Contoh: SMA/SMK, D3, S1"
                                            value="{{ $dl['pendidikan_terakhir'] ?? '' }}"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" data-for-template="dl___INDEX___pengalaman_kerja" for="dl_{{ $i }}_pengalaman_kerja">Pengalaman Kerja</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="dl_{{ $i }}_pengalaman_kerja"
                                            data-id-template="dl___INDEX___pengalaman_kerja"
                                            name="detail_lowongan[{{ $i }}][pengalaman_kerja]"
                                            data-name-template="detail_lowongan[__INDEX__][pengalaman_kerja]"
                                            placeholder="Contoh: 0-1 tahun / 2 tahun"
                                            value="{{ $dl['pengalaman_kerja'] ?? '' }}"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" data-for-template="dl___INDEX___lokasi_penempatan" for="dl_{{ $i }}_lokasi_penempatan">Lokasi Penempatan</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="dl_{{ $i }}_lokasi_penempatan"
                                            data-id-template="dl___INDEX___lokasi_penempatan"
                                            name="detail_lowongan[{{ $i }}][lokasi_penempatan]"
                                            data-name-template="detail_lowongan[__INDEX__][lokasi_penempatan]"
                                            placeholder="Contoh: Jakarta / Bandung / Remote"
                                            value="{{ $dl['lokasi_penempatan'] ?? '' }}"
                                        >
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" data-for-template="dl___INDEX___kompetensi_yang_dibutuhkan" for="dl_{{ $i }}_kompetensi_yang_dibutuhkan">Kompetensi Yang Dibutuhkan</label>
                                        <textarea
                                            class="form-control"
                                            id="dl_{{ $i }}_kompetensi_yang_dibutuhkan"
                                            data-id-template="dl___INDEX___kompetensi_yang_dibutuhkan"
                                            name="detail_lowongan[{{ $i }}][kompetensi_yang_dibutuhkan]"
                                            data-name-template="detail_lowongan[__INDEX__][kompetensi_yang_dibutuhkan]"
                                            rows="2"
                                            placeholder="Tuliskan kompetensi/skill yang dibutuhkan"
                                        >{{ $dl['kompetensi_yang_dibutuhkan'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" data-for-template="dl___INDEX___tahapan_seleksi" for="dl_{{ $i }}_tahapan_seleksi">Tahapan Seleksi</label>
                                        <textarea
                                            class="form-control"
                                            id="dl_{{ $i }}_tahapan_seleksi"
                                            data-id-template="dl___INDEX___tahapan_seleksi"
                                            name="detail_lowongan[{{ $i }}][tahapan_seleksi]"
                                            data-name-template="detail_lowongan[__INDEX__][tahapan_seleksi]"
                                            rows="2"
                                            placeholder="Contoh: Seleksi administrasi, tes, interview, dsb"
                                        >{{ $dl['tahapan_seleksi'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex">
                    <button type="button" class="btn btn-outline-primary" id="btnAddLowongan">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Detail Lowongan
                    </button>
                </div>
                <template id="detailLowonganTemplate">
                    <div class="card mb-3 detail-lowongan-item" data-index="__INDEX__">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-semibold">Lowongan #<span class="detail-lowongan-number">__NUMBER__</span></div>
                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-lowongan">Hapus</button>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" data-for-template="dl___INDEX___jabatan_yang_dibuka" for="dl___INDEX___jabatan_yang_dibuka">Jabatan Yang Dibuka</label>
                                    <input type="text" class="form-control" id="dl___INDEX___jabatan_yang_dibuka" data-id-template="dl___INDEX___jabatan_yang_dibuka" name="detail_lowongan[__INDEX__][jabatan_yang_dibuka]" data-name-template="detail_lowongan[__INDEX__][jabatan_yang_dibuka]" placeholder="Contoh: Admin, Operator Produksi, Software Engineer" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-for-template="dl___INDEX___jumlah_kebutuhan" for="dl___INDEX___jumlah_kebutuhan">Jumlah Kebutuhan</label>
                                    <input type="number" class="form-control" id="dl___INDEX___jumlah_kebutuhan" data-id-template="dl___INDEX___jumlah_kebutuhan" name="detail_lowongan[__INDEX__][jumlah_kebutuhan]" data-name-template="detail_lowongan[__INDEX__][jumlah_kebutuhan]" placeholder="Contoh: 10" min="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-for-template="dl___INDEX___gender" for="dl___INDEX___gender">Gender</label>
                                    <select class="form-select" id="dl___INDEX___gender" data-id-template="dl___INDEX___gender" name="detail_lowongan[__INDEX__][gender]" data-name-template="detail_lowongan[__INDEX__][gender]">
                                        <option value="">-- Pilih Gender --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                        <option value="Laki-laki/Perempuan">Laki-laki / Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-for-template="dl___INDEX___pendidikan_terakhir" for="dl___INDEX___pendidikan_terakhir">Pendidikan Terakhir</label>
                                    <input type="text" class="form-control" id="dl___INDEX___pendidikan_terakhir" data-id-template="dl___INDEX___pendidikan_terakhir" name="detail_lowongan[__INDEX__][pendidikan_terakhir]" data-name-template="detail_lowongan[__INDEX__][pendidikan_terakhir]" placeholder="Contoh: SMA/SMK, D3, S1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-for-template="dl___INDEX___pengalaman_kerja" for="dl___INDEX___pengalaman_kerja">Pengalaman Kerja</label>
                                    <input type="text" class="form-control" id="dl___INDEX___pengalaman_kerja" data-id-template="dl___INDEX___pengalaman_kerja" name="detail_lowongan[__INDEX__][pengalaman_kerja]" data-name-template="detail_lowongan[__INDEX__][pengalaman_kerja]" placeholder="Contoh: 0-1 tahun / 2 tahun">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" data-for-template="dl___INDEX___lokasi_penempatan" for="dl___INDEX___lokasi_penempatan">Lokasi Penempatan</label>
                                    <input type="text" class="form-control" id="dl___INDEX___lokasi_penempatan" data-id-template="dl___INDEX___lokasi_penempatan" name="detail_lowongan[__INDEX__][lokasi_penempatan]" data-name-template="detail_lowongan[__INDEX__][lokasi_penempatan]" placeholder="Contoh: Jakarta / Bandung / Remote">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" data-for-template="dl___INDEX___kompetensi_yang_dibutuhkan" for="dl___INDEX___kompetensi_yang_dibutuhkan">Kompetensi Yang Dibutuhkan</label>
                                    <textarea class="form-control" id="dl___INDEX___kompetensi_yang_dibutuhkan" data-id-template="dl___INDEX___kompetensi_yang_dibutuhkan" name="detail_lowongan[__INDEX__][kompetensi_yang_dibutuhkan]" data-name-template="detail_lowongan[__INDEX__][kompetensi_yang_dibutuhkan]" rows="2" placeholder="Tuliskan kompetensi/skill yang dibutuhkan"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" data-for-template="dl___INDEX___tahapan_seleksi" for="dl___INDEX___tahapan_seleksi">Tahapan Seleksi</label>
                                    <textarea class="form-control" id="dl___INDEX___tahapan_seleksi" data-id-template="dl___INDEX___tahapan_seleksi" name="detail_lowongan[__INDEX__][tahapan_seleksi]" data-name-template="detail_lowongan[__INDEX__][tahapan_seleksi]" rows="2" placeholder="Contoh: Seleksi administrasi, tes, interview, dsb"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

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

        <!-- Gallery (Big View) -->
        <div class="col-12" id="panelGallery">
            <div class="card shadow-lg w-100 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h5 class="mb-0"><i class="bi bi-images me-2"></i>Galeri Walk In</h5>
                            <div class="text-muted small">Dokumentasi kegiatan (foto, video, komentar)</div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnGalleryRefresh">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>

                    <div id="walkinGalleryAlert" class="alert alert-info py-2 px-3 d-none" role="alert"></div>

                    <div id="walkinGalleryLoading" class="text-muted small">Memuat galeri...</div>

                    <!-- Company cards -->
                    <div id="walkinGalleryCompanies" class="row g-2 d-none"></div>

                    <!-- Company detail -->
                    <div id="walkinGalleryCompanyDetail" class="d-none">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-light btn-sm border" id="btnCompanyBack">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </button>
                                <div>
                                    <div class="text-muted small">Dokumentasi perusahaan</div>
                                    <div class="walkin-company-title fw-semibold" id="walkinGalleryCompanyTitle"></div>
                                </div>
                            </div>
                            <div class="text-muted small d-none d-md-block">Klik item untuk melihat detail.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-lg-8">
                                <div class="walkin-panel p-3 p-md-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="fw-semibold">Galeri</div>
                                        <div class="text-muted small">Foto & video terbaru</div>
                                    </div>
                                    <div id="walkinGalleryGrid" class="row g-3"></div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div id="walkinGalleryComments" class="walkin-panel p-3 p-md-4 walkin-sticky">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="fw-semibold">Komentar</div>
                                        <div class="text-muted small">Setelah disetujui admin</div>
                                    </div>

                                    <div class="walkin-comment-compose mb-3">
                                        <form id="walkinGalleryCommentForm">
                                            <input type="hidden" name="company_name" id="walkinGalleryCommentCompany" value="">
                                            <div class="mb-2">
                                                <label class="form-label small text-muted mb-1">Nama</label>
                                                <input type="text" class="form-control" name="name" placeholder="Nama kamu" required maxlength="80">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small text-muted mb-1">Komentar</label>
                                                <textarea class="form-control" name="comment" rows="3" placeholder="Tulis komentar singkat..." required maxlength="1000"></textarea>
                                            </div>
                                            <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off">
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                                    <i class="bi bi-send me-1"></i>Kirim
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="fw-semibold">Terbaru</div>
                                        <div class="text-muted small" id="walkinCommentCount"></div>
                                    </div>
                                    <div id="walkinGalleryCommentList" class="d-flex flex-column gap-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Agenda Detail -->
<div class="modal fade" id="agendaDetailModal" tabindex="-1" aria-labelledby="agendaDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title" id="agendaDetailModalLabel">Detail Jadwal Walk In</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-5">
            <img id="agendaModalImage" src="" alt="Agenda Image" class="img-fluid rounded-4 w-100" style="object-fit:cover;max-height:320px;">
          </div>
          <div class="col-md-7">
            <h4 id="agendaModalTitle" class="fw-bold mb-1"></h4>
            <div class="mb-2 text-muted" id="agendaModalOrganizer"></div>
            <div class="mb-2"><i class="fa fa-calendar-alt me-2"></i><span id="agendaModalDate"></span></div>
            <div class="mb-2"><i class="fa fa-map-marker-alt me-2"></i><span id="agendaModalLocation"></span></div>
            <div class="mb-2" id="agendaModalRegistration"></div>
            <div class="mt-3" id="agendaModalDescription"></div>
          </div>
        </div>
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

<!-- Walkin Gallery Modal -->
<div class="modal fade" id="walkinGalleryModal" tabindex="-1" aria-labelledby="walkinGalleryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title" id="walkinGalleryModalTitle">Galeri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="walkinGalleryModalBody">
        <div class="text-muted small">Memuat...</div>
      </div>
    </div>
  </div>
</div>

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
                    return false;
                }
                
                // Custom validation for foto_kartu_pegawai_pic field
                var fotoKartuPegawai = document.getElementById('foto_kartu_pegawai_pic');
                if (!fotoKartuPegawai.files || fotoKartuPegawai.files.length === 0) {
                    alert('Field "Upload Foto Kartu Pegawai (PIC)" wajib diisi.');
                    fotoKartuPegawai.focus();
                    e.preventDefault();
                    return false;
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

    // Ensure facility validation binds to the registration form (not gallery comment form)
    (function () {
        const mainForm = document.querySelector('form[action="{{ route('kemitraan.store') }}"]');
        if (!mainForm) return;
        mainForm.addEventListener('submit', function (e) {
            const selectedFacility = document.querySelectorAll('input[name="pasker_facility_ids[]"]:checked');
            const otherFacilityText = document.getElementById('facilityOtherText');
            const hasOther = otherFacilityText && otherFacilityText.style.display !== 'none' && otherFacilityText.value.trim().length > 0;

            if (selectedFacility.length === 0 && !hasOther) {
                e.preventDefault();
                alert('Silakan pilih minimal satu fasilitas atau isi Lainnya.');
            }
        });
    })();

    // Detail Lowongan (repeatable)
    (function () {
        const list = document.getElementById('detailLowonganList');
        const btnAdd = document.getElementById('btnAddLowongan');
        const tpl = document.getElementById('detailLowonganTemplate');
        if (!list || !btnAdd || !tpl) return;

        function reindex() {
            const items = Array.from(list.querySelectorAll('.detail-lowongan-item'));
            items.forEach((item, idx) => {
                item.dataset.index = String(idx);
                const numberEl = item.querySelector('.detail-lowongan-number');
                if (numberEl) numberEl.textContent = String(idx + 1);

                item.querySelectorAll('[data-name-template]').forEach((el) => {
                    const t = el.getAttribute('data-name-template');
                    if (t) el.setAttribute('name', t.replace(/__INDEX__/g, String(idx)));
                });
                item.querySelectorAll('[data-id-template]').forEach((el) => {
                    const t = el.getAttribute('data-id-template');
                    if (t) el.setAttribute('id', t.replace(/__INDEX__/g, String(idx)));
                });
                item.querySelectorAll('label[data-for-template]').forEach((label) => {
                    const t = label.getAttribute('data-for-template');
                    if (t) label.setAttribute('for', t.replace(/__INDEX__/g, String(idx)));
                });
            });

            const canRemove = items.length > 1;
            items.forEach((item) => {
                const btn = item.querySelector('.btn-remove-lowongan');
                if (btn) btn.style.display = canRemove ? '' : 'none';
            });
        }

        function addItem() {
            const idx = list.querySelectorAll('.detail-lowongan-item').length;
            const html = tpl.innerHTML
                .replace(/__INDEX__/g, String(idx))
                .replace(/__NUMBER__/g, String(idx + 1));
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const node = wrapper.firstElementChild;
            if (node) list.appendChild(node);
            reindex();
        }

        list.addEventListener('click', function (e) {
            const target = e.target;
            if (!(target instanceof Element)) return;
            if (target.classList.contains('btn-remove-lowongan')) {
                const item = target.closest('.detail-lowongan-item');
                if (item) item.remove();
                reindex();
            }
        });

        btnAdd.addEventListener('click', addItem);
        reindex();
    })();

    // Mobile segmented toggle: Form vs Galeri
    (function () {
        const btnForm = document.getElementById('btnPanelForm');
        const btnGallery = document.getElementById('btnPanelGallery');
        const btnSchedule = document.getElementById('btnPanelSchedule');
        const panelForm = document.getElementById('panelForm');
        const panelGallery = document.getElementById('panelGallery');
        const panelSchedule = document.getElementById('panelSchedule');
        if (!btnForm || !btnGallery || !btnSchedule || !panelForm || !panelGallery || !panelSchedule) return;

        function setActive(which) {
            const isForm = which === 'form';
            const isGallery = which === 'gallery';
            const isSchedule = which === 'schedule';
            btnForm.classList.toggle('active', isForm);
            btnGallery.classList.toggle('active', isGallery);
            btnSchedule.classList.toggle('active', isSchedule);
            btnForm.setAttribute('aria-selected', isForm ? 'true' : 'false');
            btnGallery.setAttribute('aria-selected', isGallery ? 'true' : 'false');
            btnSchedule.setAttribute('aria-selected', isSchedule ? 'true' : 'false');
            panelForm.classList.toggle('d-none', !isForm);
            panelGallery.classList.toggle('d-none', !isGallery);
            panelSchedule.classList.toggle('d-none', !isSchedule);
            try { localStorage.setItem('walkin_panel', which); } catch (e) {}
        }

        btnForm.addEventListener('click', () => setActive('form'));
        btnGallery.addEventListener('click', () => setActive('gallery'));
        btnSchedule.addEventListener('click', () => setActive('schedule'));

        // default: Gallery first
        let last = null;
        try { last = localStorage.getItem('walkin_panel'); } catch (e) {}
        if (last === 'form') setActive('form');
        else if (last === 'schedule') setActive('schedule');
        else setActive('gallery');
    })();

    // Schedule detail modal (reuse virtual-karir style)
    document.addEventListener('DOMContentLoaded', function () {
        const agendaModal = document.getElementById('agendaDetailModal');
        if (!agendaModal) return;
        agendaModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;
            document.getElementById('agendaModalTitle').textContent = button.getAttribute('data-title') || '';
            document.getElementById('agendaModalOrganizer').textContent = button.getAttribute('data-organizer') || '';
            document.getElementById('agendaModalDate').textContent = button.getAttribute('data-date') || '';
            document.getElementById('agendaModalLocation').textContent = button.getAttribute('data-location') || '';
            const img = document.getElementById('agendaModalImage');
            const imgUrl = button.getAttribute('data-image');
            if (imgUrl) {
                img.src = imgUrl;
                img.alt = button.getAttribute('data-title') || 'Agenda Image';
                img.onerror = function () {
                    this.src = 'https://via.placeholder.com/400x300?text=No+Image';
                };
            } else {
                img.src = 'https://via.placeholder.com/400x300?text=No+Image';
                img.alt = 'No Image';
            }
            const regUrl = button.getAttribute('data-registration');
            if (regUrl) {
                document.getElementById('agendaModalRegistration').innerHTML =
                    '<a href="' + regUrl + '" target="_blank" class="btn btn-success btn-sm mb-2"><i class="fa fa-link me-1"></i> Link Pendaftaran</a>';
            } else {
                document.getElementById('agendaModalRegistration').innerHTML = '';
            }
            document.getElementById('agendaModalDescription').textContent = button.getAttribute('data-description') || '';
        });
    });

    // Walk-in gallery UI (dynamic, admin-managed)
    (function () {
        const feedUrl = @json(route('walkin-gallery.feed'));
        const commentUrl = @json(route('walkin-gallery.comments.store'));
        const csrf = @json(csrf_token());

        const loadingEl = document.getElementById('walkinGalleryLoading');
        const gridEl = document.getElementById('walkinGalleryGrid');
        const companiesEl = document.getElementById('walkinGalleryCompanies');
        const companyDetailEl = document.getElementById('walkinGalleryCompanyDetail');
        const companyTitleEl = document.getElementById('walkinGalleryCompanyTitle');
        const companyBackBtn = document.getElementById('btnCompanyBack');
        const commentsWrapEl = document.getElementById('walkinGalleryComments');
        const commentListEl = document.getElementById('walkinGalleryCommentList');
        const alertEl = document.getElementById('walkinGalleryAlert');
        const refreshBtn = document.getElementById('btnGalleryRefresh');
        const commentForm = document.getElementById('walkinGalleryCommentForm');
        const commentCompanyInput = document.getElementById('walkinGalleryCommentCompany');

        if (!loadingEl || !gridEl || !companiesEl || !companyDetailEl || !companyTitleEl || !companyBackBtn || !commentsWrapEl || !commentListEl || !alertEl || !commentCompanyInput) return;

        function showAlert(text, type = 'info') {
            alertEl.className = `alert alert-${type} py-2 px-3`;
            alertEl.textContent = text;
            alertEl.classList.remove('d-none');
            setTimeout(() => alertEl.classList.add('d-none'), 3500);
        }

        function storageUrl(path) {
            if (!path) return '';
            const clean = String(path).replace(/^storage[\\\/]/, '').replace(/^[\\\/]+/, '');
            return '/storage/' + clean.replace(/\\/g, '/');
        }

        function renderGrid(items) {
            gridEl.innerHTML = '';
            if (!items || items.length === 0) {
                gridEl.innerHTML = `<div class="text-muted small">Belum ada dokumentasi.</div>`;
                return;
            }

            items.forEach((item) => {
                const type = item.type;
                const isPhoto = type === 'photo';
                const isVideoUpload = type === 'video_upload';
                const isVideoEmbed = type === 'video_embed';

                const thumb = isPhoto
                    ? storageUrl(item.media_path)
                    : (item.thumbnail_path ? storageUrl(item.thumbnail_path) : (item.embed_thumbnail_url || ''));

                const icon = isPhoto ? 'bi-image' : 'bi-play-circle';
                const label = isPhoto ? 'Foto' : 'Video';

                const col = document.createElement('div');
                col.className = 'col-12 col-sm-6 col-xl-4';
                col.innerHTML = `
                    <div class="walkin-media-card" role="button" tabindex="0"
                         data-item='${encodeURIComponent(JSON.stringify(item))}'>
                        <div class="walkin-media-thumb" style="background-image:url('${thumb ? thumb : ''}')">
                            <div class="walkin-media-badge"><i class="bi ${icon} me-1"></i>${label}</div>
                            <div class="walkin-media-grad"></div>
                        </div>
                        <div class="walkin-media-caption">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <div class="fw-semibold text-truncate">${escapeHtml(item.title || label)}</div>
                                <i class="bi bi-arrow-up-right text-muted"></i>
                            </div>
                            <div class="text-muted small walkin-2line">${escapeHtml(item.caption || '')}</div>
                        </div>
                    </div>
                `;
                gridEl.appendChild(col);
            });
        }

        function renderCompanies(companies) {
            companiesEl.innerHTML = '';
            if (!companies || companies.length === 0) {
                companiesEl.innerHTML = `<div class="text-muted small">Belum ada dokumentasi.</div>`;
                return;
            }
            companies.forEach((c) => {
                const name = c.company_name || 'Umum';
                const count = c.count || 0;
                const coverType = c.cover_type || 'photo';
                const thumb = (c.cover_thumbnail_path ? storageUrl(c.cover_thumbnail_path) : (c.cover_media_path ? storageUrl(c.cover_media_path) : (c.cover_embed_thumbnail_url || '')));
                const badgeIcon = (coverType === 'photo') ? 'bi-image' : 'bi-play-circle';

                const col = document.createElement('div');
                col.className = 'col-12 col-lg-6';
                col.innerHTML = `
                    <div class="walkin-company-card" role="button" tabindex="0" data-company="${encodeURIComponent(name)}">
                        <div class="walkin-company-thumb" style="background-image:url('${thumb ? thumb : ''}')">
                            <div class="walkin-company-badge"><i class="bi ${badgeIcon} me-1"></i>${escapeHtml(String(count))} item</div>
                            <div class="walkin-company-grad"></div>
                        </div>
                        <div class="walkin-company-body">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <div class="fw-semibold text-truncate">${escapeHtml(name)}</div>
                                <span class="walkin-chip">Lihat</span>
                            </div>
                            <div class="text-muted small">Dokumentasi & komentar terbaru</div>
                        </div>
                    </div>
                `;
                companiesEl.appendChild(col);
            });
        }

        function renderComments(comments) {
            commentListEl.innerHTML = '';
            const countEl = document.getElementById('walkinCommentCount');
            if (countEl) countEl.textContent = (comments && comments.length) ? `${comments.length} komentar` : '';
            if (!comments || comments.length === 0) {
                commentListEl.innerHTML = `<div class="text-muted small">Belum ada komentar.</div>`;
                return;
            }
            comments.forEach((c) => {
                const card = document.createElement('div');
                card.className = 'walkin-comment-card';
                const date = c.created_at ? String(c.created_at).slice(0, 10) : '';
                const name = (c.name || '').trim();
                const initial = name ? name.slice(0, 1).toUpperCase() : '?';
                card.innerHTML = `
                    <div class="d-flex align-items-start gap-2">
                        <div class="walkin-avatar">${escapeHtml(initial)}</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="fw-semibold">${escapeHtml(name)}</div>
                                <div class="text-muted small">${escapeHtml(date)}</div>
                            </div>
                            <div class="small mt-1">${escapeHtml(c.comment || '')}</div>
                        </div>
                    </div>
                `;
                commentListEl.appendChild(card);
            });
        }

        function escapeHtml(str) {
            return String(str ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        let currentFilter = 'all';
        let currentCompany = '';
        let lastFeed = { items: [], comments: [], companies: [] };

        function setView(mode) {
            // mode: companies | company
            if (mode === 'companies') {
                companiesEl.classList.remove('d-none');
                companyDetailEl.classList.add('d-none');
                commentsWrapEl.classList.add('d-none');
            } else {
                companiesEl.classList.add('d-none');
                companyDetailEl.classList.remove('d-none');
                commentsWrapEl.classList.remove('d-none');
            }
        }

        async function loadCompanies() {
            loadingEl.classList.remove('d-none');
            companiesEl.classList.add('d-none');
            companyDetailEl.classList.add('d-none');
            commentsWrapEl.classList.add('d-none');
            try {
                const res = await fetch(feedUrl, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                lastFeed = data || { companies: [] };
                loadingEl.classList.add('d-none');
                setView('companies');
                companiesEl.classList.remove('d-none');
                renderCompanies(lastFeed.companies || []);
            } catch (e) {
                loadingEl.classList.add('d-none');
                showAlert('Gagal memuat daftar perusahaan. Coba refresh.', 'warning');
            }
        }

        async function loadCompany(company, filter = 'all') {
            currentFilter = filter || 'all';
            currentCompany = company;
            loadingEl.classList.remove('d-none');
            setView('company');
            try {
                const url = new URL(feedUrl, window.location.origin);
                url.searchParams.set('company', company);
                if (filter && filter !== 'all') url.searchParams.set('type', filter);
                const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                lastFeed = data || { items: [], comments: [] };

                loadingEl.classList.add('d-none');
                companyTitleEl.textContent = company;
                commentCompanyInput.value = company;
                renderGrid(lastFeed.items || []);
                renderComments(lastFeed.comments || []);
            } catch (e) {
                loadingEl.classList.add('d-none');
                showAlert('Gagal memuat galeri. Coba refresh.', 'warning');
            }
        }

        if (refreshBtn) refreshBtn.addEventListener('click', () => {
            if (currentCompany) return loadCompany(currentCompany, currentFilter);
            return loadCompanies();
        });

        companiesEl.addEventListener('click', (e) => {
            const card = e.target && e.target.closest ? e.target.closest('.walkin-company-card') : null;
            if (!card) return;
            const raw = card.getAttribute('data-company');
            if (!raw) return;
            const company = decodeURIComponent(raw);
            loadCompany(company, 'all');
        });

        companyBackBtn.addEventListener('click', () => {
            currentCompany = '';
            commentCompanyInput.value = '';
            loadCompanies();
        });

        // Modal viewer
        const modalEl = document.getElementById('walkinGalleryModal');
        let bsModal = null;
        if (modalEl && window.bootstrap) {
            bsModal = new bootstrap.Modal(modalEl);
        }

        function openItem(item) {
            const body = document.getElementById('walkinGalleryModalBody');
            const title = document.getElementById('walkinGalleryModalTitle');
            if (!body || !title) return;
            title.textContent = item.title || (item.type === 'photo' ? 'Foto' : 'Video');

            if (item.type === 'photo') {
                body.innerHTML = `
                    <div class="walkin-modal-media">
                        <img src="${storageUrl(item.media_path)}" class="walkin-modal-img" alt="">
                    </div>
                    ${item.caption ? `<div class="text-muted mt-2">${escapeHtml(item.caption)}</div>` : ``}
                `;
            } else if (item.type === 'video_upload') {
                body.innerHTML = `
                    <video class="w-100 rounded" controls src="${storageUrl(item.media_path)}"></video>
                    ${item.caption ? `<div class="text-muted mt-2">${escapeHtml(item.caption)}</div>` : ``}
                `;
            } else {
                const src = item.embed_url || '';
                body.innerHTML = `
                    <div class="ratio ratio-16x9">
                        <iframe src="${src}" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    ${item.caption ? `<div class="text-muted mt-2">${escapeHtml(item.caption)}</div>` : ``}
                `;
            }

            if (bsModal) bsModal.show();
        }

        gridEl.addEventListener('click', (e) => {
            const card = e.target && e.target.closest ? e.target.closest('.walkin-media-card') : null;
            if (!card) return;
            const raw = card.getAttribute('data-item');
            if (!raw) return;
            try {
                const item = JSON.parse(decodeURIComponent(raw));
                openItem(item);
            } catch (err) {}
        });

        // Comment submit
        if (commentForm) {
            commentForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fd = new FormData(commentForm);
                const payload = {
                    walkin_gallery_item_id: null,
                    company_name: fd.get('company_name'),
                    name: fd.get('name'),
                    comment: fd.get('comment'),
                    website: fd.get('website'),
                };
                try {
                    const res = await fetch(commentUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify(payload),
                    });
                    if (!res.ok) {
                        showAlert('Gagal mengirim komentar. Coba lagi.', 'warning');
                        return;
                    }
                    const data = await res.json();
                    commentForm.reset();
                    // keep current company in hidden input after reset
                    commentCompanyInput.value = currentCompany || payload.company_name || '';
                    showAlert((data && data.message) ? data.message : 'Komentar terkirim.', 'success');
                } catch (err) {
                    showAlert('Gagal mengirim komentar. Coba lagi.', 'warning');
                }
            });
        }

        // initial load: show company cards
        loadCompanies();
    })();
</script>
@endpush
<style>
    .walkin-segmented {
        display: inline-flex;
        width: 100%;
        background: #eef2f7;
        border-radius: 999px;
        padding: 6px;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.06);
        gap: 6px;
    }
    .walkin-seg-btn {
        flex: 1;
        border: 0;
        border-radius: 999px;
        padding: 10px 12px;
        font-weight: 600;
        background: transparent;
        color: #475569;
    }
    .walkin-seg-btn.active {
        background: #ffffff;
        color: #111827;
        box-shadow: 0 6px 16px rgba(2,6,23,0.10);
    }
    .walkin-company-title {
        font-size: 1.15rem;
        line-height: 1.25;
        letter-spacing: -0.01em;
        color: #0f172a;
    }
    .walkin-panel {
        border: 1px solid rgba(15,23,42,0.10);
        border-radius: 16px;
        background: rgba(255,255,255,0.92);
        box-shadow: 0 10px 30px rgba(2,6,23,0.06);
        backdrop-filter: blur(8px);
    }
    .walkin-sticky {
        position: sticky;
        top: 88px;
    }
    @media (max-width: 991.98px) {
        .walkin-sticky { position: static; top: auto; }
    }

    .walkin-2line {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
    }
    .walkin-media-card {
        border: 1px solid rgba(15,23,42,0.10);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 8px 22px rgba(2,6,23,0.06);
        transition: transform .14s ease, box-shadow .14s ease, border-color .14s ease;
    }
    .walkin-media-card:hover {
        transform: translateY(-2px);
        border-color: rgba(15,23,42,0.16);
        box-shadow: 0 16px 40px rgba(2,6,23,0.12);
    }
    .walkin-media-thumb {
        position: relative;
        width: 100%;
        height: 170px;
        background-color: #eef2ff;
        background-size: cover;
        background-position: center;
    }
    .walkin-media-badge {
        position: absolute;
        left: 12px;
        top: 12px;
        background: rgba(15,23,42,0.78);
        color: #fff;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        z-index: 2;
    }
    .walkin-media-grad {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(2,6,23,0.0) 30%, rgba(2,6,23,0.35) 100%);
        opacity: 0.85;
        pointer-events: none;
    }
    .walkin-media-caption {
        padding: 12px 12px 14px 12px;
    }

    .walkin-company-card {
        border: 1px solid rgba(15,23,42,0.10);
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 26px rgba(2,6,23,0.06);
        transition: transform .14s ease, box-shadow .14s ease, border-color .14s ease;
    }
    .walkin-company-card:hover {
        transform: translateY(-2px);
        border-color: rgba(15,23,42,0.16);
        box-shadow: 0 18px 44px rgba(2,6,23,0.12);
    }
    .walkin-company-thumb {
        position: relative;
        width: 100%;
        height: 140px;
        background-color: #eef2ff;
        background-size: cover;
        background-position: center;
    }
    .walkin-company-badge {
        position: absolute;
        left: 12px;
        top: 12px;
        background: rgba(15,23,42,0.78);
        color: #fff;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        z-index: 2;
    }
    .walkin-company-grad {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(2,6,23,0.0) 20%, rgba(2,6,23,0.40) 100%);
        opacity: 0.9;
        pointer-events: none;
    }
    .walkin-company-body {
        padding: 12px 12px 14px 12px;
    }
    .walkin-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 999px;
        background: rgba(37,99,235,0.10);
        color: #1d4ed8;
        border: 1px solid rgba(37,99,235,0.15);
        flex: 0 0 auto;
    }

    .walkin-comment-compose textarea.form-control {
        resize: vertical;
        min-height: 88px;
    }
    .walkin-comment-card {
        border: 1px solid rgba(15,23,42,0.10);
        border-radius: 14px;
        background: #ffffff;
        padding: 10px 10px;
        box-shadow: 0 6px 16px rgba(2,6,23,0.05);
    }
    .walkin-avatar {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(37,99,235,0.18), rgba(16,185,129,0.16));
        border: 1px solid rgba(15,23,42,0.10);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #0f172a;
        flex: 0 0 auto;
    }

    /* Modal media (neat for big photos) */
    #walkinGalleryModal .modal-body {
        padding: 14px;
    }
    .walkin-modal-media {
        width: 100%;
        max-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0b1220;
        border-radius: 16px;
        overflow: hidden;
    }
    .walkin-modal-img {
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: contain;
        display: block;
    }
    @media (max-width: 576px) {
        .walkin-modal-media { max-height: 60vh; }
        .walkin-modal-img { max-height: 60vh; }
    }

    /* Schedule table */
    .walkin-schedule-table td, .walkin-schedule-table th {
        vertical-align: top;
    }
    .walkin-schedule-icon {
        color: #2563eb;
        margin-right: 8px;
    }
    .walkin-schedule-date {
        font-size: 1.05rem;
        letter-spacing: -0.01em;
        color: #0f172a;
    }
    .walkin-schedule-odd {
        background: rgba(2,6,23,0.02);
    }
    .walkin-schedule-upcoming {
        background: rgba(37,99,235,0.10) !important;
    }

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
