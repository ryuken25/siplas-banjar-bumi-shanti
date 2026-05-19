<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Http\Requests\BayarIuranRequest;
use App\Models\IuranBulanan;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IuranController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $aktif = IuranBulanan::where('user_id', $user->id)
            ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi', 'ditolak'])
            ->orderByDesc('tahun')->orderByDesc('bulan')
            ->get();

        $riwayat = IuranBulanan::where('user_id', $user->id)
            ->where('status', 'lunas')
            ->orderByDesc('tahun')->orderByDesc('bulan')
            ->paginate(10);

        return view('warga.iuran', compact('aktif', 'riwayat'));
    }

    public function bayar(BayarIuranRequest $request, IuranBulanan $iuran, UploadService $upload): RedirectResponse
    {
        abort_unless($iuran->user_id === $request->user()->id, 403);
        abort_if($iuran->status === 'lunas', 422, 'Tagihan sudah lunas.');

        $data = [
            'metode_bayar' => $request->metode_bayar,
            'tanggal_bayar' => now(),
            'status' => 'menunggu_verifikasi',
            'alasan_tolak' => null,
        ];

        if ($request->metode_bayar === 'transfer' && $request->hasFile('bukti_bayar')) {
            if ($iuran->bukti_bayar) {
                $upload->delete($iuran->bukti_bayar);
            }
            $data['bukti_bayar'] = $upload->storeImage($request->file('bukti_bayar'), 'bukti-bayar');
        }

        $iuran->update($data);

        return back()->with('success', 'Pembayaran berhasil dikirim dan sedang menunggu verifikasi petugas.');
    }
}
