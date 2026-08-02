<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kemitraan_evaluation_activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name')->unique();
            $table->string('activity_theme');
            $table->date('activity_date');
            $table->time('activity_start_time');
            $table->time('activity_end_time');
            $table->string('activity_timezone', 10)->default('WIB');
            $table->string('activity_location');
            $table->string('activity_organizer');
            $table->unsignedInteger('participants_invited')->nullable();
            $table->unsignedInteger('participants_attended')->nullable();
            $table->unsignedInteger('respondent_count')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kemitraan_evaluation_activities');
    }
};
