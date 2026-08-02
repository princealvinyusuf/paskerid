<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kemitraan_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');
            $table->string('activity_theme');
            $table->date('activity_date');
            $table->time('activity_start_time');
            $table->time('activity_end_time');
            $table->string('activity_timezone', 10);
            $table->string('activity_location');
            $table->string('activity_organizer');
            $table->unsignedInteger('participants_invited')->nullable();
            $table->unsignedInteger('participants_attended')->nullable();
            $table->unsignedInteger('respondent_count')->nullable();

            $table->string('respondent_name')->nullable();
            $table->string('respondent_organization')->nullable();
            $table->string('respondent_role')->nullable();
            $table->string('respondent_contact')->nullable();
            $table->string('respondent_category');
            $table->string('respondent_category_other')->nullable();
            $table->string('participation_mode');

            $table->text('form_a_special_notes')->nullable();
            $table->text('form_a_most_useful_material')->nullable();
            $table->text('form_a_material_needs')->nullable();
            $table->text('form_a_facility_notes')->nullable();
            $table->text('form_a_proposed_followup')->nullable();
            $table->string('overall_satisfaction');
            $table->string('willing_to_follow_up');
            $table->json('preferred_channels');
            $table->text('best_part');
            $table->text('needs_improvement');
            $table->text('needed_topics');
            $table->text('additional_suggestions')->nullable();

            $table->string('evaluator_name');
            $table->string('evaluator_position');
            $table->string('evaluator_unit');
            $table->date('evaluation_date');
            $table->string('evaluator_role');
            $table->string('evaluator_role_other')->nullable();

            $table->text('form_b_planning_constraints')->nullable();
            $table->text('form_b_incident_notes')->nullable();
            $table->text('form_b_good_practices')->nullable();
            $table->text('form_b_root_issues')->nullable();
            $table->text('form_b_priority_recommendations')->nullable();

            $table->unsignedInteger('recap_participants_present')->nullable();
            $table->unsignedInteger('recap_forms_distributed')->nullable();
            $table->unsignedInteger('recap_forms_received')->nullable();
            $table->unsignedInteger('recap_forms_valid')->nullable();
            $table->decimal('recap_response_rate_percent', 5, 2)->nullable();
            $table->string('recap_collection_period')->nullable();
            $table->string('recap_highest_aspect')->nullable();
            $table->decimal('recap_highest_value', 5, 2)->nullable();
            $table->string('recap_lowest_aspect')->nullable();
            $table->decimal('recap_lowest_value', 5, 2)->nullable();
            $table->decimal('recap_overall_score', 5, 2)->nullable();
            $table->string('recap_result_category')->nullable();
            $table->decimal('recap_internal_target', 5, 2)->nullable();
            $table->string('recap_achievement_status')->nullable();
            $table->text('recap_general_conclusion')->nullable();
            $table->json('qualitative_feedback')->nullable();
            $table->json('indicator_achievements')->nullable();

            $table->string('priority_level')->nullable();
            $table->string('monitoring_coordinator')->nullable();
            $table->string('monitoring_frequency')->nullable();
            $table->json('monitoring_media')->nullable();
            $table->string('monitoring_media_other')->nullable();
            $table->date('first_review_date')->nullable();
            $table->text('evidence_documents')->nullable();
            $table->text('leader_notes')->nullable();

            $table->string('execution_status')->nullable();
            $table->string('recommendation_status')->nullable();
            $table->text('recommendation_1')->nullable();
            $table->text('recommendation_2')->nullable();
            $table->text('recommendation_3')->nullable();

            $table->string('prepared_by_name')->nullable();
            $table->string('prepared_by_nip', 100)->nullable();
            $table->date('prepared_by_date')->nullable();
            $table->string('verified_by_name')->nullable();
            $table->string('verified_by_nip', 100)->nullable();
            $table->date('verified_by_date')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->string('approved_by_nip', 100)->nullable();
            $table->date('approved_by_date')->nullable();

            $table->string('document_code', 50)->default('KEP-LEK-01');
            $table->string('document_version', 50)->default('1.0');
            $table->date('document_effective_date')->nullable();
            $table->string('document_status')->nullable();
            $table->string('document_storage_location')->nullable();
            $table->string('document_retention_period', 100)->nullable();
            $table->string('document_access_level')->nullable();
            $table->string('document_owner')->nullable();
            $table->text('document_usage_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kemitraan_evaluations');
    }
};
