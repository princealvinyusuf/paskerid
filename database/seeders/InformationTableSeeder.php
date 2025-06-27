<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformationTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('information')->insert([
            [
                'title' => 'Jumlah Penduduk Usia 15 Tahun ke Atas Menurut Golongan Umur, 2025',
                'description' => 'Data statistik usia 15 tahun ke atas.',
                'date' => '2025-01-01',
                'type' => 'statistik',
                'file_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Publikasi Pasar Kerja 2025',
                'description' => 'Publikasi resmi pasar kerja tahun 2025.',
                'date' => '2025-02-01',
                'type' => 'publikasi',
                'file_url' => 'https://example.com/publikasi2025.pdf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 