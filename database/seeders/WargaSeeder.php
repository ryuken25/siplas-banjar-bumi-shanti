<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        // 15 warga dengan nama Bali authentic — 12 aktif, 3 pending
        $warga = [
            ['name' => 'I Wayan Adnyana',         'status' => 'aktif'],
            ['name' => 'Ni Kadek Ayu Lestari',    'status' => 'aktif'],
            ['name' => 'I Made Suarsana',         'status' => 'aktif'],
            ['name' => 'Ni Nyoman Sri Wahyuni',   'status' => 'aktif'],
            ['name' => 'I Komang Bagus Pratama',  'status' => 'aktif'],
            ['name' => 'Ni Putu Indah Cahyani',   'status' => 'aktif'],
            ['name' => 'I Gede Arta Wijaya',      'status' => 'aktif'],
            ['name' => 'Ni Luh Putu Diah Saraswati', 'status' => 'aktif'],
            ['name' => 'I Ketut Sudiarsa',        'status' => 'aktif'],
            ['name' => 'Ni Wayan Sukerti',        'status' => 'aktif'],
            ['name' => 'I Putu Eka Mahendra',     'status' => 'aktif'],
            ['name' => 'Ni Made Ratih Pradnyani', 'status' => 'aktif'],
            ['name' => 'I Nyoman Adi Kusuma',     'status' => 'pending'],
            ['name' => 'Ni Kadek Sintia Dewi',    'status' => 'pending'],
            ['name' => 'I Wayan Bayu Permana',    'status' => 'pending'],
        ];

        $i = 1;
        foreach ($warga as $data) {
            $emailSlug = strtolower(str_replace(' ', '.', $data['name']));
            $emailSlug = preg_replace('/[^a-z\.]/', '', $emailSlug);
            $email = "{$emailSlug}@warga.banjarbumishanti.id";

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('Warga123!'),
                    'nik' => '5171'.str_pad((string) $i, 12, '0', STR_PAD_LEFT),
                    'no_kk' => '5171'.str_pad((string) (1000 + intval(($i - 1) / 4)), 12, '0', STR_PAD_LEFT),
                    'no_telp' => '0812'.str_pad((string) (10000000 + $i), 8, '0', STR_PAD_LEFT),
                    'alamat' => "Banjar Bumi Shanti, Gang Mawar No. {$i}, Desa Dauh Puri Kelod, Denpasar Barat",
                    'status_akun' => $data['status'],
                    'email_verified_at' => $data['status'] === 'aktif' ? now() : null,
                ]
            );
            $user->syncRoles(['warga']);
            $i++;
        }
    }
}
