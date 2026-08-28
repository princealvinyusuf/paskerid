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
            if (!Schema::hasColumn('kemitraan_detail_lowongan', 'jumlah_penempatan')) {
                $table->string('jumlah_penempatan')->nullable()->after('sasaran_pemenuhan_walk_in_interview');
            }
            if (!Schema::hasColumn('kemitraan_detail_lowongan', 'link_melamar')) {
                $table->text('link_melamar')->nullable()->after('jumlah_penempatan');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('kemitraan_detail_lowongan')) {
            return;
        }

        Schema::table('kemitraan_detail_lowongan', function (Blueprint $table) {
            if (Schema::hasColumn('kemitraan_detail_lowongan', 'link_melamar')) {
                $table->dropColumn('link_melamar');
            }
            if (Schema::hasColumn('kemitraan_detail_lowongan', 'jumlah_penempatan')) {
                $table->dropColumn('jumlah_penempatan');
            }
        });
    }
};
