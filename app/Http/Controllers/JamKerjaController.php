<?php

namespace App\Http\Controllers;

use App\DataTables\JamKerjaDataTable;
use App\Http\Requests\JamKerjaRequest;
use App\Models\ActivityLog;
use App\Models\JamKerja;
use Illuminate\Support\Facades\Auth;

class JamKerjaController extends Controller
{
    public function index(JamKerjaDataTable $dataTable)
    {
        $total = JamKerja::count();

        return $dataTable->render('layouts.pages.jam-kerja.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jam-kerja.index', ['create' => 1]);
    }

    public function store(JamKerjaRequest $request)
    {
        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);
        $validated['is_normal'] = $request->boolean('is_normal');

        $item = JamKerja::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jam Kerja',
            "Menambahkan {$item->nama} ({$item->jam_datang} - {$item->jam_pulang}).",
            'success',
        );

        return redirect()
            ->route('jam-kerja.index')
            ->with('success', 'Jam kerja berhasil ditambahkan.');
    }

    public function edit(JamKerja $jamKerja)
    {
        return redirect()->route('jam-kerja.index');
    }

    public function update(JamKerjaRequest $request, JamKerja $jamKerja)
    {
        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);
        $validated['is_normal'] = $request->boolean('is_normal');

        $jamKerja->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jam Kerja',
            "Memperbarui {$jamKerja->nama} ({$jamKerja->jam_datang} - {$jamKerja->jam_pulang}).",
            'info',
        );

        return redirect()
            ->route('jam-kerja.index')
            ->with('success', 'Jam kerja berhasil diperbarui.');
    }

    public function destroy(JamKerja $jamKerja)
    {
        $label = "{$jamKerja->nama} ({$jamKerja->jam_datang} - {$jamKerja->jam_pulang})";
        $jamKerja->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jam Kerja',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jam-kerja.index')
            ->with('success', "Jam kerja ({$label}) berhasil dihapus.");
    }
}
