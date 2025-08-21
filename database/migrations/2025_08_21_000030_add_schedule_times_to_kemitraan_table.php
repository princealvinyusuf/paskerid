<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kemitraan', function (Blueprint $table) {
            if (!Schema::hasColumn('kemitraan', 'scheduletimestart')) {
                $table->time('scheduletimestart')->nullable()->after('schedule');
            }
            if (!Schema::hasColumn('kemitraan', 'scheduletimefinish')) {
                $table->time('scheduletimefinish')->nullable()->after('scheduletimestart');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kemitraan', function (Blueprint $table) {
            if (Schema::hasColumn('kemitraan', 'scheduletimefinish')) {
                $table->dropColumn('scheduletimefinish');
            }
            if (Schema::hasColumn('kemitraan', 'scheduletimestart')) {
                $table->dropColumn('scheduletimestart');
            }
        });
    }
}; 