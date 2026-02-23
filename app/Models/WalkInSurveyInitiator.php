<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkInSurveyInitiator extends Model
{
    use HasFactory;

    protected $table = 'walk_in_survey_initiators';

    protected $fillable = [
        'initiator_name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}

