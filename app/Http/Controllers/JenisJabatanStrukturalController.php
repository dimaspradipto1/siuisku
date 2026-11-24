<?php

namespace App\Http\Controllers;

use App\DataTables\JenisJabatanStrukturalDataTable;
use App\Http\Requests\JenisJabatanStrukturalRequest;
use App\Models\ActivityLog;
use App\Models\JenisJabatanStruktural;
use Illuminate\Support\Facades\Auth;

class JenisJabatanStrukturalController extends Controller
{
    public function index(JenisJabatanStrukturalDataTable $dataTable)
    {
        $total = JenisJabatanStruktural::count();

        return $dataTable->render('layouts.pages.jenis-jabatan-struktural.index', compact('total'));
    }

    public function create()
    {
        return view('layouts.pages.jenis-jabatan-struktural.create');
    }

    public function store(JenisJabatanStrukturalRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));

        $item = JenisJabatanStruktural::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan jenis jabatan struktural',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-jabatan-struktural.index')
            ->with('success', 'Jenis jabatan struktural berhasil ditambahkan.');
    }

    public function edit(JenisJabatanStruktural $jenisJabatanStruktural)
    {
        return view('layouts.pages.jenis-jabatan-struktural.edit', [
            'jabatan' => $jenisJabatanStruktural,
        ]);
    }

    public function update(JenisJabatanStrukturalRequest $request, JenisJabatanStruktural $jenisJabatanStruktural)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));

        $jenisJabatanStruktural->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui jenis jabatan struktural',
            "Memperbarui {$jenisJabatanStruktural->kode} - {$jenisJabatanStruktural->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-jabatan-struktural.index')
            ->with('success', 'Jenis jabatan struktural berhasil diperbarui.');
    }

    public function destroy(JenisJabatanStruktural $jenisJabatanStruktural)
    {
        $label = "{$jenisJabatanStruktural->kode} - {$jenisJabatanStruktural->nama}";
        $jenisJabatanStruktural->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus jenis jabatan struktural',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-jabatan-struktural.index')
            ->with('success', "Jenis jabatan struktural ({$label}) berhasil dihapus.");
    }
}
