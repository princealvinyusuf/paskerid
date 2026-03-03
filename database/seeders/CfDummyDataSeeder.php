<?php

namespace Database\Seeders;

use App\Models\CfCategory;
use App\Models\CfNotification;
use App\Models\CfReport;
use App\Models\CfReportAudit;
use App\Models\CfReply;
use App\Models\CfThread;
use App\Models\CfVerificationRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CfDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->seedUsers();
        $categories = $this->seedCategories();
        $threads = $this->seedThreads($users, $categories);
        $replies = $this->seedReplies($users, $threads);

        $this->seedNotifications($threads, $replies);
        $this->seedVerificationRequests($users);
        $this->seedReportsAndAudits($users, $threads, $replies);
    }

    private function seedUsers(): array
    {
        $now = now();
        $rows = [
            [
                'name' => 'Admin CF Demo',
                'email' => 'cf-admin-demo@paskerid.local',
                'password' => Hash::make('password123'),
                'cf_verified_role' => 'employer',
                'cf_verified_at' => $now->copy()->subDays(50),
            ],
            [
                'name' => 'PT Nusantara Recruit',
                'email' => 'employer-demo@paskerid.local',
                'password' => Hash::make('password123'),
                'cf_verified_role' => 'employer',
                'cf_verified_at' => $now->copy()->subDays(32),
            ],
            [
                'name' => 'Rina Pratama',
                'email' => 'jobseeker-demo@paskerid.local',
                'password' => Hash::make('password123'),
                'cf_verified_role' => 'jobseeker',
                'cf_verified_at' => $now->copy()->subDays(26),
            ],
            [
                'name' => 'Komunitas HR Indonesia',
                'email' => 'community-demo@paskerid.local',
                'password' => Hash::make('password123'),
                'cf_verified_role' => null,
                'cf_verified_at' => null,
            ],
        ];

        $result = [];
        foreach ($rows as $row) {
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => $row['password'],
                ]
            );

            $user->cf_verified_role = $row['cf_verified_role'];
            $user->cf_verified_at = $row['cf_verified_at'];
            $user->save();

            $result[$row['email']] = $user;
        }

        return $result;
    }

    private function seedCategories()
    {
        $defaults = [
            ['name' => 'Lowongan & Kebutuhan Talenta', 'slug' => 'lowongan-kebutuhan-talenta', 'sort_order' => 1],
            ['name' => 'Pencari Kerja & Profil Kandidat', 'slug' => 'pencari-kerja-profil-kandidat', 'sort_order' => 2],
            ['name' => 'Diskusi Industri per Sektor', 'slug' => 'diskusi-industri-per-sektor', 'sort_order' => 3],
            ['name' => 'Tips Rekrutmen & Karier', 'slug' => 'tips-rekrutmen-karier', 'sort_order' => 4],
            ['name' => 'Event Job Fair & Walk In Interview', 'slug' => 'event-job-fair-walk-in-interview', 'sort_order' => 5],
        ];

        foreach ($defaults as $item) {
            CfCategory::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'description' => 'Kategori demo CF.',
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        return CfCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('slug');
    }

    private function seedThreads(array $users, $categories): array
    {
        $employer = $users['employer-demo@paskerid.local'];
        $jobseeker = $users['jobseeker-demo@paskerid.local'];
        $community = $users['community-demo@paskerid.local'];

        $rows = [
            [
                'title' => 'Dibutuhkan Frontend Vue Developer untuk Proyek Pemerintahan',
                'body' => 'Kami membuka 3 posisi frontend developer untuk proyek layanan publik. Stack utama: Vue + Laravel API. Silakan share portofolio.',
                'author' => $employer,
                'author_type' => 'employer',
                'category_slug' => 'lowongan-kebutuhan-talenta',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'work_type' => 'hybrid',
                'salary_range' => 'Rp8.000.000 - Rp13.000.000',
                'experience_level' => 'mid',
                'job_role' => 'Frontend Developer',
                'sector' => 'Teknologi Informasi',
                'location' => 'Jakarta Selatan',
                'status' => 'open',
                'is_pinned' => true,
            ],
            [
                'title' => 'Profile Review: Data Analyst Fresh Graduate',
                'body' => 'Halo semua, mohon masukan untuk CV saya sebagai data analyst entry level. Saya punya pengalaman internship 6 bulan.',
                'author' => $jobseeker,
                'author_type' => 'jobseeker',
                'category_slug' => 'pencari-kerja-profil-kandidat',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'work_type' => 'onsite',
                'salary_range' => 'Rp4.500.000 - Rp7.000.000',
                'experience_level' => 'entry',
                'job_role' => 'Data Analyst',
                'sector' => 'Jasa Keuangan',
                'location' => 'Bandung',
                'status' => 'open',
                'is_pinned' => false,
            ],
            [
                'title' => 'Tren Kebutuhan Operator Produksi Q2 2026',
                'body' => 'Beberapa anggota komunitas menyebut kebutuhan operator produksi naik. Di daerah kalian bagaimana? Share insight dan data lapangan.',
                'author' => $community,
                'author_type' => 'community',
                'category_slug' => 'diskusi-industri-per-sektor',
                'province' => 'Jawa Tengah',
                'city' => 'Semarang',
                'work_type' => 'onsite',
                'salary_range' => 'Rp3.200.000 - Rp5.200.000',
                'experience_level' => 'entry',
                'job_role' => 'Operator Produksi',
                'sector' => 'Manufaktur',
                'location' => 'Semarang',
                'status' => 'open',
                'is_pinned' => false,
            ],
            [
                'title' => 'Tips Screening CV Agar Time-to-Hire Lebih Cepat',
                'body' => 'Kami sedang eksperimen checklist screening 15 menit per CV. Ada template atau praktik terbaik yang bisa dibagikan?',
                'author' => $employer,
                'author_type' => 'employer',
                'category_slug' => 'tips-rekrutmen-karier',
                'province' => 'Banten',
                'city' => 'Tangerang',
                'work_type' => 'hybrid',
                'salary_range' => 'N/A',
                'experience_level' => 'senior',
                'job_role' => 'Recruiter',
                'sector' => 'Sumber Daya Manusia',
                'location' => 'Tangerang',
                'status' => 'open',
                'is_pinned' => false,
            ],
            [
                'title' => 'Koordinasi Kandidat untuk Walk In Interview Bulan Ini',
                'body' => 'Thread khusus koordinasi peserta Walk In Interview. Mohon share pertanyaan umum, dokumen, dan tips saat hari-H.',
                'author' => $community,
                'author_type' => 'community',
                'category_slug' => 'event-job-fair-walk-in-interview',
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'work_type' => 'onsite',
                'salary_range' => 'N/A',
                'experience_level' => 'entry',
                'job_role' => 'General',
                'sector' => 'Multi-sektor',
                'location' => 'Surabaya',
                'status' => 'closed',
                'is_pinned' => false,
            ],
        ];

        $result = [];
        foreach ($rows as $index => $item) {
            $categoryId = $categories[$item['category_slug']]->id ?? null;
            if (!$categoryId) {
                continue;
            }

            $slug = Str::slug($item['title']) . '-demo-' . ($index + 1);

            $thread = CfThread::updateOrCreate(
                ['slug' => $slug],
                [
                    'cf_category_id' => $categoryId,
                    'user_id' => $item['author']->id,
                    'title' => $item['title'],
                    'body' => $item['body'],
                    'author_type' => $item['author_type'],
                    'location' => $item['location'],
                    'sector' => $item['sector'],
                    'job_role' => $item['job_role'],
                    'province' => $item['province'],
                    'city' => $item['city'],
                    'work_type' => $item['work_type'],
                    'salary_range' => $item['salary_range'],
                    'experience_level' => $item['experience_level'],
                    'attachment_urls' => [
                        'https://example.com/demo/cf/job-desc-' . ($index + 1) . '.pdf',
                        'https://example.com/demo/cf/career-guide-' . ($index + 1) . '.jpg',
                    ],
                    'status' => $item['status'],
                    'is_pinned' => $item['is_pinned'],
                    'is_locked' => $item['status'] === 'closed',
                    'is_hidden' => false,
                    'views_count' => 90 + ($index * 30),
                    'last_activity_at' => now()->subHours(12 - $index),
                ]
            );

            $result[] = $thread;
        }

        return $result;
    }

    private function seedReplies(array $users, array $threads): array
    {
        $employer = $users['employer-demo@paskerid.local'];
        $jobseeker = $users['jobseeker-demo@paskerid.local'];
        $community = $users['community-demo@paskerid.local'];

        $replyBodies = [
            'Terima kasih infonya, apakah ada opsi interview online untuk kandidat luar kota?',
            'Saya tertarik apply. Untuk level pengalaman minimal apakah 1 tahun sudah memenuhi?',
            'Insight yang bagus. Mungkin bisa ditambah contoh KPI agar kandidat lebih paham ekspektasi peran.',
            'Untuk event ini apakah disediakan sesi konsultasi CV di lokasi?',
        ];

        $actors = [$employer, $jobseeker, $community];
        $result = [];

        foreach ($threads as $index => $thread) {
            for ($i = 0; $i < 2; $i++) {
                $actor = $actors[($index + $i) % count($actors)];
                $body = $replyBodies[($index + $i) % count($replyBodies)];

                $reply = CfReply::firstOrCreate(
                    [
                        'cf_thread_id' => $thread->id,
                        'user_id' => $actor->id,
                        'body' => $body,
                    ],
                    [
                        'is_solution' => $i === 1 && $index % 2 === 0,
                        'is_hidden' => false,
                    ]
                );

                $result[] = $reply;
            }
        }

        return $result;
    }

    private function seedNotifications(array $threads, array $replies): void
    {
        foreach ($threads as $thread) {
            $reply = collect($replies)->first(function (CfReply $item) use ($thread) {
                return (int) $item->cf_thread_id === (int) $thread->id
                    && (int) $item->user_id !== (int) $thread->user_id;
            });

            if (!$reply) {
                continue;
            }

            CfNotification::firstOrCreate(
                [
                    'user_id' => $thread->user_id,
                    'type' => 'thread_reply',
                    'cf_thread_id' => $thread->id,
                    'cf_reply_id' => $reply->id,
                    'actor_user_id' => $reply->user_id,
                ],
                [
                    'title' => 'Balasan baru di diskusi Anda',
                    'message' => 'Seseorang membalas thread "' . Str::limit($thread->title, 70) . '".',
                    'is_read' => false,
                    'read_at' => null,
                ]
            );
        }
    }

    private function seedVerificationRequests(array $users): void
    {
        $employer = $users['employer-demo@paskerid.local'];
        $jobseeker = $users['jobseeker-demo@paskerid.local'];
        $admin = $users['cf-admin-demo@paskerid.local'];

        CfVerificationRequest::firstOrCreate(
            [
                'user_id' => $employer->id,
                'requested_role' => 'employer',
                'status' => 'approved',
            ],
            [
                'organization_name' => 'PT Nusantara Recruit',
                'evidence_url' => 'https://example.com/demo/legalitas-pt-nusantara.pdf',
                'notes' => 'Pengajuan verifikasi akun employer demo.',
                'reviewed_by_user_id' => $admin->id,
                'review_note' => 'Dokumen valid, disetujui.',
                'reviewed_at' => now()->subDays(24),
            ]
        );

        CfVerificationRequest::firstOrCreate(
            [
                'user_id' => $jobseeker->id,
                'requested_role' => 'jobseeker',
                'status' => 'pending',
            ],
            [
                'organization_name' => null,
                'evidence_url' => 'https://example.com/demo/portfolio-rina',
                'notes' => 'Mohon review profil dan pengalaman internship.',
            ]
        );
    }

    private function seedReportsAndAudits(array $users, array $threads, array $replies): void
    {
        $admin = $users['cf-admin-demo@paskerid.local'];
        $reporter = $users['community-demo@paskerid.local'];

        $thread = $threads[0] ?? null;
        $reply = $replies[0] ?? null;
        if (!$thread || !$reply) {
            return;
        }

        $openReport = CfReport::updateOrCreate(
            [
                'reportable_type' => 'thread',
                'reportable_id' => $thread->id,
                'reported_by_user_id' => $reporter->id,
                'reason' => 'Judul thread terlalu umum, mohon dipertegas requirement dan batas waktu.',
            ],
            [
                'status' => 'open',
                'priority_score' => 42,
                'priority_level' => 'medium',
                'escalation_level' => 'watch',
                'escalated_at' => now()->subHours(6),
                'reviewed_by_user_id' => null,
                'review_note' => null,
                'reviewed_at' => null,
            ]
        );

        CfReportAudit::firstOrCreate(
            [
                'cf_report_id' => $openReport->id,
                'action' => 'created',
            ],
            [
                'actor_user_id' => $reporter->id,
                'from_status' => null,
                'to_status' => 'open',
                'escalation_level' => 'watch',
                'note' => 'Laporan dibuat otomatis oleh data demo.',
                'metadata' => ['seed' => true, 'sample' => 'watch'],
                'created_at' => now()->subHours(6),
            ]
        );

        $resolvedReport = CfReport::updateOrCreate(
            [
                'reportable_type' => 'reply',
                'reportable_id' => $reply->id,
                'reported_by_user_id' => $reporter->id,
                'reason' => 'Bahasa kurang relevan dengan topik diskusi.',
            ],
            [
                'status' => 'resolved',
                'priority_score' => 64,
                'priority_level' => 'high',
                'escalation_level' => 'urgent',
                'escalated_at' => now()->subDays(1),
                'reviewed_by_user_id' => $admin->id,
                'review_note' => 'Sudah ditinjau dan diberikan pengingat etika diskusi.',
                'reviewed_at' => now()->subHours(8),
            ]
        );

        CfReportAudit::firstOrCreate(
            [
                'cf_report_id' => $resolvedReport->id,
                'action' => 'status_updated',
                'to_status' => 'resolved',
            ],
            [
                'actor_user_id' => $admin->id,
                'from_status' => 'open',
                'escalation_level' => 'urgent',
                'note' => 'Laporan ditutup setelah review admin demo.',
                'metadata' => ['seed' => true, 'sample' => 'resolved'],
                'created_at' => now()->subHours(8),
            ]
        );
    }
}
