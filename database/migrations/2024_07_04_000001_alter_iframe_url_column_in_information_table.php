<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('information', function (Blueprint $table) {
            $table->text('iframe_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Do not revert to VARCHAR(255) to avoid data truncation issues.
        // The column will remain as TEXT.
    }
}; 