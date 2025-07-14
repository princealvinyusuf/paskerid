<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCharacteristic extends Model
{
    protected $table = 'job_characteristics';
    protected $fillable = [
        'title', 'description', 'tableau_embed_code', 'order'
    ];
} 