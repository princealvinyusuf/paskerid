<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_kemitraan_evaluations', function (Blueprint $table): void {
            $table->string('certificate_code', 64)->nullable()->unique()->after('document_code');
        });

        Schema::create('program_kemitraan_certificate_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('signature_image_path')->nullable();
            $table->string('signer_name')->default('R. Nurhidajat, S.E., M.Ec.Dev.');
            $table->string('certificate_title')->default('Sertifikat Partisipasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kemitraan_certificate_settings');

        Schema::table('program_kemitraan_evaluations', function (Blueprint $table): void {
            $table->dropUnique(['certificate_code']);
            $table->dropColumn('certificate_code');
        });
    }
};
