<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniVideo extends Model
{
    protected $fillable = [
        'title',
        'youtube_url',
        'order',
    ];
} 