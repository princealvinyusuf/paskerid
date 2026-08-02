<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramKemitraanEvaluation extends Model
{
    use HasFactory;

    protected $table = 'program_kemitraan_evaluations';

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
        'respondent_name',
        'respondent_organization',
        'respondent_role',
        'respondent_contact',
        'respondent_category',
        'respondent_category_other',
        'participation_mode',
        'form_a_special_notes',
        'form_a_most_useful_material',
        'form_a_material_needs',
        'form_a_facility_notes',
        'form_a_proposed_followup',
        'overall_satisfaction',
        'willing_to_follow_up',
        'preferred_channels',
        'best_part',
        'needs_improvement',
        'needed_topics',
        'additional_suggestions',
        'evaluator_name',
        'evaluator_position',
        'evaluator_unit',
        'evaluation_date',
        'evaluator_role',
        'evaluator_role_other',
        'form_b_planning_constraints',
        'form_b_incident_notes',
        'form_b_good_practices',
        'form_b_root_issues',
        'form_b_priority_recommendations',
        'recap_participants_present',
        'recap_forms_distributed',
        'recap_forms_received',
        'recap_forms_valid',
        'recap_response_rate_percent',
        'recap_collection_period',
        'recap_highest_aspect',
        'recap_highest_value',
        'recap_lowest_aspect',
        'recap_lowest_value',
        'recap_overall_score',
        'recap_result_category',
        'recap_internal_target',
        'recap_achievement_status',
        'recap_general_conclusion',
        'qualitative_feedback',
        'indicator_achievements',
        'priority_level',
        'monitoring_coordinator',
        'monitoring_frequency',
        'monitoring_media',
        'monitoring_media_other',
        'first_review_date',
        'evidence_documents',
        'leader_notes',
        'execution_status',
        'recommendation_status',
        'recommendation_1',
        'recommendation_2',
        'recommendation_3',
        'prepared_by_name',
        'prepared_by_nip',
        'prepared_by_date',
        'verified_by_name',
        'verified_by_nip',
        'verified_by_date',
        'approved_by_name',
        'approved_by_nip',
        'approved_by_date',
        'document_code',
        'document_version',
        'document_effective_date',
        'document_status',
        'document_storage_location',
        'document_retention_period',
        'document_access_level',
        'document_owner',
        'document_usage_notes',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'evaluation_date' => 'date',
        'first_review_date' => 'date',
        'prepared_by_date' => 'date',
        'verified_by_date' => 'date',
        'approved_by_date' => 'date',
        'document_effective_date' => 'date',
        'preferred_channels' => 'array',
        'monitoring_media' => 'array',
        'qualitative_feedback' => 'array',
        'indicator_achievements' => 'array',
        'recap_response_rate_percent' => 'decimal:2',
        'recap_highest_value' => 'decimal:2',
        'recap_lowest_value' => 'decimal:2',
        'recap_overall_score' => 'decimal:2',
        'recap_internal_target' => 'decimal:2',
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(ProgramKemitraanEvaluationAnswer::class, 'evaluation_id');
    }

    public function rtlItems(): HasMany
    {
        return $this->hasMany(ProgramKemitraanEvaluationRtlItem::class, 'evaluation_id');
    }
}
