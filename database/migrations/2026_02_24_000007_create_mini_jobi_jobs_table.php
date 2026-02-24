<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mini_jobi_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company_name');
            $table->string('location');
            $table->string('employment_type')->nullable();
            $table->string('category')->nullable();
            $table->string('salary_range')->nullable();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('apply_url')->nullable();
            $table->date('deadline_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_jobi_jobs');
    }
};

