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
        Schema::create('kemitraan', function (Blueprint $table) {
            $table->id();
            $table->string('pic_name'); // Nama Penanggung Jawab (PIC)
            // $table->string('pic_position'); // Jabatan Penanggung Jawab
            $table->string('pic_email'); // Alamat Email Penanggung Jawab
            $table->string('pic_whatsapp'); // Nomor WhatsApp Aktif
            $table->foreignId('company_sectors_id')->constrained('company_sectors')->onDelete('cascade'); // Kategori/Sektor Instansi
            $table->string('institution_name'); // Nama Instansi
            $table->string('business_sector')->nullable(); // Sektor Lapangan Usaha
            $table->string('institution_address'); // Alamat Instansi
            $table->foreignId('type_of_partnership_id')->constrained('type_of_partnership')->onDelete('cascade'); // Jenis Kemitraan yang Diajukan
            $table->foreignId('pasker_room_id')->constrained('pasker_room')->nullable(); //Kebutuhan ruangan yang Diajukan
            $table->string('other_pasker_room')->nullable(); //Kebutuhan ruangan lain yang Diajukan
            $table->foreignId('pasker_facility_id')->constrained('pasker_facility')->nullable(); // Kebutuhan fasilitas yang Diajukan
            $table->string('other_pasker_facility')->nullable(); //Kebutuhan fasilitas lain yang Diajukan
            $table->string('schedule')->nullable(); // Usulan Jadwal Kegiatan
            $table->string('request_letter')->nullable(); // Surat Permohonan Kemitraan (file path)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemitraan');
    }
}; 