<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('type_of_partnership', function (Blueprint $table) {
            if (!Schema::hasColumn('type_of_partnership', 'max_bookings')) {
                $table->integer('max_bookings')->default(10)->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('type_of_partnership', function (Blueprint $table) {
            if (Schema::hasColumn('type_of_partnership', 'max_bookings')) {
                $table->dropColumn('max_bookings');
            }
        });
    }
};


