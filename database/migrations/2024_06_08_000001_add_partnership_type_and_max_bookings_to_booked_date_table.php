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
        Schema::table('booked_date', function (Blueprint $table) {
            $table->string('partnership_type')->after('kemitraan_id');
            $table->integer('max_bookings')->default(10)->after('partnership_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booked_date', function (Blueprint $table) {
            $table->dropColumn(['partnership_type', 'max_bookings']);
        });
    }
}; 