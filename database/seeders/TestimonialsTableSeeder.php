<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestimonialsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('testimonials')->insert([
            [
                'name' => 'Albar Arifin',
                'position' => 'Staff Administrasi',
                'company' => 'PT Mega Jaya Abadi',
                'photo_url' => 'https://example.com/testi1.jpg',
                'quote' => 'Saya merasa kini lapangan pekerjaan sangat luas! Terima kasih Paskerid.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lidya Santoso',
                'position' => 'HRD Manager',
                'company' => 'PT Sinar Global',
                'photo_url' => 'https://example.com/testi2.jpg',
                'quote' => 'Kami sangat terbantu mencari pekerja yang sesuai kebutuhan perusahaan.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 