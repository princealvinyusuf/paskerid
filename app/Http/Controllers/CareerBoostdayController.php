<?php

namespace App\Http\Controllers;

use App\Models\BookedDate;
use App\Models\CareerBoostdayConsultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $bookedFeatureAvailable =
            Schema::hasTable('career_boostday_consultations') &&
            Schema::hasColumn('career_boostday_consultations', 'admin_status') &&
            Schema::hasColumn('career_boostday_consultations', 'booked_date');

        $hasPicRelation =
            Schema::hasColumn('career_boostday_consultations', 'pic_id') &&
            Schema::hasTable('career_boostday_pics');

        $bookedKonsultasi = collect();
        if ($bookedFeatureAvailable) {
            $today = Carbon::today()->toDateString();
            $q = DB::table('career_boostday_consultations as c');
            if ($hasPicRelation) {
                $q->leftJoin('career_boostday_pics as p', 'p.id', '=', 'c.pic_id');
            }

            $select = [
                'c.booked_date',
                'c.booked_time_start',
                'c.booked_time_finish',
                'c.name',
                'c.jenis_konseling',
                'c.jadwal_konseling',
            ];
            if ($hasPicRelation) {
                $select[] = DB::raw('p.name as konselor_name');
            } else {
                $select[] = DB::raw("'-' as konselor_name");
            }

            $rows = $q
                ->where('c.admin_status', 'accepted')
                ->whereNotNull('c.booked_date')
                ->where('c.booked_date', '>=', $today)
                ->orderBy('c.booked_date', 'asc')
                ->orderBy('c.booked_time_start', 'asc')
                ->orderBy('c.created_at', 'asc')
                ->limit(200)
                ->get($select);

            $bookedKonsultasi = $rows->map(function ($r) {
                $time = null;
                if (!empty($r->booked_time_start) && !empty($r->booked_time_finish)) {
                    $time = substr((string)$r->booked_time_start, 0, 5) . ' - ' . substr((string)$r->booked_time_finish, 0, 5);
                }

                return (object) [
                    'booked_date' => $r->booked_date,
                    'time' => $time,
                    'masked_name' => $this->maskPersonName((string)($r->name ?? '')),
                    'konselor_name' => (string)($r->konselor_name ?? '-'),
                    'jenis_konseling' => (string)($r->jenis_konseling ?? ''),
                    'jadwal_konseling' => (string)($r->jadwal_konseling ?? ''),
                ];
            });
        }

        return view('career_boostday.index', compact('tab', 'konsultasiSlots', 'konsultasiAgendas', 'bookedKonsultasi', 'bookedFeatureAvailable'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'status_choice' => ['required', 'string', 'in:fresh,pindah,lainnya'],
            'status_other' => ['nullable', 'string', 'max:120'],
            'jenis_konseling' => ['required', 'string', 'max:255'],
            'jadwal_konseling' => ['required', 'string', 'max:120'],
            'pendidikan_choice' => ['nullable', 'string', 'max:40'],
            'pendidikan_other' => ['nullable', 'string', 'max:120'],
            'jurusan' => ['nullable', 'string', 'max:120'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $statusChoice = $validated['status_choice'];
        $status = $statusChoice;
        if ($statusChoice === 'lainnya') {
            $other = trim((string)($validated['status_other'] ?? ''));
            if ($other === '') {
                return back()
                    ->withErrors(['status_other' => 'Field Other wajib diisi jika memilih Lainnya.'])
                    ->withInput();
            }
            $status = $other;
        } elseif ($statusChoice === 'fresh') {
            $status = 'Fresh Graduate';
        } elseif ($statusChoice === 'pindah') {
            $status = 'Sudah bekerja & ingin pindah kerja';
        }

        $pendidikan = null;
        if (array_key_exists('pendidikan_choice', $validated) && $validated['pendidikan_choice'] !== null && $validated['pendidikan_choice'] !== '') {
            if ($validated['pendidikan_choice'] === 'Lainnya') {
                $other = trim((string)($validated['pendidikan_other'] ?? ''));
                if ($other === '') {
                    return back()
                        ->withErrors(['pendidikan_other' => 'Field Pendidikan (Lainnya) wajib diisi jika memilih Lainnya.'])
                        ->withInput();
                }
                $pendidikan = $other;
            } else {
                $pendidikan = $validated['pendidikan_choice'];
            }
        }

        $cvPath = null;
        $cvOriginalName = null;
        if ($request->hasFile('cv')) {
            $cvOriginalName = $request->file('cv')->getClientOriginalName();
            $cvPath = $request->file('cv')->store('career_boostday/cv', 'public');
        }

        $jurusan = null;
        if (array_key_exists('jurusan', $validated)) {
            $j = trim((string)$validated['jurusan']);
            $jurusan = $j !== '' ? $j : null;
        }

        $payload = [
            'name' => $validated['name'],
            'whatsapp' => $validated['whatsapp'],
            'status' => $status,
            'jenis_konseling' => $validated['jenis_konseling'],
            'jadwal_konseling' => $validated['jadwal_konseling'],
            'pendidikan_terakhir' => $pendidikan,
            'cv_path' => $cvPath,
            'cv_original_name' => $cvOriginalName,
        ];

        // Backward compatible: only save if the column exists (migration may not be applied yet).
        if (Schema::hasColumn('career_boostday_consultations', 'jurusan')) {
            $payload['jurusan'] = $jurusan;
        }

        CareerBoostdayConsultation::create($payload);

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

    private function maskPersonName(string $name): string
    {
        $name = trim(preg_replace('/\s+/u', ' ', $name) ?? '');
        if ($name === '') {
            return '';
        }

        $parts = explode(' ', $name);
        $maskedParts = [];

        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }

            $len = mb_strlen($part, 'UTF-8');
            if ($len <= 1) {
                $maskedParts[] = '*';
                continue;
            }
            if ($len === 2) {
                $maskedParts[] = mb_substr($part, 0, 1, 'UTF-8') . '*';
                continue;
            }

            $first = mb_substr($part, 0, 1, 'UTF-8');
            $last = mb_substr($part, $len - 1, 1, 'UTF-8');
            $middleCount = max(2, $len - 2);
            $maskedParts[] = $first . str_repeat('*', $middleCount) . $last;
        }

        return implode(' ', $maskedParts);
    }
}


