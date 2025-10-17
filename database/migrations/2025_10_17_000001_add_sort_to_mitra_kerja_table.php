<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitra_kerja', function (Blueprint $table) {
            $table->unsignedInteger('sort')->default(0)->after('logo');
            $table->index('sort');
        });
    }

    public function down(): void
    {
        Schema::table('mitra_kerja', function (Blueprint $table) {
            $table->dropIndex(['sort']);
            $table->dropColumn('sort');
        });
    }
};


