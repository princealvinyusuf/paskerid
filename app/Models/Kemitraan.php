<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kemitraan extends Model
{
    use HasFactory;

    protected $table = 'kemitraan';

    protected $fillable = [
        'pic_name',
        'pic_position',
        'pic_email',
        'pic_whatsapp',
        'sector_category',
        'institution_name',
        'business_sector',
        'institution_address',
        'partnership_type',
        'needs',
        'schedule',
        'request_letter',
    ];
} 