<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CfThread extends Model
{
    protected $fillable = [
        'cf_category_id',
        'user_id',
        'title',
        'slug',
        'body',
        'author_type',
        'location',
        'sector',
        'job_role',
        'province',
        'city',
        'work_type',
        'salary_range',
        'experience_level',
        'status',
        'is_pinned',
        'is_locked',
        'is_hidden',
        'hidden_reason',
        'hidden_by_report_id',
        'hidden_at',
        'views_count',
        'last_activity_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'is_hidden' => 'boolean',
        'hidden_by_report_id' => 'integer',
        'hidden_at' => 'datetime',
        'views_count' => 'integer',
        'last_activity_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CfCategory::class, 'cf_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(CfReply::class, 'cf_thread_id');
    }
}
