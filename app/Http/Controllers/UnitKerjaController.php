<?php

namespace App\Http\Controllers;

use App\DataTables\UnitKerjaDataTable;
use App\Http\Requests\UnitKerjaRequest;
use App\Models\ActivityLog;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class UnitKerjaController extends Controller
{
    private function jenisOptions(): array
    {
        return [
            'universitas' => 'Universitas',
            'lembaga' => 'Lembaga',
            'biro' => 'Biro',
            'fakultas' => 'Fakultas',
            'prodi' => 'Program Studi',
            'unit' => 'Unit / Bagian',
        ];
    }

    public function index(UnitKerjaDataTable $dataTable)
    {
        $total = UnitKerja::count();

        return $dataTable->render('layouts.pages.unit-kerja.index', compact('total'));
    }

    public function create()
    {
        $parents = UnitKerja::orderBy('nama')->get();
        $jenisOptions = $this->jenisOptions();

        return view('layouts.pages.unit-kerja.create', compact('parents', 'jenisOptions'));
    }

    public function store(UnitKerjaRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));

        $unit = UnitKerja::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan unit kerja',
            "Menambahkan unit {$unit->kode} - {$unit->nama}.",
            'success',
        );

        return redirect()
            ->route('unit-kerja.index')
            ->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function edit(UnitKerja $unitKerja)
    {
        $parents = UnitKerja::where('id', '!=', $unitKerja->id)->orderBy('nama')->get();
        $jenisOptions = $this->jenisOptions();

        return view('layouts.pages.unit-kerja.edit', [
            'unit' => $unitKerja,
            'parents' => $parents,
            'jenisOptions' => $jenisOptions,
        ]);
    }

    public function update(UnitKerjaRequest $request, UnitKerja $unitKerja)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));

        $unitKerja->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui unit kerja',
            "Memperbarui unit {$unitKerja->kode} - {$unitKerja->nama}.",
            'info',
        );

        return redirect()
            ->route('unit-kerja.index')
            ->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unitKerja)
    {
        $label = "{$unitKerja->kode} - {$unitKerja->nama}";
        $unitKerja->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus unit kerja',
            "Menghapus unit {$label}.",
            'warning',
        );

        return redirect()
            ->route('unit-kerja.index')
            ->with('success', "Unit kerja ({$label}) berhasil dihapus.");
    }
}
