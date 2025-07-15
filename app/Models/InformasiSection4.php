<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiSection4 extends Model
{
    protected $table = 'informasi_section_4';
    protected $fillable = [
        'title', 'description', 'tableau_embed_code', 'order'
    ];
} 