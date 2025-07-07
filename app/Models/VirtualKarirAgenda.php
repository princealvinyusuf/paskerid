<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualKarirAgenda extends Model
{
    protected $table = 'virtual_karir_agendas';
    protected $fillable = [
        'title', 'description', 'date',
    ];
} 