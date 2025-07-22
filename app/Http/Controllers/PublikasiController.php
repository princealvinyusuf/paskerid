<?php

namespace App\Http\Controllers;

use App\Models\Publication;

class PublikasiController extends Controller
{
    public function index()
    {
        $publikasi = Publication::orderByDesc('date')->get();
        return view('publikasi.index', compact('publikasi'));
    }
} 