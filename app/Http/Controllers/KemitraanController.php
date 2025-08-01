<?php

namespace App\Http\Controllers;

use App\Models\Kemitraan;
use App\Models\TypeOfPartnership;
use App\Models\companysector;
use App\Models\PaskerRoom;
use App\Models\PaskerFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KemitraanController extends Controller
{
    public function create(Request $request)
    {
        // Get selected partnership type from request (default to first type if not set)
        $selectedType = $request->input('partnership_type', 'Walk-in Interview');
        $dropdownPartnership = TypeOfPartnership::all();
        $dropdownCompanySectors = companysector::all();
        $imagePaskerRoom = PaskerRoom::all();
        $paskerFacility = PaskerFacility::all();

        // Partnership type limits (should match your PHP array)
        // $type_limits = [
        //     'Walk-in Interview' => 10,
        //     'Pendidikan Pasar Kerja' => 5,
        //     'Talenta Muda' => 8,
        //     'Job Fair' => 7,
        //     'Konsultasi Pasar Kerja' => 3,
        //     'Konsultasi Informasi Pasar Kerja' => 3,
        // ];
        // $max_bookings = $type_limits[$selectedType] ?? 10;

        // // Query for fully booked dates for the selected type
        // $fullyBookedDates = DB::table('booked_date')
        //     ->select('booked_date')
        //     ->where('type_of_partnership_id')
        //     ->groupBy('booked_date', 'max_bookings')
        //     ->havingRaw('COUNT(*) >= max_bookings')
        //     ->pluck('booked_date')
        //     ->toArray();
        // $formData = $request->all();
        // return view('kemitraan.create', compact('fullyBookedDates', 'selectedType', 'formData'));
        return view('kemitraan.create', compact('dropdownPartnership', 'dropdownCompanySectors', 'imagePaskerRoom' ,'paskerFacility'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pic_name' => 'required|string|max:255',
            'pic_position' => 'required|string|max:255',
            'pic_email' => 'required|email|max:255',
            'pic_whatsapp' => 'required|string|max:30',
            'company_sectors_id' => 'required',
            'institution_name' => 'required|string|max:255',
            'business_sector' => 'nullable|string|max:255',
            'institution_address' => 'required|string|max:255',
            'type_of_partnership_id' => 'required',
            'facility' => 'required|array|min:1',
            [
                'facility.required' => 'Pilih minimal satu fasilitas.',
                'facility.min' => 'Pilih minimal satu fasilitas.',
            ],
            'schedule' => 'nullable|string|max:255',
            'request_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('request_letter')) {
            $validated['request_letter'] = $request->file('request_letter')->store('kemitraan_letters', 'public');
        }

        Kemitraan::create($validated);

        return redirect()->route('kemitraan.create')->with('success', true);
    }
} 