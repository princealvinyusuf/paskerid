<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaskerRoom extends Model
{
    protected $table = 'pasker_room';

    protected $fillable = [
        'room_name',
        'walkin_location_id',
        'image_base64',
        'mime_type',
    ];

    public function location()
    {
        return $this->belongsTo(WalkinLocation::class, 'walkin_location_id');
    }
}
