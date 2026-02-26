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
        $walkinStats = $this->buildWalkinStatistics();
        $surveyPasscodeEnabled = $this->isSurveyPasscodeEnabled();
        $formPasscodeEnabled = $this->isFormPasscodeEnabled();
        $surveyCompanies = collect();
        
        // Fetch unique institution names from kemitraan for autocomplete
        $availableInstitutions = [];
        if (Schema::hasTable('kemitraan')) {
            $availableInstitutions = DB::table('kemitraan')
                ->whereNotNull('institution_name')
                ->where('institution_name', '!=', '')
                ->distinct()
                ->orderBy('institution_name')
                ->pluck('institution_name')
                ->toArray();
        }
        $hasSurveyInitiatorTable = Schema::hasTable('walk_in_survey_initiators');
        $hasInitiatorColumnOnCompany = Schema::hasTable('company_walk_in_survey') && Schema::hasColumn('company_walk_in_survey', 'walk_in_initiator_id');
        if (Schema::hasTable('company_walk_in_survey')) {
            $query = WalkInSurveyCompany::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('company_name');

            if ($hasSurveyInitiatorTable && $hasInitiatorColumnOnCompany) {
                $query->with(['initiator:id,initiator_name']);
            }

            $columns = ['id', 'company_name'];
            if ($hasInitiatorColumnOnCompany) {
                $columns[] = 'walk_in_initiator_id';
            }
            $surveyCompanies = $query->get($columns);
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
            'surveyCompanies',
            'walkinStats',
            'surveyPasscodeEnabled',
            'formPasscodeEnabled',
            'availableInstitutions'
        ));
    }

    public function verifySurveyPasscode(Request $request)
    {
        $validated = $request->validate([
            'passcode' => 'required|string|max:255',
        ]);

        $settings = $this->getSurveyAccessSettingsRow();
        if (!$settings || (int) ($settings->is_enabled ?? 0) !== 1) {
            return response()->json(['ok' => true, 'enabled' => false]);
        }

        $input = (string) $validated['passcode'];
        $hash = (string) ($settings->passcode_hash ?? '');

        if ($hash !== '' && password_verify($input, $hash)) {
            return response()->json(['ok' => true, 'enabled' => true]);
        }

        return response()->json([
            'ok' => false,
            'enabled' => true,
            'message' => 'Passcode Survei Evaluasi tidak valid.',
        ], 422);
    }

    public function verifyFormPasscode(Request $request)
    {
        $validated = $request->validate([
            'passcode' => 'required|string|max:255',
        ]);

        $settings = $this->getFormAccessSettingsRow();
        if (!$settings || (int) ($settings->is_enabled ?? 0) !== 1) {
            return response()->json(['ok' => true, 'enabled' => false]);
        }

        $input = (string) $validated['passcode'];
        $hash = (string) ($settings->passcode_hash ?? '');

        if ($hash !== '' && password_verify($input, $hash)) {
            return response()->json(['ok' => true, 'enabled' => true]);
        }

        return response()->json([
            'ok' => false,
            'enabled' => true,
            'message' => 'Passcode Form Pendaftaran tidak valid.',
        ], 422);
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

        return redirect()->route('kemitraan.create')->with('success', 'Pendaftaran kemitraan Anda telah berhasil dikirim. Tim kami akan segera meninjau pengajuan Anda.');
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
        if (
            !Schema::hasTable('company_walk_in_survey') ||
            !Schema::hasTable('walk_in_survey_responses') ||
            !Schema::hasTable('walk_in_survey_initiators') ||
            !Schema::hasColumn('company_walk_in_survey', 'walk_in_initiator_id') ||
            !Schema::hasColumn('walk_in_survey_responses', 'walk_in_initiator_id') ||
            !Schema::hasColumn('walk_in_survey_responses', 'walkin_initiator_snapshot')
        ) {
            return redirect()
                ->route('kemitraan.create', ['panel' => 'survey'])
                ->withErrors(['survey' => 'Fitur survei belum aktif di database. Jalankan migrasi terlebih dahulu.'], 'survey')
                ->withInput();
        }

        $validated = $request->validateWithBag('survey', [
            'survey_applied_company' => 'required|integer|exists:company_walk_in_survey,id',
            'survey_walkin_initiator_id' => 'required|integer|exists:walk_in_survey_initiators,id',
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

        $company = WalkInSurveyCompany::query()
            ->with(['initiator:id,initiator_name'])
            ->findOrFail((int) $validated['survey_applied_company']);
        $initiator = $company->initiator;

        if (!$initiator || (int) $initiator->id !== (int) $validated['survey_walkin_initiator_id']) {
            return redirect()
                ->route('kemitraan.create', ['panel' => 'survey'])
                ->withErrors(['survey_walkin_initiator_id' => 'Walk In Initiator tidak valid untuk perusahaan yang dipilih.'], 'survey')
                ->withInput();
        }
        $today = Carbon::today()->toDateString();
        $hasWalkinDateColumn = Schema::hasColumn('walk_in_survey_responses', 'walkin_date');

        $payload = [
            'company_walk_in_survey_id' => (int) $company->id,
            'walk_in_initiator_id' => (int) $initiator->id,
            'company_name_snapshot' => (string) $company->company_name,
            'walkin_initiator_snapshot' => (string) $initiator->initiator_name,
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
        static $hasInformasiLainnyaColumn = null;
        if ($hasInformasiLainnyaColumn === null) {
            $hasInformasiLainnyaColumn = Schema::hasColumn('booked_date', 'informasi_lainnya');
        }

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

        $informasiLainnyaItems = $hasInformasiLainnyaColumn
            ? $this->parseInformasiLainnyaItems($bookedDate->informasi_lainnya)
            : [];

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
            'informasi_lainnya' => !empty($informasiLainnyaItems) ? $informasiLainnyaItems[0] : null,
            'informasi_lainnya_items' => $informasiLainnyaItems,
        ];
    }

    private function parseInformasiLainnyaItems($raw): array
    {
        $value = trim((string) ($raw ?? ''));
        if ($value === '') {
            return [];
        }

        $items = [];
        if (str_starts_with($value, '[')) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                foreach ($decoded as $v) {
                    $s = trim((string) $v);
                    if ($s !== '') {
                        $items[] = $s;
                    }
                }
            }
        }

        if (empty($items)) {
            $items[] = $value;
        }

        return array_values(array_unique($items));
    }

    private function buildWalkinStatistics(): array
    {
        $empty = [
            'ready' => false,
            'summary' => [
                'total_responses' => 0,
                'avg_rating' => 0,
                'responses_today' => 0,
                'responses_month' => 0,
                'active_companies' => 0,
                'active_initiators' => 0,
                'total_walkin_year' => 0,
            ],
            'trend' => [],
            'rating_distribution' => [],
            'gender_distribution' => [],
            'age_distribution' => [],
            'education_distribution' => [],
            'domisili_distribution' => [],
            'top_companies' => [],
            'top_initiators' => [],
            'info_sources' => [],
            'job_portals' => [],
        ];

        if (!Schema::hasTable('walk_in_survey_responses')) {
            return $empty;
        }

        $hasWalkinDate = Schema::hasColumn('walk_in_survey_responses', 'walkin_date');
        $dateExpr = $hasWalkinDate ? 'COALESCE(walkin_date, DATE(created_at))' : 'DATE(created_at)';

        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();

        $totalResponses = (int) DB::table('walk_in_survey_responses')->count();
        $avgRatingRaw = DB::table('walk_in_survey_responses')->avg('rating_satisfaction');
        $responsesToday = (int) DB::table('walk_in_survey_responses')->whereDate(DB::raw($dateExpr), $today->toDateString())->count();
        $responsesMonth = (int) DB::table('walk_in_survey_responses')->whereDate(DB::raw($dateExpr), '>=', $monthStart->toDateString())->count();

        $activeCompanies = 0;
        if (Schema::hasTable('company_walk_in_survey')) {
            $activeCompanies = (int) DB::table('company_walk_in_survey')
                ->where('is_active', 1)
                ->count();
        }

        $activeInitiators = 0;
        if (Schema::hasTable('walk_in_survey_initiators')) {
            $activeInitiators = (int) DB::table('walk_in_survey_initiators')
                ->where('is_active', 1)
                ->count();
        }

        $totalWalkinYear = 0;
        if (Schema::hasTable('booked_date') && Schema::hasTable('kemitraan') && Schema::hasTable('type_of_partnership')) {
            $yearStart = $today->copy()->startOfYear();
            $yearEnd = $today->copy()->endOfYear();
            $hasBookedDateStart = Schema::hasColumn('booked_date', 'booked_date_start');
            $hasBookedDate = Schema::hasColumn('booked_date', 'booked_date');
            
            $query = DB::table('booked_date as bd')
                ->join('kemitraan as k', 'bd.kemitraan_id', '=', 'k.id')
                ->leftJoin('type_of_partnership as top', 'k.type_of_partnership_id', '=', 'top.id')
                ->where(function($q) use ($yearStart, $yearEnd, $hasBookedDateStart, $hasBookedDate) {
                    // After migration, booked_date was renamed to booked_date_start
                    // So we check for booked_date_start first, then fallback to booked_date if it still exists
                    if ($hasBookedDateStart) {
                        $q->whereBetween('bd.booked_date_start', [$yearStart->toDateString(), $yearEnd->toDateString()]);
                    } elseif ($hasBookedDate) {
                        // Legacy: if booked_date_start doesn't exist but booked_date does
                        $q->whereBetween('bd.booked_date', [$yearStart->toDateString(), $yearEnd->toDateString()]);
                    }
                })
                ->where(function($q) {
                    // Filter for walk-in related entries (matching buildWalkinAgendaFromBookedDate logic)
                    $q->whereRaw("LOWER(COALESCE(top.name, '')) LIKE '%walk%'")
                      ->orWhereRaw("LOWER(COALESCE(top.name, '')) LIKE '%interview%'")
                      ->orWhereRaw("LOWER(COALESCE(top.name, '')) LIKE '%wawancara%'");
                });
            
            $totalWalkinYear = (int) $query->count();
        }

        $trendRows = DB::table('walk_in_survey_responses')
            ->selectRaw("DATE({$dateExpr}) AS d, COUNT(*) AS total, ROUND(AVG(rating_satisfaction), 2) AS avg_rating")
            ->whereDate(DB::raw($dateExpr), '>=', $today->copy()->subDays(89)->toDateString())
            ->groupBy('d')
            ->orderBy('d', 'asc')
            ->get();
        $trendMap = [];
        foreach ($trendRows as $row) {
            $trendMap[(string) $row->d] = [
                'date' => (string) $row->d,
                'total' => (int) ($row->total ?? 0),
                'avg_rating' => $row->avg_rating !== null ? (float) $row->avg_rating : null,
            ];
        }
        $trend = [];
        for ($i = 89; $i >= 0; $i--) {
            $d = $today->copy()->subDays($i)->toDateString();
            $trend[] = $trendMap[$d] ?? ['date' => $d, 'total' => 0, 'avg_rating' => null];
        }

        $ratingDistribution = [];
        $ratingRows = DB::table('walk_in_survey_responses')
            ->selectRaw('rating_satisfaction AS label, COUNT(*) AS total')
            ->groupBy('rating_satisfaction')
            ->orderBy('rating_satisfaction', 'asc')
            ->get();
        foreach ($ratingRows as $row) {
            $ratingDistribution[] = [
                'label' => (string) ($row->label ?? '-'),
                'total' => (int) ($row->total ?? 0),
            ];
        }

        $genderDistribution = $this->buildDistribution('walk_in_survey_responses', 'gender');
        $ageDistribution = $this->buildDistribution('walk_in_survey_responses', 'age_range');
        $educationDistribution = $this->buildDistribution('walk_in_survey_responses', 'education');
        $domisiliDistribution = $this->buildDistribution('walk_in_survey_responses', 'domisili');

        $topCompanies = [];
        if (Schema::hasTable('company_walk_in_survey')) {
            $companyRows = DB::table('company_walk_in_survey as c')
                ->leftJoin('walk_in_survey_responses as r', 'r.company_walk_in_survey_id', '=', 'c.id')
                ->selectRaw('c.company_name, COUNT(r.id) AS peserta_hadir, ROUND(AVG(r.rating_satisfaction), 2) AS avg_rating')
                ->groupBy('c.id', 'c.company_name')
                ->orderByDesc('peserta_hadir')
                ->orderBy('c.company_name')
                ->limit(30)
                ->get();
            foreach ($companyRows as $row) {
                $topCompanies[] = [
                    'name' => trim((string) ($row->company_name ?? '-')),
                    'peserta_hadir' => (int) ($row->peserta_hadir ?? 0),
                    'avg_rating' => $row->avg_rating !== null ? (float) $row->avg_rating : null,
                ];
            }
        }

        $topInitiators = [];
        if (
            Schema::hasTable('walk_in_survey_initiators') &&
            Schema::hasTable('company_walk_in_survey') &&
            Schema::hasColumn('company_walk_in_survey', 'walk_in_initiator_id')
        ) {
            $initiatorRows = DB::table('walk_in_survey_initiators as i')
                ->leftJoin('company_walk_in_survey as c', 'c.walk_in_initiator_id', '=', 'i.id')
                ->leftJoin('walk_in_survey_responses as r', 'r.company_walk_in_survey_id', '=', 'c.id')
                ->selectRaw('i.initiator_name, COUNT(DISTINCT c.id) AS company_count, COUNT(r.id) AS peserta_hadir, ROUND(AVG(r.rating_satisfaction), 2) AS avg_rating')
                ->groupBy('i.id', 'i.initiator_name')
                ->orderByDesc('peserta_hadir')
                ->orderBy('i.initiator_name')
                ->limit(30)
                ->get();
            foreach ($initiatorRows as $row) {
                $topInitiators[] = [
                    'name' => trim((string) ($row->initiator_name ?? '-')),
                    'company_count' => (int) ($row->company_count ?? 0),
                    'peserta_hadir' => (int) ($row->peserta_hadir ?? 0),
                    'avg_rating' => $row->avg_rating !== null ? (float) $row->avg_rating : null,
                ];
            }
        }

        $infoSourceCounts = [];
        $jobPortalCounts = [];
        $sourceRows = DB::table('walk_in_survey_responses')->select('info_sources', 'job_portals')->get();
        foreach ($sourceRows as $row) {
            foreach ((array) json_decode((string) ($row->info_sources ?? '[]'), true) as $value) {
                $k = trim((string) $value);
                if ($k !== '') $infoSourceCounts[$k] = ($infoSourceCounts[$k] ?? 0) + 1;
            }
            foreach ((array) json_decode((string) ($row->job_portals ?? '[]'), true) as $value) {
                $k = trim((string) $value);
                if ($k !== '') $jobPortalCounts[$k] = ($jobPortalCounts[$k] ?? 0) + 1;
            }
        }
        arsort($infoSourceCounts);
        arsort($jobPortalCounts);
        $infoSources = [];
        foreach (array_slice($infoSourceCounts, 0, 20, true) as $label => $total) {
            $infoSources[] = ['label' => $label, 'total' => (int) $total];
        }
        $jobPortals = [];
        foreach (array_slice($jobPortalCounts, 0, 20, true) as $label => $total) {
            $jobPortals[] = ['label' => $label, 'total' => (int) $total];
        }

        return [
            'ready' => true,
            'summary' => [
                'total_responses' => $totalResponses,
                'avg_rating' => $avgRatingRaw !== null ? round((float) $avgRatingRaw, 2) : 0,
                'responses_today' => $responsesToday,
                'responses_month' => $responsesMonth,
                'active_companies' => $activeCompanies,
                'active_initiators' => $activeInitiators,
                'total_walkin_year' => $totalWalkinYear,
            ],
            'trend' => $trend,
            'rating_distribution' => $ratingDistribution,
            'gender_distribution' => $genderDistribution,
            'age_distribution' => $ageDistribution,
            'education_distribution' => $educationDistribution,
            'domisili_distribution' => $domisiliDistribution,
            'top_companies' => $topCompanies,
            'top_initiators' => $topInitiators,
            'info_sources' => $infoSources,
            'job_portals' => $jobPortals,
        ];
    }

    private function buildDistribution(string $table, string $column): array
    {
        if (!Schema::hasColumn($table, $column)) {
            return [];
        }

        $safeColumn = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
        if ($safeColumn === '') {
            return [];
        }

        $rows = DB::table($table)
            ->selectRaw("TRIM(COALESCE({$safeColumn}, '')) AS label, COUNT(*) AS total")
            ->whereNotNull($safeColumn)
            ->whereRaw("TRIM(COALESCE({$safeColumn}, '')) <> ''")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $out[] = [
                'label' => (string) ($row->label ?? '-'),
                'total' => (int) ($row->total ?? 0),
            ];
        }
        return $out;
    }

    private function isSurveyPasscodeEnabled(): bool
    {
        $settings = $this->getSurveyAccessSettingsRow();
        return $settings && (int) ($settings->is_enabled ?? 0) === 1;
    }

    private function getSurveyAccessSettingsRow(): ?object
    {
        if (!Schema::hasTable('walkin_survey_access_settings')) {
            return null;
        }

        return DB::table('walkin_survey_access_settings')
            ->where('id', 1)
            ->first(['id', 'is_enabled', 'passcode_hash']);
    }

    private function isFormPasscodeEnabled(): bool
    {
        $settings = $this->getFormAccessSettingsRow();
        return $settings && (int) ($settings->is_enabled ?? 0) === 1;
    }

    private function getFormAccessSettingsRow(): ?object
    {
        if (!Schema::hasTable('walkin_form_access_settings')) {
            return null;
        }

        return DB::table('walkin_form_access_settings')
            ->where('id', 1)
            ->first(['id', 'is_enabled', 'passcode_hash']);
    }
} 