<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KemitraanDetailLowongan extends Model
{
    use HasFactory;

    protected $table = 'kemitraan_detail_lowongan';

    protected $fillable = [
        'kemitraan_id',
        'jabatan_yang_dibuka',
        'jumlah_kebutuhan',
        'gender',
        'pendidikan_terakhir',
        'pengalaman_kerja',
        'kompetensi_yang_dibutuhkan',
        'tahapan_seleksi',
        'lokasi_penempatan',
        'nama_perusahaan',
    ];

    protected $casts = [
        'nama_perusahaan' => 'array',
    ];

    public function kemitraan()
    {
        return $this->belongsTo(Kemitraan::class, 'kemitraan_id');
    }
}


