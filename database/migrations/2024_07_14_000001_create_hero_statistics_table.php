<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hero_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('value');
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->string('type');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_statistics');
    }
}; 