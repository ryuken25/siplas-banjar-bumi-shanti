<?php

namespace Database\Seeders;

use App\Models\IuranBulanan;
use App\Models\TarifIuran;
use App\Models\User;
use Illuminate\Database\Seeder;

class IuranBulananSeeder extends Seeder
{
    public function run(): void
    {
        $tarif = TarifIuran::aktif();
        $nominal = $tarif?->nominal ?? 25000;

        $wargaAktif = User::role('warga')->where('status_akun', 'aktif')->get();
        $petugas = User::role('petugas')->get();

        if ($wargaAktif->isEmpty()) {
            return;
        }

        // Generate iuran for the last 3 months (relative to today 2026-05-19)
        $periode = collect();
        for ($i = 0; $i < 3; $i++) {
            $tgl = now()->copy()->subMonths($i)->startOfMonth();
            $periode->push(['bulan' => $tgl->month, 'tahun' => $tgl->year]);
        }

        foreach ($wargaAktif as $warga) {
            foreach ($periode as $idx => $p) {
                // Distribusi status: bulan tertua cenderung lunas, terbaru cenderung belum bayar
                if ($idx === 0) {
                    $statusPool = ['belum_bayar', 'belum_bayar', 'menunggu_verifikasi', 'lunas'];
                } elseif ($idx === 1) {
                    $statusPool = ['lunas', 'lunas', 'menunggu_verifikasi', 'belum_bayar', 'ditolak'];
                } else {
                    $statusPool = ['lunas', 'lunas', 'lunas', 'ditolak'];
                }
                $status = $statusPool[array_rand($statusPool)];

                $data = [
                    'user_id' => $warga->id,
                    'bulan' => $p['bulan'],
                    'tahun' => $p['tahun'],
                    'nominal' => $nominal,
                    'status' => $status,
                ];

                if (in_array($status, ['menunggu_verifikasi', 'lunas', 'ditolak'])) {
                    $data['metode_bayar'] = ['transfer', 'tunai'][array_rand([0, 1])];
                    $data['tanggal_bayar'] = now()->subDays(rand(1, 20));
                    if ($data['metode_bayar'] === 'transfer') {
                        $data['bukti_bayar'] = 'laporan/placeholder.jpg';
                    }
                }
                if (in_array($status, ['lunas', 'ditolak']) && $petugas->isNotEmpty()) {
                    $data['verifikator_id'] = $petugas->random()->id;
                    $data['tanggal_verifikasi'] = now()->subDays(rand(0, 10));
                }
                if ($status === 'ditolak') {
                    $data['alasan_tolak'] = 'Bukti transfer tidak jelas atau nominal tidak sesuai.';
                }

                IuranBulanan::create($data);
            }
        }
    }
}
