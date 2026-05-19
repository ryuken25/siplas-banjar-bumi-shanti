<?php

namespace App\Notifications;

use App\Models\LaporanSampah;

class LaporanDiterimaNotification extends BaseSiplasNotification
{
    public function __construct(public LaporanSampah $laporan) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Laporan Anda Telah Diterima',
            'body' => 'Laporan '.$this->laporan->kode_laporan.' telah diterima petugas dan akan segera ditindaklanjuti.',
            'icon' => 'primary',
            'url' => route('warga.laporan.show', $this->laporan->id),
            'laporan_id' => $this->laporan->id,
            'kode' => $this->laporan->kode_laporan,
        ];
    }
}
