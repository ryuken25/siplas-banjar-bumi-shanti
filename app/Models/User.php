<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'no_kk',
        'no_telp',
        'alamat',
        'foto_profil',
        'status_akun',
        'alasan_tolak_akun',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(LaporanSampah::class, 'user_id');
    }

    public function laporanDitangani(): HasMany
    {
        return $this->hasMany(LaporanSampah::class, 'petugas_id');
    }

    public function iuran(): HasMany
    {
        return $this->hasMany(IuranBulanan::class, 'user_id');
    }

    public function iuranDiverifikasi(): HasMany
    {
        return $this->hasMany(IuranBulanan::class, 'verifikator_id');
    }

    public function isAktif(): bool
    {
        return $this->status_akun === 'aktif';
    }

    public function isPending(): bool
    {
        return $this->status_akun === 'pending';
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isPetugas(): bool
    {
        return $this->hasRole('petugas');
    }

    public function isWarga(): bool
    {
        return $this->hasRole('warga');
    }

    public function getFotoProfilUrlAttribute(): string
    {
        if ($this->foto_profil && Storage::disk('public')->exists($this->foto_profil)) {
            return Storage::disk('public')->url($this->foto_profil);
        }

        return $this->getAvatarFallbackUrl();
    }

    public function getInisialAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->name ?? ''));
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $second = mb_substr($parts[count($parts) - 1] ?? '', 0, 1);

        return strtoupper($first.($parts && count($parts) > 1 ? $second : ''));
    }

    public function getAvatarColorAttribute(): string
    {
        $palette = ['#10B981', '#059669', '#34D399', '#F59E0B', '#0EA5E9', '#6366F1', '#EC4899', '#14B8A6'];
        $idx = abs(crc32($this->name ?? 'U')) % count($palette);

        return $palette[$idx];
    }

    private function getAvatarFallbackUrl(): string
    {
        $initials = urlencode($this->inisial ?: 'U');
        $color = ltrim($this->avatar_color, '#');

        return "https://ui-avatars.com/api/?name={$initials}&background={$color}&color=ffffff&bold=true&format=svg";
    }

    public function getRoleLabelAttribute(): string
    {
        return match (true) {
            $this->hasRole('admin') => 'Admin',
            $this->hasRole('petugas') => 'Petugas',
            $this->hasRole('warga') => 'Warga',
            default => 'Pengguna',
        };
    }
}
