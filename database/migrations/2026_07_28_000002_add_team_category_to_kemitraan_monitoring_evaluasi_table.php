<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kemitraan_monitoring_evaluasi')) {
            return;
        }

        Schema::table('kemitraan_monitoring_evaluasi', function (Blueprint $table) {
            if (!Schema::hasColumn('kemitraan_monitoring_evaluasi', 'team_category')) {
                $table->string('team_category', 30)->default('tim_layanan')->after('kemitraan_id');
            }
        });

        DB::table('kemitraan_monitoring_evaluasi')
            ->whereNull('team_category')
            ->update(['team_category' => 'tim_layanan']);

        try {
            Schema::table('kemitraan_monitoring_evaluasi', function (Blueprint $table) {
                $table->dropUnique('kemitraan_monitoring_evaluasi_kemitraan_id_unique');
            });
        } catch (Throwable $e) {
            // Ignore when unique index is already dropped.
        }

        try {
            Schema::table('kemitraan_monitoring_evaluasi', function (Blueprint $table) {
                $table->unique(['kemitraan_id', 'team_category'], 'kme_kemitraan_team_unique');
            });
        } catch (Throwable $e) {
            // Ignore when composite unique index already exists.
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('kemitraan_monitoring_evaluasi')) {
            return;
        }

        try {
            Schema::table('kemitraan_monitoring_evaluasi', function (Blueprint $table) {
                $table->dropUnique('kme_kemitraan_team_unique');
            });
        } catch (Throwable $e) {
            // Ignore when unique index is missing.
        }

        try {
            Schema::table('kemitraan_monitoring_evaluasi', function (Blueprint $table) {
                $table->dropColumn('team_category');
            });
        } catch (Throwable $e) {
            // Ignore when column already removed.
        }

        try {
            Schema::table('kemitraan_monitoring_evaluasi', function (Blueprint $table) {
                $table->unique('kemitraan_id');
            });
        } catch (Throwable $e) {
            // Ignore when index cannot be restored automatically.
        }
    }
};
