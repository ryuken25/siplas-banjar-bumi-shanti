<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = [
            [
                'email' => 'petugas1@banjarbumishanti.id',
                'name' => 'I Wayan Adi Kebersihan',
                'nik' => '5171010101850001',
                'no_kk' => '5171010101850000',
                'no_telp' => '081200000010',
            ],
            [
                'email' => 'petugas2@banjarbumishanti.id',
                'name' => 'Ni Made Sri Asih',
                'nik' => '5171010101850002',
                'no_kk' => '5171010101850000',
                'no_telp' => '081200000011',
            ],
        ];

        foreach ($petugas as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('Petugas123!'),
                    'nik' => $data['nik'],
                    'no_kk' => $data['no_kk'],
                    'no_telp' => $data['no_telp'],
                    'alamat' => 'Banjar Bumi Shanti, Desa Dauh Puri Kelod, Denpasar Barat, Bali',
                    'status_akun' => 'aktif',
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['petugas']);
        }
    }
}
