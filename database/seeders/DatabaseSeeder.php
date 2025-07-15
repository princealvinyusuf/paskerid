<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            StatisticsTableSeeder::class,
            InformationTableSeeder::class,
            ChartsTableSeeder::class,
            TopListsTableSeeder::class,
            ContributionsTableSeeder::class,
            ServicesTableSeeder::class,
            NewsTableSeeder::class,
            TestimonialsTableSeeder::class,
            DashboardTrendSeeder::class,
            DashboardDistributionSeeder::class,
            DashboardPerformanceSeeder::class,
            DashboardLaborDemandSeeder::class,
            MitraKerjaTableSeeder::class,
            JobCharacteristicsTableSeeder::class,
            JobCharacteristics2TableSeeder::class,
            JobCharacteristics3TableSeeder::class,
            InformasiSection1TableSeeder::class,
            InformasiSection2TableSeeder::class,
            InformasiSection3TableSeeder::class,
            InformasiSection4TableSeeder::class,
            // \Database\Seeders\DashboardTrendSeeder::class,
        ]);
    }
}
