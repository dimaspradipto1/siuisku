<?php

namespace App\Http\Controllers;

use App\DataTables\JenisPelanggaranDataTable;
use App\Http\Requests\JenisPelanggaranRequest;
use App\Models\ActivityLog;
use App\Models\JenisPelanggaran;
use Illuminate\Support\Facades\Auth;

class JenisPelanggaranController extends Controller
{
    public function index(JenisPelanggaranDataTable $dataTable)
    {
        $total = JenisPelanggaran::count();

        return $dataTable->render('layouts.pages.jenis-pelanggaran.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-pelanggaran.index', ['create' => 1]);
    }

    public function store(JenisPelanggaranRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = JenisPelanggaran::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Pelanggaran',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    public function edit(JenisPelanggaran $jenisPelanggaran)
    {
        return redirect()->route('jenis-pelanggaran.index');
    }

    public function update(JenisPelanggaranRequest $request, JenisPelanggaran $jenisPelanggaran)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $jenisPelanggaran->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Pelanggaran',
            "Memperbarui {$jenisPelanggaran->kode} - {$jenisPelanggaran->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran)
    {
        $label = "{$jenisPelanggaran->kode} - {$jenisPelanggaran->nama}";
        $jenisPelanggaran->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Pelanggaran',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-pelanggaran.index')
            ->with('success', "Jenis pelanggaran ({$label}) berhasil dihapus.");
    }
}
