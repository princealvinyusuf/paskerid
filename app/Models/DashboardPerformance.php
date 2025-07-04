<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardPerformance extends Model
{
    use HasFactory;
    protected $table = 'dashboard_performance';
    protected $fillable = ['name', 'iframe_code'];
} 