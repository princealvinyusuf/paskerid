<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardTrend extends Model
{
    use HasFactory;
    protected $table = 'dashboard_trend';
    protected $fillable = ['name', 'iframe_code'];
} 