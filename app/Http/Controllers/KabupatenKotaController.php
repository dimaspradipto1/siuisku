<?php

namespace App\Http\Controllers;

use App\DataTables\KabupatenKotaDataTable;
use App\Http\Requests\KabupatenKotaRequest;
use App\Models\ActivityLog;
use App\Models\KabupatenKota;
use App\Models\Provinsi;
use Illuminate\Support\Facades\Auth;

class KabupatenKotaController extends Controller
{
    public function index(KabupatenKotaDataTable $dataTable)
    {
        $total = KabupatenKota::count();
        $provinsis = Provinsi::orderBy('kode')->orderBy('id')->get(['id', 'kode', 'nama']);
        $defaultProvinsiId = request('provinsi_id') ?: ($provinsis->firstWhere('kode', '21')?->id ?? '');

        return $dataTable->render(
            'layouts.pages.kabupaten-kota.index',
            compact('total', 'provinsis', 'defaultProvinsiId')
        );
    }

    public function create()
    {
        return redirect()->route('kabupaten-kota.index', ['create' => 1]);
    }

    public function store(KabupatenKotaRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = strtoupper(trim($validated['nama']));

        $item = KabupatenKota::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Kabupaten/Kota',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('kabupaten-kota.index', ['provinsi_id' => $item->provinsi_id])
            ->with('success', 'Kabupaten/Kota berhasil ditambahkan.');
    }

    public function edit(KabupatenKota $kabupatenKota)
    {
        return redirect()->route('kabupaten-kota.index');
    }

    public function update(KabupatenKotaRequest $request, KabupatenKota $kabupatenKota)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = strtoupper(trim($validated['nama']));

        $kabupatenKota->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Kabupaten/Kota',
            "Memperbarui {$kabupatenKota->kode} - {$kabupatenKota->nama}.",
            'info',
        );

        return redirect()
            ->route('kabupaten-kota.index', ['provinsi_id' => $kabupatenKota->provinsi_id])
            ->with('success', 'Kabupaten/Kota berhasil diperbarui.');
    }

    public function destroy(KabupatenKota $kabupatenKota)
    {
        $provinsiId = $kabupatenKota->provinsi_id;
        $label = "{$kabupatenKota->kode} - {$kabupatenKota->nama}";
        $kabupatenKota->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Kabupaten/Kota',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('kabupaten-kota.index', ['provinsi_id' => $provinsiId])
            ->with('success', "Kabupaten/Kota ({$label}) berhasil dihapus.");
    }
}
