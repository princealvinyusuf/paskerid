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
        'company_sectors_id',
        'institution_name',
        'business_sector',
        'institution_address',
        'type_of_partnership_id',
        'pasker_room_id',
        'other_pasker_room',
        'pasker_facility_id',
        'other_pasker_facility',
        'schedule',
        'request_letter',
        'status',
    ];
} 