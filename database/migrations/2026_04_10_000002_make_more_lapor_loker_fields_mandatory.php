<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_hoax_reports')) {
            return;
        }

        DB::statement("ALTER TABLE job_hoax_reports MODIFY nomor_kontak_terduga VARCHAR(60) NOT NULL");
        DB::statement("ALTER TABLE job_hoax_reports MODIFY platform_sumber VARCHAR(120) NOT NULL");
        DB::statement("ALTER TABLE job_hoax_reports MODIFY tautan_informasi VARCHAR(500) NOT NULL");
        DB::statement("ALTER TABLE job_hoax_reports MODIFY pelapor_nama VARCHAR(120) NOT NULL");
        DB::statement("ALTER TABLE job_hoax_reports MODIFY pelapor_email VARCHAR(255) NOT NULL");
    }

    public function down(): void
    {
        if (!Schema::hasTable('job_hoax_reports')) {
            return;
        }

        DB::statement("ALTER TABLE job_hoax_reports MODIFY nomor_kontak_terduga VARCHAR(60) NULL");
        DB::statement("ALTER TABLE job_hoax_reports MODIFY platform_sumber VARCHAR(120) NULL");
        DB::statement("ALTER TABLE job_hoax_reports MODIFY tautan_informasi VARCHAR(500) NULL");
        DB::statement("ALTER TABLE job_hoax_reports MODIFY pelapor_nama VARCHAR(120) NULL");
        DB::statement("ALTER TABLE job_hoax_reports MODIFY pelapor_email VARCHAR(255) NULL");
    }
};
