<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booked_date', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kemitraan_id')->constrained('kemitraan')->onDelete('cascade');
            $table->date('booked_date');
            $table->timestamps();

            $table->index('booked_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booked_date');
    }
}; 