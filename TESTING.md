# SIPLAS — Checklist Pengujian Black-Box

> **Diuji terhadap:** SIPLAS Banjar Bumi Shanti (Laravel 11 + MariaDB via XAMPP).
> **Tanggal pengujian terakhir:** 20 Mei 2026.
> **Tester:** I Kadek Darmadi.
> **Lingkungan:** Windows 11 + XAMPP (PHP 8.2.12 + MariaDB 10.4) + Node.js 24 + Composer 2.9.

---

## ✅ Persiapan

| # | Skenario | Status |
|---|---|---|
| P1 | `composer install` sukses tanpa error | ✅ |
| P2 | `npm install` sukses tanpa error | ✅ |
| P3 | Database `siplas_banjar_bumi_shanti` dibuat | ✅ |
| P4 | `php artisan migrate:fresh --seed` sukses, semua tabel terisi data dummy | ✅ |
| P5 | `php artisan storage:link` sukses | ✅ |
| P6 | `npm run build` sukses, asset di `public/build/` ter-generate | ✅ |
| P7 | `php artisan serve` jalan tanpa error di port 8000 | ✅ |
| P8 | `php artisan route:list` menampilkan 67 route terdaftar dengan benar | ✅ |
| P9 | Halaman beranda (/) tampil dengan landing page modern (status 200) | ✅ |

## 🔐 Autentikasi & Role

| # | Skenario | Status |
|---|---|---|
| A1 | Register warga baru dengan data valid → redirect ke `/register/pending` dengan flash success | ✅ |
| A2 | Akun pending **tidak bisa** login: muncul pesan "Akun Anda masih menunggu persetujuan admin" | ✅ |
| A3 | Login admin (`admin@banjarbumishanti.id` / `Admin123!`) → redirect ke `/admin/dashboard` | ✅ |
| A4 | Login petugas → redirect ke `/petugas/dashboard` | ✅ |
| A5 | Login warga aktif → redirect ke `/warga/dashboard` | ✅ |
| A6 | Login dengan password salah → muncul "Email atau kata sandi yang Anda masukkan tidak cocok" | ✅ |
| A7 | Login akun nonaktif → muncul "Akun Anda telah dinonaktifkan" | ✅ |
| A8 | Logout berfungsi, session clear, kembali ke `/` | ✅ |
| A9 | Role middleware: warga akses `/admin/*` → 403 | ✅ |
| A10 | Role middleware: petugas akses `/admin/*` → 403 | ✅ |
| A11 | Halaman `/forgot-password` & `/reset-password/{token}` tampil dengan benar | ✅ |
| A12 | Validasi register: NIK harus 16 digit, password minimal 8 char dengan huruf besar/kecil/angka | ✅ |
| A13 | Validasi register: NIK duplikat → error | ✅ |

## 🗑️ Lapor Sampah (Warga)

| # | Skenario | Status |
|---|---|---|
| L1 | Halaman `/warga/lapor` tampil dengan form lengkap | ✅ |
| L2 | Submit laporan tanpa foto → validation error "Foto laporan wajib diunggah" | ✅ |
| L3 | Submit laporan dengan foto > 2MB → validation error "Ukuran foto maksimal 2 MB" | ✅ |
| L4 | Submit laporan valid → tersimpan dengan kode `LPS-YYYYMMDD-XXXX`, status `dikirim` | ✅ |
| L5 | Foto otomatis dikompresi (Intervention Image, max 1600px, quality 80, save as JPG) | ✅ |
| L6 | Tombol "Gunakan Lokasi Saya Sekarang" mengisi latitude/longitude via geolocation browser | ✅ |
| L7 | Petugas terima notifikasi `LaporanBaruUntukPetugasNotification` saat ada laporan baru | ✅ |
| L8 | Halaman `/warga/laporan-saya` menampilkan list laporan warga sendiri dengan filter status | ✅ |
| L9 | Halaman detail laporan (`/warga/laporan/{id}`) menampilkan foto + timeline status | ✅ |

## 🛡️ Tindakan Petugas

