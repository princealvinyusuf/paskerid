<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiSection1 extends Model
{
    protected $table = 'informasi_section_1';
    protected $fillable = [
        'title', 'description', 'tableau_embed_code', 'order'
    ];
} 