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
        Schema::dropIfExists('booked_date');
        Schema::dropIfExists('kemitraan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('kemitraan', function (Blueprint $table) {
            $table->id();
            $table->string('pic_name');
            $table->string('pic_position');
            $table->string('pic_email');
            $table->string('pic_whatsapp');
            $table->string('sector_category');
            $table->string('institution_name');
            $table->string('business_sector')->nullable();
            $table->string('institution_address');
            $table->string('partnership_type');
            $table->text('needs')->nullable();
            $table->string('schedule')->nullable();
            $table->string('request_letter')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
        Schema::create('booked_date', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kemitraan_id')->constrained('kemitraan')->onDelete('cascade');
            $table->string('partnership_type');
            $table->integer('max_bookings')->default(10);
            $table->date('booked_date');
            $table->timestamps();
            $table->index('booked_date');
        });
    }
}; 