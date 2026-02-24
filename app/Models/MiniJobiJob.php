<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniJobiJob extends Model
{
    protected $table = 'mini_jobi_jobs';

    protected $fillable = [
        'title',
        'company_name',
        'location',
        'employment_type',
        'category',
        'salary_range',
        'description',
        'requirements',
        'apply_url',
        'deadline_date',
        'is_active',
    ];

    protected $casts = [
        'deadline_date' => 'date',
        'is_active' => 'boolean',
    ];
}

