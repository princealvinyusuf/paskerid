<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCharacteristic3 extends Model
{
    protected $table = 'job_characteristics_3';
    protected $fillable = [
        'title', 'description', 'tableau_embed_code', 'order'
    ];
} 