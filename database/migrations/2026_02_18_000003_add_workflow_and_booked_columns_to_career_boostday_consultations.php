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
            if (!Schema::hasColumn('career_boostday_consultations', 'admin_status')) {
                $table->string('admin_status', 20)->default('pending')->after('cv_original_name');
            }
            if (!Schema::hasColumn('career_boostday_consultations', 'pic_id')) {
                $table->unsignedBigInteger('pic_id')->nullable()->after('admin_status');
            }
            if (!Schema::hasColumn('career_boostday_consultations', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('pic_id');
            }
            if (!Schema::hasColumn('career_boostday_consultations', 'alasan')) {
                $table->text('alasan')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('career_boostday_consultations', 'admin_updated_at')) {
                $table->dateTime('admin_updated_at')->nullable()->after('alasan');
            }
            if (!Schema::hasColumn('career_boostday_consultations', 'booked_date')) {
                $table->date('booked_date')->nullable()->after('admin_updated_at');
            }
            if (!Schema::hasColumn('career_boostday_consultations', 'booked_time_start')) {
                $table->time('booked_time_start')->nullable()->after('booked_date');
            }
            if (!Schema::hasColumn('career_boostday_consultations', 'booked_time_finish')) {
                $table->time('booked_time_finish')->nullable()->after('booked_time_start');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('career_boostday_consultations')) {
            return;
        }

        Schema::table('career_boostday_consultations', function (Blueprint $table) {
            foreach ([
                'booked_time_finish',
                'booked_time_start',
                'booked_date',
                'admin_updated_at',
                'alasan',
                'keterangan',
                'pic_id',
                'admin_status',
            ] as $col) {
                if (Schema::hasColumn('career_boostday_consultations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};


