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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Pelatihan Soft Skill',
                'description' => 'Pelatihan pengembangan soft skill untuk pencari kerja.',
                'date' => '2025-07-15',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Job Fair Nasional',
                'description' => 'Pameran bursa kerja nasional dengan berbagai perusahaan.',
                'date' => '2025-07-22',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 