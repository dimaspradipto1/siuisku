<?php

namespace App\Http\Controllers;

use App\DataTables\EselonDataTable;
use App\Http\Requests\EselonRequest;
use App\Models\ActivityLog;
use App\Models\Eselon;
use Illuminate\Support\Facades\Auth;

class EselonController extends Controller
{
    public function index(EselonDataTable $dataTable)
    {
        $total = Eselon::count();

        return $dataTable->render('layouts.pages.eselon.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('eselon.index', ['create' => 1]);
    }

    public function store(EselonRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['aktif'] = $request->boolean('aktif');

        $item = Eselon::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Eselon',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('eselon.index')
            ->with('success', 'Eselon berhasil ditambahkan.');
    }

    public function edit(Eselon $eselon)
    {
        return redirect()->route('eselon.index');
    }

    public function update(EselonRequest $request, Eselon $eselon)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['aktif'] = $request->boolean('aktif');

        $eselon->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Eselon',
            "Memperbarui {$eselon->kode} - {$eselon->nama}.",
            'info',
        );

        return redirect()
            ->route('eselon.index')
            ->with('success', 'Eselon berhasil diperbarui.');
    }

    public function destroy(Eselon $eselon)
    {
        $label = "{$eselon->kode} - {$eselon->nama}";
        $eselon->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Eselon',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('eselon.index')
            ->with('success', "Eselon ({$label}) berhasil dihapus.");
    }
}
