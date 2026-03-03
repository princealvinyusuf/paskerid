<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cf_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('description', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cf_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cf_category_id')->constrained('cf_categories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 180);
            $table->string('slug', 220)->unique();
            $table->longText('body');
            $table->enum('author_type', ['employer', 'jobseeker', 'community'])->default('community');
            $table->string('location', 120)->nullable();
            $table->string('sector', 120)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->index(['cf_category_id', 'status']);
            $table->index(['author_type', 'status']);
            $table->index('last_activity_at');
        });

        Schema::create('cf_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cf_thread_id')->constrained('cf_threads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_solution')->default(false);
            $table->timestamps();
            $table->index(['cf_thread_id', 'created_at']);
        });

        DB::table('cf_categories')->insert([
            [
                'name' => 'Lowongan & Kebutuhan Talenta',
                'slug' => 'lowongan-kebutuhan-talenta',
                'description' => 'Ruang perusahaan mempublikasikan kebutuhan kandidat dan peluang kolaborasi.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pencari Kerja & Profil Kandidat',
                'slug' => 'pencari-kerja-profil-kandidat',
                'description' => 'Tempat pencari kerja memperkenalkan diri dan berdiskusi terkait peluang karier.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diskusi Industri per Sektor',
                'slug' => 'diskusi-industri-per-sektor',
                'description' => 'Bahas tren kebutuhan tenaga kerja berdasarkan sektor industri.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tips Rekrutmen & Karier',
                'slug' => 'tips-rekrutmen-karier',
                'description' => 'Berbagi strategi CV, interview, dan best practice hiring.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Event Job Fair & Walk In Interview',
                'slug' => 'event-job-fair-walk-in-interview',
                'description' => 'Informasi event ketenagakerjaan serta tindak lanjut peserta.',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cf_replies');
        Schema::dropIfExists('cf_threads');
        Schema::dropIfExists('cf_categories');
    }
};
