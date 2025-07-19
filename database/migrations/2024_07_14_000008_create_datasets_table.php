<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasetsTable extends Migration
{
    public function up()
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g. "Pencari Kerja", "Pemberi Kerja", "Lowongan Kerja"
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('location')->nullable(); // e.g. "38 Provinsi"
            $table->string('years')->nullable();    // e.g. "2022-2024"
            $table->string('csv_url')->nullable();
            $table->string('xlsx_url')->nullable();
            $table->string('icon')->nullable();     // for category icon (optional)
            $table->integer('order')->default(0);   // for sorting
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('datasets');
    }
}
