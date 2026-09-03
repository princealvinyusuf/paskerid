<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_kemitraan_certificate_settings', function (Blueprint $table): void {
            $table->string('logo_image_path')->nullable()->after('background_image_path');
            $table->string('ministry_header_text')->default('KEMENTERIAN KETENAGAKERJAAN REPUBLIK INDONESIA')->after('logo_image_path');
            $table->string('signer_position')->default('Kepala Pusat Pasar Kerja')->after('signer_name');
            $table->string('sign_place')->default('Jakarta')->after('signer_position');
            $table->string('participation_role_default')->default('Peserta')->after('certificate_title');
        });

        DB::table('program_kemitraan_certificate_settings')
            ->where('certificate_title', 'Sertifikat Partisipasi')
            ->update(['certificate_title' => 'Sertifikat']);
    }

    public function down(): void
    {
        Schema::table('program_kemitraan_certificate_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'logo_image_path',
                'ministry_header_text',
                'signer_position',
                'sign_place',
                'participation_role_default',
            ]);
        });
    }
};
