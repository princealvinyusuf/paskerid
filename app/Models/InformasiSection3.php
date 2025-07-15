<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiSection3 extends Model
{
    protected $table = 'informasi_section_3';
    protected $fillable = [
        'title', 'description', 'tableau_embed_code', 'order'
    ];
} 