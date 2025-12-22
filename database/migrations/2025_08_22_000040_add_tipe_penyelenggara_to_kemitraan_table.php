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
        Schema::table('kemitraan', function (Blueprint $table) {
            if (!Schema::hasColumn('kemitraan', 'tipe_penyelenggara')) {
                $table->string('tipe_penyelenggara')->nullable()->after('type_of_partnership_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemitraan', function (Blueprint $table) {
            if (Schema::hasColumn('kemitraan', 'tipe_penyelenggara')) {
                $table->dropColumn('tipe_penyelenggara');
            }
        });
    }
};

