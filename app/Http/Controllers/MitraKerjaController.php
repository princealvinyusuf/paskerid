<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MitraKerja;

class MitraKerjaController extends Controller
{
    public function index(Request $request)
    {
        $divider = $request->query('divider', 'dinas');
        $query = MitraKerja::where('divider', $divider);
        $wilayah = $request->query('wilayah');
        $category = $request->query('category');
        if ($wilayah) {
            $query->where('wilayah', $wilayah);
        }
        if ($category) {
            $query->where('category', $category);
        }
        $stakeholders = $query->paginate(9);
        $wilayahList = MitraKerja::where('divider', $divider)->whereNotNull('wilayah')->distinct()->pluck('wilayah');
        $categoryList = MitraKerja::where('divider', $divider)->whereNotNull('category')->distinct()->pluck('category');
        return view('mitra_kerja.index', compact('stakeholders', 'divider', 'wilayahList', 'categoryList', 'wilayah', 'category'));
    }
} 