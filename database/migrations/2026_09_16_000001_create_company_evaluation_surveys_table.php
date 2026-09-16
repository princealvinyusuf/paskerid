<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_evaluation_surveys', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('respondent_name');
            $table->string('position');
            $table->string('position_other')->nullable();
            $table->string('company_email');
            $table->string('phone', 30);
            $table->string('province');
            $table->string('input_assistance');
            $table->string('obtained_worker', 10);
            $table->string('waiting_time')->nullable();
            $table->json('information_sources');
            $table->string('information_source_other')->nullable();

            $table->unsignedTinyInteger('service_requirements_ease');
            $table->unsignedTinyInteger('system_procedure_ease');
            $table->unsignedTinyInteger('service_speed');
            $table->unsignedTinyInteger('fee_compliance');
            $table->unsignedTinyInteger('product_quality');
            $table->unsignedTinyInteger('officer_competence');
            $table->unsignedTinyInteger('officer_behavior');
            $table->unsignedTinyInteger('facility_quality');
            $table->unsignedTinyInteger('complaint_media_completeness');
            $table->unsignedTinyInteger('karirhub_procedure_ease');
            $table->unsignedTinyInteger('procedure_information_fit');
            $table->unsignedTinyInteger('admin_service_hours_fit');
            $table->unsignedTinyInteger('candidate_fit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_evaluation_surveys');
    }
};
