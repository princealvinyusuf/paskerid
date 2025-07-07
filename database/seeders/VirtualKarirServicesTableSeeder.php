<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VirtualKarirServicesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('virtual_karir_services')->insert([
            [
                'icon' => 'fa fa-briefcase',
                'title' => 'SIAPKerja',
                'description' => 'SIAPKerja adalah suatu ekosistem digital yang menjadi platform bagi segala jenis layanan publik...',
                'link' => 'https://siapkerja.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa fa-users',
                'title' => 'Karirhub',
                'description' => 'Karirhub adalah suatu layanan daring ketenagakerjaan pada Portal kemnaker.go.id yang menyediakan...',
                'link' => 'https://karirhub.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa fa-lightbulb',
                'title' => 'Talenthub',
                'description' => 'Program yang mendukung peningkatan produktivitas bagi talenta muda di sektor kreatif, digital, ...',
                'link' => 'https://talenthub.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa fa-search',
                'title' => 'Lowongan pekerjaan',
                'description' => 'SIAPKerja adalah suatu ekosistem digital yang menjadi platform bagi segala jenis layanan publik...',
                'link' => 'https://loker.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa fa-globe',
                'title' => 'Job Fair Virtual',
                'description' => 'Layanan job fair / bursa kerja kemnaker merupakan layanan yang mempertemukan antara pencari pek...',
                'link' => 'https://jobfair.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'fa fa-code',
                'title' => 'Tech:X Programme',
                'description' => 'Kerja sama pemerintah Indonesia dengan pemerintah Singapura untuk pengembangan talenta digital ...',
                'link' => 'https://techx.kemnaker.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 