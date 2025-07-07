<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VirtualKarirService;
use App\Models\VirtualKarirJobFair;
use App\Models\VirtualKarirAgenda;

class VirtualKarirController extends Controller
{
    public function index()
    {
        $services = VirtualKarirService::all();
        $jobFairs = VirtualKarirJobFair::orderBy('date', 'desc')->get();
        $agendas = VirtualKarirAgenda::orderBy('date', 'asc')->get();
        return view('virtual_karir', compact('services', 'jobFairs', 'agendas'));
    }
} 