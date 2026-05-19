<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@banjarbumishanti.id'],
            [
                'name' => 'I Dewa Gede Satia Putra',
                'password' => Hash::make('Admin123!'),
                'nik' => '5171010101800001',
                'no_kk' => '5171010101800000',
                'no_telp' => '081200000001',
                'alamat' => 'Banjar Bumi Shanti, Desa Dauh Puri Kelod, Denpasar Barat, Bali',
                'status_akun' => 'aktif',
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);
    }
}
