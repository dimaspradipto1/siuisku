<?php

namespace App\Http\Controllers;

use App\DataTables\SettingValidasiDataTable;
use App\Http\Requests\JenisValidasiRequest;
use App\Http\Requests\SettingValidasiStepRequest;
use App\Models\ActivityLog;
use App\Models\JenisValidasi;
use App\Models\Role;
use App\Models\SettingValidasiStep;
use Illuminate\Support\Facades\Auth;

class SettingValidasiController extends Controller
{
    public function index(SettingValidasiDataTable $dataTable)
    {
        $total = JenisValidasi::count();

        return $dataTable->render('layouts.pages.setting-validasi.index', compact('total'));
    }

    public function create()
    {
        return redirect()->route('setting-validasi.index', ['create' => 1]);
    }

    public function store(JenisValidasiRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $item = JenisValidasi::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Jenis Validasi',
            "Menambahkan {$item->kode} - {$item->nama}.",
            'success',
        );

        return redirect()
            ->route('setting-validasi.index')
            ->with('success', 'Jenis validasi berhasil ditambahkan.');
    }

    public function show(JenisValidasi $settingValidasi)
    {
        $settingValidasi->load('steps.role');
        $roles = Role::orderBy('id')->get();
        $nextUrutan = ((int) $settingValidasi->steps->max('urutan')) + 1;

        return view('layouts.pages.setting-validasi.show', compact('settingValidasi', 'roles', 'nextUrutan'));
    }

    public function edit(JenisValidasi $settingValidasi)
    {
        return redirect()->route('setting-validasi.index');
    }

    public function update(JenisValidasiRequest $request, JenisValidasi $settingValidasi)
    {
        $validated = $request->validated();
        $validated['kode'] = strtoupper(trim($validated['kode']));
        $validated['nama'] = trim($validated['nama']);

        $settingValidasi->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Jenis Validasi',
            "Memperbarui {$settingValidasi->kode} - {$settingValidasi->nama}.",
            'info',
        );

        return redirect()
            ->route('setting-validasi.index')
            ->with('success', 'Jenis validasi berhasil diperbarui.');
    }

    public function destroy(JenisValidasi $settingValidasi)
    {
        $label = "{$settingValidasi->kode} - {$settingValidasi->nama}";
        $settingValidasi->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Jenis Validasi',
            "Menghapus {$label}.",
            'warning',
        );

        return redirect()
            ->route('setting-validasi.index')
            ->with('success', "Jenis validasi ({$label}) berhasil dihapus.");
    }

    public function storeStep(SettingValidasiStepRequest $request, JenisValidasi $settingValidasi)
    {
        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);

        $step = $settingValidasi->steps()->create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan Tahap Validasi',
            "Menambahkan tahap #{$step->urutan} ({$step->nama}) pada {$settingValidasi->kode} - {$settingValidasi->nama}.",
            'success',
        );

        return redirect()
            ->route('setting-validasi.show', $settingValidasi)
            ->with('success', 'Tahap validasi berhasil ditambahkan.');
    }

    public function updateStep(
        SettingValidasiStepRequest $request,
        JenisValidasi $settingValidasi,
        SettingValidasiStep $step,
    ) {
        abort_if($step->jenis_validasi_id !== $settingValidasi->id, 404);

        $validated = $request->validated();
        $validated['nama'] = trim($validated['nama']);

        $step->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui Tahap Validasi',
            "Memperbarui tahap #{$step->urutan} ({$step->nama}) pada {$settingValidasi->kode} - {$settingValidasi->nama}.",
            'info',
        );

        return redirect()
            ->route('setting-validasi.show', $settingValidasi)
            ->with('success', 'Tahap validasi berhasil diperbarui.');
    }

    public function destroyStep(JenisValidasi $settingValidasi, SettingValidasiStep $step)
    {
        abort_if($step->jenis_validasi_id !== $settingValidasi->id, 404);

        $label = "#{$step->urutan} - {$step->nama}";
        $step->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus Tahap Validasi',
            "Menghapus tahap {$label} pada {$settingValidasi->kode} - {$settingValidasi->nama}.",
            'warning',
        );

        return redirect()
            ->route('setting-validasi.show', $settingValidasi)
            ->with('success', "Tahap validasi ({$label}) berhasil dihapus.");
    }
}
