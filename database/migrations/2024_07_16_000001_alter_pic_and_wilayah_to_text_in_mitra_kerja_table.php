<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mitra_kerja', function (Blueprint $table) {
            $table->text('pic')->nullable()->change();
            $table->text('wilayah')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mitra_kerja', function (Blueprint $table) {
            $table->string('pic')->nullable()->change();
            $table->string('wilayah')->nullable()->change();
        });
    }
}; 