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
            if (!Schema::hasColumn('job_hoax_reports', 'tindak_lanjut_tutup_lowongan')) {
                $table->boolean('tindak_lanjut_tutup_lowongan')->default(false)->after('laporan_mitra');
            }
            if (!Schema::hasColumn('job_hoax_reports', 'tindak_lanjut_tutup_akun_perusahaan')) {
                $table->boolean('tindak_lanjut_tutup_akun_perusahaan')->default(false)->after('tindak_lanjut_tutup_lowongan');
            }
            if (!Schema::hasColumn('job_hoax_reports', 'tindak_lanjut_lainnya_checked')) {
                $table->boolean('tindak_lanjut_lainnya_checked')->default(false)->after('tindak_lanjut_tutup_akun_perusahaan');
            }
            if (!Schema::hasColumn('job_hoax_reports', 'tindak_lanjut_lainnya_text')) {
                $table->text('tindak_lanjut_lainnya_text')->nullable()->after('tindak_lanjut_lainnya_checked');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('job_hoax_reports')) {
            return;
        }

        Schema::table('job_hoax_reports', function (Blueprint $table) {
            if (Schema::hasColumn('job_hoax_reports', 'tindak_lanjut_lainnya_text')) {
                $table->dropColumn('tindak_lanjut_lainnya_text');
            }
            if (Schema::hasColumn('job_hoax_reports', 'tindak_lanjut_lainnya_checked')) {
                $table->dropColumn('tindak_lanjut_lainnya_checked');
            }
            if (Schema::hasColumn('job_hoax_reports', 'tindak_lanjut_tutup_akun_perusahaan')) {
                $table->dropColumn('tindak_lanjut_tutup_akun_perusahaan');
            }
            if (Schema::hasColumn('job_hoax_reports', 'tindak_lanjut_tutup_lowongan')) {
                $table->dropColumn('tindak_lanjut_tutup_lowongan');
            }
        });
    }
};
