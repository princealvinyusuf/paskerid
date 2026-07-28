<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tim_pusaka_monitoring')) {
            return;
        }

        Schema::create('tim_pusaka_monitoring', function (Blueprint $table) {
            $table->id();
            $table->date('metric_date');
            $table->string('portal_name', 120);
            $table->text('tim_kerja_pelaksana')->nullable();
            $table->text('pic_pusat_pasar_kerja')->nullable();
            $table->text('pic_mitra')->nullable();
            $table->text('permasalahan_hambatan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('dokumentasi_link')->nullable();
            $table->timestamps();

            $table->unique(['metric_date', 'portal_name'], 'tim_pusaka_metric_portal_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_pusaka_monitoring');
    }
};
