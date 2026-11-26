<?php

namespace App\Http\Controllers;

use App\DataTables\JenisKenaikanPangkatDataTable;
use App\Http\Requests\JenisKenaikanPangkatRequest;
use App\Models\ActivityLog;
use App\Models\JenisKenaikanPangkat;
use Illuminate\Support\Facades\Auth;

class JenisKenaikanPangkatController extends Controller
{
    public function index(JenisKenaikanPangkatDataTable $dataTable)
    {
        $total = JenisKenaikanPangkat::count();

        return $dataTable->render('layouts.pages.jenis-kenaikan-pangkat.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-kenaikan-pangkat.index', ['create' => 1]);
    }

    public function store(JenisKenaikanPangkatRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = JenisKenaikanPangkat::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Kenaikan Pangkat',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-kenaikan-pangkat.index')
            ->with('success', 'Jenis kenaikan pangkat berhasil ditambahkan.');
    }

    public function edit(JenisKenaikanPangkat $jenisKenaikanPangkat)
    {
        return redirect()->route('jenis-kenaikan-pangkat.index');
    }

    public function update(JenisKenaikanPangkatRequest $request, JenisKenaikanPangkat $jenisKenaikanPangkat)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $jenisKenaikanPangkat->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Kenaikan Pangkat',
            "Memperbarui {$jenisKenaikanPangkat->kode} - {$jenisKenaikanPangkat->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-kenaikan-pangkat.index')
            ->with('success', 'Jenis kenaikan pangkat berhasil diperbarui.');
    }

    public function destroy(JenisKenaikanPangkat $jenisKenaikanPangkat)
    {
        $label = "{$jenisKenaikanPangkat->kode} - {$jenisKenaikanPangkat->nama}";
        $jenisKenaikanPangkat->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Kenaikan Pangkat',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-kenaikan-pangkat.index')
            ->with('success', "Jenis kenaikan pangkat ({$label}) berhasil dihapus.");
    }
}
