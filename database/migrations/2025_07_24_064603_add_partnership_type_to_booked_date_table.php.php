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
            $table->foreignId('type_of_partnership_id')->after('booked_time')->constrained('type_of_partnership')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booked_date', function (Blueprint $table) {
            $table->dropColumn('type_of_partnership');
        });
    }
};
