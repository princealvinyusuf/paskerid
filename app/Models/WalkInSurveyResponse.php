<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkInSurveyResponse extends Model
{
    use HasFactory;

    protected $table = 'walk_in_survey_responses';

    protected $fillable = [
        'company_walk_in_survey_id',
        'walk_in_initiator_id',
        'company_name_snapshot',
        'walkin_initiator_snapshot',
        'walkin_date',
        'email',
        'name',
        'phone',
        'domisili',
        'domisili_other',
        'gender',
        'age_range',
        'education',
        'info_sources',
        'info_source_other',
        'job_portals',
        'job_portal_other',
        'strengths',
        'strengths_other',
        'missing_infos',
        'missing_info_other',
        'general_feedback',
        'rating_info',
        'feedback_info',
        'rating_facility',
        'feedback_facility',
        'rating_registration',
        'feedback_registration',
        'rating_quality_quantity',
        'feedback_quality_quantity',
        'rating_committee_help',
        'feedback_committee_help',
        'rating_access_info',
        'feedback_access_info',
        'rating_satisfaction',
        'improvement_aspects',
        'feedback_improvement_aspects',
    ];

    protected $casts = [
        'company_walk_in_survey_id' => 'integer',
        'walk_in_initiator_id' => 'integer',
        'walkin_date' => 'date',
        'info_sources' => 'array',
        'job_portals' => 'array',
        'strengths' => 'array',
        'missing_infos' => 'array',
        'improvement_aspects' => 'array',
        'rating_info' => 'integer',
        'rating_facility' => 'integer',
        'rating_registration' => 'integer',
        'rating_quality_quantity' => 'integer',
        'rating_committee_help' => 'integer',
        'rating_access_info' => 'integer',
        'rating_satisfaction' => 'integer',
    ];
}


