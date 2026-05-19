<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanSampah;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $query = LaporanSampah::with('pelapor', 'petugas');

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

        $laporan = $query->latest('tanggal_lapor')->paginate(15)->withQueryString();

        $statusOptions = ['' => 'Semua', 'dikirim' => 'Dikirim', 'diterima' => 'Diterima', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
        $jenisOptions = ['' => 'Semua jenis', 'organik' => 'Organik', 'anorganik' => 'Anorganik', 'b3' => 'B3', 'campuran' => 'Campuran'];

        return view('admin.laporan-index', compact('laporan', 'statusOptions', 'jenisOptions'));
    }

    public function show(LaporanSampah $laporan): View
    {
        $laporan->load('pelapor', 'petugas');

        return view('admin.laporan-detail', compact('laporan'));
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'laporan-sampah-'.now()->format('Ymd-His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->streamDownload(function () use ($request) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['Kode', 'Tanggal Lapor', 'Pelapor', 'Jenis Sampah', 'Lokasi', 'Status', 'Petugas', 'Keterangan']);

            $query = LaporanSampah::with('pelapor', 'petugas')->latest('tanggal_lapor');
            if ($status = $request->query('status')) {
                $query->where('status', $status);
            }
            if ($from = $request->query('from')) {
                $query->whereDate('tanggal_lapor', '>=', $from);
            }
            if ($to = $request->query('to')) {
                $query->whereDate('tanggal_lapor', '<=', $to);
            }

            $query->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->kode_laporan,
                        $r->tanggal_lapor?->format('Y-m-d H:i'),
                        $r->pelapor?->name,
                        $r->jenis_label,
                        $r->lokasi_text,
                        $r->status_label,
                        $r->petugas?->name ?? '-',
                        $r->keterangan,
                    ]);
                }
            });

            fclose($out);
        }, $filename, $headers);
    }
}
