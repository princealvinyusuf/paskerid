<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobCharacteristics2TableSeeder extends Seeder
{
    public function run()
    {
        DB::table('job_characteristics_2')->insert([
            [
                'title' => 'Karakteristik 2-1',
                'description' => 'Deskripsi karakteristik lowongan kerja 2-1',
                'tableau_embed_code' => '<iframe src="https://public.tableau.com/views/sample2-1" width="100%" height="350"></iframe>',
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Karakteristik 2-2',
                'description' => 'Deskripsi karakteristik lowongan kerja 2-2',
                'tableau_embed_code' => '<iframe src="https://public.tableau.com/views/sample2-2" width="100%" height="350"></iframe>',
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Karakteristik 2-3',
                'description' => 'Deskripsi karakteristik lowongan kerja 2-3',
                'tableau_embed_code' => '<iframe src="https://public.tableau.com/views/sample2-3" width="100%" height="350"></iframe>',
                'order' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Karakteristik 2-4',
                'description' => 'Deskripsi karakteristik lowongan kerja 2-4',
                'tableau_embed_code' => '<iframe src="https://public.tableau.com/views/sample2-4" width="100%" height="350"></iframe>',
                'order' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 