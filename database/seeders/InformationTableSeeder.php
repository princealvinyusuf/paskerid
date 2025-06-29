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
                'subject' => 'Tenaga Kerja',
                'file_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Publikasi Pasar Kerja 2025',
                'description' => 'Publikasi resmi pasar kerja tahun 2025.',
                'date' => '2025-02-01',
                'type' => 'publikasi',
                'subject' => 'Tenaga Kerja',
                'file_url' => 'https://example.com/publikasi2025.pdf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Angkatan Kerja (AK) Menurut Golongan Umur, 1986 - 2025',
                'description' => 'Statistik angkatan kerja menurut golongan umur.',
                'date' => '2025-01-01',
                'type' => 'statistik',
                'subject' => 'Angkatan Kerja',
                'file_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Publikasi Angkatan Kerja 2025',
                'description' => 'Publikasi angkatan kerja tahun 2025.',
                'date' => '2025-02-01',
                'type' => 'publikasi',
                'subject' => 'Angkatan Kerja',
                'file_url' => 'https://example.com/publikasi-ak2025.pdf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Angkatan Kerja (AK) Menurut Kelompok Umur, 2025',
                'description' => 'Statistik AK menurut kelompok umur.',
                'date' => '2025-01-01',
                'type' => 'statistik',
                'subject' => 'Subjek 3',
                'file_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Publikasi Subjek 3 Tahun 2025',
                'description' => 'Publikasi subjek 3 tahun 2025.',
                'date' => '2025-02-01',
                'type' => 'publikasi',
                'subject' => 'Subjek 3',
                'file_url' => 'https://example.com/publikasi-subjek3-2025.pdf',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 