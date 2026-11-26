<?php

namespace App\Http\Controllers;

use App\DataTables\JenisPkmDataTable;
use App\Http\Requests\JenisPkmRequest;
use App\Models\ActivityLog;
use App\Models\JenisPkm;
use Illuminate\Support\Facades\Auth;

class JenisPkmController extends Controller
{
    public function index(JenisPkmDataTable $dataTable)
    {
        $total = JenisPkm::count();

        return $dataTable->render('layouts.pages.jenis-pkm.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-pkm.index', ['create' => 1]);
    }

    public function store(JenisPkmRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = JenisPkm::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis PKM',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-pkm.index')
            ->with('success', 'Jenis pengabdian masyarakat berhasil ditambahkan.');
    }

    public function edit(JenisPkm $jenisPkm)
    {
        return redirect()->route('jenis-pkm.index');
    }

    public function update(JenisPkmRequest $request, JenisPkm $jenisPkm)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $jenisPkm->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis PKM',
            "Memperbarui {$jenisPkm->kode} - {$jenisPkm->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-pkm.index')
            ->with('success', 'Jenis pengabdian masyarakat berhasil diperbarui.');
    }

    public function destroy(JenisPkm $jenisPkm)
    {
        $label = "{$jenisPkm->kode} - {$jenisPkm->nama}";
        $jenisPkm->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis PKM',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-pkm.index')
            ->with('success', "Jenis pengabdian masyarakat ({$label}) berhasil dihapus.");
    }
}
