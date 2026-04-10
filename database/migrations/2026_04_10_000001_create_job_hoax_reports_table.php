<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_hoax_reports', function (Blueprint $table) {
            $table->id();
            $table->string('email_terduga_pelaku', 255);
            $table->date('tanggal_terdeteksi');
            $table->string('nama_perusahaan_digunakan', 255);
            $table->string('nama_hr_digunakan', 255);
            $table->string('provinsi', 150);
            $table->string('kota', 150);
            $table->string('nomor_kontak_terduga', 60)->nullable();
            $table->string('platform_sumber', 120)->nullable();
            $table->string('tautan_informasi', 500)->nullable();
            $table->string('bukti_pendukung_path', 500)->nullable();
            $table->string('bukti_pendukung_nama', 255)->nullable();
            $table->text('kronologi')->nullable();
            $table->string('pelapor_nama', 120)->nullable();
            $table->string('pelapor_email', 255)->nullable();
            $table->string('laporan_mitra', 120)->nullable();
            $table->boolean('tindak_lanjut_tutup_lowongan')->default(false);
            $table->boolean('tindak_lanjut_tutup_akun_perusahaan')->default(false);
            $table->boolean('tindak_lanjut_lainnya_checked')->default(false);
            $table->text('tindak_lanjut_lainnya_text')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('tanggal_terdeteksi');
            $table->index(['provinsi', 'kota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_hoax_reports');
    }
};
