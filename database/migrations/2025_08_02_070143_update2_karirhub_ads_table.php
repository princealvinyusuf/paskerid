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
            $table->boolean('secret')->default(false)->after('salary_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karirhub_ads', function (Blueprint $table) {
            $table->dropColumn('secret');
        });
    }
};
