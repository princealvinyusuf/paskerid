<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingResultEvidence extends Model
{
    use HasFactory;

    /**
     * Laravel treats "evidence" as an uncountable noun, so the default table
     * name would be "counseling_result_evidence". Our migration creates
     * "counseling_result_evidences", so we pin it here explicitly.
     */
    protected $table = 'counseling_result_evidences';

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


