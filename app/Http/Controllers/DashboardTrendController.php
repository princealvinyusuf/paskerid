<?php

namespace App\Http\Controllers;

use App\Models\DashboardTrend;
use Illuminate\Http\Request;

class DashboardTrendController extends Controller
{
    public function index()
    {
        $dashboard = DashboardTrend::first();
        return view('dashboard_trend.index', compact('dashboard'));
    }
} 