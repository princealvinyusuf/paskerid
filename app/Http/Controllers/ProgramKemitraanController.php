<?php

namespace App\Http\Controllers;

use App\Models\ProgramKemitraanEvaluation;
use App\Models\ProgramKemitraanEvaluationActivity;
use App\Models\ProgramKemitraanSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramKemitraanController extends Controller
{
    private const TAB_PENDAFTARAN = 'pendaftaran';
    private const TAB_EVALUASI = 'evaluasi';
    private const TAB_HASIL_EVALUASI = 'hasil-evaluasi';
    private const SCORE_OPTIONS = ['1', '2', '3', '4', '5'];

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

    /**
     * @return array<string, mixed>
     */
    private function evaluasiFormASections(): array
    {
        return [
            'a_relevansi_tujuan' => [
                'title' => 'B. Relevansi dan Kejelasan Tujuan Kegiatan',
                'items' => [
                    'Tujuan kegiatan disampaikan dengan jelas sejak awal.',
                    'Tema kegiatan sesuai dengan kebutuhan peserta/instansi.',
                    'Susunan kegiatan mendukung pencapaian tujuan yang telah ditetapkan.',
                    'Kegiatan relevan dengan penguatan layanan informasi pasar kerja.',
                    'Kegiatan memberikan gambaran yang jelas mengenai peran Pusat Pasar Kerja.',
                    'Sasaran peserta telah sesuai dengan materi dan tujuan kegiatan.',
                    'Durasi kegiatan memadai untuk membahas substansi utama.',
                    'Kegiatan menjawab permasalahan atau kebutuhan yang dihadapi peserta/mitra.',
                ],
            ],
            'a_kualitas_materi' => [
                'title' => 'C. Kualitas Materi dan Edukasi',
                'items' => [
                    'Materi disusun secara sistematis dan mudah diikuti.',
                    'Informasi yang disampaikan akurat, relevan, dan mutakhir.',
                    'Istilah teknis dijelaskan dengan bahasa yang mudah dipahami.',
                    'Contoh, studi kasus, atau simulasi membantu pemahaman peserta.',
                    'Materi memberikan pengetahuan baru yang dapat diterapkan.',
                    'Materi memperkuat pemahaman mengenai ekosistem layanan SIAPkerja/Karirhub.',
                    'Materi memberikan pemahaman mengenai informasi pasar kerja dan kebutuhan dunia kerja.',
                    'Materi menjelaskan hak, kewajiban, atau peran para pihak secara proporsional.',
                    'Materi mendukung literasi digital dan penggunaan layanan ketenagakerjaan.',
                    'Bahan tayang atau bahan pendukung dapat dibaca dengan jelas.',
                    'Materi memperhatikan kebutuhan peserta dengan latar belakang yang beragam.',
                    'Peserta memperoleh kesempatan yang cukup untuk bertanya atau berdiskusi.',
                ],
            ],
            'a_narasumber' => [
                'title' => 'D. Narasumber/Fasilitator',
                'items' => [
                    'Narasumber menguasai substansi yang disampaikan.',
                    'Penyampaian narasumber jelas, runtut, dan komunikatif.',
                    'Narasumber mampu menyesuaikan bahasa dengan karakteristik peserta.',
                    'Narasumber memberikan jawaban yang relevan atas pertanyaan peserta.',
                    'Narasumber mendorong partisipasi aktif dan diskusi yang sehat.',
                    'Narasumber menjaga sikap profesional, inklusif, dan menghargai peserta.',
                    'Fasilitator/moderator mengelola alur kegiatan secara efektif.',
                    'Waktu penyampaian dan diskusi dikelola secara proporsional.',
                ],
            ],
            'a_layanan_peserta' => [
                'title' => 'E. Penyelenggaraan dan Layanan Peserta',
                'items' => [
                    'Informasi undangan, jadwal, dan mekanisme keikutsertaan disampaikan dengan jelas.',
                    'Proses registrasi atau konfirmasi kehadiran mudah dilakukan.',
                    'Panitia memberikan layanan yang ramah, tanggap, dan profesional.',
                    'Kegiatan dimulai dan diakhiri sesuai jadwal atau dengan penjelasan yang memadai.',
                    'Tempat atau media daring mendukung kenyamanan dan kelancaran peserta.',
                    'Peralatan, jaringan, suara, dan tampilan materi berfungsi dengan baik.',
                    'Kebutuhan aksesibilitas peserta diperhatikan secara memadai.',
                    'Dokumentasi, bahan kegiatan, atau informasi lanjutan tersedia dengan baik.',
                ],
            ],
            'a_promosi_komunikasi' => [
                'title' => 'F. Efektivitas Promosi dan Komunikasi Publik',
                'items' => [
                    'Informasi kegiatan mudah ditemukan dan dipahami.',
                    'Pesan utama promosi konsisten dengan isi kegiatan.',
                    'Media publikasi yang digunakan sesuai dengan sasaran audiens.',
                    'Desain atau tampilan bahan promosi menarik dan profesional.',
                    'Informasi mengenai layanan Pusat Pasar Kerja disampaikan secara jelas.',
                    'Tautan, kode QR, kontak, atau kanal informasi lanjutan berfungsi dengan baik.',
                    'Promosi kegiatan mendorong minat untuk menggunakan atau menyebarluaskan layanan.',
                    'Komunikasi publik memperkuat citra layanan pemerintah yang tepercaya dan mudah diakses.',
                ],
            ],
            'a_manfaat_dampak' => [
                'title' => 'G. Manfaat, Dampak, dan Potensi Kemitraan',
                'items' => [
                    'Kegiatan meningkatkan pemahaman saya terhadap layanan Pusat Pasar Kerja.',
                    'Kegiatan meningkatkan kemampuan saya untuk memanfaatkan layanan yang diperkenalkan.',
                    'Kegiatan membuka peluang kolaborasi atau sinergi antarinstansi/organisasi.',
                    'Kegiatan menghasilkan informasi, jejaring, atau kontak yang relevan untuk tindak lanjut.',
                    'Kegiatan mendorong komitmen untuk berbagi informasi pasar kerja secara lebih aktif.',
                    'Kegiatan mendorong partisipasi dalam penyebarluasan informasi kesempatan kerja.',
                    'Kegiatan berpotensi memberikan manfaat bagi pencari kerja dan pemberi kerja.',
                    'Kegiatan layak dilaksanakan kembali atau dikembangkan dalam bentuk lanjutan.',
                ],
            ],
            'a_penilaian_keseluruhan' => [
                'title' => 'H. Penilaian Keseluruhan',
                'items' => [
                    'Secara keseluruhan, kegiatan diselenggarakan dengan baik.',
                    'Kegiatan memberikan manfaat yang sebanding dengan waktu yang saya luangkan.',
                    'Saya bersedia merekomendasikan kegiatan sejenis kepada pihak lain.',
                    'Saya berminat mengikuti kegiatan lanjutan dari Pusat Pasar Kerja.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function evaluasiFormBSections(): array
    {
        return [
            'b_perencanaan' => [
                'title' => 'B. Perencanaan dan Kesiapan Kegiatan',
                'items' => [
                    'Tujuan, keluaran, dan indikator keberhasilan kegiatan ditetapkan secara jelas.',
                    'Kerangka acuan kerja, jadwal, dan pembagian tugas tersedia sebelum pelaksanaan.',
                    'Sasaran peserta/mitra dipetakan sesuai tujuan kegiatan.',
                    'Koordinasi internal dan eksternal dilakukan tepat waktu.',
                    'Narasumber/fasilitator dipilih sesuai kompetensi dan kebutuhan materi.',
                    'Materi, bahan promosi, dan bahan pendukung melalui proses reviu yang memadai.',
                    'Daftar risiko, rencana mitigasi, dan penanggung jawab telah ditetapkan.',
                    'Kebutuhan anggaran, sarana, dan dukungan teknis dipersiapkan secara memadai.',
                    'Kanal pendaftaran, konfirmasi, dan layanan informasi peserta telah siap.',
                    'Aspek aksesibilitas, inklusivitas, dan keamanan peserta telah dipertimbangkan.',
                ],
            ],
            'b_pelaksanaan' => [
                'title' => 'C. Pelaksanaan dan Pengendalian Kegiatan',
                'items' => [
                    'Registrasi, penerimaan, dan pengarahan peserta berjalan tertib.',
                    'Susunan acara terlaksana sesuai jadwal atau perubahan dikelola dengan baik.',
                    'Koordinasi panitia selama kegiatan berlangsung efektif.',
                    'Narasumber, moderator, dan petugas teknis menjalankan peran sesuai penugasan.',
                    'Kehadiran dan partisipasi peserta sesuai target yang ditetapkan.',
                    'Diskusi berlangsung produktif, aman, dan fokus pada tujuan kegiatan.',
                    'Permasalahan teknis atau operasional ditangani secara cepat dan tepat.',
                    'Pelayanan terhadap peserta/mitra diberikan secara profesional dan responsif.',
                    'Kebutuhan peserta disabilitas atau kebutuhan khusus difasilitasi secara layak.',
                    'Dokumentasi foto, video, daftar hadir, notula, dan bahan kegiatan tersedia.',
                    'Perlindungan data pribadi dan penggunaan dokumentasi telah diperhatikan.',
                    'Pengendalian biaya dan penggunaan sumber daya dilakukan secara efisien.',
                ],
            ],
            'b_kinerja_kemitraan' => [
                'title' => 'D. Kinerja Kemitraan',
                'items' => [
                    'Kegiatan menghadirkan mitra yang relevan dan memiliki kewenangan/kapasitas untuk menindaklanjuti.',
                    'Peran, kontribusi, dan kepentingan para pihak teridentifikasi dengan jelas.',
                    'Pembahasan menghasilkan kesepahaman atau arah kerja sama yang konkret.',
                    'Terdapat daftar kontak, narahubung, atau penanggung jawab dari masing-masing pihak.',
                    'Terdapat kesepakatan mengenai tahapan, keluaran, atau jadwal tindak lanjut.',
                    'Potensi risiko, keterbatasan, dan kebutuhan dukungan kemitraan telah dipetakan.',
                ],
            ],
            'b_kinerja_edukasi' => [
                'title' => 'E. Kinerja Edukasi',
                'items' => [
                    'Tujuan pembelajaran atau perubahan pemahaman peserta tercapai.',
                    'Materi sesuai dengan tingkat pengetahuan dan kebutuhan peserta.',
                    'Metode penyampaian mendorong keterlibatan dan pemahaman aktif.',
                    'Pertanyaan, umpan balik, atau hasil diskusi menunjukkan peningkatan pemahaman.',
                    'Tersedia bahan referensi atau kanal pembelajaran lanjutan.',
                    'Terdapat rencana pengukuran penerapan pengetahuan setelah kegiatan.',
                ],
            ],
            'b_kinerja_promosi' => [
                'title' => 'F. Kinerja Promosi',
                'items' => [
                    'Pesan utama promosi konsisten dengan identitas dan mandat Pusat Pasar Kerja.',
                    'Media dan kanal promosi menjangkau sasaran yang ditetapkan.',
                    'Materi publikasi telah melalui pemeriksaan substansi, bahasa, dan visual.',
                    'Kegiatan menghasilkan dokumentasi atau konten yang dapat digunakan kembali.',
                    'Terdapat ajakan bertindak yang jelas, seperti tautan layanan, kontak, atau pendaftaran.',
                    'Efektivitas promosi dapat diukur melalui data jangkauan, interaksi, atau konversi.',
                ],
            ],
            'b_keluaran_dampak' => [
                'title' => 'G. Keluaran, Dampak, dan Keberlanjutan',
                'items' => [
                    'Keluaran kegiatan tersedia dan sesuai dengan rencana (notula, materi, dokumentasi, daftar kontak, atau dokumen kesepakatan).',
                    'Capaian peserta, jangkauan, dan keterlibatan terdokumentasi secara akurat.',
                    'Umpan balik peserta dikumpulkan dengan jumlah responden yang memadai.',
                    'Hasil kegiatan memberikan nilai tambah bagi layanan Pusat Pasar Kerja.',
                    'Terdapat temuan, rekomendasi, atau keputusan yang dapat ditindaklanjuti.',
                    'Penanggung jawab dan target waktu tindak lanjut telah ditetapkan.',
                    'Hasil kegiatan telah atau akan dikomunikasikan kepada pimpinan dan pihak terkait.',
                    'Pembelajaran kegiatan terdokumentasi untuk penyelenggaraan berikutnya.',
                ],
            ],
            'b_kepatuhan' => [
                'title' => 'H. Kepatuhan dan Tata Kelola',
                'items' => [
                    'Pelaksanaan kegiatan sesuai dengan surat tugas, undangan, atau dasar penugasan yang berlaku.',
                    'Penggunaan identitas visual, logo, dan materi publikasi sesuai ketentuan.',
                    'Pengelolaan data peserta, dokumentasi, dan persetujuan publikasi dilakukan secara patut.',
                    'Pengadaan, pembiayaan, dan pertanggungjawaban administrasi didukung dokumen yang memadai.',
                    'Tidak terdapat konflik kepentingan atau pelanggaran etika selama kegiatan.',
                    'Dokumen dan bukti kegiatan disimpan pada lokasi penyimpanan yang ditetapkan.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function evaluasiQuestionGroups(): array
    {
        return [
            'form_a' => $this->evaluasiFormASections(),
            'form_b' => $this->evaluasiFormBSections(),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiAnswerKeys(array $groups): array
    {
        $keys = [];

        foreach ($groups as $formKey => $sections) {
            foreach ($sections as $sectionKey => $section) {
                $items = $section['items'] ?? [];
                foreach ($items as $itemIndex => $unusedItemText) {
                    $keys[] = $formKey . '.' . $sectionKey . '.' . ($itemIndex + 1);
                }
            }
        }

        return $keys;
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiRespondentCategories(): array
    {
        return [
            'Pencari kerja',
            'Pemberi kerja',
            'Pemerintah pusat/daerah',
            'Lembaga pendidikan',
            'Asosiasi/komunitas',
            'Mitra platform digital/media',
            'Narasumber/fasilitator',
            'Masyarakat umum',
            'Lainnya',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiParticipationModes(): array
    {
        return ['Luring', 'Daring', 'Hibrida'];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiEvaluatorRoles(): array
    {
        return ['Ketua tim', 'Koordinator', 'Panitia', 'Evaluator/pengamat', 'Lainnya'];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiSatisfactionOptions(): array
    {
        return ['Sangat puas', 'Puas', 'Cukup puas', 'Kurang puas', 'Tidak puas'];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiWillingFollowupOptions(): array
    {
        return ['Ya', 'Mungkin', 'Tidak'];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiCommunicationChannels(): array
    {
        return ['Surel', 'WhatsApp', 'Telepon', 'Surat resmi'];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiResultCategories(): array
    {
        return ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiAchievementStatuses(): array
    {
        return ['Melampaui target', 'Mencapai target', 'Belum mencapai target'];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiPriorityOptions(): array
    {
        return [
            'Prioritas 1 - Mendesak dan berdampak besar',
            'Prioritas 2 - Penting dan perlu dijadwalkan',
            'Prioritas 3 - Penyempurnaan bertahap',
            'Dapat dipantau tanpa tindakan segera',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiMonitoringFrequencies(): array
    {
        return ['Mingguan', 'Dua mingguan', 'Bulanan', 'Sesuai tenggat'];
    }

    /**
     * @return array<int, string>
     */
    private function evaluasiMonitoringMediaOptions(): array
    {
        return ['Rapat', 'Lembar kendali', 'Sistem/aplikasi', 'Lainnya'];
    }

    private function resolveTab(?string $tab): string
    {
        return in_array($tab, [self::TAB_PENDAFTARAN, self::TAB_EVALUASI, self::TAB_HASIL_EVALUASI], true)
            ? (string) $tab
            : self::TAB_PENDAFTARAN;
    }

    /**
     * @return array<string, string>
     */
    private function evaluasiSectionTitleMap(): array
    {
        $map = [];
        foreach ($this->evaluasiQuestionGroups() as $sections) {
            foreach ($sections as $sectionKey => $sectionConfig) {
                $map[$sectionKey] = (string) ($sectionConfig['title'] ?? $sectionKey);
            }
        }

        return $map;
    }

    /**
     * @return array<string, mixed>
     */
    private function evaluasiStats(): array
    {
        $sectionTitleMap = $this->evaluasiSectionTitleMap();
        $scoreLabels = ['1', '2', '3', '4', '5'];
        $scoreCountMap = array_fill_keys($scoreLabels, 0);
        $formTypeMap = ['A' => 0, 'B' => 0];

        $totalActivities = ProgramKemitraanEvaluationActivity::query()
            ->where('is_active', true)
            ->count();
        $totalResponses = ProgramKemitraanEvaluation::query()->count();

        $averageScoreValue = DB::table('program_kemitraan_evaluation_answers')
            ->whereNotNull('score')
            ->avg('score');
        $averageScore = $averageScoreValue !== null ? round((float) $averageScoreValue, 2) : null;

        $busiestActivity = ProgramKemitraanEvaluation::query()
            ->selectRaw('activity_master_id, activity_name, COUNT(*) as total_responses')
            ->whereNotNull('activity_master_id')
            ->groupBy('activity_master_id', 'activity_name')
            ->orderByDesc('total_responses')
            ->first();

        $scoreDistributionRows = DB::table('program_kemitraan_evaluation_answers')
            ->selectRaw('CAST(score AS UNSIGNED) as score_value, COUNT(*) as total')
            ->whereNotNull('score')
            ->groupBy('score')
            ->orderBy('score')
            ->get();

        foreach ($scoreDistributionRows as $row) {
            $scoreKey = (string) ((int) ($row->score_value ?? 0));
            if (array_key_exists($scoreKey, $scoreCountMap)) {
                $scoreCountMap[$scoreKey] = (int) ($row->total ?? 0);
            }
        }

        $sectionAverageRows = DB::table('program_kemitraan_evaluation_answers')
            ->selectRaw('section_key, AVG(score) as average_score, COUNT(*) as total_answers')
            ->whereNotNull('section_key')
            ->whereNotNull('score')
            ->groupBy('section_key')
            ->orderByDesc('average_score')
            ->get();

        $sectionLabels = [];
        $sectionAverages = [];
        $sectionAnswerCounts = [];
        foreach ($sectionAverageRows as $row) {
            $sectionKey = (string) ($row->section_key ?? '');
            if ($sectionKey === '') {
                continue;
            }

            $sectionLabels[] = (string) ($sectionTitleMap[$sectionKey] ?? $sectionKey);
            $sectionAverages[] = round((float) ($row->average_score ?? 0), 2);
            $sectionAnswerCounts[] = (int) ($row->total_answers ?? 0);
        }

        // Keep the chart readable by showing top aspects only.
        $maxSectionItems = 8;
        if (count($sectionLabels) > $maxSectionItems) {
            $sectionLabels = array_slice($sectionLabels, 0, $maxSectionItems);
            $sectionAverages = array_slice($sectionAverages, 0, $maxSectionItems);
            $sectionAnswerCounts = array_slice($sectionAnswerCounts, 0, $maxSectionItems);
        }

        $formTypeRows = DB::table('program_kemitraan_evaluation_answers')
            ->selectRaw('form_type, COUNT(DISTINCT evaluation_id) as total')
            ->whereIn('form_type', ['A', 'B'])
            ->whereNotNull('score')
            ->groupBy('form_type')
            ->get();

        foreach ($formTypeRows as $row) {
            $formType = (string) ($row->form_type ?? '');
            if (array_key_exists($formType, $formTypeMap)) {
                $formTypeMap[$formType] = (int) ($row->total ?? 0);
            }
        }

        $participantModeRows = DB::table('program_kemitraan_evaluations as e')
            ->join('program_kemitraan_evaluation_answers as ans', function ($join): void {
                $join->on('ans.evaluation_id', '=', 'e.id')
                    ->where('ans.form_type', '=', 'A');
            })
            ->whereNotNull('e.participation_mode')
            ->where('e.participation_mode', '!=', '')
            ->selectRaw('e.participation_mode as label, COUNT(DISTINCT e.id) as total')
            ->groupBy('e.participation_mode')
            ->orderByDesc('total')
            ->get();

        $participantModeLabels = [];
        $participantModeValues = [];
        foreach ($participantModeRows as $row) {
            $participantModeLabels[] = (string) ($row->label ?? '-');
            $participantModeValues[] = (int) ($row->total ?? 0);
        }

        $satisfactionRows = DB::table('program_kemitraan_evaluations as e')
            ->join('program_kemitraan_evaluation_answers as ans', function ($join): void {
                $join->on('ans.evaluation_id', '=', 'e.id')
                    ->where('ans.form_type', '=', 'A');
            })
            ->whereNotNull('e.overall_satisfaction')
            ->where('e.overall_satisfaction', '!=', '')
            ->selectRaw('e.overall_satisfaction as label, COUNT(DISTINCT e.id) as total')
            ->groupBy('e.overall_satisfaction')
            ->orderByDesc('total')
            ->get();

        $satisfactionLabels = [];
        $satisfactionValues = [];
        foreach ($satisfactionRows as $row) {
            $satisfactionLabels[] = (string) ($row->label ?? '-');
            $satisfactionValues[] = (int) ($row->total ?? 0);
        }

        $organizerRoleRows = DB::table('program_kemitraan_evaluations as e')
            ->join('program_kemitraan_evaluation_answers as ans', function ($join): void {
                $join->on('ans.evaluation_id', '=', 'e.id')
                    ->where('ans.form_type', '=', 'B');
            })
            ->whereNotNull('e.evaluator_role')
            ->where('e.evaluator_role', '!=', '')
            ->where('e.evaluator_role', '!=', '-')
            ->selectRaw('e.evaluator_role as label, COUNT(DISTINCT e.id) as total')
            ->groupBy('e.evaluator_role')
            ->orderByDesc('total')
            ->get();

        $organizerRoleLabels = [];
        $organizerRoleValues = [];
        foreach ($organizerRoleRows as $row) {
            $organizerRoleLabels[] = (string) ($row->label ?? '-');
            $organizerRoleValues[] = (int) ($row->total ?? 0);
        }

        $trendRows = DB::table('program_kemitraan_evaluations')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, COUNT(*) as total")
            ->whereNotNull('created_at')
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get();

        $trendLabels = [];
        $trendValues = [];
        foreach ($trendRows as $row) {
            $monthKey = (string) ($row->month_key ?? '');
            if ($monthKey === '') {
                continue;
            }

            $trendLabels[] = $monthKey;
            $trendValues[] = (int) ($row->total ?? 0);
        }

        $topActivityRows = DB::table('program_kemitraan_evaluations as e')
            ->leftJoin('program_kemitraan_evaluation_answers as ans', function ($join): void {
                $join->on('ans.evaluation_id', '=', 'e.id')
                    ->whereNotNull('ans.score');
            })
            ->whereNotNull('e.activity_master_id')
            ->whereNotNull('e.activity_name')
            ->where('e.activity_name', '!=', '')
            ->selectRaw('
                e.activity_master_id,
                e.activity_name,
                COUNT(DISTINCT e.id) as total_responses,
                ROUND(AVG(ans.score), 2) as average_score,
                MAX(e.updated_at) as last_update
            ')
            ->groupBy('e.activity_master_id', 'e.activity_name')
            ->orderByDesc('total_responses')
            ->orderBy('e.activity_name')
            ->limit(8)
            ->get();

        $topActivityLabels = [];
        $topActivityResponseValues = [];
        $topActivityAverageValues = [];
        $topActivityItems = [];
        foreach ($topActivityRows as $row) {
            $activityName = (string) ($row->activity_name ?? '-');
            $responses = (int) ($row->total_responses ?? 0);
            $avgScore = $row->average_score !== null ? (float) $row->average_score : null;

            $topActivityLabels[] = $activityName;
            $topActivityResponseValues[] = $responses;
            $topActivityAverageValues[] = $avgScore !== null ? round($avgScore, 2) : 0.0;
            $topActivityItems[] = [
                'activity_master_id' => (int) ($row->activity_master_id ?? 0),
                'activity_name' => $activityName,
                'total_responses' => $responses,
                'average_score' => $avgScore,
                'last_update' => (string) ($row->last_update ?? ''),
            ];
        }

        $highestSectionAspect = $sectionLabels[0] ?? '-';
        $highestSectionScore = $sectionAverages[0] ?? null;
        $averageResponsesPerActivity = $totalActivities > 0 ? round($totalResponses / $totalActivities, 2) : 0.0;

        return [
            'kpi' => [
                'total_activities' => $totalActivities,
                'total_responses' => $totalResponses,
                'average_score' => $averageScore,
                'average_responses_per_activity' => $averageResponsesPerActivity,
                'highest_section_aspect' => $highestSectionAspect,
                'highest_section_score' => $highestSectionScore,
                'busiest_activity' => [
                    'name' => $busiestActivity ? (string) ($busiestActivity->activity_name ?? '-') : '-',
                    'responses' => $busiestActivity ? (int) ($busiestActivity->total_responses ?? 0) : 0,
                ],
            ],
            'score_distribution' => [
                'labels' => array_keys($scoreCountMap),
                'values' => array_values($scoreCountMap),
            ],
            'section_average' => [
                'labels' => $sectionLabels,
                'values' => $sectionAverages,
                'answer_counts' => $sectionAnswerCounts,
            ],
            'form_composition' => [
                'labels' => ['Form A (Peserta)', 'Form B (Penyelenggara)'],
                'values' => [(int) $formTypeMap['A'], (int) $formTypeMap['B']],
            ],
            'participant_modes' => [
                'labels' => $participantModeLabels,
                'values' => $participantModeValues,
            ],
            'satisfaction_levels' => [
                'labels' => $satisfactionLabels,
                'values' => $satisfactionValues,
            ],
            'organizer_roles' => [
                'labels' => $organizerRoleLabels,
                'values' => $organizerRoleValues,
            ],
            'monthly_trend' => [
                'labels' => $trendLabels,
                'values' => $trendValues,
            ],
            'top_activities' => [
                'labels' => $topActivityLabels,
                'response_values' => $topActivityResponseValues,
                'average_values' => $topActivityAverageValues,
                'items' => $topActivityItems,
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function evaluasiActivities(): array
    {
        return ProgramKemitraanEvaluationActivity::query()
            ->where('is_active', true)
            ->withCount('evaluations')
            ->orderByDesc('activity_date')
            ->orderBy('activity_name')
            ->get([
                'id',
                'activity_name',
                'activity_theme',
                'activity_date',
                'activity_start_time',
                'activity_end_time',
                'activity_timezone',
                'activity_location',
                'activity_organizer',
                'participants_invited',
                'participants_attended',
                'respondent_count',
            ])
            ->map(function (ProgramKemitraanEvaluationActivity $activity): array {
                return [
                    'id' => (int) $activity->id,
                    'activity_name' => (string) $activity->activity_name,
                    'activity_theme' => (string) $activity->activity_theme,
                    'activity_date' => $activity->activity_date ? $activity->activity_date->format('Y-m-d') : null,
                    'activity_start_time' => (string) $activity->activity_start_time,
                    'activity_end_time' => (string) $activity->activity_end_time,
                    'activity_timezone' => (string) $activity->activity_timezone,
                    'activity_location' => (string) $activity->activity_location,
                    'activity_organizer' => (string) $activity->activity_organizer,
                    'participants_invited' => $activity->participants_invited,
                    'participants_attended' => $activity->participants_attended,
                    'respondent_count' => (int) ($activity->evaluations_count ?? 0),
                ];
            })
            ->values()
            ->all();
    }

    public function create(Request $request)
    {
        $businessSectors = $this->businessSectors();
        $evaluasiQuestionGroups = $this->evaluasiQuestionGroups();
        $tab = $this->resolveTab($request->query('tab'));

        return view('program-kemitraan.create', [
            'tab' => $tab,
            'institutionCategories' => $this->institutionCategories(),
            'mitraPembangunanTypes' => $this->mitraPembangunanTypes(),
            'activityTypes' => $this->activityTypes(),
            'businessSectors' => $businessSectors,
            'evaluasiQuestionGroups' => $evaluasiQuestionGroups,
            'evaluasiRespondentCategories' => $this->evaluasiRespondentCategories(),
            'evaluasiParticipationModes' => $this->evaluasiParticipationModes(),
            'evaluasiEvaluatorRoles' => $this->evaluasiEvaluatorRoles(),
            'evaluasiSatisfactionOptions' => $this->evaluasiSatisfactionOptions(),
            'evaluasiWillingFollowupOptions' => $this->evaluasiWillingFollowupOptions(),
            'evaluasiCommunicationChannels' => $this->evaluasiCommunicationChannels(),
            'evaluasiResultCategories' => $this->evaluasiResultCategories(),
            'evaluasiAchievementStatuses' => $this->evaluasiAchievementStatuses(),
            'evaluasiPriorityOptions' => $this->evaluasiPriorityOptions(),
            'evaluasiMonitoringFrequencies' => $this->evaluasiMonitoringFrequencies(),
            'evaluasiMonitoringMediaOptions' => $this->evaluasiMonitoringMediaOptions(),
            'evaluasiActivities' => $this->evaluasiActivities(),
            'evaluasiStats' => $this->evaluasiStats(),
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
            'business_sector' => ['nullable', 'string', 'max:255', 'required_if:institution_category,' . $mitraCategory],
            'institution_address' => ['required', 'string', 'max:2000'],
            'proposed_activity_type' => ['required', Rule::in($this->activityTypes())],
            'request_letter' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        // Keep legacy `institution_name` populated for existing schema/admin consumers.
        $validated['institution_name'] = (string) ($validated['instansi_lembaga_name'] ?? '');
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

    public function storeEvaluasi(Request $request)
    {
        $questionGroups = $this->evaluasiQuestionGroups();
        $innerTab = (string) $request->input('evaluasi_inner_tab', 'peserta');
        if (!in_array($innerTab, ['peserta', 'penyelenggara'], true)) {
            $innerTab = 'peserta';
        }
        $isPesertaTab = $innerTab === 'peserta';

        $participantAnswerKeys = $this->evaluasiAnswerKeys([
            'form_a' => $questionGroups['form_a'] ?? [],
        ]);
        $organizerAnswerKeys = $this->evaluasiAnswerKeys([
            'form_b' => $questionGroups['form_b'] ?? [],
        ]);

        $rules = [
            'activity_master_id' => [
                'required',
                Rule::exists('program_kemitraan_evaluation_activities', 'id')->where(function ($query): void {
                    $query->where('is_active', 1);
                }),
            ],
        ];

        if ($isPesertaTab) {
            $rules = array_merge($rules, [
                'respondent_name' => ['required', 'string', 'max:255'],
                'respondent_organization' => ['required', 'string', 'max:255'],
                'respondent_role' => ['required', 'string', 'max:255'],
                'respondent_contact' => ['required', 'string', 'max:255'],
                'respondent_category' => ['required', Rule::in($this->evaluasiRespondentCategories())],
                'respondent_category_other' => ['nullable', 'string', 'max:255', 'required_if:respondent_category,Lainnya'],
                'participation_mode' => ['required', Rule::in($this->evaluasiParticipationModes())],
                'form_a_special_notes' => ['nullable', 'string'],
                'form_a_most_useful_material' => ['nullable', 'string'],
                'form_a_material_needs' => ['nullable', 'string'],
                'form_a_facility_notes' => ['nullable', 'string'],
                'form_a_proposed_followup' => ['nullable', 'string'],
                'overall_satisfaction' => ['required', Rule::in($this->evaluasiSatisfactionOptions())],
                'willing_to_follow_up' => ['required', Rule::in($this->evaluasiWillingFollowupOptions())],
                'preferred_channels' => ['required', 'array', 'min:1'],
                'preferred_channels.*' => ['required', Rule::in($this->evaluasiCommunicationChannels())],
                'best_part' => ['required', 'string'],
                'needs_improvement' => ['required', 'string'],
                'needed_topics' => ['required', 'string'],
                'additional_suggestions' => ['nullable', 'string'],
            ]);
        } else {
            $todayPassword = now()->format('dmY');
            $rules = array_merge($rules, [
                'evaluator_name' => ['required', 'string', 'max:255'],
                'evaluator_position' => ['required', 'string', 'max:255'],
                'evaluator_unit' => ['required', 'string', 'max:255'],
                'evaluation_date' => ['required', 'date'],
                'evaluator_role' => ['required', Rule::in($this->evaluasiEvaluatorRoles())],
                'penyelenggara_submit_password' => [
                    'required',
                    'string',
                    function (string $attribute, mixed $value, \Closure $fail) use ($todayPassword): void {
                        if ((string) $value !== $todayPassword) {
                            $fail('Password Penyelenggara tidak valid.');
                        }
                    },
                ],
                'evaluator_role_other' => ['nullable', 'string', 'max:255', 'required_if:evaluator_role,Lainnya'],
                'form_b_planning_constraints' => ['nullable', 'string'],
                'form_b_incident_notes' => ['nullable', 'string'],
                'form_b_good_practices' => ['nullable', 'string'],
                'form_b_root_issues' => ['nullable', 'string'],
                'form_b_priority_recommendations' => ['nullable', 'string'],
                'recap_participants_present' => ['nullable', 'integer', 'min:0'],
                'recap_forms_distributed' => ['nullable', 'integer', 'min:0'],
                'recap_forms_received' => ['nullable', 'integer', 'min:0'],
                'recap_forms_valid' => ['nullable', 'integer', 'min:0'],
                'recap_response_rate_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'recap_collection_period' => ['nullable', 'string', 'max:255'],
                'recap_highest_aspect' => ['nullable', 'string', 'max:255'],
                'recap_highest_value' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'recap_lowest_aspect' => ['nullable', 'string', 'max:255'],
                'recap_lowest_value' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'recap_overall_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'recap_result_category' => ['nullable', Rule::in($this->evaluasiResultCategories())],
                'recap_internal_target' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'recap_achievement_status' => ['nullable', Rule::in($this->evaluasiAchievementStatuses())],
                'recap_general_conclusion' => ['nullable', 'string'],
                'indicator_achievements' => ['nullable', 'array'],
                'indicator_achievements.*.indicator' => ['nullable', 'string', 'max:255'],
                'indicator_achievements.*.target' => ['nullable', 'string', 'max:255'],
                'indicator_achievements.*.realization' => ['nullable', 'string', 'max:255'],
                'indicator_achievements.*.status' => ['nullable', 'string', 'max:255'],
                'priority_level' => ['nullable', Rule::in($this->evaluasiPriorityOptions())],
                'rtl_items' => ['nullable', 'array'],
                'rtl_items.*.issue' => ['nullable', 'string'],
                'rtl_items.*.follow_up' => ['nullable', 'string'],
                'rtl_items.*.responsible_person' => ['nullable', 'string', 'max:255'],
                'rtl_items.*.target_date' => ['nullable', 'date'],
                'rtl_items.*.completion_indicator' => ['nullable', 'string'],
                'rtl_items.*.status' => ['nullable', 'string', 'max:255'],
                'monitoring_coordinator' => ['nullable', 'string', 'max:255'],
                'monitoring_frequency' => ['nullable', Rule::in($this->evaluasiMonitoringFrequencies())],
                'monitoring_media' => ['nullable', 'array'],
                'monitoring_media.*' => ['required', Rule::in($this->evaluasiMonitoringMediaOptions())],
                'monitoring_media_other' => ['nullable', 'string', 'max:255'],
                'first_review_date' => ['nullable', 'date'],
                'evidence_documents' => ['nullable', 'string'],
                'leader_notes' => ['nullable', 'string'],
            ]);
        }

        $requiredAnswerKeys = $isPesertaTab ? $participantAnswerKeys : $organizerAnswerKeys;
        foreach ($requiredAnswerKeys as $answerKey) {
            $rules['answers.' . $answerKey] = ['required', Rule::in(self::SCORE_OPTIONS)];
        }

        $validated = $request->validateWithBag('evaluasi', $rules);

        $preferredChannels = array_values(array_unique($validated['preferred_channels'] ?? []));
        $monitoringMedia = array_values(array_unique($validated['monitoring_media'] ?? []));
        if (in_array('Lainnya', $monitoringMedia, true) && empty($validated['monitoring_media_other'])) {
            return redirect()
                ->route('program-kemitraan.create', ['tab' => self::TAB_EVALUASI])
                ->withErrors(['monitoring_media_other' => 'Kolom media pemantauan lainnya wajib diisi.'], 'evaluasi')
                ->withInput();
        }
        $indicatorAchievements = $this->sanitizeRows($validated['indicator_achievements'] ?? [], ['indicator', 'target', 'realization', 'status']);
        $rtlItems = $this->sanitizeRows($validated['rtl_items'] ?? [], ['issue', 'follow_up', 'responsible_person', 'target_date', 'completion_indicator', 'status']);
        $selectedActivity = ProgramKemitraanEvaluationActivity::query()
            ->where('is_active', true)
            ->findOrFail((int) $validated['activity_master_id']);

        // Keep schema compatibility because the current table stores both form A and B columns in one row.
        $participantDefaults = [
            'respondent_category' => 'Masyarakat umum',
            'participation_mode' => 'Luring',
            'overall_satisfaction' => 'Cukup puas',
            'willing_to_follow_up' => 'Mungkin',
            'best_part' => '-',
            'needs_improvement' => '-',
            'needed_topics' => '-',
        ];
        $evaluatorDefaults = [
            'evaluator_name' => '-',
            'evaluator_position' => '-',
            'evaluator_unit' => '-',
            'evaluation_date' => now()->toDateString(),
            'evaluator_role' => 'Panitia',
        ];

        DB::transaction(function () use ($validated, $preferredChannels, $monitoringMedia, $indicatorAchievements, $rtlItems, $questionGroups, $selectedActivity, $participantDefaults, $evaluatorDefaults): void {
            $evaluation = ProgramKemitraanEvaluation::create([
                'activity_master_id' => (int) $selectedActivity->id,
                'activity_name' => (string) $selectedActivity->activity_name,
                'activity_theme' => (string) $selectedActivity->activity_theme,
                'activity_date' => $selectedActivity->activity_date ? $selectedActivity->activity_date->format('Y-m-d') : null,
                'activity_start_time' => (string) $selectedActivity->activity_start_time,
                'activity_end_time' => (string) $selectedActivity->activity_end_time,
                'activity_timezone' => (string) $selectedActivity->activity_timezone,
                'activity_location' => (string) $selectedActivity->activity_location,
                'activity_organizer' => (string) $selectedActivity->activity_organizer,
                'participants_invited' => $selectedActivity->participants_invited,
                'participants_attended' => $selectedActivity->participants_attended,
                'respondent_count' => null,
                'respondent_name' => $validated['respondent_name'] ?? null,
                'respondent_organization' => $validated['respondent_organization'] ?? null,
                'respondent_role' => $validated['respondent_role'] ?? null,
                'respondent_contact' => $validated['respondent_contact'] ?? null,
                'respondent_category' => $validated['respondent_category'] ?? $participantDefaults['respondent_category'],
                'respondent_category_other' => $validated['respondent_category_other'] ?? null,
                'participation_mode' => $validated['participation_mode'] ?? $participantDefaults['participation_mode'],
                'form_a_special_notes' => $validated['form_a_special_notes'] ?? null,
                'form_a_most_useful_material' => $validated['form_a_most_useful_material'] ?? null,
                'form_a_material_needs' => $validated['form_a_material_needs'] ?? null,
                'form_a_facility_notes' => $validated['form_a_facility_notes'] ?? null,
                'form_a_proposed_followup' => $validated['form_a_proposed_followup'] ?? null,
                'overall_satisfaction' => $validated['overall_satisfaction'] ?? $participantDefaults['overall_satisfaction'],
                'willing_to_follow_up' => $validated['willing_to_follow_up'] ?? $participantDefaults['willing_to_follow_up'],
                'preferred_channels' => $preferredChannels,
                'best_part' => $validated['best_part'] ?? $participantDefaults['best_part'],
                'needs_improvement' => $validated['needs_improvement'] ?? $participantDefaults['needs_improvement'],
                'needed_topics' => $validated['needed_topics'] ?? $participantDefaults['needed_topics'],
                'additional_suggestions' => $validated['additional_suggestions'] ?? null,
                'evaluator_name' => $validated['evaluator_name'] ?? $evaluatorDefaults['evaluator_name'],
                'evaluator_position' => $validated['evaluator_position'] ?? $evaluatorDefaults['evaluator_position'],
                'evaluator_unit' => $validated['evaluator_unit'] ?? $evaluatorDefaults['evaluator_unit'],
                'evaluation_date' => $validated['evaluation_date'] ?? $evaluatorDefaults['evaluation_date'],
                'evaluator_role' => $validated['evaluator_role'] ?? $evaluatorDefaults['evaluator_role'],
                'evaluator_role_other' => $validated['evaluator_role_other'] ?? null,
                'form_b_planning_constraints' => $validated['form_b_planning_constraints'] ?? null,
                'form_b_incident_notes' => $validated['form_b_incident_notes'] ?? null,
                'form_b_good_practices' => $validated['form_b_good_practices'] ?? null,
                'form_b_root_issues' => $validated['form_b_root_issues'] ?? null,
                'form_b_priority_recommendations' => $validated['form_b_priority_recommendations'] ?? null,
                'recap_participants_present' => $validated['recap_participants_present'] ?? null,
                'recap_forms_distributed' => $validated['recap_forms_distributed'] ?? null,
                'recap_forms_received' => $validated['recap_forms_received'] ?? null,
                'recap_forms_valid' => $validated['recap_forms_valid'] ?? null,
                'recap_response_rate_percent' => $validated['recap_response_rate_percent'] ?? null,
                'recap_collection_period' => $validated['recap_collection_period'] ?? null,
                'recap_highest_aspect' => $validated['recap_highest_aspect'] ?? null,
                'recap_highest_value' => $validated['recap_highest_value'] ?? null,
                'recap_lowest_aspect' => $validated['recap_lowest_aspect'] ?? null,
                'recap_lowest_value' => $validated['recap_lowest_value'] ?? null,
                'recap_overall_score' => $validated['recap_overall_score'] ?? null,
                'recap_result_category' => $validated['recap_result_category'] ?? null,
                'recap_internal_target' => $validated['recap_internal_target'] ?? null,
                'recap_achievement_status' => $validated['recap_achievement_status'] ?? null,
                'recap_general_conclusion' => $validated['recap_general_conclusion'] ?? null,
                'indicator_achievements' => $indicatorAchievements,
                'priority_level' => $validated['priority_level'] ?? null,
                'monitoring_coordinator' => $validated['monitoring_coordinator'] ?? null,
                'monitoring_frequency' => $validated['monitoring_frequency'] ?? null,
                'monitoring_media' => $monitoringMedia,
                'monitoring_media_other' => $validated['monitoring_media_other'] ?? null,
                'first_review_date' => $validated['first_review_date'] ?? null,
                'evidence_documents' => $validated['evidence_documents'] ?? null,
                'leader_notes' => $validated['leader_notes'] ?? null,
            ]);

            $answerRows = [];
            foreach ($questionGroups as $formKey => $sections) {
                $formType = $formKey === 'form_a' ? 'A' : 'B';
                foreach ($sections as $sectionKey => $section) {
                    $items = $section['items'] ?? [];
                    foreach ($items as $itemIndex => $itemText) {
                        $indicatorNumber = $itemIndex + 1;
                        $scoreValue = $validated['answers'][$formKey][$sectionKey][$indicatorNumber] ?? null;
                        if ($scoreValue === null) {
                            continue;
                        }

                        $answerRows[] = [
                            'form_type' => $formType,
                            'section_key' => $sectionKey,
                            'indicator_number' => $indicatorNumber,
                            'indicator_text' => $itemText,
                            'score' => (int) $scoreValue,
                            'is_not_applicable' => false,
                        ];
                    }
                }
            }

            if (!empty($answerRows)) {
                $evaluation->answers()->createMany($answerRows);
            }

            if (!empty($rtlItems)) {
                $rtlRows = [];
                foreach ($rtlItems as $index => $rtlItem) {
                    $rtlRows[] = [
                        'row_order' => $index + 1,
                        'issue' => $rtlItem['issue'] ?? null,
                        'follow_up' => $rtlItem['follow_up'] ?? null,
                        'responsible_person' => $rtlItem['responsible_person'] ?? null,
                        'target_date' => $rtlItem['target_date'] ?? null,
                        'completion_indicator' => $rtlItem['completion_indicator'] ?? null,
                        'status' => $rtlItem['status'] ?? null,
                    ];
                }
                $evaluation->rtlItems()->createMany($rtlRows);
            }

            $latestRespondentCount = ProgramKemitraanEvaluation::query()
                ->where('activity_master_id', (int) $selectedActivity->id)
                ->count();

            $selectedActivity->respondent_count = $latestRespondentCount;
            $selectedActivity->save();

            $evaluation->respondent_count = $latestRespondentCount;
            $evaluation->save();
        });

        return redirect()
            ->route('program-kemitraan.create', ['tab' => self::TAB_EVALUASI])
            ->with('evaluasi_success', 'Form evaluasi berhasil dikirim. Terima kasih atas partisipasi Anda.');
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @param array<int, string> $allowedKeys
     * @return array<int, array<string, mixed>>
     */
    private function sanitizeRows(array $rows, array $allowedKeys): array
    {
        $cleanRows = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $cleanRow = [];
            $hasValue = false;
            foreach ($allowedKeys as $key) {
                $value = $row[$key] ?? null;
                if (is_string($value)) {
                    $value = trim($value);
                }
                if ($value !== null && $value !== '') {
                    $hasValue = true;
                }
                $cleanRow[$key] = $value === '' ? null : $value;
            }

            if ($hasValue) {
                $cleanRows[] = $cleanRow;
            }
        }

        return $cleanRows;
    }
}
