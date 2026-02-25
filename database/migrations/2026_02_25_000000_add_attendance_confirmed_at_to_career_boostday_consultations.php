<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('career_boostday_consultations')) {
            return;
        }

        Schema::table('career_boostday_consultations', function (Blueprint $table) {
            if (!Schema::hasColumn('career_boostday_consultations', 'attendance_confirmed_at')) {
                $table->dateTime('attendance_confirmed_at')->nullable()->after('booked_time_finish');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('career_boostday_consultations')) {
            return;
        }

        Schema::table('career_boostday_consultations', function (Blueprint $table) {
            if (Schema::hasColumn('career_boostday_consultations', 'attendance_confirmed_at')) {
                $table->dropColumn('attendance_confirmed_at');
            }
        });
    }
};


