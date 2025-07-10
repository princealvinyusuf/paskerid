<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MitraKerja;

class MitraKerjaController extends Controller
{
    public function index(Request $request)
    {
        $stakeholders = MitraKerja::paginate(9); // 9 per page for 3x3 grid
        return view('mitra_kerja.index', compact('stakeholders'));
    }
} 