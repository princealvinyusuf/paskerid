<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'description',
        'location',
        'years',
        'csv_url',
        'xlsx_url',
        'icon',
        'order',
    ];
}
