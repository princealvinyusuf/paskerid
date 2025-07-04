<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardDistribution extends Model
{
    use HasFactory;
    protected $table = 'dashboard_distribution';
    protected $fillable = ['name', 'iframe_code'];
} 