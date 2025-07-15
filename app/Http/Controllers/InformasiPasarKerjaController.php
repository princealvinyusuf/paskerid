<?php

namespace App\Http\Controllers;

use App\Models\InformasiSection1;
use Illuminate\Http\Request;

class InformasiPasarKerjaController extends Controller
{
    public function index()
    {
        $informasiSection1 = InformasiSection1::orderBy('order')->get();
        return view('informasi_pasar_kerja.index', compact('informasiSection1'));
    }
} 