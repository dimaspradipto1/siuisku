<?php

namespace App\Http\Controllers;

use App\DataTables\KecamatanDataTable;
use App\Http\Requests\KecamatanRequest;
use App\Models\ActivityLog;
use App\Models\KabupatenKota;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Illuminate\Support\Facades\Auth;

class KecamatanController extends Controller
{
    public function index(KecamatanDataTable $dataTable)
    {
        $total = Kecamatan::count();
        $provinsis = Provinsi::orderBy('kode')->orderBy('id')->get(['id', 'kode', 'nama']);
        $kabupatenKotas = KabupatenKota::orderBy('kode')->orderBy('id')->get(['id', 'provinsi_id', 'kode', 'nama']);

        $defaultKabKotaId = request('kabupaten_kota_id') ?: ($kabupatenKotas->firstWhere('kode', '2171')?->id ?? '');
        $defaultProvinsiId = request('provinsi_id')
            ?: ($kabupatenKotas->firstWhere('id', $defaultKabKotaId)?->provinsi_id
                ?? $provinsis->firstWhere('kode', '21')?->id
                ?? '');

        return $dataTable->render(
            'layouts.pages.kecamatan.index',
            compact('total', 'provinsis', 'kabupatenKotas', 'defaultProvinsiId', 'defaultKabKotaId')
        );
    }

    public function create()
    {
        return redirect()->route('kecamatan.index', ['create' => 1]);
    }

    public function store(KecamatanRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = strtoupper(trim($validated['nama']));

        $item = Kecamatan::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Kecamatan',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('kecamatan.index', ['kabupaten_kota_id' => $item->kabupaten_kota_id])
            ->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        return redirect()->route('kecamatan.index');
    }

    public function update(KecamatanRequest $request, Kecamatan $kecamatan)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = strtoupper(trim($validated['nama']));

        $kecamatan->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Kecamatan',
            "Memperbarui {$kecamatan->kode} - {$kecamatan->nama}.",
            'info',
        );

        return redirect()
            ->route('kecamatan.index', ['kabupaten_kota_id' => $kecamatan->kabupaten_kota_id])
            ->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kabKotaId = $kecamatan->kabupaten_kota_id;
        $label = "{$kecamatan->kode} - {$kecamatan->nama}";
        $kecamatan->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Kecamatan',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('kecamatan.index', ['kabupaten_kota_id' => $kabKotaId])
            ->with('success', "Kecamatan ({$label}) berhasil dihapus.");
    }
}
