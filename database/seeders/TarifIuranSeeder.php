<?php

namespace Database\Seeders;

use App\Models\TarifIuran;
use Illuminate\Database\Seeder;

class TarifIuranSeeder extends Seeder
{
    public function run(): void
    {
        TarifIuran::updateOrCreate(
            ['periode_mulai' => '2026-01-01'],
            [
                'nominal' => 25000,
                'keterangan' => 'Tarif iuran sampah bulanan per KK, berlaku sejak Januari 2026.',
                'aktif' => true,
            ]
        );
    }
}
