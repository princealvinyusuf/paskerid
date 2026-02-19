<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerBoostdayConsultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'whatsapp',
        'status',
        'jenis_konseling',
        'jadwal_konseling',
        'pendidikan_terakhir',
        'jurusan',
        'cv_path',
        'cv_original_name',
    ];
}


