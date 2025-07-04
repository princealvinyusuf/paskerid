<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardLaborDemand extends Model
{
    use HasFactory;
    protected $table = 'dashboard_labor_demand';
    protected $fillable = ['name', 'iframe_code'];
} 