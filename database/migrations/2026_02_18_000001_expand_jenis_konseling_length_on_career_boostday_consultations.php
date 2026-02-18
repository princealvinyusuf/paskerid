<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('career_boostday_consultations')) {
            return;
        }

        // Avoid requiring doctrine/dbal by using raw SQL for MySQL.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE career_boostday_consultations MODIFY jenis_konseling VARCHAR(255) NOT NULL");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('career_boostday_consultations')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE career_boostday_consultations MODIFY jenis_konseling VARCHAR(120) NOT NULL");
        }
    }
};


