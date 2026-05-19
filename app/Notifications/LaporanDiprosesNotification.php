<?php

namespace App\Notifications;

use App\Models\LaporanSampah;

class LaporanDiprosesNotification extends BaseSiplasNotification
{
    public function __construct(public LaporanSampah $laporan) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Laporan Anda Sedang Diproses',
            'body' => 'Laporan '.$this->laporan->kode_laporan.' sedang ditangani oleh petugas. Mohon tunggu informasi selanjutnya.',
            'icon' => 'warning',
            'url' => route('warga.laporan.show', $this->laporan->id),
            'laporan_id' => $this->laporan->id,
            'kode' => $this->laporan->kode_laporan,
        ];
    }
}
