<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('statistics')->insert([
            [
                'title' => 'Jumlah Angkatan Kerja',
                'value' => '152.107.603',
                'unit' => '',
                'description' => 'Total angkatan kerja di Indonesia tahun 2025',
                'type' => 'hero',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Tingkat Partisipasi Kerja',
                'value' => '70,63%',
                'unit' => '%',
                'description' => 'Persentase partisipasi kerja tahun 2025',
                'type' => 'hero',
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Penduduk Bekerja',
                'value' => '144.642.004',
                'unit' => '',
                'description' => 'Jumlah penduduk bekerja tahun 2025',
                'type' => 'hero',
                'order' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}

// Duplicated seeder for hero_statistics
// File: database/seeders/HeroStatisticsTableSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HeroStatisticsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('hero_statistics')->insert([
            [
                'title' => 'Jumlah Angkatan Kerja',
                'value' => '152.107.603',
                'unit' => '',
                'description' => 'Total angkatan kerja di Indonesia tahun 2025',
                'type' => 'hero',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Tingkat Partisipasi Kerja',
                'value' => '70,63%',
                'unit' => '%',
                'description' => 'Persentase partisipasi kerja tahun 2025',
                'type' => 'hero',
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Penduduk Bekerja',
                'value' => '144.642.004',
                'unit' => '',
                'description' => 'Jumlah penduduk bekerja tahun 2025',
                'type' => 'hero',
                'order' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 