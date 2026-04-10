<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobHoaxReport extends Model
{
    protected $fillable = [
        'email_terduga_pelaku',
        'tanggal_terdeteksi',
        'nama_perusahaan_digunakan',
        'nama_hr_digunakan',
        'provinsi',
        'kota',
        'nomor_kontak_terduga',
        'platform_sumber',
        'tautan_informasi',
        'bukti_pendukung_path',
        'bukti_pendukung_nama',
        'kronologi',
        'pelapor_nama',
        'pelapor_email',
        'laporan_mitra',
        'tindak_lanjut_tutup_lowongan',
        'tindak_lanjut_tutup_akun_perusahaan',
        'tindak_lanjut_lainnya_checked',
        'tindak_lanjut_lainnya_text',
        'status',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'tanggal_terdeteksi' => 'date',
        'tindak_lanjut_tutup_lowongan' => 'boolean',
        'tindak_lanjut_tutup_akun_perusahaan' => 'boolean',
        'tindak_lanjut_lainnya_checked' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];
}
