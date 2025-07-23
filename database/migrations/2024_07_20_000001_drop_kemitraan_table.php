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
        Schema::dropIfExists('kemitraan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('kemitraan', function (Blueprint $table) {
            $table->id();
            $table->string('pic_name'); // Nama Penanggung Jawab (PIC)
            $table->string('pic_position'); // Jabatan Penanggung Jawab
            $table->string('pic_email'); // Alamat Email Penanggung Jawab
            $table->string('pic_whatsapp'); // Nomor WhatsApp Aktif
            $table->string('sector_category'); // Kategori/Sektor Instansi
            $table->string('institution_name'); // Nama Instansi
            $table->string('business_sector')->nullable(); // Sektor Lapangan Usaha
            $table->string('institution_address'); // Alamat Instansi
            $table->string('partnership_type'); // Jenis Kemitraan yang Diajukan
            $table->text('needs')->nullable(); // Kebutuhan yang Diajukan
            $table->string('schedule')->nullable(); // Usulan Jadwal Kegiatan
            $table->string('request_letter')->nullable(); // Surat Permohonan Kemitraan (file path)
            $table->string('status')->default('pending'); // Status kolom dari migrasi tambahan
            $table->timestamps();
        });
    }
}; 