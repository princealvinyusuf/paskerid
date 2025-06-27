<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContributionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('contributions')->insert([
            [
                'icon' => 'fa-users',
                'title' => 'Matching',
                'description' => 'Membantu pencari kerja dan pemberi kerja saling menemukan.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa-graduation-cap',
                'title' => 'Skill',
                'description' => 'Meningkatkan keterampilan tenaga kerja.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa-briefcase',
                'title' => 'Inklusif',
                'description' => 'Mendukung inklusivitas di pasar kerja.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa-lightbulb',
                'title' => 'Inovasi',
                'description' => 'Mendorong inovasi dalam ketenagakerjaan.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 