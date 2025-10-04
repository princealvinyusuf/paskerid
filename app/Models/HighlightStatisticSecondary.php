<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighlightStatisticSecondary extends Model
{
    use HasFactory;

    protected $table = 'highlight_statistics_secondary';

    protected $fillable = [
        'title',
        'value',
        'unit',
        'description',
        'logo',
    ];
}


