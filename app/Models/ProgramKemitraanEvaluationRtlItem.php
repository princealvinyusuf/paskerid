<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramKemitraanEvaluationRtlItem extends Model
{
    use HasFactory;

    protected $table = 'program_kemitraan_evaluation_rtl_items';

    protected $fillable = [
        'evaluation_id',
        'row_order',
        'issue',
        'follow_up',
        'responsible_person',
        'target_date',
        'completion_indicator',
        'status',
    ];

    protected $casts = [
        'target_date' => 'date',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(ProgramKemitraanEvaluation::class, 'evaluation_id');
    }
}
