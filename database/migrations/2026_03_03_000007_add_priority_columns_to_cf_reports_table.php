<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cf_reports', function (Blueprint $table) {
            $table->unsignedTinyInteger('priority_score')->default(30)->after('status');
            $table->string('priority_level', 20)->default('low')->after('priority_score');
            $table->index(['status', 'priority_score']);
        });
    }

    public function down(): void
    {
        Schema::table('cf_reports', function (Blueprint $table) {
            $table->dropIndex(['status', 'priority_score']);
            $table->dropColumn(['priority_score', 'priority_level']);
        });
    }
};
