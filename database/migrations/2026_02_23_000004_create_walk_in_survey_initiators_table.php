<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('walk_in_survey_initiators')) {
            return;
        }

        Schema::create('walk_in_survey_initiators', function (Blueprint $table) {
            $table->id();
            $table->string('initiator_name');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('walk_in_survey_initiators');
    }
};

