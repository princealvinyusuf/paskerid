<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MitraKerja;

class MitraKerjaController extends Controller
{
    public function index(Request $request)
    {
        $divider  = $request->query('divider', 'dinas');
        $wilayah  = $request->query('wilayah');
        $category = $request->query('category');

        $query = MitraKerja::where('divider', $divider);

        if ($wilayah) {
            $query->where('wilayah', $wilayah);
        }

        if ($category) {
            $query->where('category', $category);
        }

        // 🔑 INI YANG PENTING → bawa semua query string ke pagination
        $stakeholders = $query->paginate(9)->withQueryString();

        $wilayahList = MitraKerja::where('divider', $divider)
            ->whereNotNull('wilayah')
            ->distinct()
            ->pluck('wilayah');

        $categoryList = MitraKerja::where('divider', $divider)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('mitra_kerja.index', compact(
            'stakeholders',
            'divider',
            'wilayahList',
            'categoryList',
            'wilayah',
            'category'
        ));
    }
}
