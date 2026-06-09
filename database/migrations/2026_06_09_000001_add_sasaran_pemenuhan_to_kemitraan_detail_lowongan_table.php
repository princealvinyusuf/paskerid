<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kemitraan_detail_lowongan')) {
            return;
        }

        Schema::table('kemitraan_detail_lowongan', function (Blueprint $table) {
            if (!Schema::hasColumn('kemitraan_detail_lowongan', 'sasaran_pemenuhan_walk_in_interview')) {
                $table->unsignedInteger('sasaran_pemenuhan_walk_in_interview')->default(0)->after('jumlah_kebutuhan');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('kemitraan_detail_lowongan')) {
            return;
        }

        Schema::table('kemitraan_detail_lowongan', function (Blueprint $table) {
            if (Schema::hasColumn('kemitraan_detail_lowongan', 'sasaran_pemenuhan_walk_in_interview')) {
                $table->dropColumn('sasaran_pemenuhan_walk_in_interview');
            }
        });
    }
};
