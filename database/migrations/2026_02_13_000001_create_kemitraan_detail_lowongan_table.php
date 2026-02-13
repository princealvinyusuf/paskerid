<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kemitraan_detail_lowongan')) {
            return;
        }

        Schema::create('kemitraan_detail_lowongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kemitraan_id')->constrained('kemitraan')->onDelete('cascade');

            $table->string('jabatan_yang_dibuka');
            $table->unsignedInteger('jumlah_kebutuhan');
            $table->string('gender')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('pengalaman_kerja')->nullable();
            $table->text('kompetensi_yang_dibutuhkan')->nullable();
            $table->text('tahapan_seleksi')->nullable();
            $table->string('lokasi_penempatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kemitraan_detail_lowongan');
    }
};


