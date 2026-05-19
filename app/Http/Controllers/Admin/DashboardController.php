<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IuranBulanan;
use App\Models\LaporanSampah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $bulanIni = now()->startOfMonth();
        $bulanLalu = now()->copy()->subMonth()->startOfMonth();

        $totalWargaAktif = User::role('warga')->where('status_akun', 'aktif')->count();

        $laporanBulanIni = LaporanSampah::where('tanggal_lapor', '>=', $bulanIni)->count();
        $laporanBulanLalu = LaporanSampah::whereBetween('tanggal_lapor', [$bulanLalu, $bulanIni])->count();
        $trendLaporan = $this->trend($laporanBulanIni, $laporanBulanLalu);

        $iuranTerkumpul = IuranBulanan::where('status', 'lunas')
            ->where('tanggal_verifikasi', '>=', $bulanIni)
            ->sum('nominal');
        $iuranLalu = IuranBulanan::where('status', 'lunas')
            ->whereBetween('tanggal_verifikasi', [$bulanLalu, $bulanIni])
            ->sum('nominal');
        $trendIuran = $this->trend($iuranTerkumpul, $iuranLalu);

        $totalLaporanBulanIni = max($laporanBulanIni, 1);
        $selesaiBulanIni = LaporanSampah::where('status', 'selesai')->where('tanggal_lapor', '>=', $bulanIni)->count();
        $tingkatPenyelesaian = round(($selesaiBulanIni / $totalLaporanBulanIni) * 100);

        // 6 bulan chart
        $iuranLabels = [];
        $iuranValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $tgl = now()->copy()->subMonths($i);
            $iuranLabels[] = $tgl->translatedFormat('M Y');
            $iuranValues[] = (int) IuranBulanan::where('status', 'lunas')
                ->whereYear('tanggal_verifikasi', $tgl->year)
                ->whereMonth('tanggal_verifikasi', $tgl->month)
                ->sum('nominal');
        }

        // doughnut: distribusi status laporan bulan ini
        $statusDist = [];
        foreach (['dikirim', 'diterima', 'diproses', 'selesai', 'ditolak'] as $s) {
            $statusDist[$s] = LaporanSampah::where('status', $s)
                ->where('tanggal_lapor', '>=', $bulanIni)
                ->count();
        }

        $aktivitasTerbaru = LaporanSampah::with('pelapor')
            ->latest('updated_at')
            ->take(8)
            ->get();

        $pendingApproval = User::role('warga')->where('status_akun', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalWargaAktif', 'laporanBulanIni', 'trendLaporan',
            'iuranTerkumpul', 'trendIuran', 'tingkatPenyelesaian',
            'iuranLabels', 'iuranValues', 'statusDist', 'aktivitasTerbaru',
            'pendingApproval'
        ));
    }

    private function trend(int|float $curr, int|float $prev): array
    {
        if ($prev <= 0) {
            return ['direction' => 'up', 'label' => 'baru'];
        }
        $delta = (($curr - $prev) / $prev) * 100;

        return [
            'direction' => $delta >= 0 ? 'up' : 'down',
            'label' => ($delta >= 0 ? '▲ ' : '▼ ').number_format(abs($delta), 1).'% dari bulan lalu',
        ];
    }
}
