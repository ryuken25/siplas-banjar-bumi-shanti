<?php

namespace App\Services;

use App\Models\LaporanSampah;
use App\Models\User;
use App\Notifications\LaporanBaruUntukPetugasNotification;
use App\Notifications\LaporanDiprosesNotification;
use App\Notifications\LaporanDiterimaNotification;
use App\Notifications\LaporanDitolakNotification;
use App\Notifications\LaporanSelesaiNotification;
use Illuminate\Support\Facades\Notification;

class LaporanService
{
    public function notifyPetugasOfNewLaporan(LaporanSampah $laporan): void
    {
        $petugas = User::role('petugas')->where('status_akun', 'aktif')->get();
        if ($petugas->isNotEmpty()) {
            Notification::send($petugas, new LaporanBaruUntukPetugasNotification($laporan));
        }
    }

    public function terima(LaporanSampah $laporan, User $petugas): LaporanSampah
    {
        $laporan->update([
            'status' => 'diterima',
            'petugas_id' => $petugas->id,
            'tanggal_diterima' => now(),
        ]);
        $laporan->pelapor?->notify(new LaporanDiterimaNotification($laporan));

        return $laporan->fresh();
    }

    public function proses(LaporanSampah $laporan, User $petugas): LaporanSampah
    {
        $laporan->update([
            'status' => 'diproses',
            'petugas_id' => $laporan->petugas_id ?? $petugas->id,
            'tanggal_diproses' => now(),
            'tanggal_diterima' => $laporan->tanggal_diterima ?? now(),
        ]);
        $laporan->pelapor?->notify(new LaporanDiprosesNotification($laporan));

        return $laporan->fresh();
    }

    public function selesai(LaporanSampah $laporan, User $petugas): LaporanSampah
    {
        $laporan->update([
            'status' => 'selesai',
            'petugas_id' => $laporan->petugas_id ?? $petugas->id,
            'tanggal_selesai' => now(),
            'tanggal_diproses' => $laporan->tanggal_diproses ?? now(),
            'tanggal_diterima' => $laporan->tanggal_diterima ?? now(),
        ]);
        $laporan->pelapor?->notify(new LaporanSelesaiNotification($laporan));

        return $laporan->fresh();
    }

    public function tolak(LaporanSampah $laporan, User $petugas, string $alasan): LaporanSampah
    {
        $laporan->update([
            'status' => 'ditolak',
            'petugas_id' => $laporan->petugas_id ?? $petugas->id,
            'alasan_tolak' => $alasan,
        ]);
        $laporan->pelapor?->notify(new LaporanDitolakNotification($laporan));

        return $laporan->fresh();
    }
}
