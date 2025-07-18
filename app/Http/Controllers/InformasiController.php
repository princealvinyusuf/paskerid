<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Information;

class InformasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Information::query();
        $query->where('status', 'publik'); // Only show public information

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }
        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $information = $query->orderByDesc('date')->paginate(10);
        $types = Information::where('status', 'publik')->select('type')->distinct()->pluck('type');
        $subjects = Information::where('status', 'publik')->select('subject')->distinct()->pluck('subject');
        $selectedSubject = $request->subject;
        $showId = $request->query('show');
        $showInfo = null;
        if ($showId) {
            $showInfo = \App\Models\Information::find($showId);
        }
        // Fetch description for selected subject and type
        $description = null;
        if ($selectedSubject) {
            $descQuery = Information::where('subject', $selectedSubject)->where('status', 'publik');
            if ($request->filled('type')) {
                $descQuery->where('type', $request->type);
            }
            $descRecord = $descQuery->orderByDesc('date')->first();
            $description = $descRecord ? $descRecord->description : null;
        }
        return view('informasi.index', compact('information', 'types', 'subjects', 'selectedSubject', 'showInfo', 'description'));
    }

    public function show($id)
    {
        $info = \App\Models\Information::findOrFail($id);
        return view('informasi.show', compact('info'));
    }
} 