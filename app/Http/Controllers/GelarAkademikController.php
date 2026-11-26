<?php

namespace App\Http\Controllers;

use App\DataTables\GelarAkademikDataTable;
use App\Http\Requests\GelarAkademikRequest;
use App\Models\ActivityLog;
use App\Models\GelarAkademik;
use Illuminate\Support\Facades\Auth;

class GelarAkademikController extends Controller
{
    public function index(GelarAkademikDataTable $dataTable)
    {
        $total = GelarAkademik::count();

        return $dataTable->render('layouts.pages.gelar-akademik.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('gelar-akademik.index', ['create' => 1]);
    }

    public function store(GelarAkademikRequest $request)
    {
        $validated = $request->validated();
        $validated['gelar'] = trim($validated['gelar']);
        $validated['nama'] = trim($validated['nama']);
        $validated['urutan'] = ((int) GelarAkademik::max('urutan')) + 1;

        $item = GelarAkademik::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Gelar Akademik',
            "Menambahkan {$item->gelar} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('gelar-akademik.index')
            ->with('success', 'Gelar akademik berhasil ditambahkan.');
    }

    public function edit(GelarAkademik $gelarAkademik)
    {
        return redirect()->route('gelar-akademik.index');
    }

    public function update(GelarAkademikRequest $request, GelarAkademik $gelarAkademik)
    {
        $validated = $request->validated();
        $validated['gelar'] = trim($validated['gelar']);
        $validated['nama'] = trim($validated['nama']);

        $gelarAkademik->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Gelar Akademik',
            "Memperbarui {$gelarAkademik->gelar} - {$gelarAkademik->nama}.",
            'info',
        );

        return redirect()
            ->route('gelar-akademik.index')
            ->with('success', 'Gelar akademik berhasil diperbarui.');
    }

    public function destroy(GelarAkademik $gelarAkademik)
    {
        $label = "{$gelarAkademik->gelar} - {$gelarAkademik->nama}";
        $gelarAkademik->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Gelar Akademik',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('gelar-akademik.index')
            ->with('success', "Gelar akademik ({$label}) berhasil dihapus.");
    }
}
