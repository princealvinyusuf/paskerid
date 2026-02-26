<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('booked_date')) {
            return;
        }

        Schema::table('booked_date', function (Blueprint $table) {
            if (!Schema::hasColumn('booked_date', 'informasi_lainnya')) {
                $table->text('informasi_lainnya')->nullable()->after('booked_time_finish');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('booked_date')) {
            return;
        }

        Schema::table('booked_date', function (Blueprint $table) {
            if (Schema::hasColumn('booked_date', 'informasi_lainnya')) {
                $table->dropColumn('informasi_lainnya');
            }
        });
    }
};


