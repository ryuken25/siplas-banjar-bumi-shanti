<?php

namespace App\Notifications;

use App\Models\User;

class PendaftaranBaruUntukAdminNotification extends BaseSiplasNotification
{
    public function __construct(public User $user) {}

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pendaftaran Warga Baru',
            'body' => $this->user->name.' telah mendaftar dan menunggu persetujuan akun.',
            'icon' => 'info',
            'url' => route('admin.pengguna.index', ['tab' => 'pending']),
            'user_id' => $this->user->id,
        ];
    }
}
