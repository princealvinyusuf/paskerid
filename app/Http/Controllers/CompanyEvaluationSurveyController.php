<?php

namespace App\Http\Controllers;

use App\Models\CompanyEvaluationSurvey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompanyEvaluationSurveyController extends Controller
{
    public const PROVINCES = [
        'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi',
        'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kep. Riau',
        'Kep. Bangka Belitung', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah',
        'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali', 'Nusa Tenggara Barat',
        'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah',
        'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
        'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan',
        'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat', 'Maluku',
        'Maluku Utara', 'Papua Barat', 'Papua', 'Papua Barat Daya',
        'Papua Pegunungan', 'Papua Selatan', 'Papua Tengah',
    ];

    public const RATING_FIELDS = [
        'service_requirements_ease',
        'system_procedure_ease',
        'service_speed',
        'fee_compliance',
        'product_quality',
        'officer_competence',
        'officer_behavior',
        'facility_quality',
        'complaint_media_completeness',
        'karirhub_procedure_ease',
        'procedure_information_fit',
        'admin_service_hours_fit',
        'candidate_fit',
    ];

    public function index(): View
    {
        return view('company_evaluation_survey.index', [
            'provinces' => self::PROVINCES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'company_name' => ['required', 'string', 'max:255'],
            'respondent_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'in:Human Resources (HR),Management Level,Direktur,Lainnya'],
            'position_other' => ['nullable', 'required_if:position,Lainnya', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9]+$/', 'max:30'],
            'province' => ['required', 'in:' . implode(',', self::PROVINCES)],
            'input_assistance' => ['required', Rule::in(['Ya, dibantu', 'Tidak, secara mandiri'])],
            'obtained_worker' => ['required', 'in:Ya,Tidak'],
            'waiting_time' => ['nullable', 'required_if:obtained_worker,Ya', 'in:Kurang dari 1 bulan,Antara 1-2 bulan,Antara 2-3 bulan,Lebih dari 3 bulan'],
            'information_sources' => ['required', 'array', 'min:1'],
            'information_sources.*' => ['string', 'in:Sosial media,Dinas Ketenagakerjaan,Teman/kolega,Lainnya'],
            'information_source_other' => [
                'nullable',
                Rule::requiredIf(fn () => in_array('Lainnya', $request->input('information_sources', []), true)),
                'string',
                'max:255',
            ],
        ];

        foreach (self::RATING_FIELDS as $field) {
            $rules[$field] = ['required', 'integer', 'between:1,4'];
        }
        $rules['fee_compliance'] = ['required', 'integer', 'between:1,2'];

        $validated = $request->validate($rules);
        $validated['information_sources'] = array_values(array_unique($validated['information_sources']));

        if ($validated['position'] !== 'Lainnya') {
            $validated['position_other'] = null;
        }
        if ($validated['obtained_worker'] !== 'Ya') {
            $validated['waiting_time'] = null;
        }
        if (!in_array('Lainnya', $validated['information_sources'], true)) {
            $validated['information_source_other'] = null;
        }

        CompanyEvaluationSurvey::create($validated);

        return redirect()
            ->route('company-evaluation-survey.index')
            ->with('success', 'Terima kasih! Survei evaluasi perusahaan berhasil dikirim.');
    }
}
