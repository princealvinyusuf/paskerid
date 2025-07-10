<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MitraKerja;

class MitraKerjaController extends Controller
{
    public function index(Request $request)
    {
        $divider = $request->query('divider', 'dinas');
        $stakeholders = MitraKerja::where('divider', $divider)->paginate(9);
        return view('mitra_kerja.index', compact('stakeholders', 'divider'));
    }
} 