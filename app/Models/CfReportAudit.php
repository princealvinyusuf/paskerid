<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CfReportAudit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'cf_report_id',
        'actor_user_id',
        'action',
        'from_status',
        'to_status',
        'escalation_level',
        'note',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(CfReport::class, 'cf_report_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
