<?php

namespace App\Http\Controllers;

use App\Models\Dataset;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::orderBy('order')->get()->groupBy('category');
        return view('datasets.index', compact('datasets'));
    }
}
