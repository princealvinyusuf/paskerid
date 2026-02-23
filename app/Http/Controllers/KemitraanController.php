<?php

namespace App\Http\Controllers;

use App\Models\Kemitraan;
use App\Models\TypeOfPartnership;
use App\Models\BookedDate;
use App\Models\companysector;
use App\Models\PaskerRoom;
use App\Models\PaskerFacility;
use App\Models\WalkInSurveyCompany;
use App\Models\WalkInSurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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

        $walkinAgendas = $this->getWalkinAgendasFromBookedDates('upcoming');
        $walkinAgendasPast = $this->getWalkinAgendasFromBookedDates('past', 80);
        $surveyCompanies = collect();
        if (Schema::hasTable('company_walk_in_survey')) {
            $surveyCompanies = WalkInSurveyCompany::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('company_name')
                ->get(['id', 'company_name']);
        }

        return view('kemitraan.create', compact(
            'dropdownPartnership',
            'dropdownCompanySectors',
            'imagePaskerRoom',
            'paskerFacility',
            'fullyBookedDates',
            'defaultTypeId',
            'walkinAgendas',
            'walkinAgendasPast',
            'surveyCompanies'
        ));
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
            // Detail Lowongan (repeatable)
            'detail_lowongan' => 'required|array|min:1',
            'detail_lowongan.*.jabatan_yang_dibuka' => 'required|string|max:255',
            'detail_lowongan.*.jumlah_kebutuhan' => 'required|integer|min:1|max:1000000',
            'detail_lowongan.*.gender' => 'nullable|string|max:50',
            'detail_lowongan.*.pendidikan_terakhir' => 'nullable|string|max:255',
            'detail_lowongan.*.pengalaman_kerja' => 'nullable|string|max:255',
            'detail_lowongan.*.kompetensi_yang_dibutuhkan' => 'nullable|string',
            'detail_lowongan.*.tahapan_seleksi' => 'nullable|string',
            'detail_lowongan.*.lokasi_penempatan' => 'nullable|string|max:255',
            'detail_lowongan.*.nama_perusahaan' => 'nullable|array',
            'detail_lowongan.*.nama_perusahaan.*' => 'nullable|string|max:255',
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
            'foto_kartu_pegawai_pic' => 'required|file|mimes:png,jpeg,jpg|max:2048',
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
        // foto_kartu_pegawai_pic is required, so it will always be present after validation
        $validated['foto_kartu_pegawai_pic'] = $request->file('foto_kartu_pegawai_pic')->store('kemitraan_foto_kartu', 'public');

        // Normalize time fields to HH:MM:SS if provided
        $timeStart = $request->input('scheduletimestart');
        $timeFinish = $request->input('scheduletimefinish');
        $validated['scheduletimestart'] = $timeStart ? ($timeStart . ':00') : null;
        $validated['scheduletimefinish'] = $timeFinish ? ($timeFinish . ':00') : null;

        // Backward compatibility: persist first selected room/facility id into legacy columns
        $roomIds = $request->input('pasker_room_ids', []);
        $validated['pasker_room_id'] = !empty($roomIds) ? (int) $roomIds[0] : null;

        $validated['pasker_facility_id'] = !empty($facilityIds) ? (int) $facilityIds[0] : null;

        $detailLowongan = $request->input('detail_lowongan', []);
        $isJobPortal = ($request->input('tipe_penyelenggara') === 'Job Portal');

        if ($isJobPortal) {
            foreach ($detailLowongan as $idx => $dl) {
                $companyNames = $dl['nama_perusahaan'] ?? [];
                if (!is_array($companyNames)) {
                    $companyNames = [$companyNames];
                }

                $companyNames = array_values(array_filter(array_map(static function ($name) {
                    return trim((string) $name);
                }, $companyNames), static function ($name) {
                    return $name !== '';
                }));

                if (count($companyNames) < 1) {
                    return back()
                        ->withErrors(["detail_lowongan.$idx.nama_perusahaan" => 'Untuk Tipe Penyelenggara Job Portal, setiap Detail Lowongan wajib memiliki minimal satu Nama Perusahaan.'])
                        ->withInput();
                }
            }
        }

        DB::transaction(function () use ($validated, $roomIds, $facilityIds, $detailLowongan, $isJobPortal) {
            $kemitraan = Kemitraan::create($validated);

            if (!empty($roomIds)) {
                $kemitraan->rooms()->sync(array_values(array_unique(array_map('intval', $roomIds))));
            }
            if (!empty($facilityIds)) {
                $kemitraan->facilities()->sync(array_values(array_unique(array_map('intval', $facilityIds))));
            }

            // Persist Detail Lowongan (1 kemitraan can have many lowongan)
            foreach ($detailLowongan as $dl) {
                $companyNames = $dl['nama_perusahaan'] ?? [];
                if (!is_array($companyNames)) {
                    $companyNames = [$companyNames];
                }
                $companyNames = array_values(array_unique(array_filter(array_map(static function ($name) {
                    return trim((string) $name);
                }, $companyNames), static function ($name) {
                    return $name !== '';
                })));

                $kemitraan->detailLowongan()->create([
                    'jabatan_yang_dibuka' => $dl['jabatan_yang_dibuka'] ?? null,
                    'jumlah_kebutuhan' => isset($dl['jumlah_kebutuhan']) ? (int) $dl['jumlah_kebutuhan'] : null,
                    'gender' => $dl['gender'] ?? null,
                    'pendidikan_terakhir' => $dl['pendidikan_terakhir'] ?? null,
                    'pengalaman_kerja' => $dl['pengalaman_kerja'] ?? null,
                    'kompetensi_yang_dibutuhkan' => $dl['kompetensi_yang_dibutuhkan'] ?? null,
                    'tahapan_seleksi' => $dl['tahapan_seleksi'] ?? null,
                    'lokasi_penempatan' => $dl['lokasi_penempatan'] ?? null,
                    'nama_perusahaan' => $isJobPortal ? $companyNames : null,
                ]);
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

    public function storeSurvey(Request $request)
    {
        if (!Schema::hasTable('company_walk_in_survey') || !Schema::hasTable('walk_in_survey_responses')) {
            return redirect()
                ->route('kemitraan.create', ['panel' => 'survey'])
                ->withErrors(['survey' => 'Fitur survei belum aktif di database. Jalankan migrasi terlebih dahulu.'], 'survey')
                ->withInput();
        }

        $validated = $request->validateWithBag('survey', [
            'survey_applied_company' => 'required|integer|exists:company_walk_in_survey,id',
            'survey_walkin_date' => 'nullable|date',
            'survey_email' => 'required|email|max:255',
            'survey_name' => 'required|string|max:255',
            'survey_phone' => 'required|string|max:30',
            'survey_domisili' => 'required|string|max:120',
            'survey_domisili_other' => 'nullable|string|max:255',
            'survey_gender' => 'required|string|max:50',
            'survey_age' => 'required|string|max:50',
            'survey_education' => 'required|string|max:100',

            'survey_info_source' => 'required|array|min:1',
            'survey_info_source.*' => 'string|max:255',
            'survey_info_source_other' => 'nullable|string|max:255',

            'survey_job_portal' => 'required|array|min:1',
            'survey_job_portal.*' => 'string|max:255',
            'survey_job_portal_other' => 'nullable|string|max:255',

            'survey_strengths' => 'required|array|min:1',
            'survey_strengths.*' => 'string|max:255',
            'survey_strengths_other' => 'nullable|string|max:255',

            'survey_missing_info' => 'required|array|min:1',
            'survey_missing_info.*' => 'string|max:255',
            'survey_missing_info_other' => 'nullable|string|max:255',

            'survey_general_feedback' => 'required|string|max:5000',

            'survey_rating_info' => 'required|integer|between:1,5',
            'survey_feedback_info' => 'required|string|max:5000',
            'survey_rating_facility' => 'required|integer|between:1,5',
            'survey_feedback_facility' => 'required|string|max:5000',
            'survey_rating_registration' => 'required|integer|between:1,5',
            'survey_feedback_registration' => 'required|string|max:5000',
            'survey_rating_quality_quantity' => 'required|integer|between:1,5',
            'survey_feedback_quality_quantity' => 'required|string|max:5000',
            'survey_rating_committee_help' => 'required|integer|between:1,5',
            'survey_feedback_committee_help' => 'required|string|max:5000',
            'survey_rating_access_info' => 'required|integer|between:1,5',
            'survey_feedback_access_info' => 'required|string|max:5000',
            'survey_rating_satisfaction' => 'required|integer|between:1,5',

            'survey_improvement_aspects' => 'required|array|min:1',
            'survey_improvement_aspects.*' => 'string|max:255',
            'survey_feedback_improvement_aspects' => 'required|string|max:5000',
        ]);

        $company = WalkInSurveyCompany::query()->findOrFail((int) $validated['survey_applied_company']);
        $today = Carbon::today()->toDateString();
        $hasWalkinDateColumn = Schema::hasColumn('walk_in_survey_responses', 'walkin_date');

        $payload = [
            'company_walk_in_survey_id' => (int) $company->id,
            'company_name_snapshot' => (string) $company->company_name,
            'email' => $validated['survey_email'],
            'name' => $validated['survey_name'],
            'phone' => $validated['survey_phone'],
            'domisili' => $validated['survey_domisili'],
            'domisili_other' => $validated['survey_domisili_other'] ?? null,
            'gender' => $validated['survey_gender'],
            'age_range' => $validated['survey_age'],
            'education' => $validated['survey_education'],
            'info_sources' => array_values(array_unique($validated['survey_info_source'] ?? [])),
            'info_source_other' => $validated['survey_info_source_other'] ?? null,
            'job_portals' => array_values(array_unique($validated['survey_job_portal'] ?? [])),
            'job_portal_other' => $validated['survey_job_portal_other'] ?? null,
            'strengths' => array_values(array_unique($validated['survey_strengths'] ?? [])),
            'strengths_other' => $validated['survey_strengths_other'] ?? null,
            'missing_infos' => array_values(array_unique($validated['survey_missing_info'] ?? [])),
            'missing_info_other' => $validated['survey_missing_info_other'] ?? null,
            'general_feedback' => $validated['survey_general_feedback'],
            'rating_info' => (int) $validated['survey_rating_info'],
            'feedback_info' => $validated['survey_feedback_info'],
            'rating_facility' => (int) $validated['survey_rating_facility'],
            'feedback_facility' => $validated['survey_feedback_facility'],
            'rating_registration' => (int) $validated['survey_rating_registration'],
            'feedback_registration' => $validated['survey_feedback_registration'],
            'rating_quality_quantity' => (int) $validated['survey_rating_quality_quantity'],
            'feedback_quality_quantity' => $validated['survey_feedback_quality_quantity'],
            'rating_committee_help' => (int) $validated['survey_rating_committee_help'],
            'feedback_committee_help' => $validated['survey_feedback_committee_help'],
            'rating_access_info' => (int) $validated['survey_rating_access_info'],
            'feedback_access_info' => $validated['survey_feedback_access_info'],
            'rating_satisfaction' => (int) $validated['survey_rating_satisfaction'],
            'improvement_aspects' => array_values(array_unique($validated['survey_improvement_aspects'] ?? [])),
            'feedback_improvement_aspects' => $validated['survey_feedback_improvement_aspects'],
        ];
        if ($hasWalkinDateColumn) {
            // System-filled date to keep field read-only and tamper-resistant.
            $payload['walkin_date'] = $today;
        }

        WalkInSurveyResponse::create($payload);

        return redirect()
            ->route('kemitraan.create', ['panel' => 'survey'])
            ->with('survey_success', 'Terima kasih! Survei evaluasi berhasil dikirim.');
    }

    public function companyWalkinSchedule(Request $request)
    {
        $company = trim((string) $request->query('company', ''));
        if ($company === '' || mb_strlen($company) > 255) {
            return response()->json(['upcoming' => [], 'past' => []]);
        }

        $keywords = $this->extractCompanyKeywords($company);
        if (empty($keywords)) {
            return response()->json(['upcoming' => [], 'past' => []]);
        }

        $today = Carbon::today()->format('Y-m-d');
        $hasRange = Schema::hasColumn('booked_date', 'booked_date_start');
        $hasTimeRange = Schema::hasColumn('booked_date', 'booked_time_start');

        $base = BookedDate::with([
            'kemitraan.typeOfPartnership',
            'kemitraan.rooms',
            'kemitraan.facilities',
            'typeOfPartnership'
        ])->whereHas('kemitraan', function ($q) use ($keywords) {
            // Match by keyword(s) so incomplete company names still work
            // Example: "Prima Karya" will match "PT Prima Karya Sarana Sejahtera (PKSS)"
            foreach ($keywords as $kw) {
                $like = '%' . $this->escapeLike($kw) . '%';
                $q->whereRaw("LOWER(institution_name) LIKE ? ESCAPE '\\\\'", [strtolower($like)]);
            }
            if (Schema::hasColumn('kemitraan', 'status')) {
                $q->where('status', 'approved');
            }
        });

        $upcomingQ = (clone $base);
        if ($hasRange) $upcomingQ->where('booked_date_start', '>=', $today)->orderBy('booked_date_start', 'asc');
        else $upcomingQ->where('booked_date', '>=', $today)->orderBy('booked_date', 'asc');

        $pastQ = (clone $base);
        if ($hasRange) {
            $pastQ->where(function ($q) use ($today) {
                $q->where('booked_date_finish', '<', $today)
                  ->orWhere(function ($q2) use ($today) {
                      $q2->whereNull('booked_date_finish')
                         ->where('booked_date_start', '<', $today);
                  });
            })->orderBy('booked_date_start', 'desc');
        } else {
            $pastQ->where('booked_date', '<', $today)->orderBy('booked_date', 'desc');
        }

        $upcoming = [];
        foreach ($upcomingQ->limit(50)->get() as $bd) {
            $agenda = $this->buildWalkinAgendaFromBookedDate($bd, $hasRange, $hasTimeRange);
            if ($agenda) $upcoming[] = $agenda;
        }

        $past = [];
        foreach ($pastQ->limit(50)->get() as $bd) {
            $agenda = $this->buildWalkinAgendaFromBookedDate($bd, $hasRange, $hasTimeRange);
            if ($agenda) $past[] = $agenda;
        }

        return response()->json(['upcoming' => $upcoming, 'past' => $past]);
    }

    private function extractCompanyKeywords(string $company): array
    {
        $c = mb_strtolower($company);
        // Normalize punctuation to spaces
        $c = preg_replace('/[^\pL\pN]+/u', ' ', $c) ?? $c;
        $parts = array_values(array_filter(array_map('trim', preg_split('/\s+/', $c) ?: [])));

        // Common legal/entity stopwords
        $stop = [
            'pt', 'cv', 'tbk', 'ud', 'pd',
            'persero', 'perseroan', 'terbatas', 'sejahtera', 'indonesia',
            'co', 'company', 'corp', 'corporation', 'inc', 'ltd', 'limited',
            'the', 'of', 'and',
        ];

        $keywords = [];
        foreach ($parts as $p) {
            if (mb_strlen($p) < 3) continue;
            if (in_array($p, $stop, true)) continue;
            $keywords[] = $p;
        }

        // Fallback: if everything was filtered out, use the first meaningful token
        if (empty($keywords)) {
            foreach ($parts as $p) {
                if (mb_strlen($p) >= 2 && !in_array($p, $stop, true)) {
                    $keywords[] = $p;
                    break;
                }
            }
        }

        // Limit to 3 keywords to keep query cheap
        return array_slice(array_values(array_unique($keywords)), 0, 3);
    }

    private function escapeLike(string $value): string
    {
        // Escape % and _ for LIKE; backslash is the escape char
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $value);
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

    /**
     * @param string $scope 'upcoming'|'past'
     * @param int $limit max records for past scope to keep UI light
     */
    private function getWalkinAgendasFromBookedDates(string $scope = 'upcoming', int $limit = 200)
    {
        $hasRange = Schema::hasColumn('booked_date', 'booked_date_start');
        $hasTimeRange = Schema::hasColumn('booked_date', 'booked_time_start');

        $today = Carbon::today()->format('Y-m-d');

        $query = BookedDate::with([
            'kemitraan.typeOfPartnership',
            'kemitraan.rooms',
            'kemitraan.facilities',
            'typeOfPartnership'
        ])->whereHas('kemitraan', function ($q) {
            // Only show approved kemitraan bookings in the public agenda
            if (Schema::hasColumn('kemitraan', 'status')) {
                $q->where('status', 'approved');
            }
        });

        // Scope filter: upcoming (today/future) or past
        if ($scope === 'past') {
            if ($hasRange) {
                // consider past when finish < today (or start < today when finish null)
                $query->where(function ($q) use ($today) {
                    $q->where('booked_date_finish', '<', $today)
                      ->orWhere(function ($q2) use ($today) {
                          $q2->whereNull('booked_date_finish')
                             ->where('booked_date_start', '<', $today);
                      });
                })->orderBy('booked_date_start', 'desc');
            } else {
                $query->where('booked_date', '<', $today)->orderBy('booked_date', 'desc');
            }
            $query->limit(max(1, $limit));
        } else {
            // Upcoming only (today and future)
            if ($hasRange) {
                $query->where('booked_date_start', '>=', $today)->orderBy('booked_date_start', 'asc');
            } else {
                $query->where('booked_date', '>=', $today)->orderBy('booked_date', 'asc');
            }
        }

        $bookedDates = $query->get();
        $agendas = collect();

        foreach ($bookedDates as $bookedDate) {
            $agenda = $this->buildWalkinAgendaFromBookedDate($bookedDate, $hasRange, $hasTimeRange);
            if ($agenda) $agendas->push($agenda);
        }

        if ($scope === 'past') {
            return $agendas->sortByDesc('date')->values();
        }
        return $agendas->sortBy('date')->values();
    }

    private function buildWalkinAgendaFromBookedDate(BookedDate $bookedDate, bool $hasRange, bool $hasTimeRange): ?object
    {
        $kemitraan = $bookedDate->kemitraan;
        if (!$kemitraan) return null;

        $date = $hasRange
            ? ($bookedDate->booked_date_start ?? $bookedDate->booked_date)
            : $bookedDate->booked_date;
        if (!$date) return null;

        $dateStr = $date instanceof Carbon
            ? $date->format('Y-m-d')
            : (is_string($date) ? $date : Carbon::parse($date)->format('Y-m-d'));

        $partnershipType = $bookedDate->typeOfPartnership ?? $kemitraan->typeOfPartnership;
        $partnershipTypeName = $partnershipType ? $partnershipType->name : 'Kegiatan Kemitraan';

        // Keep only Walk-in related entries
        $tLower = strtolower($partnershipTypeName);
        if (stripos($tLower, 'walk') === false && stripos($tLower, 'interview') === false && stripos($tLower, 'wawancara') === false) {
            return null;
        }

        $rooms = $kemitraan->rooms->pluck('room_name')->toArray();
        $roomNames = !empty($rooms)
            ? implode(', ', $rooms)
            : ($kemitraan->other_pasker_room ?? '-');

        $facilities = $kemitraan->facilities->pluck('facility_name')->toArray();
        $facilityNames = !empty($facilities)
            ? implode(', ', $facilities)
            : ($kemitraan->other_pasker_facility ?? '-');

        $location = trim($roomNames . ($facilityNames !== '-' ? ' - ' . $facilityNames : ''), ' -');

        $timeInfo = '';
        if ($hasTimeRange) {
            $timeStart = $bookedDate->booked_time_start ? substr($bookedDate->booked_time_start, 0, 5) : ($kemitraan->scheduletimestart ?? '');
            $timeFinish = $bookedDate->booked_time_finish ? substr($bookedDate->booked_time_finish, 0, 5) : ($kemitraan->scheduletimefinish ?? '');
            if ($timeStart && $timeFinish) $timeInfo = $timeStart . ' - ' . $timeFinish;
            elseif ($timeStart) $timeInfo = $timeStart;
        } else {
            $timeStart = $bookedDate->booked_time ? substr($bookedDate->booked_time, 0, 5) : ($kemitraan->scheduletimestart ?? '');
            $timeFinish = $kemitraan->scheduletimefinish ?? '';
            if ($timeStart && $timeFinish) $timeInfo = $timeStart . ' - ' . $timeFinish;
            elseif ($timeStart) $timeInfo = $timeStart;
        }

        return (object) [
            'id' => (int) $bookedDate->id,
            'title' => $partnershipTypeName . ($kemitraan->institution_name ? ' - ' . $kemitraan->institution_name : ''),
            'description' => ($kemitraan->institution_name ?? '')
                . ($timeInfo ? ' (' . $timeInfo . ')' : '')
                . ($location && $location !== '-' ? ' - Lokasi: ' . $location : ''),
            'date' => $dateStr,
            'location' => $location ?: '-',
            'organizer' => $kemitraan->institution_name ?? '-',
            'registration_url' => null,
        ];
    }
} 