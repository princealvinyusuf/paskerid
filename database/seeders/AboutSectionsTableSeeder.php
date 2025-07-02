<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutSection;

class AboutSectionsTableSeeder extends Seeder
{
    public function run()
    {
        AboutSection::insert([
            [
                'type' => 'text_image',
                'title' => 'Tentang Kami',
                'content' => 'Pusat Pasar Kerja dibentuk sebagai jawaban dari permasalahan Link and Match...',
                'media_url' => 'images/tentang_kami.jpg',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'image',
                'title' => 'Struktur Organisasi',
                'content' => null,
                'media_url' => 'images/struktur_organisasi.jpg',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'image',
                'title' => 'Tugas dan Fungsi',
                'content' => null,
                'media_url' => 'images/tugas_fungsi.jpg',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'image',
                'title' => 'Sejarah',
                'content' => null,
                'media_url' => 'images/sejarah.jpg',
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'image',
                'title' => 'Maskot Pasker',
                'content' => null,
                'media_url' => 'images/maskot_pasker.jpg',
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'youtube',
                'title' => 'Video Profil',
                'content' => null,
                'media_url' => 'https://www.youtube.com/embed/your_video_id1',
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'youtube',
                'title' => 'Jingle',
                'content' => null,
                'media_url' => 'https://www.youtube.com/embed/your_video_id2',
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'image',
                'title' => 'Maklumat',
                'content' => null,
                'media_url' => 'images/maklumat.jpg',
                'order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
