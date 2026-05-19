<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IuranBulanan;
use App\Services\IuranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IuranController extends Controller
{
    public function index(Request $request): View
    {
        $tahun = (int) $request->query('tahun', now()->year);
        $bulan = $request->query('bulan');

        $query = IuranBulanan::with('warga', 'verifikator')->whereYear('created_at', $tahun);
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $iuran = $query->orderByDesc('tahun')->orderByDesc('bulan')->paginate(20)->withQueryString();

        $ringkasan = [
            'lunas' => IuranBulanan::where('status', 'lunas')->whereYear('created_at', $tahun)->when($bulan, fn ($q) => $q->where('bulan', $bulan))->count(),
            'menunggu' => IuranBulanan::where('status', 'menunggu_verifikasi')->whereYear('created_at', $tahun)->when($bulan, fn ($q) => $q->where('bulan', $bulan))->count(),
            'belum' => IuranBulanan::where('status', 'belum_bayar')->whereYear('created_at', $tahun)->when($bulan, fn ($q) => $q->where('bulan', $bulan))->count(),
            'total_nominal' => IuranBulanan::where('status', 'lunas')->whereYear('created_at', $tahun)->when($bulan, fn ($q) => $q->where('bulan', $bulan))->sum('nominal'),
        ];

        return view('admin.iuran-index', compact('iuran', 'tahun', 'bulan', 'ringkasan'));
    }

    public function generate(Request $request, IuranService $service): RedirectResponse
    {
        $data = $request->validate([
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2020,2099'],
        ]);

        $count = $service->generateTagihanBulan($data['bulan'], $data['tahun']);
        $periode = IuranBulanan::BULAN_LABEL[$data['bulan']].' '.$data['tahun'];

        return back()->with('success', "Generate tagihan {$periode} berhasil. {$count} tagihan baru dibuat.");
    }
}
