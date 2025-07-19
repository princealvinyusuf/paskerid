<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatasetsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('datasets')->insert([
            // PENCARI KERJA
            [
                'category' => 'Pencari Kerja',
                'title' => 'Pencari Kerja Berdasarkan Pendidikan',
                'description' => null,
                'location' => '38 Provinsi',
                'years' => '2022-2024',
                'csv_url' => '/documents/pencari_kerja_pendidikan.csv',
                'xlsx_url' => '/documents/pencari_kerja_pendidikan.xlsx',
                'icon' => 'fa-users',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category' => 'Pencari Kerja',
                'title' => 'Pencari Kerja Berdasarkan Jurusan/Kejuruan',
                'description' => null,
                'location' => '38 Provinsi',
                'years' => '2022-2024',
                'csv_url' => '/documents/pencari_kerja_jurusan.csv',
                'xlsx_url' => '/documents/pencari_kerja_jurusan.xlsx',
                'icon' => 'fa-users',
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Add more for each card in your screenshot, grouped by category...
            // Example for Pemberi Kerja, Lowongan Kerja, etc.
        ]);
    }
}
