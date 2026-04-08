<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_companies')) {
            Schema::create('partner_companies', function (Blueprint $table) {
                $table->id();
                $table->string('company_name', 255);
                $table->string('gallery_company_name', 255)->nullable();
                $table->string('logo_path', 255)->nullable();
                $table->decimal('rating', 3, 1)->default(0);
                $table->unsignedInteger('review_count')->default(0);
                $table->unsignedInteger('job_count')->default(0);
                $table->text('profile_summary')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['is_active', 'sort_order']);
                $table->index('gallery_company_name');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_companies');
    }
};

