<?php

namespace App\Notifications;

use App\Models\IuranBulanan;

class IuranTerverifikasiNotification extends BaseSiplasNotification
{
    public function __construct(public IuranBulanan $iuran) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pembayaran Iuran Terverifikasi',
            'body' => 'Pembayaran iuran '.$this->iuran->periode_label.' ('.$this->iuran->kode_tagihan.') telah diverifikasi dan dinyatakan LUNAS. Terima kasih.',
            'icon' => 'success',
            'url' => route('warga.iuran.index'),
            'iuran_id' => $this->iuran->id,
            'kode' => $this->iuran->kode_tagihan,
        ];
    }
}
