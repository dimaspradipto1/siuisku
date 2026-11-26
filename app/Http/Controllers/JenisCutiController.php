<?php

namespace App\Http\Controllers;

use App\DataTables\JenisCutiDataTable;
use App\Http\Requests\JenisCutiRequest;
use App\Models\ActivityLog;
use App\Models\JenisCuti;
use Illuminate\Support\Facades\Auth;

class JenisCutiController extends Controller
{
    public function index(JenisCutiDataTable $dataTable)
    {
        $total = JenisCuti::count();

        return $dataTable->render('layouts.pages.jenis-cuti.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-cuti.index', ['create' => 1]);
    }

    public function store(JenisCutiRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['format_nomor_surat'] = isset($validated['format_nomor_surat']) && trim((string) $validated['format_nomor_surat']) !== ''
            ? trim((string) $validated['format_nomor_surat'])
            : null;
        $validated['keterangan'] = isset($validated['keterangan']) && trim((string) $validated['keterangan']) !== ''
            ? trim((string) $validated['keterangan'])
            : null;
        $validated['is_system'] = false;

        $item = JenisCuti::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Cuti',
            "Menambahkan {$item->kode} - {$item->nama} ({$item->standar_cuti} hari).",
            'success',
        );

        return redirect()
            ->route('jenis-cuti.index')
            ->with('success', 'Jenis cuti berhasil ditambahkan.');
    }

    public function edit(JenisCuti $jenisCuti)
    {
        return redirect()->route('jenis-cuti.index');
    }

    public function update(JenisCutiRequest $request, JenisCuti $jenisCuti)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['format_nomor_surat'] = isset($validated['format_nomor_surat']) && trim((string) $validated['format_nomor_surat']) !== ''
            ? trim((string) $validated['format_nomor_surat'])
            : null;
        $validated['keterangan'] = isset($validated['keterangan']) && trim((string) $validated['keterangan']) !== ''
            ? trim((string) $validated['keterangan'])
            : null;

        $jenisCuti->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Cuti',
            "Memperbarui {$jenisCuti->kode} - {$jenisCuti->nama} ({$jenisCuti->standar_cuti} hari).",
            'info',
        );

        return redirect()
            ->route('jenis-cuti.index')
            ->with('success', 'Jenis cuti berhasil diperbarui.');
    }

    public function destroy(JenisCuti $jenisCuti)
    {
        if ($jenisCuti->is_system) {
            return redirect()
                ->route('jenis-cuti.index')
                ->with('error', "Jenis cuti sistem ({$jenisCuti->kode} - {$jenisCuti->nama}) tidak dapat dihapus.");
        }

        $label = "{$jenisCuti->kode} - {$jenisCuti->nama}";
        $jenisCuti->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Cuti',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-cuti.index')
            ->with('success', "Jenis cuti ({$label}) berhasil dihapus.");
    }
}
