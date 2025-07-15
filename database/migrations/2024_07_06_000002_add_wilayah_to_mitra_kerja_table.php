<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mitra_kerja', function (Blueprint $table) {
            $table->string('wilayah')->nullable()->after('pic');
        });
    }

    public function down(): void
    {
        // No need to drop the column, as the table will be dropped by the main migration.
    }
}; 