<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_boostday_consultations', function (Blueprint $table) {
            if (!Schema::hasColumn('career_boostday_consultations', 'jurusan')) {
                $table->string('jurusan', 120)->nullable()->after('pendidikan_terakhir');
            }
        });
    }

    public function down(): void
    {
        Schema::table('career_boostday_consultations', function (Blueprint $table) {
            if (Schema::hasColumn('career_boostday_consultations', 'jurusan')) {
                $table->dropColumn('jurusan');
            }
        });
    }
};



