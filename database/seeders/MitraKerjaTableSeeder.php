<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MitraKerjaTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('mitra_kerja')->insert([
            [
                'name' => 'LPK A',
                'wilayah' => 'DKI Jakarta',
                'divider' => 'dinas',
                'address' => 'Jl. Contoh No.1',
                'contact' => '08123456789',
                'email' => 'lpkA@example.com',
                'website_url' => 'https://lpka.example.com',
                'pic' => 'Budi Santoso',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'LPK B',
                'wilayah' => 'Jawa Barat',
                'divider' => 'mitra',
                'address' => 'Jl. Contoh No.2',
                'contact' => '08123456788',
                'email' => 'lpkB@example.com',
                'website_url' => 'https://lpkb.example.com',
                'pic' => 'Siti Aminah',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Tambahkan data lain sesuai kebutuhan
        ]);
    }
} 