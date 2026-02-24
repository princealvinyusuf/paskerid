<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mini_jobi_job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mini_jobi_job_id')->constrained('mini_jobi_jobs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('submitted');
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();

            $table->unique(['mini_jobi_job_id', 'user_id'], 'uniq_job_user_application');
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mini_jobi_job_applications');
    }
};

