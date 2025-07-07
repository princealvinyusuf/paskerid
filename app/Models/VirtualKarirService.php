<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualKarirService extends Model
{
    protected $table = 'virtual_karir_services';
    protected $fillable = [
        'icon', 'title', 'description', 'link',
    ];
} 