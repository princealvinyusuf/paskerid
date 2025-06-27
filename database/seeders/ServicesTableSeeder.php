<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('services')->insert([
            [
                'icon' => 'fa-rocket',
                'title' => 'SIAPKerja',
                'description' => 'Layanan ketenagakerjaan terintegrasi.',
                'link' => 'https://siapkerja.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa-briefcase',
                'title' => 'Job Fair',
                'description' => 'Informasi dan pendaftaran job fair.',
                'link' => 'https://jobfair.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa-certificate',
                'title' => 'Sertifikasi',
                'description' => 'Layanan sertifikasi kompetensi kerja.',
                'link' => 'https://sertifikasi.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 