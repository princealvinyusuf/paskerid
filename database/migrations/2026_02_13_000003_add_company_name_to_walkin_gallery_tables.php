<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('walkin_gallery_items') && !Schema::hasColumn('walkin_gallery_items', 'company_name')) {
            Schema::table('walkin_gallery_items', function (Blueprint $table) {
                $table->string('company_name')->nullable()->after('type');
                $table->index('company_name');
            });
        }

        if (Schema::hasTable('walkin_gallery_comments') && !Schema::hasColumn('walkin_gallery_comments', 'company_name')) {
            Schema::table('walkin_gallery_comments', function (Blueprint $table) {
                $table->string('company_name')->nullable()->after('walkin_gallery_item_id');
                $table->index(['company_name', 'status']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('walkin_gallery_comments') && Schema::hasColumn('walkin_gallery_comments', 'company_name')) {
            Schema::table('walkin_gallery_comments', function (Blueprint $table) {
                $table->dropIndex(['company_name', 'status']);
                $table->dropColumn('company_name');
            });
        }

        if (Schema::hasTable('walkin_gallery_items') && Schema::hasColumn('walkin_gallery_items', 'company_name')) {
            Schema::table('walkin_gallery_items', function (Blueprint $table) {
                $table->dropIndex(['company_name']);
                $table->dropColumn('company_name');
            });
        }
    }
};


