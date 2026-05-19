<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\IuranBulanan;
use App\Services\IuranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifikasiIuranController extends Controller
{
    public function index(Request $request): View
    {
        $iuran = IuranBulanan::with('warga')
            ->where('status', 'menunggu_verifikasi')
            ->orderBy('tanggal_bayar', 'desc')
            ->paginate(12);

        return view('petugas.iuran-index', compact('iuran'));
    }

    public function setujui(Request $request, IuranBulanan $iuran, IuranService $service): RedirectResponse
    {
        abort_unless($iuran->status === 'menunggu_verifikasi', 422);
        $service->verifikasi($iuran, $request->user());

        return back()->with('success', 'Pembayaran iuran '.$iuran->periode_label.' atas nama '.$iuran->warga->name.' telah diverifikasi.');
    }

    public function tolak(Request $request, IuranBulanan $iuran, IuranService $service): RedirectResponse
    {
        $request->validate(['alasan' => ['required', 'string', 'min:5', 'max:500']]);
        abort_unless($iuran->status === 'menunggu_verifikasi', 422);
        $service->tolak($iuran, $request->user(), $request->alasan);

        return back()->with('success', 'Pembayaran ditolak. Warga telah diberi notifikasi.');
    }
}
