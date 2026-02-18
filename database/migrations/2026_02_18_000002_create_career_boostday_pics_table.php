<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('career_boostday_pics')) {
            Schema::create('career_boostday_pics', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120)->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Seed defaults if empty
        $count = DB::table('career_boostday_pics')->count();
        if ($count === 0) {
            $defaults = ['Rici', 'Arifa', 'Ryan', 'Widya', 'Nikira', 'Jules'];
            foreach ($defaults as $nm) {
                DB::table('career_boostday_pics')->insert([
                    'name' => $nm,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('career_boostday_pics');
    }
};


