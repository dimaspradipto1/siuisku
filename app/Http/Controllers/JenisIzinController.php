<?php

namespace App\Http\Controllers;

use App\DataTables\JenisIzinDataTable;
use App\Http\Requests\JenisIzinRequest;
use App\Models\ActivityLog;
use App\Models\JenisIzin;
use App\Models\JenisKehadiran;
use Illuminate\Support\Facades\Auth;

class JenisIzinController extends Controller
{
    public function index(JenisIzinDataTable $dataTable)
    {
        $total = JenisIzin::count();
        $statusPresensis = JenisKehadiran::orderBy('kode')->orderBy('id')->get(['id', 'kode', 'nama']);

        return $dataTable->render(
            'layouts.pages.jenis-izin.index',
            compact('total', 'statusPresensis')
        );
    }

    public function create()
    {
        return redirect()->route('jenis-izin.index', ['create' => 1]);
    }

    public function store(JenisIzinRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['jenis_kehadiran_id'] = $validated['jenis_kehadiran_id'] ?: null;
        $validated['maksimal_hari'] = isset($validated['maksimal_hari']) && $validated['maksimal_hari'] !== ''
            ? (int) $validated['maksimal_hari']
            : null;
        $validated['potong_cuti'] = $request->boolean('potong_cuti');

        $item = JenisIzin::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Izin',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-izin.index')
            ->with('success', 'Jenis izin berhasil ditambahkan.');
    }

    public function edit(JenisIzin $jenisIzin)
    {
        return redirect()->route('jenis-izin.index');
    }

    public function update(JenisIzinRequest $request, JenisIzin $jenisIzin)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['jenis_kehadiran_id'] = $validated['jenis_kehadiran_id'] ?: null;
        $validated['maksimal_hari'] = isset($validated['maksimal_hari']) && $validated['maksimal_hari'] !== ''
            ? (int) $validated['maksimal_hari']
            : null;
        $validated['potong_cuti'] = $request->boolean('potong_cuti');

        $jenisIzin->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Izin',
            "Memperbarui {$jenisIzin->kode} - {$jenisIzin->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-izin.index')
            ->with('success', 'Jenis izin berhasil diperbarui.');
    }

    public function destroy(JenisIzin $jenisIzin)
    {
        $label = "{$jenisIzin->kode} - {$jenisIzin->nama}";
        $jenisIzin->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Izin',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-izin.index')
            ->with('success', "Jenis izin ({$label}) berhasil dihapus.");
    }
}
