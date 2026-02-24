<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniJobiJobApplication extends Model
{
    protected $table = 'mini_jobi_job_applications';

    protected $fillable = [
        'mini_jobi_job_id',
        'user_id',
        'status',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];
}

