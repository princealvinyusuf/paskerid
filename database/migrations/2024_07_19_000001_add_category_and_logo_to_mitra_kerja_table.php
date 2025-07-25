<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mitra_kerja', function (Blueprint $table) {
            $table->string('category')->nullable()->after('divider');
            $table->string('logo')->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('mitra_kerja', function (Blueprint $table) {
            $table->dropColumn(['category', 'logo']);
        });
    }
}; 