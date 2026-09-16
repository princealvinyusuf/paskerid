<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEvaluationSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'respondent_name',
        'position',
        'position_other',
        'company_email',
        'phone',
        'province',
        'input_assistance',
        'obtained_worker',
        'waiting_time',
        'information_sources',
        'information_source_other',
        'service_requirements_ease',
        'system_procedure_ease',
        'service_speed',
        'fee_compliance',
        'product_quality',
        'officer_competence',
        'officer_behavior',
        'facility_quality',
        'complaint_media_completeness',
        'karirhub_procedure_ease',
        'procedure_information_fit',
        'admin_service_hours_fit',
        'candidate_fit',
    ];

    protected function casts(): array
    {
        return [
            'information_sources' => 'array',
            'service_requirements_ease' => 'integer',
            'system_procedure_ease' => 'integer',
            'service_speed' => 'integer',
            'fee_compliance' => 'integer',
            'product_quality' => 'integer',
            'officer_competence' => 'integer',
            'officer_behavior' => 'integer',
            'facility_quality' => 'integer',
            'complaint_media_completeness' => 'integer',
            'karirhub_procedure_ease' => 'integer',
            'procedure_information_fit' => 'integer',
            'admin_service_hours_fit' => 'integer',
            'candidate_fit' => 'integer',
        ];
    }
}
