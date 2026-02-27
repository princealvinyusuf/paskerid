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
        Schema::create('career_boostday_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('job_title', 200)->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->text('testimony');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_boostday_testimonials');
    }
};

