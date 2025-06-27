<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TopListsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('top_lists')->insert([
            [
                'title' => 'Top 5 Kualifikasi Paling Umum Pencari Kerja',
                'type' => 'skills',
                'data_json' => json_encode([
                    'items' => [
                        ['name' => 'Komunikasi', 'count' => 1200],
                        ['name' => 'Kerja Tim', 'count' => 1100],
                        ['name' => 'Manajemen Waktu', 'count' => 900],
                        ['name' => 'Kepemimpinan', 'count' => 800],
                        ['name' => 'Teknologi Informasi', 'count' => 700],
                    ]
                ]),
                'date' => '2025-01-01',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Top 5 Provinsi dengan Pencari Kerja Terbanyak',
                'type' => 'provinces',
                'data_json' => json_encode([
                    'items' => [
                        ['name' => 'Jawa Barat', 'count' => 5000],
                        ['name' => 'Jawa Timur', 'count' => 4500],
                        ['name' => 'Jawa Tengah', 'count' => 4000],
                        ['name' => 'Sumatera Utara', 'count' => 3500],
                        ['name' => 'Banten', 'count' => 3000],
                    ]
                ]),
                'date' => '2025-01-01',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Top 5 Talenta dengan Lowongan Terbanyak',
                'type' => 'talents',
                'data_json' => json_encode([
                    'items' => [
                        ['name' => 'Software Engineer', 'count' => 2000],
                        ['name' => 'Data Analyst', 'count' => 1800],
                        ['name' => 'Marketing', 'count' => 1600],
                        ['name' => 'Sales', 'count' => 1400],
                        ['name' => 'Customer Service', 'count' => 1200],
                    ]
                ]),
                'date' => '2025-01-01',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Top 5 Sektor Industri Pemberi Lowongan Terbanyak',
                'type' => 'sectors',
                'data_json' => json_encode([
                    'items' => [
                        ['name' => 'Teknologi', 'count' => 3000],
                        ['name' => 'Manufaktur', 'count' => 2500],
                        ['name' => 'Pendidikan', 'count' => 2000],
                        ['name' => 'Kesehatan', 'count' => 1800],
                        ['name' => 'Perdagangan', 'count' => 1500],
                    ]
                ]),
                'date' => '2025-01-01',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 