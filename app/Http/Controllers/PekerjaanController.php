<?php

namespace App\Http\Controllers;

use App\DataTables\PekerjaanDataTable;
use App\Http\Requests\PekerjaanRequest;
use App\Models\ActivityLog;
use App\Models\Pekerjaan;
use Illuminate\Support\Facades\Auth;

class PekerjaanController extends Controller
{
    public function index(PekerjaanDataTable $dataTable)
    {
        $total = Pekerjaan::count();

        return $dataTable->render('layouts.pages.pekerjaan.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('pekerjaan.index', ['create' => 1]);
    }

    public function store(PekerjaanRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = Pekerjaan::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Pekerjaan',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('pekerjaan.index')
            ->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function edit(Pekerjaan $pekerjaan)
    {
        return redirect()->route('pekerjaan.index');
    }

    public function update(PekerjaanRequest $request, Pekerjaan $pekerjaan)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $pekerjaan->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Pekerjaan',
            "Memperbarui {$pekerjaan->kode} - {$pekerjaan->nama}.",
            'info',
        );

        return redirect()
            ->route('pekerjaan.index')
            ->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(Pekerjaan $pekerjaan)
    {
        $label = "{$pekerjaan->kode} - {$pekerjaan->nama}";
        $pekerjaan->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Pekerjaan',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('pekerjaan.index')
            ->with('success', "Pekerjaan ({$label}) berhasil dihapus.");
    }
}
