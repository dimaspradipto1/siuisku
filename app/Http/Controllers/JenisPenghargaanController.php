<?php

namespace App\Http\Controllers;

use App\DataTables\JenisPenghargaanDataTable;
use App\Http\Requests\JenisPenghargaanRequest;
use App\Models\ActivityLog;
use App\Models\JenisPenghargaan;
use Illuminate\Support\Facades\Auth;

class JenisPenghargaanController extends Controller
{
    public function index(JenisPenghargaanDataTable $dataTable)
    {
        $total = JenisPenghargaan::count();

        return $dataTable->render('layouts.pages.jenis-penghargaan.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-penghargaan.index', ['create' => 1]);
    }

    public function store(JenisPenghargaanRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = JenisPenghargaan::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Penghargaan',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-penghargaan.index')
            ->with('success', 'Jenis penghargaan berhasil ditambahkan.');
    }

    public function edit(JenisPenghargaan $jenisPenghargaan)
    {
        return redirect()->route('jenis-penghargaan.index');
    }

    public function update(JenisPenghargaanRequest $request, JenisPenghargaan $jenisPenghargaan)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $jenisPenghargaan->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Penghargaan',
            "Memperbarui {$jenisPenghargaan->kode} - {$jenisPenghargaan->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-penghargaan.index')
            ->with('success', 'Jenis penghargaan berhasil diperbarui.');
    }

    public function destroy(JenisPenghargaan $jenisPenghargaan)
    {
        $label = "{$jenisPenghargaan->kode} - {$jenisPenghargaan->nama}";
        $jenisPenghargaan->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Penghargaan',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-penghargaan.index')
            ->with('success', "Jenis penghargaan ({$label}) berhasil dihapus.");
    }
}
