<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModulRequest;
use App\Models\ActivityLog;
use App\Models\Modul;
use Illuminate\Support\Facades\Auth;

class ModulController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userRoles = collect(explode(',', (string) $user->roles))
            ->map(fn (string $role) => trim($role))
            ->filter()
            ->values();

        $modules = Modul::query()
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $defaultModule = $modules->firstWhere('is_active', true) ?? $modules->first();

        return view('layouts.modul.index', compact('user', 'userRoles', 'modules', 'defaultModule'));
    }

    public function pengaturan()
    {
        $modules = Modul::query()
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $iconPresets = [
            'bi-people-fill' => 'Kepegawaian (People)',
            'bi-mortarboard-fill' => 'Akademik (Mortarboard)',
            'bi-patch-check-fill' => 'Akreditasi (Patch Check)',
            'bi-book-half' => 'E-Learning (Book)',
            'bi-bar-chart-line-fill' => 'Pelaporan (Bar Chart)',
            'bi-headset' => 'Helpdesk (Headset)',
            'bi-cash-coin' => 'Keuangan (Cash)',
            'bi-building' => 'Sarana Prasarana (Building)',
            'bi-folder-fill' => 'Arsip / Dokumen (Folder)',
            'bi-shield-lock-fill' => 'Keamanan (Shield)',
            'bi-laptop' => 'CBT / Ujian (Laptop)',
            'bi-grid-fill' => 'Umum (Grid)',
        ];

        return view('layouts.pages.pengaturan.modul.index', compact('modules', 'iconPresets'));
    }

    public function store(ModulRequest $request)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        $modul = Modul::create($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan modul baru',
            "Menambahkan modul {$modul->nama} ({$modul->kode}).",
            'success',
        );

        return redirect()
            ->route('pengaturan.modul.index')
            ->with('success', 'Modul berhasil ditambahkan.');
    }

    public function update(ModulRequest $request, Modul $modul)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        $modul->update($validated);

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui modul',
            "Memperbarui pengaturan modul {$modul->nama}.",
            'info',
        );

        return redirect()
            ->route('pengaturan.modul.index')
            ->with('success', 'Modul berhasil diperbarui.');
    }

    public function toggleStatus(Modul $modul)
    {
        $modul->update([
            'is_active' => ! $modul->is_active,
        ]);

        $statusLabel = $modul->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            Auth::user(),
            'system',
            'Mengubah status modul',
            "Modul {$modul->nama} {$statusLabel}.",
            'warning',
        );

        return redirect()
            ->route('pengaturan.modul.index')
            ->with('success', "Modul {$modul->nama} berhasil {$statusLabel}.");
    }

    public function destroy(Modul $modul)
    {
        $nama = $modul->nama;
        $modul->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus modul',
            "Menghapus modul {$nama}.",
            'warning',
        );

        return redirect()
            ->route('pengaturan.modul.index')
            ->with('success', "Modul {$nama} berhasil dihapus.");
    }
}
