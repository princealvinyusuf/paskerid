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
            if (!Schema::hasColumn('walk_in_survey_responses', 'service_integrity_detail')) {
                $table->text('service_integrity_detail')->nullable()->after('rating_service_integrity');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('walk_in_survey_responses')) {
            return;
        }

        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            if (Schema::hasColumn('walk_in_survey_responses', 'service_integrity_detail')) {
                $table->dropColumn('service_integrity_detail');
            }
        });
    }
};
