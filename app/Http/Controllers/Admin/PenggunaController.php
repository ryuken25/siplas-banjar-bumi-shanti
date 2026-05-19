<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AkunDiapproveNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'pending');
        $statusMap = ['pending' => 'pending', 'aktif' => 'aktif', 'nonaktif' => 'nonaktif'];
        $status = $statusMap[$tab] ?? 'pending';

        $pengguna = User::role('warga')
            ->where('status_akun', $status)
            ->when($request->query('search'), function ($q, $search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $counts = [
            'pending' => User::role('warga')->where('status_akun', 'pending')->count(),
            'aktif' => User::role('warga')->where('status_akun', 'aktif')->count(),
            'nonaktif' => User::role('warga')->where('status_akun', 'nonaktif')->count(),
        ];

        return view('admin.pengguna', compact('pengguna', 'tab', 'counts'));
    }

    public function approve(User $user): RedirectResponse
    {
        abort_unless($user->status_akun === 'pending' && $user->hasRole('warga'), 422);
        $user->update(['status_akun' => 'aktif', 'email_verified_at' => now()]);
        $user->notify(new AkunDiapproveNotification);

        return back()->with('success', 'Akun '.$user->name.' telah disetujui.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        $request->validate(['alasan' => ['required', 'string', 'min:5', 'max:500']]);
        abort_unless($user->status_akun === 'pending', 422);
        $user->update(['status_akun' => 'nonaktif', 'alasan_tolak_akun' => $request->alasan]);

        return back()->with('success', 'Pendaftaran '.$user->name.' telah ditolak.');
    }

    public function nonaktifkan(User $user): RedirectResponse
    {
        abort_if($user->hasRole('admin'), 422, 'Admin tidak dapat dinonaktifkan dari sini.');
        $user->update(['status_akun' => 'nonaktif']);

        return back()->with('success', 'Akun '.$user->name.' telah dinonaktifkan.');
    }

    public function aktifkan(User $user): RedirectResponse
    {
        $user->update(['status_akun' => 'aktif']);

        return back()->with('success', 'Akun '.$user->name.' telah diaktifkan kembali.');
    }
}
