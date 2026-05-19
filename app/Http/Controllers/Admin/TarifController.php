<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarifIuran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TarifController extends Controller
{
    public function index(): View
    {
        $tarif = TarifIuran::orderByDesc('periode_mulai')->paginate(10);

        return view('admin.tarif-index', compact('tarif'));
    }

    public function create(): View
    {
        return view('admin.tarif-form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nominal' => ['required', 'integer', 'min:1000', 'max:1000000'],
            'periode_mulai' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'aktif' => ['nullable', 'boolean'],
        ]);
        $data['aktif'] = $request->boolean('aktif', true);

        if ($data['aktif']) {
            TarifIuran::where('aktif', true)->update(['aktif' => false]);
        }
        TarifIuran::create($data);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif iuran baru berhasil ditambahkan.');
    }

    public function edit(TarifIuran $tarif): View
    {
        return view('admin.tarif-form', compact('tarif'));
    }

    public function update(Request $request, TarifIuran $tarif): RedirectResponse
    {
        $data = $request->validate([
            'nominal' => ['required', 'integer', 'min:1000', 'max:1000000'],
            'periode_mulai' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'aktif' => ['nullable', 'boolean'],
        ]);
        $data['aktif'] = $request->boolean('aktif');
        if ($data['aktif']) {
            TarifIuran::where('id', '!=', $tarif->id)->update(['aktif' => false]);
        }
        $tarif->update($data);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif diperbarui.');
    }

    public function destroy(TarifIuran $tarif): RedirectResponse
    {
        $tarif->delete();

        return back()->with('success', 'Tarif dihapus.');
    }
}
