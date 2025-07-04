<?php

namespace App\Http\Controllers;

use App\Models\DashboardLaborDemand;
use Illuminate\Http\Request;

class DashboardLaborDemandController extends Controller
{
    public function index()
    {
        $dashboard = DashboardLaborDemand::first();
        return view('dashboard_labor_demand.index', compact('dashboard'));
    }
} 