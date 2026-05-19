<?php

namespace App\Notifications;

use App\Models\LaporanSampah;

class LaporanDitolakNotification extends BaseSiplasNotification
{
    public function __construct(public LaporanSampah $laporan) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Laporan Anda Ditolak',
            'body' => 'Laporan '.$this->laporan->kode_laporan.' ditolak. Alasan: '.($this->laporan->alasan_tolak ?? '-'),
            'icon' => 'danger',
            'url' => route('warga.laporan.show', $this->laporan->id),
            'laporan_id' => $this->laporan->id,
            'kode' => $this->laporan->kode_laporan,
        ];
    }
}
