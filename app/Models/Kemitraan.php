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
        'tipe_penyelenggara',
        'pasker_room_id',
        'other_pasker_room',
        'pasker_facility_id',
        'other_pasker_facility',
        'schedule',
        'scheduletimestart',
        'scheduletimefinish',
        'request_letter',
        'foto_kartu_pegawai_pic',
        'status',
    ];

    public function rooms()
    {
        return $this->belongsToMany(PaskerRoom::class, 'kemitraan_pasker_room', 'kemitraan_id', 'pasker_room_id')->withTimestamps();
    }

    public function facilities()
    {
        return $this->belongsToMany(PaskerFacility::class, 'kemitraan_pasker_facility', 'kemitraan_id', 'pasker_facility_id')->withTimestamps();
    }

    public function bookedDates()
    {
        return $this->hasMany(BookedDate::class, 'kemitraan_id');
    }

    public function typeOfPartnership()
    {
        return $this->belongsTo(TypeOfPartnership::class, 'type_of_partnership_id');
    }

    public function detailLowongan()
    {
        return $this->hasMany(KemitraanDetailLowongan::class, 'kemitraan_id');
    }
} 