<?php

namespace App\Notifications;

class AkunDiapproveNotification extends BaseSiplasNotification
{
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Selamat Datang di SIPLAS',
            'body' => 'Akun Anda telah disetujui oleh admin. Sekarang Anda dapat login dan mulai menggunakan layanan SIPLAS Banjar Bumi Shanti.',
            'icon' => 'success',
            'url' => route('warga.dashboard'),
        ];
    }
}
