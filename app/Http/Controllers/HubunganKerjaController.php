<?php

namespace App\Http\Controllers;

use App\DataTables\HubunganKerjaDataTable;
use App\Http\Requests\HubunganKerjaRequest;
use App\Models\ActivityLog;
use App\Models\HubunganKerja;
use Illuminate\Support\Facades\Auth;

class HubunganKerjaController extends Controller
{
    public function index(HubunganKerjaDataTable $dataTable)
    {
        $total = HubunganKerja::count();

        return $dataTable->render('layouts.pages.hubungan-kerja.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('hubungan-kerja.index', ['create' => 1]);
    }

    public function store(HubunganKerjaRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['status_aktif'] = $request->boolean('status_aktif');
        $validated['pegawai_tetap'] = $request->boolean('pegawai_tetap');

        $item = HubunganKerja::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Hubungan Kerja',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('hubungan-kerja.index')
            ->with('success', 'Hubungan kerja berhasil ditambahkan.');
    }

    public function edit(HubunganKerja $hubunganKerja)
    {
        return redirect()->route('hubungan-kerja.index');
    }

    public function update(HubunganKerjaRequest $request, HubunganKerja $hubunganKerja)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['status_aktif'] = $request->boolean('status_aktif');
        $validated['pegawai_tetap'] = $request->boolean('pegawai_tetap');

        $hubunganKerja->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Hubungan Kerja',
            "Memperbarui {$hubunganKerja->kode} - {$hubunganKerja->nama}.",
            'info',
        );

        return redirect()
            ->route('hubungan-kerja.index')
            ->with('success', 'Hubungan kerja berhasil diperbarui.');
    }

    public function destroy(HubunganKerja $hubunganKerja)
    {
        $label = "{$hubunganKerja->kode} - {$hubunganKerja->nama}";
        $hubunganKerja->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Hubungan Kerja',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('hubungan-kerja.index')
            ->with('success', "Hubungan kerja ({$label}) berhasil dihapus.");
    }
}
