<?php

namespace App\Http\Controllers;

use App\DataTables\SukuDataTable;
use App\Http\Requests\SukuRequest;
use App\Models\ActivityLog;
use App\Models\Suku;
use Illuminate\Support\Facades\Auth;

class SukuController extends Controller
{
    public function index(SukuDataTable $dataTable)
    {
        $total = Suku::count();

        return $dataTable->render('layouts.pages.suku.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('suku.index', ['create' => 1]);
    }

    public function store(SukuRequest $request)
    {
        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);

        $item = Suku::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Suku',
            "Menambahkan suku {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('suku.index')
            ->with('success', 'Suku berhasil ditambahkan.');
    }

    public function edit(Suku $suku)
    {
        return redirect()->route('suku.index');
    }

    public function update(SukuRequest $request, Suku $suku)
    {
        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);

        $suku->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Suku',
            "Memperbarui suku {$suku->nama}.",
            'info',
        );

        return redirect()
            ->route('suku.index')
            ->with('success', 'Suku berhasil diperbarui.');
    }

    public function destroy(Suku $suku)
    {
        $label = $suku->nama;
        $suku->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Suku',
            "Menghapus suku {$label}.",
            'warning',
        );

        return redirect()
            ->route('suku.index')
            ->with('success', "Suku ({$label}) berhasil dihapus.");
    }
}
