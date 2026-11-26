<?php

namespace App\Http\Controllers;

use App\DataTables\JabatanFungsionalDataTable;
use App\Http\Requests\JabatanFungsionalRequest;
use App\Models\ActivityLog;
use App\Models\JabatanFungsional;
use App\Models\KlasifikasiSdm;
use App\Models\Pangkat;
use Illuminate\Support\Facades\Auth;

class JabatanFungsionalController extends Controller
{
    public function index(JabatanFungsionalDataTable $dataTable)
    {
        $total = JabatanFungsional::count();
        $klasifikasiSdms = KlasifikasiSdm::orderBy('kode')->orderBy('id')->get();
        $defaultKlasifikasiId = $klasifikasiSdms->firstWhere('kode', '1')?->id;

        return $dataTable->render(
            'layouts.pages.jabatan-fungsional.index',
            compact('total', 'klasifikasiSdms', 'defaultKlasifikasiId')
        );
    }

    public function create()
    {
        $klasifikasiSdms = KlasifikasiSdm::orderBy('kode')->orderBy('id')->get();
        $pangkats = Pangkat::orderBy('urutan')->orderBy('id')->get();
        $referensiSisterOptions = JabatanFungsional::REFERENSI_SISTER_OPTIONS;

        return view(
            'layouts.pages.jabatan-fungsional.create',
            compact('klasifikasiSdms', 'pangkats', 'referensiSisterOptions')
        );
    }

    public function store(JabatanFungsionalRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['keterangan'] = isset($validated['keterangan']) ? trim((string) $validated['keterangan']) : null;

        $item = JabatanFungsional::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jabatan Fungsional',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('jabatan-fungsional.index')
            ->with('success', 'Jabatan fungsional berhasil ditambahkan.');
    }

    public function edit(JabatanFungsional $jabatanFungsional)
    {
        $klasifikasiSdms = KlasifikasiSdm::orderBy('kode')->orderBy('id')->get();
        $pangkats = Pangkat::orderBy('urutan')->orderBy('id')->get();
        $referensiSisterOptions = JabatanFungsional::REFERENSI_SISTER_OPTIONS;

        return view(
            'layouts.pages.jabatan-fungsional.edit',
            compact('jabatanFungsional', 'klasifikasiSdms', 'pangkats', 'referensiSisterOptions')
        );
    }

    public function update(JabatanFungsionalRequest $request, JabatanFungsional $jabatanFungsional)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);
        $validated['keterangan'] = isset($validated['keterangan']) ? trim((string) $validated['keterangan']) : null;

        $jabatanFungsional->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jabatan Fungsional',
            "Memperbarui {$jabatanFungsional->kode} - {$jabatanFungsional->nama}.",
            'info',
        );

        return redirect()
            ->route('jabatan-fungsional.index')
            ->with('success', 'Jabatan fungsional berhasil diperbarui.');
    }

    public function destroy(JabatanFungsional $jabatanFungsional)
    {
        $label = "{$jabatanFungsional->kode} - {$jabatanFungsional->nama}";
        $jabatanFungsional->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jabatan Fungsional',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('jabatan-fungsional.index')
            ->with('success', "Jabatan fungsional ({$label}) berhasil dihapus.");
    }
}
