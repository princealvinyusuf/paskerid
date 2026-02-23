<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('walk_in_survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_walk_in_survey_id')->constrained('company_walk_in_survey')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('company_name_snapshot');

            $table->string('email');
            $table->string('name');
            $table->string('phone', 30);
            $table->string('domisili');
            $table->string('domisili_other')->nullable();
            $table->string('gender', 50);
            $table->string('age_range', 50);
            $table->string('education', 100);

            $table->json('info_sources');
            $table->string('info_source_other')->nullable();
            $table->json('job_portals');
            $table->string('job_portal_other')->nullable();
            $table->json('strengths');
            $table->string('strengths_other')->nullable();
            $table->json('missing_infos');
            $table->string('missing_info_other')->nullable();

            $table->text('general_feedback');

            $table->unsignedTinyInteger('rating_info');
            $table->text('feedback_info');
            $table->unsignedTinyInteger('rating_facility');
            $table->text('feedback_facility');
            $table->unsignedTinyInteger('rating_registration');
            $table->text('feedback_registration');
            $table->unsignedTinyInteger('rating_quality_quantity');
            $table->text('feedback_quality_quantity');
            $table->unsignedTinyInteger('rating_committee_help');
            $table->text('feedback_committee_help');
            $table->unsignedTinyInteger('rating_access_info');
            $table->text('feedback_access_info');
            $table->unsignedTinyInteger('rating_satisfaction');

            $table->json('improvement_aspects');
            $table->text('feedback_improvement_aspects');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('walk_in_survey_responses');
    }
};


