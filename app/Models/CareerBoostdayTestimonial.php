<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerBoostdayTestimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'job_title',
        'photo_url',
        'testimony',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}

