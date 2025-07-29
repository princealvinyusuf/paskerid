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
            $table->renameColumn('booked_date','booked_date_start');
            $table->renameColumn('booked_time','booked_time_start');
            $table->date('booked_date_finish')->after('booked_date_start');
            $table->time('booked_time_finish')->after('booked_time_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
