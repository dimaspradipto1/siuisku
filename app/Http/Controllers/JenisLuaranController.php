<?php

namespace App\Http\Controllers;

use App\DataTables\JenisLuaranDataTable;
use App\Http\Requests\JenisLuaranRequest;
use App\Models\ActivityLog;
use App\Models\JenisLuaran;
use Illuminate\Support\Facades\Auth;

class JenisLuaranController extends Controller
{
    public function index(JenisLuaranDataTable $dataTable)
    {
        $total = JenisLuaran::count();

        return $dataTable->render('layouts.pages.jenis-luaran.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-luaran.index', ['create' => 1]);
    }

    public function store(JenisLuaranRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = JenisLuaran::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Luaran',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-luaran.index')
            ->with('success', 'Jenis luaran berhasil ditambahkan.');
    }

    public function edit(JenisLuaran $jenisLuaran)
    {
        return redirect()->route('jenis-luaran.index');
    }

    public function update(JenisLuaranRequest $request, JenisLuaran $jenisLuaran)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $jenisLuaran->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Luaran',
            "Memperbarui {$jenisLuaran->kode} - {$jenisLuaran->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-luaran.index')
            ->with('success', 'Jenis luaran berhasil diperbarui.');
    }

    public function destroy(JenisLuaran $jenisLuaran)
    {
        $label = "{$jenisLuaran->kode} - {$jenisLuaran->nama}";
        $jenisLuaran->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Luaran',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-luaran.index')
            ->with('success', "Jenis luaran ({$label}) berhasil dihapus.");
    }
}
