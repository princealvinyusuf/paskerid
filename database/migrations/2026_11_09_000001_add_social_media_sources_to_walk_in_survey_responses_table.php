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
            if (!Schema::hasColumn('walk_in_survey_responses', 'social_media_sources')) {
                $table->json('social_media_sources')->nullable()->after('info_source_other');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('walk_in_survey_responses')) {
            return;
        }

        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            if (Schema::hasColumn('walk_in_survey_responses', 'social_media_sources')) {
                $table->dropColumn('social_media_sources');
            }
        });
    }
};
