<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKemitraanCertificateSetting extends Model
{
    use HasFactory;

    protected $table = 'program_kemitraan_certificate_settings';

    protected $fillable = [
        'signature_image_path',
        'background_image_path',
        'signer_name',
        'certificate_title',
    ];
}
