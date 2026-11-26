<?php

namespace App\Http\Controllers;

use App\DataTables\StatusAktifDataTable;
use App\Http\Requests\StatusAktifRequest;
use App\Models\ActivityLog;
use App\Models\StatusAktif;
use Illuminate\Support\Facades\Auth;

class StatusAktifController extends Controller
{
    public function index(StatusAktifDataTable $dataTable)
    {
        $total = StatusAktif::count();

        return $dataTable->render('layouts.pages.status-aktif.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('status-aktif.index', ['create' => 1]);
    }

    public function store(StatusAktifRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['status_keluar'] = $request->boolean('status_keluar');

        $item = StatusAktif::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Status Aktif',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('status-aktif.index')
            ->with('success', 'Status aktif berhasil ditambahkan.');
    }

    public function edit(StatusAktif $statusAktif)
    {
        if ($statusAktif->isSystemStatus()) {
            return redirect()
                ->route('status-aktif.index')
                ->with('error', "Status sistem ({$statusAktif->kode}) tidak dapat diubah.");
        }

        return redirect()->route('status-aktif.index');
    }

    public function update(StatusAktifRequest $request, StatusAktif $statusAktif)
    {
        if ($statusAktif->isSystemStatus()) {
            return redirect()
                ->route('status-aktif.index')
                ->with('error', "Status sistem ({$statusAktif->kode}) tidak dapat diubah.");
        }

        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['status_keluar'] = $request->boolean('status_keluar');

        $statusAktif->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Status Aktif',
            "Memperbarui {$statusAktif->kode} - {$statusAktif->nama}.",
            'info',
        );

        return redirect()
            ->route('status-aktif.index')
            ->with('success', 'Status aktif berhasil diperbarui.');
    }

    public function destroy(StatusAktif $statusAktif)
    {
        if ($statusAktif->isSystemStatus()) {
            return redirect()
                ->route('status-aktif.index')
                ->with('error', "Status sistem ({$statusAktif->kode}) tidak dapat dihapus.");
        }

        $label = "{$statusAktif->kode} - {$statusAktif->nama}";
        $statusAktif->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Status Aktif',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('status-aktif.index')
            ->with('success', "Status aktif ({$label}) berhasil dihapus.");
    }
}
