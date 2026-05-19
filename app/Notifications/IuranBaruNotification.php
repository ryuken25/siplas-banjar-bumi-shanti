<?php

namespace App\Notifications;

use App\Models\IuranBulanan;

class IuranBaruNotification extends BaseSiplasNotification
{
    public function __construct(public IuranBulanan $iuran) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Tagihan Iuran Baru',
            'body' => 'Tagihan iuran sampah periode '.$this->iuran->periode_label.' sebesar '.$this->iuran->nominal_formatted.' telah diterbitkan.',
            'icon' => 'info',
            'url' => route('warga.iuran.index'),
            'iuran_id' => $this->iuran->id,
            'kode' => $this->iuran->kode_tagihan,
        ];
    }
}
