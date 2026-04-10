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
        'status',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'tanggal_terdeteksi' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];
}
