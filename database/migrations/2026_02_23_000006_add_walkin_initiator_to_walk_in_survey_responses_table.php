<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('walk_in_survey_responses')) {
            return;
        }

        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('walk_in_survey_responses', 'walk_in_initiator_id')) {
                $table->foreignId('walk_in_initiator_id')
                    ->nullable()
                    ->after('company_walk_in_survey_id')
                    ->constrained('walk_in_survey_initiators')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('walk_in_survey_responses', 'walkin_initiator_snapshot')) {
                $table->string('walkin_initiator_snapshot')->nullable()->after('company_name_snapshot');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('walk_in_survey_responses')) {
            return;
        }

        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            if (Schema::hasColumn('walk_in_survey_responses', 'walk_in_initiator_id')) {
                $table->dropConstrainedForeignId('walk_in_initiator_id');
            }
            if (Schema::hasColumn('walk_in_survey_responses', 'walkin_initiator_snapshot')) {
                $table->dropColumn('walkin_initiator_snapshot');
            }
        });
    }
};

