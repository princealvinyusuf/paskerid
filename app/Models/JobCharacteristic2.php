<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCharacteristic2 extends Model
{
    protected $table = 'job_characteristics_2';
    protected $fillable = [
        'title', 'description', 'tableau_embed_code', 'order'
    ];
} 