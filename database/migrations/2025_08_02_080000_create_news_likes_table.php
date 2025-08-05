<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->string('ip_address', 45);
            $table->string('user_agent', 255);
            $table->timestamps();

            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->index(['news_id', 'ip_address', 'user_agent']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_likes');
    }
};