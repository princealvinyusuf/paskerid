<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cf_reports', function (Blueprint $table) {
            $table->enum('escalation_level', ['none', 'watch', 'urgent', 'critical'])
                ->default('none')
                ->after('priority_level');
            $table->timestamp('escalated_at')->nullable()->after('escalation_level');
            $table->index(['status', 'escalation_level', 'created_at'], 'cf_reports_status_escalation_created_idx');
        });

        Schema::create('cf_report_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cf_report_id')->constrained('cf_reports')->cascadeOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20)->nullable();
            $table->string('escalation_level', 20)->nullable();
            $table->text('note')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['cf_report_id', 'created_at'], 'cf_report_audits_report_created_idx');
            $table->index(['action', 'created_at'], 'cf_report_audits_action_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cf_report_audits');

        Schema::table('cf_reports', function (Blueprint $table) {
            $table->dropIndex('cf_reports_status_escalation_created_idx');
            $table->dropColumn(['escalation_level', 'escalated_at']);
        });
    }
};
