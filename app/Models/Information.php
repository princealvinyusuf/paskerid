<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'type',
        'subject',
        'file_url',
        'iframe_url',
    ];
} 