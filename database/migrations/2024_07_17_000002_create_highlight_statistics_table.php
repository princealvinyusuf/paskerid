<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('highlight_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('value');
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('highlight_statistics');
    }
}; 