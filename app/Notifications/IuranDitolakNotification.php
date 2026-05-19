<?php

namespace App\Notifications;

use App\Models\IuranBulanan;

class IuranDitolakNotification extends BaseSiplasNotification
{
    public function __construct(public IuranBulanan $iuran) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pembayaran Iuran Ditolak',
            'body' => 'Pembayaran iuran '.$this->iuran->periode_label.' ditolak. Alasan: '.($this->iuran->alasan_tolak ?? '-').'. Silakan upload ulang bukti pembayaran.',
            'icon' => 'danger',
            'url' => route('warga.iuran.index'),
            'iuran_id' => $this->iuran->id,
            'kode' => $this->iuran->kode_tagihan,
        ];
    }
}
