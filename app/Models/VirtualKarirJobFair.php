<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualKarirJobFair extends Model
{
    protected $table = 'virtual_karir_job_fairs';
    protected $fillable = [
        'title', 'description', 'image_url', 'date', 'author', 'register_url',
    ];
} 