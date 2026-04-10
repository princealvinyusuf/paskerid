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
            if (!Schema::hasColumn('job_hoax_reports', 'laporan_mitra')) {
                $table->string('laporan_mitra', 120)->nullable()->after('pelapor_email');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('job_hoax_reports')) {
            return;
        }

        Schema::table('job_hoax_reports', function (Blueprint $table) {
            if (Schema::hasColumn('job_hoax_reports', 'laporan_mitra')) {
                $table->dropColumn('laporan_mitra');
            }
        });
    }
};
