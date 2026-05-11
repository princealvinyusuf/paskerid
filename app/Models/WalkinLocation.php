<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalkinLocation extends Model
{
    protected $table = 'walkin_locations';

    protected $fillable = [
        'location_name',
    ];
}
