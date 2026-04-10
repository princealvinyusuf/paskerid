<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_hoax_reports')) {
            return;
        }

        Schema::table('job_hoax_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('job_hoax_reports', 'bukti_pendukung_path')) {
                $table->string('bukti_pendukung_path', 500)->nullable()->after('tautan_informasi');
            }

            if (!Schema::hasColumn('job_hoax_reports', 'bukti_pendukung_nama')) {
                $table->string('bukti_pendukung_nama', 255)->nullable()->after('bukti_pendukung_path');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('job_hoax_reports')) {
            return;
        }

        Schema::table('job_hoax_reports', function (Blueprint $table) {
            if (Schema::hasColumn('job_hoax_reports', 'bukti_pendukung_nama')) {
                $table->dropColumn('bukti_pendukung_nama');
            }

            if (Schema::hasColumn('job_hoax_reports', 'bukti_pendukung_path')) {
                $table->dropColumn('bukti_pendukung_path');
            }
        });
    }
};
