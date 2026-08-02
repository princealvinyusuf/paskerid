<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramKemitraanEvaluationAnswer extends Model
{
    use HasFactory;

    protected $table = 'program_kemitraan_evaluation_answers';

    protected $fillable = [
        'evaluation_id',
        'form_type',
        'section_key',
        'indicator_number',
        'indicator_text',
        'score',
        'is_not_applicable',
    ];

    protected $casts = [
        'is_not_applicable' => 'boolean',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(ProgramKemitraanEvaluation::class, 'evaluation_id');
    }
}
