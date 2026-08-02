<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKemitraanEvaluationActivity extends Model
{
    use HasFactory;

    protected $table = 'program_kemitraan_evaluation_activities';

    protected $fillable = [
        'activity_name',
        'activity_theme',
        'activity_date',
        'activity_start_time',
        'activity_end_time',
        'activity_timezone',
        'activity_location',
        'activity_organizer',
        'participants_invited',
        'participants_attended',
        'respondent_count',
        'is_active',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'is_active' => 'boolean',
    ];
}
