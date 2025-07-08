<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VirtualKarirAgendasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('virtual_karir_agendas')->insert([
            [
                'title' => 'Webinar Karir Digital',
                'description' => 'Webinar tentang peluang karir di era digital.',
                'date' => '2025-07-08',
                'location' => 'Online',
                'organizer' => 'Kemnaker',
                'image_url' => '/images/webinar_karir_digital.jpg',
                'registration_url' => 'https://example.com/webinar-karir-digital',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Pelatihan Soft Skill',
                'description' => 'Pelatihan pengembangan soft skill untuk pencari kerja.',
                'date' => '2025-07-15',
                'location' => 'BLK Jakarta',
                'organizer' => 'BLK Jakarta',
                'image_url' => '/images/pelatihan_soft_skill.jpg',
                'registration_url' => 'https://example.com/pelatihan-soft-skill',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Job Fair Nasional',
                'description' => 'Pameran bursa kerja nasional dengan berbagai perusahaan.',
                'date' => '2025-07-22',
                'location' => 'JCC Senayan',
                'organizer' => 'Kemnaker',
                'image_url' => '/images/jobfair_nasional.jpg',
                'registration_url' => 'https://example.com/jobfair-nasional',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 