<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_kemitraan_evaluations', function (Blueprint $table) {
            $table->foreignId('activity_master_id')
                ->nullable()
                ->after('id')
                ->constrained('program_kemitraan_evaluation_activities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('program_kemitraan_evaluations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_master_id');
        });
    }
};
