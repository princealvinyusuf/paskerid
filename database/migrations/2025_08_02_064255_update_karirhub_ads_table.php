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
        Schema::table('karirhub_ads', function (Blueprint $table) {
            $table->string('city')->after('company_name');
            $table->string('province')->after('city');
            $table->integer('salary_min')->nullable()->after('province');
            $table->integer('salary_max')->nullable()->after('salary_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karirhub_ads', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('province');
            $table->dropColumn('salary_min');
            $table->dropColumn('salary_max');
        });
    }
};
