<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cf_threads', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('is_locked');
            $table->string('hidden_reason', 255)->nullable()->after('is_hidden');
            $table->unsignedBigInteger('hidden_by_report_id')->nullable()->after('hidden_reason');
            $table->timestamp('hidden_at')->nullable()->after('hidden_by_report_id');
            $table->index(['is_hidden', 'status', 'last_activity_at'], 'cf_threads_hidden_status_activity_idx');
        });

        Schema::table('cf_replies', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('is_solution');
            $table->string('hidden_reason', 255)->nullable()->after('is_hidden');
            $table->unsignedBigInteger('hidden_by_report_id')->nullable()->after('hidden_reason');
            $table->timestamp('hidden_at')->nullable()->after('hidden_by_report_id');
            $table->index(['cf_thread_id', 'is_hidden', 'created_at'], 'cf_replies_thread_hidden_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('cf_replies', function (Blueprint $table) {
            $table->dropIndex('cf_replies_thread_hidden_created_idx');
            $table->dropColumn(['is_hidden', 'hidden_reason', 'hidden_by_report_id', 'hidden_at']);
        });

        Schema::table('cf_threads', function (Blueprint $table) {
            $table->dropIndex('cf_threads_hidden_status_activity_idx');
            $table->dropColumn(['is_hidden', 'hidden_reason', 'hidden_by_report_id', 'hidden_at']);
        });
    }
};
