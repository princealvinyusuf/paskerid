<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dashboard_distribution', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('iframe_code');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dashboard_distribution');
    }
}; 