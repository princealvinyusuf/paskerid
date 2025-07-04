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
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        $information = $query->orderByDesc('date')->paginate(10);
        $types = Information::select('type')->distinct()->pluck('type');
        $subjects = Information::select('subject')->distinct()->pluck('subject');
        $selectedSubject = $request->subject;
        $showId = $request->query('show');
        $showInfo = null;
        if ($showId) {
            $showInfo = \App\Models\Information::find($showId);
        }

        return view('informasi.index', compact('information', 'types', 'subjects', 'selectedSubject', 'showInfo'));
    }

    public function show($id)
    {
        $info = \App\Models\Information::findOrFail($id);
        return view('informasi.show', compact('info'));
    }
} 