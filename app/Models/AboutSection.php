<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'content',
        'media_url',
        'order',
    ];
}
