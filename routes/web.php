<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Petugas;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Warga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $u = Auth::user();

        return redirect(match (true) {
            $u->isAdmin() => route('admin.dashboard'),
            $u->isPetugas() => route('petugas.dashboard'),
            $u->isWarga() => route('warga.dashboard'),
            default => '/',
        });
    }

    return view('welcome');
})->name('welcome');

// Shared profile (Breeze default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications (shared by all roles)
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotifikasiController::class, 'unreadCount'])->name('unread.count');
        Route::post('/mark-all', [NotifikasiController::class, 'markAll'])->name('mark-all');
        Route::get('/open/{id}', [NotifikasiController::class, 'open'])->name('open');
    });
});

// ----- Warga -----
Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/dashboard', [Warga\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/lapor', [Warga\LaporanController::class, 'create'])->name('lapor.create');
    Route::post('/lapor', [Warga\LaporanController::class, 'store'])->name('lapor.store');
    Route::get('/laporan-saya', [Warga\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporan}', [Warga\LaporanController::class, 'show'])->name('laporan.show');

    Route::get('/iuran', [Warga\IuranController::class, 'index'])->name('iuran.index');
    Route::post('/iuran/{iuran}/bayar', [Warga\IuranController::class, 'bayar'])->name('iuran.bayar');
});

// ----- Petugas -----
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [Petugas\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/laporan', [Petugas\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporan}', [Petugas\LaporanController::class, 'show'])->name('laporan.show');
    Route::post('/laporan/{laporan}/terima', [Petugas\LaporanController::class, 'terima'])->name('laporan.terima');
    Route::post('/laporan/{laporan}/proses', [Petugas\LaporanController::class, 'proses'])->name('laporan.proses');
    Route::post('/laporan/{laporan}/selesai', [Petugas\LaporanController::class, 'selesai'])->name('laporan.selesai');
    Route::post('/laporan/{laporan}/tolak', [Petugas\LaporanController::class, 'tolak'])->name('laporan.tolak');

    Route::get('/verifikasi-iuran', [Petugas\VerifikasiIuranController::class, 'index'])->name('iuran.index');
    Route::post('/verifikasi-iuran/{iuran}/setujui', [Petugas\VerifikasiIuranController::class, 'setujui'])->name('iuran.setujui');
    Route::post('/verifikasi-iuran/{iuran}/tolak', [Petugas\VerifikasiIuranController::class, 'tolak'])->name('iuran.tolak');

    Route::get('/profil', [Petugas\ProfilController::class, 'edit'])->name('profil');
    Route::patch('/profil', [Petugas\ProfilController::class, 'update'])->name('profil.update');
});

// ----- Admin -----
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pengguna', [Admin\PenggunaController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna/{user}/approve', [Admin\PenggunaController::class, 'approve'])->name('pengguna.approve');
    Route::post('/pengguna/{user}/reject', [Admin\PenggunaController::class, 'reject'])->name('pengguna.reject');
    Route::post('/pengguna/{user}/nonaktifkan', [Admin\PenggunaController::class, 'nonaktifkan'])->name('pengguna.nonaktifkan');
    Route::post('/pengguna/{user}/aktifkan', [Admin\PenggunaController::class, 'aktifkan'])->name('pengguna.aktifkan');

    Route::resource('/petugas', Admin\PetugasController::class)->except('show');

    Route::get('/laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [Admin\LaporanController::class, 'export'])->name('laporan.export');
    Route::get('/laporan/{laporan}', [Admin\LaporanController::class, 'show'])->name('laporan.show');

    Route::get('/iuran', [Admin\IuranController::class, 'index'])->name('iuran.index');
    Route::post('/iuran/generate', [Admin\IuranController::class, 'generate'])->name('iuran.generate');

    Route::resource('/tarif', Admin\TarifController::class)->except('show');

    Route::get('/profil', [Admin\ProfilController::class, 'edit'])->name('profil');
    Route::patch('/profil', [Admin\ProfilController::class, 'update'])->name('profil.update');
});

require __DIR__.'/auth.php';
