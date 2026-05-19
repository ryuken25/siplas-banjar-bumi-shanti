@props(['status' => '', 'size' => 'sm'])

@php
    $map = [
        // Laporan sampah
        'dikirim'   => ['variant' => 'info',    'label' => 'Dikirim'],
        'diterima'  => ['variant' => 'primary', 'label' => 'Diterima'],
        'diproses'  => ['variant' => 'warning', 'label' => 'Diproses'],
        'selesai'   => ['variant' => 'success', 'label' => 'Selesai'],
        'ditolak'   => ['variant' => 'danger',  'label' => 'Ditolak'],
        // Iuran
        'belum_bayar'         => ['variant' => 'neutral', 'label' => 'Belum Bayar'],
        'menunggu_verifikasi' => ['variant' => 'warning', 'label' => 'Menunggu Verifikasi'],
        'lunas'               => ['variant' => 'success', 'label' => 'Lunas'],
        // Akun
        'pending'   => ['variant' => 'warning', 'label' => 'Menunggu Persetujuan'],
        'aktif'     => ['variant' => 'success', 'label' => 'Aktif'],
        'nonaktif'  => ['variant' => 'danger',  'label' => 'Nonaktif'],
    ];
    $cfg = $map[$status] ?? ['variant' => 'neutral', 'label' => ucfirst($status)];
@endphp

<x-badge :variant="$cfg['variant']" :size="$size">{{ $cfg['label'] }}</x-badge>
