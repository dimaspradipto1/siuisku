<?php

namespace App\Http\Controllers;

use App\DataTables\PangkatDataTable;
use App\Http\Requests\PangkatRequest;
use App\Models\ActivityLog;
use App\Models\Pangkat;
use Illuminate\Support\Facades\Auth;

class PangkatController extends Controller
{
    public function index(PangkatDataTable $dataTable)
    {
        $total = Pangkat::count();

        return $dataTable->render('layouts.pages.pangkat.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('pangkat.index', ['create' => 1]);
    }

    public function store(PangkatRequest $request)
    {
        $validated = $request->validated();
        $validated['pangkat'] = strtoupper(trim($validated['pangkat']));
        $validated['nama_golongan'] = trim($validated['nama_golongan']);
        $validated['urutan'] = ((int) Pangkat::max('urutan')) + 1;

        $item = Pangkat::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Pangkat/Golongan',
            "Menambahkan {$item->pangkat} - {$item->nama_golongan}.",
            'success',
        );

        return redirect()
            ->route('pangkat.index')
            ->with('success', 'Pangkat/Golongan berhasil ditambahkan.');
    }

    public function edit(Pangkat $pangkat)
    {
        return redirect()->route('pangkat.index');
    }

    public function update(PangkatRequest $request, Pangkat $pangkat)
    {
        $validated = $request->validated();
        $validated['pangkat'] = strtoupper(trim($validated['pangkat']));
        $validated['nama_golongan'] = trim($validated['nama_golongan']);

        $pangkat->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Pangkat/Golongan',
            "Memperbarui {$pangkat->pangkat} - {$pangkat->nama_golongan}.",
            'info',
        );

        return redirect()
            ->route('pangkat.index')
            ->with('success', 'Pangkat/Golongan berhasil diperbarui.');
    }

    public function destroy(Pangkat $pangkat)
    {
        $label = "{$pangkat->pangkat} - {$pangkat->nama_golongan}";
        $pangkat->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Pangkat/Golongan',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('pangkat.index')
            ->with('success', "Pangkat/Golongan ({$label}) berhasil dihapus.");
    }
}
