<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('news')->insert([
            [
                'title' => 'Pusat Pasar Kerja Fasilitasi Walk-in Interview Bersama PT SMS',
                'content' => 'Kegiatan walk-in interview diadakan untuk mempertemukan pencari kerja dengan perusahaan.',
                'image_url' => 'https://example.com/news1.jpg',
                'date' => '2025-03-01',
                'author' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Pelatihan Digital Marketing untuk Pencari Kerja',
                'content' => 'Pelatihan ini bertujuan meningkatkan keterampilan digital marketing.',
                'image_url' => 'https://example.com/news2.jpg',
                'date' => '2025-03-05',
                'author' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 