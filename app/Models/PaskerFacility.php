<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaskerFacility extends Model
{
    protected $table = 'pasker_facility';

    protected $fillable = [
        'facility_name',
        'walkin_location_id',
    ];

    public function location()
    {
        return $this->belongsTo(WalkinLocation::class, 'walkin_location_id');
    }
}
