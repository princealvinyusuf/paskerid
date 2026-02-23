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
            if (!Schema::hasColumn('kemitraan_detail_lowongan', 'nama_perusahaan')) {
                $table->json('nama_perusahaan')->nullable()->after('lokasi_penempatan');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('kemitraan_detail_lowongan')) {
            return;
        }

        Schema::table('kemitraan_detail_lowongan', function (Blueprint $table) {
            if (Schema::hasColumn('kemitraan_detail_lowongan', 'nama_perusahaan')) {
                $table->dropColumn('nama_perusahaan');
            }
        });
    }
};

