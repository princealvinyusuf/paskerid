<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiSection2 extends Model
{
    protected $table = 'informasi_section_2';
    protected $fillable = [
        'title', 'description', 'tableau_embed_code', 'order'
    ];
} 