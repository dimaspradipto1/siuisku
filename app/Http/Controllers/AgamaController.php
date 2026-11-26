<?php

namespace App\Http\Controllers;

use App\DataTables\AgamaDataTable;
use App\Http\Requests\AgamaRequest;
use App\Models\ActivityLog;
use App\Models\Agama;
use Illuminate\Support\Facades\Auth;

class AgamaController extends Controller
{
    public function index(AgamaDataTable $dataTable)
    {
        $total = Agama::count();

        return $dataTable->render('layouts.pages.agama.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('agama.index', ['create' => 1]);
    }

    public function store(AgamaRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = Agama::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Agama',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('agama.index')
            ->with('success', 'Agama berhasil ditambahkan.');
    }

    public function edit(Agama $agama)
    {
        return redirect()->route('agama.index');
    }

    public function update(AgamaRequest $request, Agama $agama)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $agama->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Agama',
            "Memperbarui {$agama->kode} - {$agama->nama}.",
            'info',
        );

        return redirect()
            ->route('agama.index')
            ->with('success', 'Agama berhasil diperbarui.');
    }

    public function destroy(Agama $agama)
    {
        $label = "{$agama->kode} - {$agama->nama}";
        $agama->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Agama',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('agama.index')
            ->with('success', "Agama ({$label}) berhasil dihapus.");
    }
}
