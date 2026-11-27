<?php

namespace App\Http\Controllers;

use App\DataTables\PerguruanTinggiDataTable;
use App\Http\Requests\PerguruanTinggiRequest;
use App\Models\ActivityLog;
use App\Models\PerguruanTinggi;
use Illuminate\Support\Facades\Auth;

class PerguruanTinggiController extends Controller
{
    public function index(PerguruanTinggiDataTable $dataTable)
    {
        $total = PerguruanTinggi::count();

        return $dataTable->render('layouts.pages.perguruan-tinggi.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('perguruan-tinggi.index', ['create' => 1]);
    }

    public function store(PerguruanTinggiRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['alamat'] = isset($validated['alamat']) && trim((string) $validated['alamat']) !== ''
            ? trim((string) $validated['alamat'])
            : null;
        $validated['no_telp'] = isset($validated['no_telp']) && trim((string) $validated['no_telp']) !== ''
            ? trim((string) $validated['no_telp'])
            : null;

        $item = PerguruanTinggi::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Perguruan Tinggi',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('perguruan-tinggi.index')
            ->with('success', 'Perguruan tinggi berhasil ditambahkan.');
    }

    public function edit(PerguruanTinggi $perguruanTinggi)
    {
        return redirect()->route('perguruan-tinggi.index');
    }

    public function update(PerguruanTinggiRequest $request, PerguruanTinggi $perguruanTinggi)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['alamat'] = isset($validated['alamat']) && trim((string) $validated['alamat']) !== ''
            ? trim((string) $validated['alamat'])
            : null;
        $validated['no_telp'] = isset($validated['no_telp']) && trim((string) $validated['no_telp']) !== ''
            ? trim((string) $validated['no_telp'])
            : null;

        $perguruanTinggi->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Perguruan Tinggi',
            "Memperbarui {$perguruanTinggi->kode} - {$perguruanTinggi->nama}.",
            'info',
        );

        return redirect()
            ->route('perguruan-tinggi.index')
            ->with('success', 'Perguruan tinggi berhasil diperbarui.');
    }

    public function destroy(PerguruanTinggi $perguruanTinggi)
    {
        $label = "{$perguruanTinggi->kode} - {$perguruanTinggi->nama}";
        $perguruanTinggi->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Perguruan Tinggi',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('perguruan-tinggi.index')
            ->with('success', "Perguruan tinggi ({$label}) berhasil dihapus.");
    }
}
