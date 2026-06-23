<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKemitraanSubmission extends Model
{
    use HasFactory;

    protected $table = 'program_kemitraan_submissions';

    protected $fillable = [
        'pic_name',
        'pic_position',
        'pic_email',
        'pic_whatsapp',
        'institution_category',
        'instansi_lembaga_name',
        'institution_name',
        'business_sector',
        'institution_address',
        'proposed_activity_type',
        'request_letter',
        'status',
    ];
}
