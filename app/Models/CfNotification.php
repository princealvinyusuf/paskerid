<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CfNotification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'cf_thread_id',
        'cf_reply_id',
        'actor_user_id',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(CfThread::class, 'cf_thread_id');
    }

    public function reply(): BelongsTo
    {
        return $this->belongsTo(CfReply::class, 'cf_reply_id');
    }
}
