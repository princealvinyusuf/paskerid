<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighlightStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'value',
        'unit',
        'description',
        'logo',
    ];
} 