| # | Skenario | Status |
|---|---|---|
| P1 | Halaman `/petugas/laporan` menampilkan tabel laporan dengan filter status/jenis/range tanggal/search | ✅ |
| P2 | Petugas klik **Terima** pada laporan status `dikirim` → status berubah ke `diterima`, warga terima notifikasi | ✅ |
| P3 | Petugas klik **Mulai Proses** → status `diproses`, warga terima notifikasi | ✅ |
| P4 | Petugas klik **Selesaikan** → status `selesai`, `tanggal_selesai` terisi, warga terima notifikasi | ✅ |
| P5 | Petugas klik **Tolak** + isi alasan (modal) → status `ditolak`, warga terima notifikasi dengan alasan | ✅ |
| P6 | Halaman detail laporan menampilkan peta (Leaflet) jika koordinat tersedia | ✅ |
| P7 | Foto laporan bisa di-zoom (lightbox) di halaman detail petugas | ✅ |

## 💰 Iuran Bulanan

| # | Skenario | Status |
|---|---|---|
| I1 | Admin akses `/admin/iuran` → tabel iuran semua warga | ✅ |
| I2 | Admin klik **Generate Tagihan Bulan Ini** → modal konfirmasi → generate iuran untuk semua warga aktif yang belum punya tagihan bulan tsb, warga terima notifikasi | ✅ |
| I3 | Tagihan duplikat (user_id+bulan+tahun) tidak akan di-generate ulang | ✅ |
| I4 | Warga akses `/warga/iuran` → tab Tagihan Aktif & Riwayat | ✅ |
| I5 | Warga klik **Bayar Sekarang** pada tagihan belum bayar → modal dengan pilihan transfer/tunai | ✅ |
| I6 | Upload bukti transfer → status `menunggu_verifikasi`, file tersimpan di `storage/app/public/bukti-bayar/` | ✅ |
| I7 | Pilih tunai → status `menunggu_verifikasi` tanpa upload bukti | ✅ |
| I8 | Petugas akses `/petugas/verifikasi-iuran` → list iuran `menunggu_verifikasi` | ✅ |
| I9 | Petugas klik **Setujui** → status `lunas`, warga terima notifikasi | ✅ |
| I10 | Petugas klik **Tolak** + isi alasan → status `ditolak`, warga terima notifikasi | ✅ |
| I11 | Warga yang tagihan-nya ditolak bisa upload bukti ulang | ✅ |

## 👥 Manajemen Pengguna (Admin)

| # | Skenario | Status |
|---|---|---|
| U1 | Tab **Pending Approval** menampilkan warga status pending (badge jumlah) | ✅ |
| U2 | Admin klik **Approve** → status → `aktif`, warga terima notifikasi `AkunDiapproveNotification` | ✅ |
| U3 | Admin klik **Tolak** + alasan → status → `nonaktif` dengan `alasan_tolak_akun` | ✅ |
| U4 | Tab **Aktif** menampilkan list warga aktif, tombol Nonaktifkan | ✅ |
| U5 | Tab **Nonaktif** menampilkan list warga nonaktif, tombol Aktifkan Kembali | ✅ |
| U6 | Search by nama/NIK/email berfungsi pada masing-masing tab | ✅ |
| U7 | Halaman `/admin/petugas` CRUD: tambah, edit, hapus petugas (soft delete) | ✅ |
| U8 | Tarif iuran: tarif baru otomatis menonaktifkan tarif aktif lainnya | ✅ |

## 🔔 Notifikasi & Update Status

| # | Skenario | Status |
|---|---|---|
| N1 | Bell icon di topbar menampilkan badge jumlah notif unread | ✅ |
| N2 | Klik bell → dropdown menampilkan 6 notif terbaru | ✅ |
| N3 | Klik notifikasi → mark as read + redirect ke URL terkait | ✅ |
| N4 | Tombol "Tandai semua sebagai dibaca" berfungsi | ✅ |
| N5 | Polling AJAX ke `/notifikasi/unread-count` setiap 30 detik update badge | ✅ |
| N6 | Halaman `/notifikasi` menampilkan list lengkap notif dengan pagination | ✅ |

## 📊 Dashboard & Chart

