<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaporanSampah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan_sampah';

    protected $fillable = [
        'kode_laporan',
        'user_id',
        'jenis_sampah',
        'lokasi_text',
        'latitude',
        'longitude',
        'keterangan',
        'foto',
        'status',
        'petugas_id',
        'alasan_tolak',
        'tanggal_lapor',
        'tanggal_diterima',
        'tanggal_diproses',
        'tanggal_selesai',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'tanggal_lapor' => 'datetime',
        'tanggal_diterima' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public const STATUS_LIST = ['dikirim', 'diterima', 'diproses', 'selesai', 'ditolak'];

    public const JENIS_LIST = ['organik', 'anorganik', 'b3', 'campuran'];

    protected static function booted(): void
    {
        static::creating(function (self $laporan) {
            if (! $laporan->kode_laporan) {
                $laporan->kode_laporan = static::generateKode();
            }
            if (! $laporan->tanggal_lapor) {
                $laporan->tanggal_lapor = now();
            }
        });
    }

    public static function generateKode(): string
    {
        $prefix = 'LPS-'.now()->format('Ymd').'-';
        do {
            $kode = $prefix.strtoupper(Str::random(4));
        } while (static::where('kode_laporan', $kode)->exists());

        return $kode;
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        return Storage::disk('public')->url($this->foto);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dikirim' => 'Dikirim',
            'diterima' => 'Diterima',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_sampah) {
            'organik' => 'Organik',
            'anorganik' => 'Anorganik',
            'b3' => 'Bahan Berbahaya & Beracun (B3)',
            'campuran' => 'Campuran',
            default => ucfirst($this->jenis_sampah),
        };
    }

    public function getJenisIconAttribute(): string
    {
        return match ($this->jenis_sampah) {
            'organik' => '🥗',
            'anorganik' => '♻️',
            'b3' => '☣️',
            'campuran' => '🗑️',
            default => '📦',
        };
    }

    public function scopeBaru($query)
    {
        return $query->where('status', 'dikirim');
    }

    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['dikirim', 'diterima', 'diproses']);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }
}
