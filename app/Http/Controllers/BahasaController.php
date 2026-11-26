<?php

namespace App\Http\Controllers;

use App\DataTables\BahasaDataTable;
use App\Http\Requests\BahasaRequest;
use App\Models\ActivityLog;
use App\Models\Bahasa;
use Illuminate\Support\Facades\Auth;

class BahasaController extends Controller
{
    public function index(BahasaDataTable $dataTable)
    {
        $total = Bahasa::count();

        return $dataTable->render('layouts.pages.bahasa.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('bahasa.index', ['create' => 1]);
    }

    public function store(BahasaRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = Bahasa::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Bahasa',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('bahasa.index')
            ->with('success', 'Bahasa berhasil ditambahkan.');
    }

    public function edit(Bahasa $bahasa)
    {
        return redirect()->route('bahasa.index');
    }

    public function update(BahasaRequest $request, Bahasa $bahasa)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $bahasa->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Bahasa',
            "Memperbarui {$bahasa->kode} - {$bahasa->nama}.",
            'info',
        );

        return redirect()
            ->route('bahasa.index')
            ->with('success', 'Bahasa berhasil diperbarui.');
    }

    public function destroy(Bahasa $bahasa)
    {
        $label = "{$bahasa->kode} - {$bahasa->nama}";
        $bahasa->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Bahasa',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('bahasa.index')
            ->with('success', "Bahasa ({$label}) berhasil dihapus.");
    }
}
