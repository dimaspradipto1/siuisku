<?php

namespace App\Http\Controllers;

use App\DataTables\GolonganDarahDataTable;
use App\Http\Requests\GolonganDarahRequest;
use App\Models\ActivityLog;
use App\Models\GolonganDarah;
use Illuminate\Support\Facades\Auth;

class GolonganDarahController extends Controller
{
    public function index(GolonganDarahDataTable $dataTable)
    {
        $total = GolonganDarah::count();

        return $dataTable->render('layouts.pages.golongan-darah.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('golongan-darah.index', ['create' => 1]);
    }

    public function store(GolonganDarahRequest $request)
    {
        $validated = $request->validated();
        $validated['nama'] = strtoupper(trim($validated['nama']));

        $item = GolonganDarah::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Golongan Darah',
            "Menambahkan golongan darah {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('golongan-darah.index')
            ->with('success', 'Golongan darah berhasil ditambahkan.');
    }

    public function edit(GolonganDarah $golonganDarah)
    {
        return redirect()->route('golongan-darah.index');
    }

    public function update(GolonganDarahRequest $request, GolonganDarah $golonganDarah)
    {
        $validated = $request->validated();
        $validated['nama'] = strtoupper(trim($validated['nama']));

        $golonganDarah->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Golongan Darah',
            "Memperbarui golongan darah {$golonganDarah->nama}.",
            'info',
        );

        return redirect()
            ->route('golongan-darah.index')
            ->with('success', 'Golongan darah berhasil diperbarui.');
    }

    public function destroy(GolonganDarah $golonganDarah)
    {
        $label = $golonganDarah->nama;
        $golonganDarah->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Golongan Darah',
            "Menghapus golongan darah {$label}.",
            'warning',
        );

        return redirect()
            ->route('golongan-darah.index')
            ->with('success', "Golongan darah ({$label}) berhasil dihapus.");
    }
}
