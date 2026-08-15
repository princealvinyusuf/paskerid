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
        Schema::table('program_kemitraan_evaluations', function (Blueprint $table) {
            $table->string('respondent_email')->nullable()->after('respondent_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_kemitraan_evaluations', function (Blueprint $table) {
            $table->dropColumn('respondent_email');
        });
    }
};
