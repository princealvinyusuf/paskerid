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
            if (!Schema::hasColumn('kemitraan', 'pic_position')) {
                $table->string('pic_position')->after('pic_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemitraan', function (Blueprint $table) {
            if (Schema::hasColumn('kemitraan', 'pic_position')) {
                $table->dropColumn('pic_position');
            }
        });
    }
}; 