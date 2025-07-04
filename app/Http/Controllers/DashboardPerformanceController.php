<?php

namespace App\Http\Controllers;

use App\Models\DashboardPerformance;
use Illuminate\Http\Request;

class DashboardPerformanceController extends Controller
{
    public function index()
    {
        $dashboard = DashboardPerformance::first();
        return view('dashboard_performance.index', compact('dashboard'));
    }
} 