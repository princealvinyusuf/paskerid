<?php

namespace App\Http\Controllers;

use App\Models\InformasiSection1;
use Illuminate\Http\Request;
use App\Models\InformasiSection2;
use App\Models\InformasiSection3;
use App\Models\InformasiSection4;
use App\Models\Publication;

class InformasiPasarKerjaController extends Controller
{
    public function index()
    {
        $informasiSection1 = InformasiSection1::orderBy('order')->get();
        $informasiSection2 = InformasiSection2::orderBy('order')->get();
        $informasiSection3 = InformasiSection3::orderBy('order')->get();
        $informasiSection4 = InformasiSection4::orderBy('order')->get();
        $publikasi = Publication::orderByDesc('date')->take(10)->get();
        return view('informasi_pasar_kerja.index', compact('informasiSection1', 'informasiSection2', 'informasiSection3', 'informasiSection4', 'publikasi'));
    }
} 