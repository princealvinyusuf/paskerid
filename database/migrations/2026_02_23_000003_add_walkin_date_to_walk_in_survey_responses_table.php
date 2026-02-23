<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('walk_in_survey_responses', 'walkin_date')) {
                $table->date('walkin_date')->nullable()->after('company_name_snapshot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            if (Schema::hasColumn('walk_in_survey_responses', 'walkin_date')) {
                $table->dropColumn('walkin_date');
            }
        });
    }
};


