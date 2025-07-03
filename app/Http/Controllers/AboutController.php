<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $sections = AboutSection::orderBy('order')->get();
        return view('about', compact('sections'));
    }
} 