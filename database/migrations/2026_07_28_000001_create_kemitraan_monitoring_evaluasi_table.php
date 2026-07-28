<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kemitraan_monitoring_evaluasi')) {
            return;
        }

        Schema::create('kemitraan_monitoring_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kemitraan_id')->unique()->constrained('kemitraan')->onDelete('cascade');
            $table->text('tim_kerja_pelaksana')->nullable();
            $table->text('pic_pusat_pasar_kerja')->nullable();
            $table->text('masalah_hambatan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('dokumentasi_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kemitraan_monitoring_evaluasi');
    }
};
