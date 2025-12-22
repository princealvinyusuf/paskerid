<?php

namespace App\Http\Controllers;

use App\Models\Kemitraan;
use App\Models\TypeOfPartnership;
use App\Models\companysector;
use App\Models\PaskerRoom;
use App\Models\PaskerFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KemitraanController extends Controller
{
    public function create(Request $request)
    {
        // Determine selected partnership type id (fallback to first type if not provided)
        $dropdownPartnership = TypeOfPartnership::all();
        $dropdownCompanySectors = companysector::all();
        $imagePaskerRoom = PaskerRoom::all();
        $paskerFacility = PaskerFacility::all();

        $defaultTypeId = (int) $request->input('type_of_partnership_id', (int) optional($dropdownPartnership->first())->id);
        $fullyBookedDates = $this->computeFullyBookedDates($defaultTypeId, 180);

        return view('kemitraan.create', compact('dropdownPartnership', 'dropdownCompanySectors', 'imagePaskerRoom' ,'paskerFacility', 'fullyBookedDates', 'defaultTypeId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pic_name' => 'required|string|max:255',
            'pic_position' => 'required|string|max:255',
            'pic_email' => 'required|email|max:255',
            'pic_whatsapp' => 'required|string|max:30',
            'company_sectors_id' => 'required|exists:company_sectors,id',
            'institution_name' => 'required|string|max:255',
            'business_sector' => 'nullable|string|max:255',
            'institution_address' => 'required|string|max:255',
            'type_of_partnership_id' => 'required|exists:type_of_partnership,id',
            'tipe_penyelenggara' => 'required|in:Job Portal,Perusahaan',
            // Multi-select rooms and facilities
            'pasker_room_ids' => 'nullable|array',
            'pasker_room_ids.*' => 'integer|exists:pasker_room,id',
            'other_pasker_room' => 'nullable|string|max:255',
            'pasker_facility_ids' => 'nullable|array',
            'pasker_facility_ids.*' => 'integer|exists:pasker_facility,id',
            'other_pasker_facility' => 'nullable|string|max:255',
            // At least one facility either selected or other provided
            // Custom manual check below will enforce this since array rules change semantics
            'schedule' => 'required|string|max:255',
            'scheduletimestart' => 'nullable|date_format:H:i',
            'scheduletimefinish' => 'nullable|date_format:H:i',
            'request_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Enforce facility presence: either array not empty or other provided
        $facilityIds = $request->input('pasker_facility_ids', []);
        $otherFacility = $request->input('other_pasker_facility');
        if (empty($facilityIds) && empty($otherFacility)) {
            return back()->withErrors(['pasker_facility_ids' => 'Pilih minimal satu fasilitas atau isi Lainnya.'])->withInput();
        }

        if ($request->hasFile('request_letter')) {
            $validated['request_letter'] = $request->file('request_letter')->store('kemitraan_letters', 'public');
        }

        // Normalize time fields to HH:MM:SS if provided
        $timeStart = $request->input('scheduletimestart');
        $timeFinish = $request->input('scheduletimefinish');
        $validated['scheduletimestart'] = $timeStart ? ($timeStart . ':00') : null;
        $validated['scheduletimefinish'] = $timeFinish ? ($timeFinish . ':00') : null;

        // Backward compatibility: persist first selected room/facility id into legacy columns
        $roomIds = $request->input('pasker_room_ids', []);
        $validated['pasker_room_id'] = !empty($roomIds) ? (int) $roomIds[0] : null;

        $validated['pasker_facility_id'] = !empty($facilityIds) ? (int) $facilityIds[0] : null;

        DB::transaction(function () use ($validated, $roomIds, $facilityIds) {
            $kemitraan = Kemitraan::create($validated);

            if (!empty($roomIds)) {
                $kemitraan->rooms()->sync(array_values(array_unique(array_map('intval', $roomIds))));
            }
            if (!empty($facilityIds)) {
                $kemitraan->facilities()->sync(array_values(array_unique(array_map('intval', $facilityIds))));
            }
        });

        return redirect()->route('kemitraan.create')->with('success', true);
    }

    public function fullyBookedDates(Request $request)
    {
        $typeId = (int) $request->query('type_id');
        if ($typeId <= 0) {
            return response()->json([]);
        }
        $daysAhead = (int) $request->query('days', 180);
        $dates = $this->computeFullyBookedDates($typeId, $daysAhead);
        return response()->json($dates);
    }

    private function computeFullyBookedDates(int $typeId, int $daysAhead = 180): array
    {
        $today = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$daysAhead} days"));

        $type = TypeOfPartnership::find($typeId);
        $maxBookings = $type && isset($type->max_bookings) ? (int) $type->max_bookings : 10;

        $hasRange = Schema::hasColumn('booked_date', 'booked_date_start');
        $hasTypeCol = Schema::hasColumn('booked_date', 'type_of_partnership_id');

        $disabled = [];

        if ($hasRange) {
            $query = DB::table('booked_date')
                ->select('booked_date_start', 'booked_date_finish');
            if ($hasTypeCol) {
                $query->where('type_of_partnership_id', $typeId);
            }
            $rows = $query
                ->where('booked_date_start', '<=', $endDate)
                ->where('booked_date_finish', '>=', $today)
                ->get();

            $counts = [];
            foreach ($rows as $row) {
                $start = $row->booked_date_start ?: $today;
                $finish = $row->booked_date_finish ?: $row->booked_date_start;
                $start = max($start, $today);
                $finish = min($finish, $endDate);
                $cur = strtotime($start);
                $end = strtotime($finish);
                if ($cur === false || $end === false) { continue; }
                while ($cur <= $end) {
                    $d = date('Y-m-d', $cur);
                    $counts[$d] = isset($counts[$d]) ? $counts[$d] + 1 : 1;
                    $cur = strtotime('+1 day', $cur);
                }
            }
            foreach ($counts as $d => $cnt) {
                if ($cnt >= $maxBookings) { $disabled[] = $d; }
            }
        } else {
            $query = DB::table('booked_date')
                ->select('booked_date as d', DB::raw('COUNT(*) as cnt'))
                ->whereBetween('booked_date', [$today, $endDate]);
            if ($hasTypeCol) {
                $query->where('type_of_partnership_id', $typeId);
            }
            $rows = $query->groupBy('d')->get();
            foreach ($rows as $row) {
                if ((int) $row->cnt >= $maxBookings) {
                    $disabled[] = $row->d;
                }
            }
        }

        sort($disabled);
        return array_values(array_unique($disabled));
    }
} 