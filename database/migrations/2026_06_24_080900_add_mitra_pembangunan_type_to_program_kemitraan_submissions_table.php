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
        Schema::table('program_kemitraan_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('program_kemitraan_submissions', 'mitra_pembangunan_type')) {
                $table->string('mitra_pembangunan_type')->nullable()->after('institution_category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_kemitraan_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('program_kemitraan_submissions', 'mitra_pembangunan_type')) {
                $table->dropColumn('mitra_pembangunan_type');
            }
        });
    }
};
