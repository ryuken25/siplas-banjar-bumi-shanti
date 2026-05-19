<?php

namespace App\Notifications;

use App\Models\LaporanSampah;

class LaporanBaruUntukPetugasNotification extends BaseSiplasNotification
{
    public function __construct(public LaporanSampah $laporan) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Laporan Sampah Baru',
            'body' => 'Laporan baru '.$this->laporan->kode_laporan.' ('.$this->laporan->jenis_label.') menunggu untuk diproses.',
            'icon' => 'info',
            'url' => route('petugas.laporan.show', $this->laporan->id),
            'laporan_id' => $this->laporan->id,
            'kode' => $this->laporan->kode_laporan,
        ];
    }
}
