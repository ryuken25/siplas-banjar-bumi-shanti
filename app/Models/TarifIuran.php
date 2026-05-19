<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifIuran extends Model
{
    use HasFactory;

    protected $table = 'tarif_iuran';

    protected $fillable = [
        'nominal',
        'periode_mulai',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'nominal' => 'integer',
        'periode_mulai' => 'date',
        'aktif' => 'boolean',
    ];

    public static function aktif(): ?self
    {
        return static::where('aktif', true)->latest('periode_mulai')->first();
    }

    public function getNominalFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->nominal, 0, ',', '.');
    }
}
