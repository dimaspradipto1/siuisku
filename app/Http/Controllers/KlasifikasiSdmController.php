<?php

namespace App\Http\Controllers;

use App\DataTables\KlasifikasiSdmDataTable;
use App\Http\Requests\KlasifikasiSdmRequest;
use App\Models\ActivityLog;
use App\Models\KlasifikasiSdm;
use Illuminate\Support\Facades\Auth;

class KlasifikasiSdmController extends Controller
{
    public function index(KlasifikasiSdmDataTable $dataTable)
    {
        $total = KlasifikasiSdm::count();
        $jenisJabatanOptions = KlasifikasiSdm::JENIS_JABATAN_OPTIONS;

        return $dataTable->render('layouts.pages.klasifikasi-sdm.index', compact('total', 'jenisJabatanOptions'));
    }

    public function create()
    {
        return redirect()->route('klasifikasi-sdm.index', ['create' => 1]);
    }

    public function store(KlasifikasiSdmRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = KlasifikasiSdm::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jabatan Akademik',
            "Menambahkan {$item->kode} - {$item->nama} ({$item->jenis_jabatan}).",
            'success',
        );

        return redirect()
            ->route('klasifikasi-sdm.index')
            ->with('success', 'Jabatan akademik berhasil ditambahkan.');
    }

    public function edit(KlasifikasiSdm $klasifikasiSdm)
    {
        return redirect()->route('klasifikasi-sdm.index');
    }

    public function update(KlasifikasiSdmRequest $request, KlasifikasiSdm $klasifikasiSdm)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $klasifikasiSdm->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jabatan Akademik',
            "Memperbarui {$klasifikasiSdm->kode} - {$klasifikasiSdm->nama} ({$klasifikasiSdm->jenis_jabatan}).",
            'info',
        );

        return redirect()
            ->route('klasifikasi-sdm.index')
            ->with('success', 'Jabatan akademik berhasil diperbarui.');
    }

    public function destroy(KlasifikasiSdm $klasifikasiSdm)
    {
        $label = "{$klasifikasiSdm->kode} - {$klasifikasiSdm->nama}";
        $klasifikasiSdm->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jabatan Akademik',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('klasifikasi-sdm.index')
            ->with('success', "Jabatan akademik ({$label}) berhasil dihapus.");
    }
}
