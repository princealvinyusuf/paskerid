<?php

namespace App\Http\Controllers;

use App\Models\ProgramKemitraanSubmission;
use App\Models\companysector;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramKemitraanController extends Controller
{
    /**
     * @return array<int, string>
     */
    private function institutionCategories(): array
    {
        return [
            'Kementerian/Lembaga',
            'Pemerintah Daerah (Kabupaten/Kota)',
            'Mitra Pembangunan (Perusahaan/Swasta/Job Portal)',
            'Lembaga Pendidikan',
            'Lembaga Non-Pemerintah (Yayasan/Asosiasi/Organisasi)',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function activityTypes(): array
    {
        return [
            'Walk-in Interview',
            'Edukasi Pasar Kerja (Seminar/Webinar/Workshop)',
            'Talenta Muda (Talent Class/Talent Talks/...)',
            'Job Fair (Virtual/Hybrid/Offline)',
            'Data Pasar Kerja',
            'Pengembangan SDM (Online/Offline)',
            'Pendampingan Pemanfaatan Karirhub (Online/Offline)',
            'Layanan Mobil Bursa Kerja (DKI Jakarta/Banten/Jawa Barat/Jawa Tengah)',
            'Audiensi/Konsultasi',
        ];
    }

    public function create()
    {
        $businessSectors = companysector::query()
            ->orderBy('sector_name')
            ->pluck('sector_name')
            ->toArray();

        return view('program-kemitraan.create', [
            'institutionCategories' => $this->institutionCategories(),
            'activityTypes' => $this->activityTypes(),
            'businessSectors' => $businessSectors,
        ]);
    }

    public function store(Request $request)
    {
        $mitraCategory = 'Mitra Pembangunan (Perusahaan/Swasta/Job Portal)';

        $validated = $request->validate([
            'pic_name' => ['required', 'string', 'max:255'],
            'pic_position' => ['required', 'string', 'max:255'],
            'pic_email' => ['required', 'email', 'max:255'],
            'pic_whatsapp' => ['required', 'string', 'max:30'],
            'institution_category' => ['required', Rule::in($this->institutionCategories())],
            'instansi_lembaga_name' => ['required', 'string', 'max:255'],
            'institution_name' => ['required', 'string', 'max:255'],
            'business_sector' => ['nullable', 'string', 'max:255', 'required_if:institution_category,' . $mitraCategory],
            'institution_address' => ['required', 'string', 'max:2000'],
            'proposed_activity_type' => ['required', Rule::in($this->activityTypes())],
            'request_letter' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $validated['request_letter'] = $request->file('request_letter')->store('program_kemitraan_letters', 'public');
        $validated['status'] = 'pending';

        ProgramKemitraanSubmission::create($validated);

        return redirect()
            ->route('program-kemitraan.create')
            ->with('success', 'Pengajuan Program Kemitraan berhasil dikirim. Tim kami akan segera meninjau pengajuan Anda.');
    }
}
