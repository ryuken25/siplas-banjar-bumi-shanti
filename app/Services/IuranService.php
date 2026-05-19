<?php

namespace App\Services;

use App\Models\IuranBulanan;
use App\Models\TarifIuran;
use App\Models\User;
use App\Notifications\IuranBaruNotification;
use App\Notifications\IuranDitolakNotification;
use App\Notifications\IuranTerverifikasiNotification;

class IuranService
{
    /**
     * Generate iuran for a given month/year for all active warga who don't yet have one.
     * Returns the count of newly generated iuran.
     */
    public function generateTagihanBulan(int $bulan, int $tahun): int
    {
        $tarif = TarifIuran::aktif();
        $nominal = $tarif?->nominal ?? 25000;

        $existing = IuranBulanan::where('bulan', $bulan)->where('tahun', $tahun)->pluck('user_id')->all();
        $warga = User::role('warga')->where('status_akun', 'aktif')->whereNotIn('id', $existing)->get();

        $count = 0;
        foreach ($warga as $w) {
            $iuran = IuranBulanan::create([
                'user_id' => $w->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nominal' => $nominal,
                'status' => 'belum_bayar',
            ]);
            $w->notify(new IuranBaruNotification($iuran));
            $count++;
        }

        return $count;
    }

    public function verifikasi(IuranBulanan $iuran, User $petugas): IuranBulanan
    {
        $iuran->update([
            'status' => 'lunas',
            'verifikator_id' => $petugas->id,
            'tanggal_verifikasi' => now(),
        ]);
        $iuran->warga?->notify(new IuranTerverifikasiNotification($iuran));

        return $iuran->fresh();
    }

    public function tolak(IuranBulanan $iuran, User $petugas, string $alasan): IuranBulanan
    {
        $iuran->update([
            'status' => 'ditolak',
            'verifikator_id' => $petugas->id,
            'tanggal_verifikasi' => now(),
            'alasan_tolak' => $alasan,
        ]);
        $iuran->warga?->notify(new IuranDitolakNotification($iuran));

        return $iuran->fresh();
    }
}
