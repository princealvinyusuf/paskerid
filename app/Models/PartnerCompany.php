<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerCompany extends Model
{
    use HasFactory;

    protected $table = 'partner_companies';

    protected $fillable = [
        'company_name',
        'gallery_company_name',
        'logo_path',
        'rating',
        'review_count',
        'job_count',
        'profile_summary',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'float',
        'review_count' => 'integer',
        'job_count' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}

