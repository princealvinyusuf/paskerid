<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformasiSection3TableSeeder extends Seeder
{
    public function run()
    {
        DB::table('informasi_section_3')->insert([
            [
                'title' => 'Informasi Pasar Kerja 3-1',
                'description' => 'Deskripsi informasi pasar kerja 3-1',
                'tableau_embed_code' => '<iframe src="https://public.tableau.com/views/informasi3-1" width="100%" height="350"></iframe>',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Informasi Pasar Kerja 3-2',
                'description' => 'Deskripsi informasi pasar kerja 3-2',
                'tableau_embed_code' => '<iframe src="https://public.tableau.com/views/informasi3-2" width="100%" height="350"></iframe>',
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Informasi Pasar Kerja 3-3',
                'description' => 'Deskripsi informasi pasar kerja 3-3',
                'tableau_embed_code' => '<iframe src="https://public.tableau.com/views/informasi3-3" width="100%" height="350"></iframe>',
                'order' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Informasi Pasar Kerja 3-4',
                'description' => 'Deskripsi informasi pasar kerja 3-4',
                'tableau_embed_code' => '<iframe src="https://public.tableau.com/views/informasi3-4" width="100%" height="350"></iframe>',
                'order' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 