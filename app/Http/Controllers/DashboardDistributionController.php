<?php

namespace App\Http\Controllers;

use App\Models\DashboardDistribution;
use Illuminate\Http\Request;

class DashboardDistributionController extends Controller
{
    public function index()
    {
        $dashboard = DashboardDistribution::first();
        return view('dashboard_distribution.index', compact('dashboard'));
    }
} 