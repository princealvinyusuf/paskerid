<?php

namespace App\Http\Controllers;

use App\Models\Kemitraan;
use Illuminate\Http\Request;

class KemitraanController extends Controller
{
    public function index()
    {
        $kemitraan = Kemitraan::latest()->paginate(10);
        return view('kemitraan.index', compact('kemitraan'));
    }

    public function create()
    {
        return view('kemitraan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pic_name' => 'required|string|max:255',
            'pic_position' => 'required|string|max:255',
            'pic_email' => 'required|email|max:255',
            'pic_whatsapp' => 'required|string|max:30',
            'sector_category' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'business_sector' => 'nullable|string|max:255',
            'institution_address' => 'required|string|max:255',
            'partnership_type' => 'required|string|max:255',
            'needs' => 'nullable|string',
            'schedule' => 'nullable|string|max:255',
            'request_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('request_letter')) {
            $validated['request_letter'] = $request->file('request_letter')->store('kemitraan_letters', 'public');
        }

        Kemitraan::create($validated);

        return redirect()->route('kemitraan.index')->with('success', 'Pendaftaran kemitraan berhasil dikirim.');
    }

    public function show(Kemitraan $kemitraan)
    {
        return view('kemitraan.show', compact('kemitraan'));
    }

    public function edit(Kemitraan $kemitraan)
    {
        return view('kemitraan.edit', compact('kemitraan'));
    }

    public function update(Request $request, Kemitraan $kemitraan)
    {
        $validated = $request->validate([
            'pic_name' => 'required|string|max:255',
            'pic_position' => 'required|string|max:255',
            'pic_email' => 'required|email|max:255',
            'pic_whatsapp' => 'required|string|max:30',
            'sector_category' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'business_sector' => 'nullable|string|max:255',
            'institution_address' => 'required|string|max:255',
            'partnership_type' => 'required|string|max:255',
            'needs' => 'nullable|string',
            'schedule' => 'nullable|string|max:255',
            'request_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('request_letter')) {
            $validated['request_letter'] = $request->file('request_letter')->store('kemitraan_letters', 'public');
        }

        $kemitraan->update($validated);

        return redirect()->route('kemitraan.index')->with('success', 'Data kemitraan berhasil diperbarui.');
    }

    public function destroy(Kemitraan $kemitraan)
    {
        $kemitraan->delete();
        return redirect()->route('kemitraan.index')->with('success', 'Data kemitraan berhasil dihapus.');
    }
} 