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
        Schema::create('program_kemitraan_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('pic_name');
            $table->string('pic_position');
            $table->string('pic_email');
            $table->string('pic_whatsapp', 30);
            $table->string('institution_category');
            $table->string('instansi_lembaga_name');
            $table->string('institution_name');
            $table->string('business_sector')->nullable();
            $table->text('institution_address');
            $table->string('proposed_activity_type');
            $table->string('request_letter')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_kemitraan_submissions');
    }
};
