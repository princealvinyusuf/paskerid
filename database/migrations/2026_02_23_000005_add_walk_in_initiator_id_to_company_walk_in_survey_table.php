<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('company_walk_in_survey')) {
            return;
        }

        Schema::table('company_walk_in_survey', function (Blueprint $table) {
            if (!Schema::hasColumn('company_walk_in_survey', 'walk_in_initiator_id')) {
                $table->foreignId('walk_in_initiator_id')
                    ->nullable()
                    ->after('company_name')
                    ->constrained('walk_in_survey_initiators')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('company_walk_in_survey')) {
            return;
        }

        Schema::table('company_walk_in_survey', function (Blueprint $table) {
            if (Schema::hasColumn('company_walk_in_survey', 'walk_in_initiator_id')) {
                $table->dropConstrainedForeignId('walk_in_initiator_id');
            }
        });
    }
};

