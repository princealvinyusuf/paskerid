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
        'logo_image_path',
        'ministry_header_text',
        'signer_name',
        'signer_position',
        'sign_place',
        'certificate_title',
        'participation_role_default',
    ];
}
