<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\IuranBulanan;
use App\Models\LaporanSampah;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $laporanTerbaru = LaporanSampah::where('user_id', $user->id)
            ->latest('tanggal_lapor')
            ->take(3)
            ->get();

        $tagihanBelum = IuranBulanan::where('user_id', $user->id)
            ->whereIn('status', ['belum_bayar', 'ditolak'])
            ->orderByDesc('tahun')->orderByDesc('bulan')
            ->take(3)
            ->get();

        $stat = [
            'laporan_aktif' => LaporanSampah::where('user_id', $user->id)->aktif()->count(),
            'laporan_selesai' => LaporanSampah::where('user_id', $user->id)->selesai()->count(),
            'tagihan_belum' => IuranBulanan::where('user_id', $user->id)->where('status', 'belum_bayar')->count(),
            'tagihan_lunas' => IuranBulanan::where('user_id', $user->id)->where('status', 'lunas')->count(),
        ];

        return view('warga.dashboard', compact('laporanTerbaru', 'tagihanBelum', 'stat'));
    }
}
