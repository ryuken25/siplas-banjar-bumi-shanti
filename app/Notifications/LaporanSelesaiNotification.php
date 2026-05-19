<?php

namespace App\Notifications;

use App\Models\LaporanSampah;

class LaporanSelesaiNotification extends BaseSiplasNotification
{
    public function __construct(public LaporanSampah $laporan) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Laporan Anda Telah Selesai Ditangani',
            'body' => 'Terima kasih atas laporannya. Sampah di lokasi '.$this->laporan->kode_laporan.' telah berhasil diangkut.',
            'icon' => 'success',
            'url' => route('warga.laporan.show', $this->laporan->id),
            'laporan_id' => $this->laporan->id,
            'kode' => $this->laporan->kode_laporan,
        ];
    }
}
