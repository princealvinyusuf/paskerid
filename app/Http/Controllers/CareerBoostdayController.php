<?php

namespace App\Http\Controllers;

use App\Models\BookedDate;
use App\Models\CareerBoostdayConsultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CareerBoostdayController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'form');

        $konsultasiSlots = [
            'Senin (pukul 09.00 s/d 11.00)',
            'Senin (pukul 13.30 s/d 15.00)',
            'Kamis (pukul 09.00 s/d 11.00)',
            'Kamis (pukul 13.30 s/d 15.00)',
        ];

        // Reuse the same public agenda datasource as Virtual Karir, but filter to "konsultasi"
        $konsultasiAgendas = $this->getAgendasFromBookedDates()
            ->filter(function ($agenda) {
                $haystack = strtolower(($agenda->title ?? '') . ' ' . ($agenda->description ?? ''));
                return str_contains($haystack, 'konsultasi');
            })
            ->values();

        return view('career_boostday.index', compact('tab', 'konsultasiSlots', 'konsultasiAgendas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'status_choice' => ['required', 'string', 'max:20'],
            'status_other' => ['nullable', 'string', 'max:120'],
            'jenis_konseling' => ['required', 'string', 'max:120'],
            'jadwal_konseling' => ['required', 'string', 'max:120'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:120'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $statusChoice = $validated['status_choice'];
        $status = $statusChoice;
        if ($statusChoice === 'Lainnya') {
            $other = trim((string)($validated['status_other'] ?? ''));
            if ($other === '') {
                return back()
                    ->withErrors(['status_other' => 'Field Other wajib diisi jika memilih Lainnya.'])
                    ->withInput();
            }
            $status = $other;
        }

        $cvPath = null;
        $cvOriginalName = null;
        if ($request->hasFile('cv')) {
            $cvOriginalName = $request->file('cv')->getClientOriginalName();
            $cvPath = $request->file('cv')->store('career_boostday/cv', 'public');
        }

        CareerBoostdayConsultation::create([
            'name' => $validated['name'],
            'whatsapp' => $validated['whatsapp'],
            'status' => $status,
            'jenis_konseling' => $validated['jenis_konseling'],
            'jadwal_konseling' => $validated['jadwal_konseling'],
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? null,
            'cv_path' => $cvPath,
            'cv_original_name' => $cvOriginalName,
        ]);

        return redirect()
            ->route('career-boostday.index', ['tab' => 'form'])
            ->with('success', 'Terima kasih! Form konsultasi karir Anda sudah terkirim.');
    }

    private function getAgendasFromBookedDates()
    {
        $hasRange = Schema::hasColumn('booked_date', 'booked_date_start');
        $hasTimeRange = Schema::hasColumn('booked_date', 'booked_time_start');

        $today = Carbon::today()->format('Y-m-d');

        $query = BookedDate::with([
            'kemitraan.typeOfPartnership',
            'kemitraan.rooms',
            'kemitraan.facilities',
            'typeOfPartnership',
        ])->whereHas('kemitraan', function ($q) {
            // Only show approved kemitraan bookings in the public agenda
            if (Schema::hasColumn('kemitraan', 'status')) {
                $q->where('status', 'approved');
            }
        });

        if ($hasRange) {
            $query->where('booked_date_start', '>=', $today);
            $query->orderBy('booked_date_start', 'asc');
        } else {
            $query->where('booked_date', '>=', $today);
            $query->orderBy('booked_date', 'asc');
        }

        $bookedDates = $query->get();
        $agendas = collect();

        foreach ($bookedDates as $bookedDate) {
            $kemitraan = $bookedDate->kemitraan;
            if (!$kemitraan) {
                continue;
            }

            $date = $hasRange
                ? ($bookedDate->booked_date_start ?? $bookedDate->booked_date)
                : $bookedDate->booked_date;

            if (!$date) {
                continue;
            }

            $dateStr = $date instanceof Carbon
                ? $date->format('Y-m-d')
                : (is_string($date) ? $date : Carbon::parse($date)->format('Y-m-d'));

            $partnershipType = $bookedDate->typeOfPartnership ?? $kemitraan->typeOfPartnership;
            $partnershipTypeName = $partnershipType ? $partnershipType->name : 'Kegiatan Kemitraan';

            $rooms = $kemitraan->rooms->pluck('room_name')->toArray();
            $roomNames = !empty($rooms) ? implode(', ', $rooms) : ($kemitraan->other_pasker_room ?? '-');

            $facilities = $kemitraan->facilities->pluck('facility_name')->toArray();
            $facilityNames = !empty($facilities) ? implode(', ', $facilities) : ($kemitraan->other_pasker_facility ?? '-');

            $location = trim($roomNames . ($facilityNames !== '-' ? ' - ' . $facilityNames : ''), ' -');

            $timeInfo = '';
            if ($hasTimeRange) {
                $timeStart = $bookedDate->booked_time_start ? substr($bookedDate->booked_time_start, 0, 5) : ($kemitraan->scheduletimestart ?? '');
                $timeFinish = $bookedDate->booked_time_finish ? substr($bookedDate->booked_time_finish, 0, 5) : ($kemitraan->scheduletimefinish ?? '');
                if ($timeStart && $timeFinish) {
                    $timeInfo = $timeStart . ' - ' . $timeFinish;
                } elseif ($timeStart) {
                    $timeInfo = $timeStart;
                }
            } else {
                $timeStart = $bookedDate->booked_time ? substr($bookedDate->booked_time, 0, 5) : ($kemitraan->scheduletimestart ?? '');
                $timeFinish = $kemitraan->scheduletimefinish ?? '';
                if ($timeStart && $timeFinish) {
                    $timeInfo = $timeStart . ' - ' . $timeFinish;
                } elseif ($timeStart) {
                    $timeInfo = $timeStart;
                }
            }

            $agenda = (object) [
                'id' => $bookedDate->id,
                'title' => $partnershipTypeName . ($kemitraan->institution_name ? ' - ' . $kemitraan->institution_name : ''),
                'description' => ($kemitraan->institution_name ?? '') .
                    ($timeInfo ? ' (' . $timeInfo . ')' : '') .
                    ($location && $location !== '-' ? ' - Lokasi: ' . $location : ''),
                'date' => $dateStr,
                'location' => $location ?: '-',
                'organizer' => $kemitraan->institution_name ?? '-',
            ];

            $agendas->push($agenda);
        }

        return $agendas->sortBy('date')->values();
    }
}


