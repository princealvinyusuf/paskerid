<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraKerja extends Model
{
    use HasFactory;
    protected $table = 'mitra_kerja';
    protected $fillable = [
        'name', 'wilayah', 'address', 'contact', 'email', 'website_url'
    ];
} 