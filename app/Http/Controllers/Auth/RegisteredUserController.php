<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PendaftaranBaruUntukAdminNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'nik' => ['required', 'string', 'size:16', 'unique:'.User::class],
            'no_kk' => ['required', 'string', 'size:16'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
            'password' => [
                'required', 'confirmed',
                Rules\Password::min(8)->letters()->numbers()->mixedCase(),
            ],
        ], [], [
            'nik' => 'NIK',
            'no_kk' => 'Nomor KK',
            'no_telp' => 'Nomor telepon',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nik' => $validated['nik'],
            'no_kk' => $validated['no_kk'],
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'status_akun' => 'pending',
        ]);
        $user->assignRole('warga');

        event(new Registered($user));

        // Notify all admins about new pending registration
        $admins = User::role('admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PendaftaranBaruUntukAdminNotification($user));
        }

        return redirect()->route('register.pending')->with('success', 'Pendaftaran berhasil dikirim! Akun Anda sedang menunggu persetujuan admin.');
    }

    public function pending(): View
    {
        return view('auth.pending');
    }
}
