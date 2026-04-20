<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating_information_clarity')->after('rating_access_info');
            $table->unsignedTinyInteger('rating_service_integrity')->after('rating_information_clarity');
        });
    }

    public function down(): void
    {
        Schema::table('walk_in_survey_responses', function (Blueprint $table) {
            $table->dropColumn([
                'rating_information_clarity',
                'rating_service_integrity',
            ]);
        });
    }
};
