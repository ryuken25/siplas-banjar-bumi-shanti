<?php

namespace Database\Seeders;

use App\Models\LaporanSampah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LaporanSampahSeeder extends Seeder
{
    public function run(): void
    {
        $this->pastikanPlaceholder();

        $wargaAktif = User::role('warga')->where('status_akun', 'aktif')->get();
        $petugas = User::role('petugas')->get();

        if ($wargaAktif->isEmpty()) {
            return;
        }

        $statuses = ['dikirim', 'diterima', 'diproses', 'selesai', 'ditolak'];
        $jenisList = ['organik', 'anorganik', 'b3', 'campuran'];
        $lokasiList = [
            'Gang Mawar, depan Pura Dalem',
            'Jalan Tukad Yeh Aya, dekat balai banjar',
            'Gang Melati, sebelah TPS sementara',
            'Jalan Imam Bonjol Gg. 5',
            'Lapangan Banjar Bumi Shanti',
            'Pinggir sungai Tukad Badung',
            'Pertigaan Gang Anggrek',
            'Depan SD Negeri 8 Dauh Puri Kelod',
        ];
        $keteranganList = [
            'Tumpukan sampah sudah menggunung sejak 3 hari, mulai berbau.',
            'Sampah berserakan di pinggir jalan menutupi trotoar.',
            'Bekas hajatan, banyak kardus dan sampah plastik.',
            'Sampah dapur menumpuk di pojok gang.',
            'Sampah daun kering dan ranting hasil pemangkasan pohon.',
            'Botol bekas, popok, dan sampah anorganik bercampur.',
        ];

        for ($i = 0; $i < 30; $i++) {
            $status = $statuses[array_rand($statuses)];
            $jenis = $jenisList[array_rand($jenisList)];
            $tanggalLapor = now()->subDays(rand(0, 30))->subHours(rand(0, 23));

            $laporan = LaporanSampah::create([
                'user_id' => $wargaAktif->random()->id,
                'jenis_sampah' => $jenis,
                'lokasi_text' => $lokasiList[array_rand($lokasiList)],
                'latitude' => -8.6705 + (rand(-50, 50) / 10000),
                'longitude' => 115.2126 + (rand(-50, 50) / 10000),
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'foto' => 'laporan/placeholder.jpg',
                'status' => $status,
                'tanggal_lapor' => $tanggalLapor,
            ]);

            if (in_array($status, ['diterima', 'diproses', 'selesai', 'ditolak']) && $petugas->isNotEmpty()) {
                $laporan->petugas_id = $petugas->random()->id;
                $laporan->tanggal_diterima = (clone $tanggalLapor)->addHours(rand(1, 12));
            }
            if (in_array($status, ['diproses', 'selesai'])) {
                $laporan->tanggal_diproses = (clone $tanggalLapor)->addHours(rand(13, 24));
            }
            if ($status === 'selesai') {
                $laporan->tanggal_selesai = (clone $tanggalLapor)->addDays(rand(1, 3));
            }
            if ($status === 'ditolak') {
                $laporan->alasan_tolak = 'Lokasi di luar wilayah Banjar Bumi Shanti.';
            }
            $laporan->save();
        }
    }

    private function pastikanPlaceholder(): void
    {
        $disk = Storage::disk('public');
        $disk->makeDirectory('laporan');
        $path = 'laporan/placeholder.jpg';

        if ($disk->exists($path)) {
            return;
        }

        // Build a simple gradient JPG via GD so we don't depend on the internet.
        if (function_exists('imagecreatetruecolor')) {
            $w = 800;
            $h = 600;
            $img = imagecreatetruecolor($w, $h);
            $start = [16, 185, 129];
            $end = [4, 120, 87];
            for ($y = 0; $y < $h; $y++) {
                $r = (int) ($start[0] + ($end[0] - $start[0]) * ($y / $h));
                $g = (int) ($start[1] + ($end[1] - $start[1]) * ($y / $h));
                $b = (int) ($start[2] + ($end[2] - $start[2]) * ($y / $h));
                imageline($img, 0, $y, $w, $y, imagecolorallocate($img, $r, $g, $b));
            }
            $white = imagecolorallocatealpha($img, 255, 255, 255, 40);
            imagefilledellipse($img, 200, 450, 700, 700, $white);
            $textColor = imagecolorallocate($img, 255, 255, 255);
            imagestring($img, 5, 280, 280, 'SIPLAS', $textColor);
            imagestring($img, 4, 220, 310, 'Foto Laporan Sampah (demo)', $textColor);

            ob_start();
            imagejpeg($img, null, 82);
            $bytes = ob_get_clean();
            imagedestroy($img);

            $disk->put($path, $bytes);

            return;
        }

        // Fallback: 1x1 transparent JPG bytes (no GD)
        $disk->put($path, base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wBDABALDA4MChAODQ4SERATGCgaGBYWGDEjJR0oOjM9PDkzODdASFxOQERXRTc4UG1RV19iZ2hnPk1xeXBkeFxlZ2P/2wBDARESEhgVGC8aGi9jQjhCY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2P/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAj/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AL+AAH//Z'));
    }
}
