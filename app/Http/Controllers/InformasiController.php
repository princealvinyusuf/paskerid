<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Information;

class InformasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Information::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $information = $query->orderByDesc('date')->paginate(10);
        $types = Information::select('type')->distinct()->pluck('type');

        return view('informasi.index', compact('information', 'types'));
    }
} 