<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PetugasController extends Controller
{
    public function index(Request $request): View
    {
        $petugas = User::role('petugas')
            ->when($request->query('search'), fn ($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
            ->latest()
            ->paginate(12);

        return view('admin.petugas-index', compact('petugas'));
    }

    public function create(): View
    {
        return view('admin.petugas-form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'nik' => ['nullable', 'string', 'size:16', 'unique:users,nik'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'status_akun' => 'aktif',
            'email_verified_at' => now(),
        ]);
        $user->assignRole('petugas');

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas baru berhasil ditambahkan.');
    }

    public function edit(User $petuga): View
    {
        abort_unless($petuga->hasRole('petugas'), 404);

        return view('admin.petugas-form', ['user' => $petuga]);
    }

    public function update(Request $request, User $petuga): RedirectResponse
    {
        abort_unless($petuga->hasRole('petugas'), 404);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$petuga->id],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'min:8'],
            'status_akun' => ['required', 'in:aktif,nonaktif'],
        ]);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $petuga->update($data);

        return redirect()->route('admin.petugas.index')->with('success', 'Data petugas diperbarui.');
    }

    public function destroy(User $petuga): RedirectResponse
    {
        abort_unless($petuga->hasRole('petugas'), 404);
        $petuga->delete(); // soft delete

        return back()->with('success', 'Petugas dihapus.');
    }
}
