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
            if (!Schema::hasColumn('walk_in_survey_responses', 'walkin_benefit')) {
                $table->string('walkin_benefit', 10)->nullable()->after('feedback_improvement_aspects');
            }
            if (!Schema::hasColumn('walk_in_survey_responses', 'walkin_benefit_reason')) {
                $table->text('walkin_benefit_reason')->nullable()->after('walkin_benefit');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('walk_in_survey_responses')) {
            return;
        }

        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            if (Schema::hasColumn('walk_in_survey_responses', 'walkin_benefit_reason')) {
                $table->dropColumn('walkin_benefit_reason');
            }
            if (Schema::hasColumn('walk_in_survey_responses', 'walkin_benefit')) {
                $table->dropColumn('walkin_benefit');
            }
        });
    }
};
