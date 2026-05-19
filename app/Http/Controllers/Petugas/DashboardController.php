<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\IuranBulanan;
use App\Models\LaporanSampah;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stat = [
            'baru' => LaporanSampah::baru()->count(),
            'diproses' => LaporanSampah::whereIn('status', ['diterima', 'diproses'])->count(),
            'selesai_hari_ini' => LaporanSampah::where('status', 'selesai')->whereDate('tanggal_selesai', today())->count(),
            'menunggu_verifikasi' => IuranBulanan::menunggu()->count(),
        ];

        $laporanTerbaru = LaporanSampah::with('pelapor')
            ->latest('tanggal_lapor')
            ->take(5)
            ->get();

        // 7-hari terakhir chart
        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->copy()->subDays($i)->startOfDay();
            $labels[] = $tgl->translatedFormat('D, d M');
            $values[] = LaporanSampah::whereDate('tanggal_lapor', $tgl)->count();
        }

        return view('petugas.dashboard', compact('stat', 'laporanTerbaru', 'labels', 'values'));
    }
}
