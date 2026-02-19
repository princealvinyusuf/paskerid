<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_konselor',
        'nama_konseli',
        'tanggal_konseling',
        'jenis_konseling',
        'hal_yang_dibahas',
        'saran_untuk_pencaker',
    ];

    protected $casts = [
        'tanggal_konseling' => 'date',
    ];

    public function evidences()
    {
        return $this->hasMany(CounselingResultEvidence::class);
    }
}


