<?php

namespace App\Http\Controllers;

use App\DataTables\NegaraDataTable;
use App\Http\Requests\NegaraRequest;
use App\Models\ActivityLog;
use App\Models\Negara;
use Illuminate\Support\Facades\Auth;

class NegaraController extends Controller
{
    public function index(NegaraDataTable $dataTable)
    {
        $total = Negara::count();

        return $dataTable->render('layouts.pages.negara.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('negara.index', ['create' => 1]);
    }

    public function store(NegaraRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['kode_emis'] = isset($validated['kode_emis']) && trim((string) $validated['kode_emis']) !== ''
            ? strtoupper(trim((string) $validated['kode_emis']))
            : null;

        $item = Negara::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Negara',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('negara.index')
            ->with('success', 'Negara berhasil ditambahkan.');
    }

    public function edit(Negara $negara)
    {
        return redirect()->route('negara.index');
    }

    public function update(NegaraRequest $request, Negara $negara)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['kode_emis'] = isset($validated['kode_emis']) && trim((string) $validated['kode_emis']) !== ''
            ? strtoupper(trim((string) $validated['kode_emis']))
            : null;

        $negara->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Negara',
            "Memperbarui {$negara->kode} - {$negara->nama}.",
            'info',
        );

        return redirect()
            ->route('negara.index')
            ->with('success', 'Negara berhasil diperbarui.');
    }

    public function destroy(Negara $negara)
    {
        $label = "{$negara->kode} - {$negara->nama}";
        $negara->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Negara',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('negara.index')
            ->with('success', "Negara ({$label}) berhasil dihapus.");
    }
}
