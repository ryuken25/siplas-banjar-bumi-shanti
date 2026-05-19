<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaporanRequest;
use App\Models\LaporanSampah;
use App\Services\LaporanService;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function create(): View
    {
        return view('warga.lapor-sampah');
    }

    public function store(StoreLaporanRequest $request, UploadService $upload, LaporanService $service): RedirectResponse
    {
        $path = $upload->storeImage($request->file('foto'), 'laporan');

        $laporan = LaporanSampah::create([
            'user_id' => $request->user()->id,
            'jenis_sampah' => $request->jenis_sampah,
            'lokasi_text' => $request->lokasi_text,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'keterangan' => $request->keterangan,
            'foto' => $path,
            'status' => 'dikirim',
        ]);

        $service->notifyPetugasOfNewLaporan($laporan);

        return redirect()->route('warga.laporan.index')->with('success', 'Laporan Anda telah berhasil dikirim. Kode laporan: '.$laporan->kode_laporan);
    }

    public function index(Request $request): View
    {
        $query = LaporanSampah::where('user_id', $request->user()->id);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $laporan = $query->latest('tanggal_lapor')->paginate(9)->withQueryString();

        $statusOptions = [
            '' => 'Semua',
            'dikirim' => 'Dikirim',
            'diterima' => 'Diterima',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];

        return view('warga.laporan-saya', compact('laporan', 'statusOptions'));
    }

    public function show(Request $request, LaporanSampah $laporan): View
    {
        abort_unless($laporan->user_id === $request->user()->id, 403);

        $laporan->load('petugas');

        return view('warga.laporan-detail', compact('laporan'));
    }
}
