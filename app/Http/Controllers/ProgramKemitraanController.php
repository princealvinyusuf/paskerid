<?php

namespace App\Http\Controllers;

use App\Models\ProgramKemitraanSubmission;
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

    /**
     * @return array<int, string>
     */
    private function businessSectors(): array
    {
        return [
            'Pertanian, kehutanan, dan Perikanan',
            'Pertambangan dan Penggalian',
            'Industri Pengolahan',
            'Pengadaan Listrik, gas, Uap/Air Panas dan Udara Dingin',
            'Treatment Air, Treatment Air Limbah, Treatment dan Pemulihan Material Sampah, dan Aktivitas Remediasi',
            'Konstruksi',
            'Perdagangan Besar dan Eceran; Reparasi dan Perawatan Mobil dan Sepeda Motor',
            'Pengangkutan dan Pergudangan',
            'Penyediaan Akomodasi dan Penyediaan Makan Minum',
            'Informasi dan Komunikasi',
            'Aktivitas Keuangan dan Asuransi',
            'Real Estat',
            'Aktivitas Profesional, Ilmiah, dan Teknis',
            'Aktivitas Penyewaan dan Sewa Guna Usaha Tanpa Hak Opsi, Ketenagakerjaan, Agen Perjalanan dan Penunjang Usaha Lainnya',
            'Administrasi Pemerintahan, Pertahanan, dan Jaminan Sosial Wajib',
            'Pendidikan',
            'Aktivitas Kesehatan Manusia dan Aktivitas Sosial',
            'Kesenian, Hiburan, dan Rekreasi',
            'Aktivitas Jasa Lainnya',
            'Aktivitas Rumah Tangga sebagai Pemberi kerja; Aktivitas yang Menghasilkan Barang dan Jasa oleh Rumah Tangga yang digunakan untuk Memenuhi Kebutuhan Sendiri',
            'Aktivitas Badan Internasional dan Badan Ekstra Internasional Lainnya',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function mitraPembangunanTypes(): array
    {
        return [
            'Perusahaan',
            'Asosiasi/Komunitas',
            'Lembaga Non-Pemerintah',
            'Lembaga Pendidikan',
            'Organisasi Masyarakat',
            'Persekutuan',
            'Lainnya',
        ];
    }

    public function create()
    {
        $businessSectors = $this->businessSectors();

        return view('program-kemitraan.create', [
            'institutionCategories' => $this->institutionCategories(),
            'mitraPembangunanTypes' => $this->mitraPembangunanTypes(),
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
            'mitra_pembangunan_type' => ['nullable', 'string', Rule::in($this->mitraPembangunanTypes()), 'required_if:institution_category,' . $mitraCategory],
            'instansi_lembaga_name' => ['required', 'string', 'max:255'],
            'institution_name' => ['required', 'string', 'max:255'],
            'business_sector' => ['nullable', 'string', 'max:255', 'required_if:institution_category,' . $mitraCategory],
            'institution_address' => ['required', 'string', 'max:2000'],
            'proposed_activity_type' => ['required', Rule::in($this->activityTypes())],
            'request_letter' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $validated['request_letter'] = $request->file('request_letter')->store('program_kemitraan_letters', 'public');
        $validated['status'] = 'pending';
        if (($validated['institution_category'] ?? '') !== $mitraCategory) {
            $validated['mitra_pembangunan_type'] = null;
        }

        ProgramKemitraanSubmission::create($validated);

        return redirect()
            ->route('program-kemitraan.create')
            ->with('success', 'Pengajuan Program Kemitraan berhasil dikirim. Tim kami akan segera meninjau pengajuan Anda.');
    }
}