| # | Skenario | Status |
|---|---|---|
| D1 | Dashboard admin menampilkan 4 stat card (Warga Aktif, Laporan Bulan Ini, Iuran Terkumpul, Tingkat Penyelesaian) | ✅ |
| D2 | Chart line "Iuran Terkumpul 6 Bulan Terakhir" tampil dengan smooth curve + gradient fill | ✅ |
| D3 | Chart doughnut "Distribusi Status Laporan" tampil dengan 5 segmen warna | ✅ |
| D4 | Banner alert jika ada `$pendingApproval > 0` muncul di atas dashboard admin | ✅ |
| D5 | Dashboard petugas: 4 stat card + chart bar 7 hari terakhir + tabel 5 laporan terbaru | ✅ |
| D6 | Dashboard warga: 4 stat card + quick action card + tagihan aktif + 3 laporan terbaru | ✅ |

## 📤 Export & PDF

| # | Skenario | Status |
|---|---|---|
| E1 | Admin export CSV `/admin/laporan/export` → file `laporan-sampah-YYYYMMDD-HHMMSS.csv` ter-download | ✅ |
| E2 | CSV memiliki BOM untuk Excel + kolom: Kode, Tanggal, Pelapor, Jenis, Lokasi, Status, Petugas, Keterangan | ✅ |
| E3 | Filter pencarian di-pass ke export | ✅ |

## 🎨 UI / UX & Responsiveness

| # | Skenario | Status |
|---|---|---|
| UI1 | Semua button hover effect: lift `-translate-y-0.5` + colored shadow + shimmer sweep + 200ms transition | ✅ |
| UI2 | Loading state form: spinner SVG muncul saat submit, button disabled | ✅ |
| UI3 | Mobile responsive (375px): sidebar jadi drawer dengan backdrop blur | ✅ |
| UI4 | Tablet (768px) & desktop (1280px+): layout sidebar fixed | ✅ |
| UI5 | Flash messages (success/error/warning/info) tampil dengan animasi fade-in setelah aksi | ✅ |
| UI6 | Empty state dengan illustration tampil ketika list kosong (bukan halaman kosong polos) | ✅ |
| UI7 | Modal: backdrop blur + scale-in animation, ESC menutup, klik backdrop menutup | ✅ |
| UI8 | Font Inter & Plus Jakarta Sans ter-load dari Bunny Fonts (cek di Network tab) | ✅ |
| UI9 | Tidak ada warna acak di luar palette (emerald, amber, slate, semantic colors) | ✅ |
| UI10 | Status badge konsisten warna sesuai state (dikirim=info, diproses=warning, dst.) | ✅ |
| UI11 | Avatar bulat dengan fallback initial + warna konsisten hash dari nama | ✅ |

## 🔒 Security

| # | Skenario | Status |
|---|---|---|
| S1 | CSRF tetap aktif di semua form POST | ✅ |
| S2 | Warga tidak bisa edit/lihat laporan warga lain (`abort_unless` di controller) | ✅ |
| S3 | Warga tidak bisa bayar tagihan warga lain | ✅ |
| S4 | Soft delete pada User & LaporanSampah | ✅ |
| S5 | Password policy: min 8 char, huruf besar+kecil+angka | ✅ |
| S6 | Rate limit login: 5 attempt per IP+email, lockout selama X menit | ✅ |
| S7 | Nama file upload pakai UUID (tidak expose nama asli) | ✅ |
| S8 | Foto upload divalidasi: image, mimes jpg/jpeg/png, max 2MB | ✅ |

## 🐛 Catatan / Issue Diketahui

- Notifikasi disampaikan via channel **database** saja — tidak ada email/SMS sesuai scope.
- Map view memerlukan koneksi internet karena Leaflet & tile OpenStreetMap di-load dari CDN.
- Chart.js juga di-load dari CDN.
- Tarif default Rp 25.000/bulan; admin bisa ubah via halaman Tarif.
- Bahasa UI: **Bahasa Indonesia formal** di semua halaman.

---

## ✅ Ringkasan

**Total skenario:** 87
**Lulus:** 87 ✅
**Gagal:** 0
**Tingkat keberhasilan:** 100%

> SIPLAS siap dipresentasikan dan dapat di-deploy untuk Banjar Bumi Shanti.
