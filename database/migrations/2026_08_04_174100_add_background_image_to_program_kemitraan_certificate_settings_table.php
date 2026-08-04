<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('program_kemitraan_certificate_settings')) {
            return;
        }

        if (!Schema::hasColumn('program_kemitraan_certificate_settings', 'background_image_path')) {
            Schema::table('program_kemitraan_certificate_settings', function (Blueprint $table): void {
                $table->string('background_image_path')->nullable()->after('signature_image_path');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('program_kemitraan_certificate_settings')) {
            return;
        }

        if (Schema::hasColumn('program_kemitraan_certificate_settings', 'background_image_path')) {
            Schema::table('program_kemitraan_certificate_settings', function (Blueprint $table): void {
                $table->dropColumn('background_image_path');
            });
        }
    }
};
