<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,
            PetugasSeeder::class,
            WargaSeeder::class,
            TarifIuranSeeder::class,
            LaporanSampahSeeder::class,
            IuranBulananSeeder::class,
        ]);
    }
}
