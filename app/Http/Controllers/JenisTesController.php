<?php

namespace App\Http\Controllers;

use App\DataTables\JenisTesDataTable;
use App\Http\Requests\JenisTesRequest;
use App\Models\ActivityLog;
use App\Models\JenisTes;
use Illuminate\Support\Facades\Auth;

class JenisTesController extends Controller
{
    public function index(JenisTesDataTable $dataTable)
    {
        $total = JenisTes::count();

        return $dataTable->render('layouts.pages.jenis-tes.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-tes.index', ['create' => 1]);
    }

    public function store(JenisTesRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['nilai_minimal'] = $validated['nilai_minimal'] !== null && $validated['nilai_minimal'] !== '' ? $validated['nilai_minimal'] : null;
        $validated['nilai_maksimal'] = $validated['nilai_maksimal'] !== null && $validated['nilai_maksimal'] !== '' ? $validated['nilai_maksimal'] : null;

        $item = JenisTes::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Tes',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-tes.index')
            ->with('success', 'Jenis tes berhasil ditambahkan.');
    }

    public function edit(JenisTes $jenisTes)
    {
        return redirect()->route('jenis-tes.index');
    }

    public function update(JenisTesRequest $request, JenisTes $jenisTes)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['nilai_minimal'] = $validated['nilai_minimal'] !== null && $validated['nilai_minimal'] !== '' ? $validated['nilai_minimal'] : null;
        $validated['nilai_maksimal'] = $validated['nilai_maksimal'] !== null && $validated['nilai_maksimal'] !== '' ? $validated['nilai_maksimal'] : null;

        $jenisTes->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Tes',
            "Memperbarui {$jenisTes->kode} - {$jenisTes->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-tes.index')
            ->with('success', 'Jenis tes berhasil diperbarui.');
    }

    public function destroy(JenisTes $jenisTes)
    {
        $label = "{$jenisTes->kode} - {$jenisTes->nama}";
        $jenisTes->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Tes',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-tes.index')
            ->with('success', "Jenis tes ({$label}) berhasil dihapus.");
    }
}
