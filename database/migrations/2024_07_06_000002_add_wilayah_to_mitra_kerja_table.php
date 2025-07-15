<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add only the intended columns here (e.g., wilayah), not 'pic'.
        // Example:
        // Schema::table('mitra_kerja', function (Blueprint $table) {
        //     $table->string('wilayah')->nullable()->after('pic');
        // });
    }

    public function down(): void
    {
        // Remove only the intended columns here (e.g., wilayah), not 'pic'.
        // Example:
        // Schema::table('mitra_kerja', function (Blueprint $table) {
        //     $table->dropColumn('wilayah');
        // });
    }
}; 