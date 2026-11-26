<?php

namespace App\Http\Controllers;

use App\DataTables\JenisSkDataTable;
use App\Http\Requests\JenisSkRequest;
use App\Models\ActivityLog;
use App\Models\JenisSk;
use Illuminate\Support\Facades\Auth;

class JenisSkController extends Controller
{
    public function index(JenisSkDataTable $dataTable)
    {
        $total = JenisSk::count();

        return $dataTable->render('layouts.pages.jenis-sk.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('jenis-sk.index', ['create' => 1]);
    }

    public function store(JenisSkRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = trim($validated['kode']);
        $validated['nama'] = trim($validated['nama']);

        $item = JenisSk::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis SK',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenis-sk.index')
            ->with('success', 'Jenis SK berhasil ditambahkan.');
    }

    public function update(JenisSkRequest $request, JenisSk $jenisSk)
    {
        $validated = $request->validated();
        $validated['kode'] = trim($validated['kode']);
        $validated['nama'] = trim($validated['nama']);

        $jenisSk->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis SK',
            "Memperbarui {$jenisSk->kode} - {$jenisSk->nama}.",
            'info',
        );

        return redirect()
            ->route('jenis-sk.index')
            ->with('success', 'Jenis SK berhasil diperbarui.');
    }

    public function destroy(JenisSk $jenisSk)
    {
        $label = "{$jenisSk->kode} - {$jenisSk->nama}";
        $jenisSk->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis SK',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenis-sk.index')
            ->with('success', "Jenis SK ({$label}) berhasil dihapus.");
    }
}
