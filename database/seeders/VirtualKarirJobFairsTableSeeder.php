<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VirtualKarirJobFairsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('virtual_karir_job_fairs')->insert([
            [
                'title' => 'JOB FAIR NASIONAL SERI I 2025',
                'description' => 'Job Fair Nasional Series I 2025 dilaksanakan oleh Kementerian Ketenagakerjaan di Jl. Jend. Gatot Subroto, Kav. 51, Jakarta Selatan.',
                'image_url' => '/images/jobfair2025.jpg',
                'date' => '2025-05-15',
                'author' => 'Admin',
                'register_url' => 'https://jobfair.kemnaker.go.id/daftar',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'JOB FAIR NASIONAL SERI II 2025',
                'description' => 'Job Fair Nasional Series II 2025 kembali hadir di Surabaya Convention Center.',
                'image_url' => '/images/jobfair2025-2.jpg',
                'date' => '2025-06-20',
                'author' => 'Admin',
                'register_url' => 'https://jobfair.kemnaker.go.id/daftar2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 