<?php

namespace App\Http\Controllers;

use App\DataTables\JenjangPendidikanDataTable;
use App\Http\Requests\JenjangPendidikanRequest;
use App\Models\ActivityLog;
use App\Models\JenjangPendidikan;
use Illuminate\Support\Facades\Auth;

class JenjangPendidikanController extends Controller
{
    public function index(JenjangPendidikanDataTable $dataTable)
    {
        $total = JenjangPendidikan::count();
        $nextUrutan = ((int) JenjangPendidikan::max('urutan')) + 1;

        return $dataTable->render('layouts.pages.jenjang-pendidikan.index', compact('total', 'nextUrutan'));
    }

    public function create()
    {
        return redirect()->route('jenjang-pendidikan.index', ['create' => 1]);
    }

    public function store(JenjangPendidikanRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = trim($validated['kode']);
        $validated['nama'] = trim($validated['nama']);
        $validated['nama_en'] = isset($validated['nama_en']) && trim((string) $validated['nama_en']) !== ''
            ? trim((string) $validated['nama_en'])
            : null;
        $validated['is_perguruan_tinggi'] = $request->boolean('is_perguruan_tinggi');
        $validated['is_pasca_sarjana'] = $request->boolean('is_pasca_sarjana');

        $item = JenjangPendidikan::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenjang Pendidikan',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jenjang-pendidikan.index')
            ->with('success', 'Jenjang pendidikan berhasil ditambahkan.');
    }

    public function edit(JenjangPendidikan $jenjangPendidikan)
    {
        return redirect()->route('jenjang-pendidikan.index');
    }

    public function update(JenjangPendidikanRequest $request, JenjangPendidikan $jenjangPendidikan)
    {
        $validated = $request->validated();
        $validated['kode'] = trim($validated['kode']);
        $validated['nama'] = trim($validated['nama']);
        $validated['nama_en'] = isset($validated['nama_en']) && trim((string) $validated['nama_en']) !== ''
            ? trim((string) $validated['nama_en'])
            : null;
        $validated['is_perguruan_tinggi'] = $request->boolean('is_perguruan_tinggi');
        $validated['is_pasca_sarjana'] = $request->boolean('is_pasca_sarjana');

        $jenjangPendidikan->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenjang Pendidikan',
            "Memperbarui {$jenjangPendidikan->kode} - {$jenjangPendidikan->nama}.",
            'info',
        );

        return redirect()
            ->route('jenjang-pendidikan.index')
            ->with('success', 'Jenjang pendidikan berhasil diperbarui.');
    }

    public function destroy(JenjangPendidikan $jenjangPendidikan)
    {
        $label = "{$jenjangPendidikan->kode} - {$jenjangPendidikan->nama}";
        $jenjangPendidikan->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenjang Pendidikan',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jenjang-pendidikan.index')
            ->with('success', "Jenjang pendidikan ({$label}) berhasil dihapus.");
    }
}
