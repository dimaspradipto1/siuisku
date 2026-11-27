<?php

namespace App\Http\Controllers;

use App\DataTables\SettingValidasiDataTable;
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

    public function show(JenisValidasi $settingValidasi)
    {
        $settingValidasi->load('steps.role');
        $roles = Role::orderBy('id')->get();
        $nextUrutan = ((int) $settingValidasi->steps->max('urutan')) + 1;

        return view('layouts.pages.setting-validasi.show', compact('settingValidasi', 'roles', 'nextUrutan'));
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
