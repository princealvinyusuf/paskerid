<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_boostday_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_registration_open')->default(true);
            $table->text('closed_message')->nullable();
            $table->timestamps();
        });

        DB::table('career_boostday_settings')->insert([
            'id' => 1,
            'is_registration_open' => 1,
            'closed_message' => 'Mohon maaf, pendaftaran Career Boost Day sedang ditutup sementara karena kuota telah terpenuhi.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('career_boostday_settings');
    }
};
