<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_results', function (Blueprint $table) {
            $table->id();
            $table->string('nama_konselor', 120);
            $table->string('nama_konseli', 120);
            $table->date('tanggal_konseling');
            $table->string('jenis_konseling', 120);
            $table->text('hal_yang_dibahas');
            $table->text('saran_untuk_pencaker');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_results');
    }
};


