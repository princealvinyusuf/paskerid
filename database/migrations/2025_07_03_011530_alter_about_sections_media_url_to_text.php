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
        Schema::table('about_sections', function (Blueprint $table) {
            $table->text('media_url')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not revert to VARCHAR(255) to avoid data truncation issues.
        // The column will remain as TEXT.
    }
};