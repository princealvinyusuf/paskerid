<?php

namespace App\Jobs;

use App\Models\CfNotification;
use App\Models\CfReply;
use App\Models\CfThread;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchCfReplyNotificationsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $threadId,
        public int $actorUserId,
        public int $replyId
    ) {
    }

    public function handle(): void
    {
        $thread = CfThread::query()->find($this->threadId);
        if (!$thread) {
            return;
        }

        $reply = CfReply::query()
            ->where('cf_thread_id', (int) $thread->id)
            ->find($this->replyId);
        if (!$reply) {
            return;
        }

        $recipientIds = [];
        if ((int) $thread->user_id !== $this->actorUserId) {
            $recipientIds[] = (int) $thread->user_id;
        }

        $participantIds = CfReply::query()
            ->where('cf_thread_id', (int) $thread->id)
            ->where('user_id', '!=', $this->actorUserId)
            ->distinct()
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $recipientIds = array_values(array_unique(array_merge($recipientIds, $participantIds)));
        if (empty($recipientIds)) {
            return;
        }

        $existingRecipientIds = CfNotification::query()
            ->where('type', 'thread_reply')
            ->where('cf_reply_id', (int) $reply->id)
            ->whereIn('user_id', $recipientIds)
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $recipientIds = array_values(array_diff($recipientIds, $existingRecipientIds));
        if (empty($recipientIds)) {
            return;
        }

        $actorName = (string) User::query()
            ->where('id', $this->actorUserId)
            ->value('name');
        $actorLabel = trim($actorName) !== '' ? $actorName : 'Seseorang';

        $rows = [];
        foreach ($recipientIds as $recipientId) {
            $rows[] = [
                'user_id' => $recipientId,
                'type' => 'thread_reply',
                'cf_thread_id' => (int) $thread->id,
                'cf_reply_id' => (int) $reply->id,
                'actor_user_id' => $this->actorUserId,
                'title' => 'Balasan baru pada thread CF',
                'message' => $actorLabel . ' membalas thread "' . $thread->title . '".',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        CfNotification::query()->insert($rows);
    }
}
