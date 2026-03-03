<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CfReply extends Model
{
    protected $fillable = [
        'cf_thread_id',
        'user_id',
        'body',
        'is_solution',
        'is_hidden',
        'hidden_reason',
        'hidden_by_report_id',
        'hidden_at',
    ];

    protected $casts = [
        'is_solution' => 'boolean',
        'is_hidden' => 'boolean',
        'hidden_by_report_id' => 'integer',
        'hidden_at' => 'datetime',
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(CfThread::class, 'cf_thread_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
