<?php

namespace App\Http\Controllers;

use App\Models\InformasiSection1;
use Illuminate\Http\Request;
use App\Models\InformasiSection2;

class InformasiPasarKerjaController extends Controller
{
    public function index()
    {
        $informasiSection1 = InformasiSection1::orderBy('order')->get();
        $informasiSection2 = InformasiSection2::orderBy('order')->get();
        return view('informasi_pasar_kerja.index', compact('informasiSection1', 'informasiSection2'));
    }
} 