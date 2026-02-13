<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('walkin_gallery_items')) {
            Schema::create('walkin_gallery_items', function (Blueprint $table) {
                $table->id();
                $table->string('type', 30); // photo | video_upload | video_embed
                $table->string('title')->nullable();
                $table->text('caption')->nullable();

                // For photo/video_upload
                $table->string('media_path')->nullable(); // relative path under storage/app/public
                $table->string('thumbnail_path')->nullable(); // optional thumbnail (esp. for video_upload)

                // For video_embed
                $table->text('embed_url')->nullable();
                $table->string('embed_thumbnail_url')->nullable();

                $table->boolean('is_published')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index(['is_published', 'sort_order']);
                $table->index(['type', 'is_published']);
            });
        }

        if (!Schema::hasTable('walkin_gallery_comments')) {
            Schema::create('walkin_gallery_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('walkin_gallery_item_id')->nullable()->constrained('walkin_gallery_items')->nullOnDelete();
                $table->string('name', 80);
                $table->text('comment');
                $table->string('status', 20)->default('pending'); // pending | approved | rejected
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent', 255)->nullable();
                $table->timestamps();

                $table->index(['status', 'created_at']);
                $table->index(['walkin_gallery_item_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('walkin_gallery_comments');
        Schema::dropIfExists('walkin_gallery_items');
    }
};


