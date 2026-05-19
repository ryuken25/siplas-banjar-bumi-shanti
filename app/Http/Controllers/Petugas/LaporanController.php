<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\LaporanSampah;
use App\Services\LaporanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $query = LaporanSampah::query()->with('pelapor', 'petugas');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($jenis = $request->query('jenis')) {
            $query->where('jenis_sampah', $jenis);
        }
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_laporan', 'like', "%{$search}%")
                    ->orWhereHas('pelapor', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }
        if ($from = $request->query('from')) {
            $query->whereDate('tanggal_lapor', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $query->whereDate('tanggal_lapor', '<=', $to);
        }

        $sort = $request->query('sort', 'newest');
        $query->orderBy('tanggal_lapor', $sort === 'oldest' ? 'asc' : 'desc');

        $laporan = $query->paginate(12)->withQueryString();

        $statusOptions = [
            '' => 'Semua', 'dikirim' => 'Dikirim', 'diterima' => 'Diterima',
            'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak',
        ];
        $jenisOptions = [
            '' => 'Semua jenis', 'organik' => 'Organik', 'anorganik' => 'Anorganik',
            'b3' => 'B3', 'campuran' => 'Campuran',
        ];

        return view('petugas.laporan-index', compact('laporan', 'statusOptions', 'jenisOptions'));
    }

    public function show(LaporanSampah $laporan): View
    {
        $laporan->load('pelapor', 'petugas');

        return view('petugas.laporan-detail', compact('laporan'));
    }

    public function terima(Request $request, LaporanSampah $laporan, LaporanService $service): RedirectResponse
    {
        abort_unless(in_array($laporan->status, ['dikirim']), 422, 'Status laporan tidak valid untuk aksi ini.');
        $service->terima($laporan, $request->user());

        return back()->with('success', 'Laporan telah ditandai sebagai diterima.');
    }

    public function proses(Request $request, LaporanSampah $laporan, LaporanService $service): RedirectResponse
    {
        abort_unless(in_array($laporan->status, ['dikirim', 'diterima']), 422, 'Status laporan tidak valid untuk aksi ini.');
        $service->proses($laporan, $request->user());

        return back()->with('success', 'Laporan sedang diproses. Warga telah diberi notifikasi.');
    }

    public function selesai(Request $request, LaporanSampah $laporan, LaporanService $service): RedirectResponse
    {
        abort_unless(in_array($laporan->status, ['diterima', 'diproses']), 422, 'Status laporan tidak valid untuk aksi ini.');
        $service->selesai($laporan, $request->user());

        return back()->with('success', 'Laporan telah ditandai selesai. Terima kasih.');
    }

    public function tolak(Request $request, LaporanSampah $laporan, LaporanService $service): RedirectResponse
    {
        $request->validate(['alasan' => ['required', 'string', 'min:5', 'max:500']]);
        abort_if($laporan->status === 'selesai', 422, 'Laporan yang sudah selesai tidak dapat ditolak.');
        $service->tolak($laporan, $request->user(), $request->alasan);

        return back()->with('success', 'Laporan ditolak dan warga telah diberi notifikasi.');
    }
}
