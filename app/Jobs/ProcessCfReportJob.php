<?php

namespace App\Jobs;

use App\Models\CfReply;
use App\Models\CfReport;
use App\Models\CfReportAudit;
use App\Models\CfThread;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCfReportJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $reportId,
        public ?int $actorUserId = null
    ) {
    }

    public function handle(): void
    {
        $report = CfReport::query()->find($this->reportId);
        if (!$report) {
            return;
        }

        $priority = $this->calculateReportPriority(
            (string) $report->reason,
            (string) $report->reportable_type,
            (int) $report->reported_by_user_id
        );
        $escalationLevel = $this->resolveEscalationLevel((string) $report->reason, (int) $priority['score']);

        $report->update([
            'priority_score' => (int) $priority['score'],
            'priority_level' => (string) $priority['level'],
            'escalation_level' => $escalationLevel,
            'escalated_at' => $escalationLevel !== 'none' ? now() : null,
        ]);

        $hasReportedAudit = CfReportAudit::query()
            ->where('cf_report_id', (int) $report->id)
            ->where('action', 'reported')
            ->exists();

        if (!$hasReportedAudit) {
            CfReportAudit::query()->create([
                'cf_report_id' => (int) $report->id,
                'actor_user_id' => $this->actorUserId,
                'action' => 'reported',
                'from_status' => null,
                'to_status' => 'open',
                'escalation_level' => $escalationLevel,
                'note' => null,
                'metadata' => [
                    'priority_score' => (int) $priority['score'],
                    'priority_level' => (string) $priority['level'],
                    'reportable_type' => (string) $report->reportable_type,
                ],
                'created_at' => now(),
            ]);
        }

        if (!in_array($escalationLevel, ['urgent', 'critical'], true)) {
            return;
        }

        $hiddenReason = 'Auto-hidden pending moderation review (' . strtoupper($escalationLevel) . ').';
        if ($report->reportable_type === 'thread') {
            $thread = CfThread::query()->find((int) $report->reportable_id);
            if (!$thread || (bool) $thread->is_hidden) {
                return;
            }

            $thread->update([
                'is_hidden' => true,
                'hidden_reason' => $hiddenReason,
                'hidden_by_report_id' => (int) $report->id,
                'hidden_at' => now(),
            ]);

            CfReportAudit::query()->create([
                'cf_report_id' => (int) $report->id,
                'actor_user_id' => $this->actorUserId,
                'action' => 'auto_hidden',
                'from_status' => null,
                'to_status' => null,
                'escalation_level' => $escalationLevel,
                'note' => $hiddenReason,
                'metadata' => ['target_type' => 'thread', 'target_id' => (int) $thread->id],
                'created_at' => now(),
            ]);
            return;
        }

        if ($report->reportable_type === 'reply') {
            $reply = CfReply::query()->find((int) $report->reportable_id);
            if (!$reply || (bool) $reply->is_hidden) {
                return;
            }

            $reply->update([
                'is_hidden' => true,
                'hidden_reason' => $hiddenReason,
                'hidden_by_report_id' => (int) $report->id,
                'hidden_at' => now(),
            ]);

            CfReportAudit::query()->create([
                'cf_report_id' => (int) $report->id,
                'actor_user_id' => $this->actorUserId,
                'action' => 'auto_hidden',
                'from_status' => null,
                'to_status' => null,
                'escalation_level' => $escalationLevel,
                'note' => $hiddenReason,
                'metadata' => ['target_type' => 'reply', 'target_id' => (int) $reply->id],
                'created_at' => now(),
            ]);
        }
    }

    private function calculateReportPriority(string $reason, string $reportableType, int $reporterId): array
    {
        $text = mb_strtolower(trim($reason));
        $score = $reportableType === 'thread' ? 20 : 15;

        $highRiskKeywords = [
            'penipuan', 'scam', 'phishing', 'pelecehan', 'ancaman', 'ujaran kebencian',
            'diskriminasi', 'sara', 'doxing', 'pornografi',
        ];
        $mediumRiskKeywords = [
            'spam', 'hoax', 'provokasi', 'palsu', 'fake', 'judi',
        ];

        if ($this->containsAnyKeyword($text, $highRiskKeywords)) {
            $score += 60;
        } elseif ($this->containsAnyKeyword($text, $mediumRiskKeywords)) {
            $score += 35;
        } else {
            $score += 10;
        }

        if (mb_strlen($text) > 220) {
            $score += 5;
        }

        $recentOpenReports = (int) CfReport::query()
            ->where('reported_by_user_id', $reporterId)
            ->where('status', 'open')
            ->where('created_at', '>=', now()->subDays(14))
            ->count();
        $score += min(20, $recentOpenReports * 5);

        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'level' => $this->resolvePriorityLevel($score),
        ];
    }

    private function containsAnyKeyword(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (mb_strpos($text, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    private function resolvePriorityLevel(int $score): string
    {
        if ($score >= 75) {
            return 'high';
        }
        if ($score >= 45) {
            return 'medium';
        }
        return 'low';
    }

    private function resolveEscalationLevel(string $reason, int $priorityScore): string
    {
        $text = mb_strtolower(trim($reason));
        $criticalKeywords = [
            'ancaman',
            'pelecehan',
            'kekerasan',
            'doxing',
            'phishing',
            'penipuan',
        ];
        $urgentKeywords = [
            'diskriminasi',
            'ujaran kebencian',
            'sara',
            'pornografi',
            'scam',
        ];

        if ($this->containsAnyKeyword($text, $criticalKeywords) || $priorityScore >= 90) {
            return 'critical';
        }
        if ($this->containsAnyKeyword($text, $urgentKeywords) || $priorityScore >= 75) {
            return 'urgent';
        }
        if ($priorityScore >= 50) {
            return 'watch';
        }

        return 'none';
    }
}
