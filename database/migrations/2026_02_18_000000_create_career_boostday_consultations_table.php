<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_boostday_consultations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('whatsapp', 30);
            $table->string('status', 120);
            $table->string('jenis_konseling', 120)->default('Online (Zoom)');
            $table->string('jadwal_konseling', 120);
            $table->string('pendidikan_terakhir', 120)->nullable();
            $table->string('cv_path')->nullable();
            $table->string('cv_original_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_boostday_consultations');
    }
};


