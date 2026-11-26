<?php

namespace App\Http\Controllers;

use App\DataTables\ProvinsiDataTable;
use App\Http\Requests\ProvinsiRequest;
use App\Models\ActivityLog;
use App\Models\Provinsi;
use Illuminate\Support\Facades\Auth;

class ProvinsiController extends Controller
{
    public function index(ProvinsiDataTable $dataTable)
    {
        $total = Provinsi::count();

        return $dataTable->render('layouts.pages.provinsi.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('provinsi.index', ['create' => 1]);
    }

    public function store(ProvinsiRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = strtoupper(trim($validated['nama']));

        $item = Provinsi::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Provinsi',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('provinsi.index')
            ->with('success', 'Provinsi berhasil ditambahkan.');
    }

    public function edit(Provinsi $provinsi)
    {
        return redirect()->route('provinsi.index');
    }

    public function update(ProvinsiRequest $request, Provinsi $provinsi)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = strtoupper(trim($validated['nama']));

        $provinsi->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Provinsi',
            "Memperbarui {$provinsi->kode} - {$provinsi->nama}.",
            'info',
        );

        return redirect()
            ->route('provinsi.index')
            ->with('success', 'Provinsi berhasil diperbarui.');
    }

    public function destroy(Provinsi $provinsi)
    {
        $label = "{$provinsi->kode} - {$provinsi->nama}";
        $provinsi->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Provinsi',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('provinsi.index')
            ->with('success', "Provinsi ({$label}) berhasil dihapus.");
    }
}
