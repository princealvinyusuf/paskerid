<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cf_threads', function (Blueprint $table) {
            $table->json('attachment_urls')->nullable()->after('experience_level');
        });
    }

    public function down(): void
    {
        Schema::table('cf_threads', function (Blueprint $table) {
            $table->dropColumn('attachment_urls');
        });
    }
};
