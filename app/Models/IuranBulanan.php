<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IuranBulanan extends Model
{
    use HasFactory;

    protected $table = 'iuran_bulanan';

    protected $fillable = [
        'kode_tagihan',
        'user_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'metode_bayar',
        'bukti_bayar',
        'tanggal_bayar',
        'verifikator_id',
        'tanggal_verifikasi',
        'alasan_tolak',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'nominal' => 'integer',
        'tanggal_bayar' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    public const STATUS_LIST = ['belum_bayar', 'menunggu_verifikasi', 'lunas', 'ditolak'];

    public const BULAN_LABEL = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $iuran) {
            if (! $iuran->kode_tagihan) {
                $iuran->kode_tagihan = static::generateKode($iuran->bulan, $iuran->tahun);
            }
        });
    }

    public static function generateKode(int $bulan, int $tahun): string
    {
        $prefix = 'TGH-'.sprintf('%04d%02d', $tahun, $bulan).'-';
        do {
            $kode = $prefix.strtoupper(Str::random(4));
        } while (static::where('kode_tagihan', $kode)->exists());

        return $kode;
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    public function getPeriodeLabelAttribute(): string
    {
        return (self::BULAN_LABEL[$this->bulan] ?? '?').' '.$this->tahun;
    }

    public function getNominalFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->nominal, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'belum_bayar' => 'Belum Bayar',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getBuktiBayarUrlAttribute(): ?string
    {
        if (! $this->bukti_bayar) {
            return null;
        }

        return Storage::disk('public')->url($this->bukti_bayar);
    }

    public function getTanggalJatuhTempoAttribute(): Carbon
    {
        return Carbon::create($this->tahun, $this->bulan, 1)->endOfMonth();
    }

    public function scopeBelum($query)
    {
        return $query->where('status', 'belum_bayar');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu_verifikasi');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }
}
