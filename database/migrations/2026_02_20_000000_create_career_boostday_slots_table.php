<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_boostday_slots', function (Blueprint $table) {
            $table->id();
            $table->string('day_name', 20);
            $table->time('time_start');
            $table->time('time_finish');
            $table->string('label', 120)->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order'], 'idx_active_sort');
        });

        // Seed defaults (match existing hardcoded slots)
        DB::table('career_boostday_slots')->insert([
            [
                'day_name' => 'Senin',
                'time_start' => '09:00:00',
                'time_finish' => '11:00:00',
                'label' => 'Senin (pukul 09.00 s/d 11.00)',
                'sort_order' => 10,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day_name' => 'Senin',
                'time_start' => '13:30:00',
                'time_finish' => '15:00:00',
                'label' => 'Senin (pukul 13.30 s/d 15.00)',
                'sort_order' => 20,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day_name' => 'Kamis',
                'time_start' => '09:00:00',
                'time_finish' => '11:00:00',
                'label' => 'Kamis (pukul 09.00 s/d 11.00)',
                'sort_order' => 30,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day_name' => 'Kamis',
                'time_start' => '13:30:00',
                'time_finish' => '15:00:00',
                'label' => 'Kamis (pukul 13.30 s/d 15.00)',
                'sort_order' => 40,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('career_boostday_slots');
    }
};


