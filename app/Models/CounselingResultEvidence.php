<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingResultEvidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'counseling_result_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function result()
    {
        return $this->belongsTo(CounselingResult::class, 'counseling_result_id');
    }
}


