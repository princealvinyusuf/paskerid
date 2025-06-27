<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('charts')->insert([
            [
                'title' => 'Tren Pencari Kerja Berdasarkan Umur',
                'description' => 'Statistik pencari kerja berdasarkan kelompok umur.',
                'chart_type' => 'bar',
                'data_json' => json_encode([
                    'labels' => ['15-24', '25-34', '35-44', '45-54', '55+'],
                    'data' => [12000, 15000, 10000, 8000, 4000],
                ]),
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Tren Pencari Kerja Berdasarkan Pendidikan',
                'description' => 'Statistik pencari kerja berdasarkan tingkat pendidikan.',
                'chart_type' => 'bar',
                'data_json' => json_encode([
                    'labels' => ['SD', 'SMP', 'SMA', 'Diploma', 'Sarjana'],
                    'data' => [5000, 7000, 12000, 4000, 6000],
                ]),
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 