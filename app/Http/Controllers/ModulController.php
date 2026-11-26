<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModulRequest;
use App\Models\ActivityLog;
use App\Models\Modul;
use App\Models\User;
use App\Models\UserRole;
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

        $assignedUserRoles = UserRole::with(['modul', 'role', 'unitKerja', 'jenisJabatanStruktural'])
            ->where('user_id', $user->id)
            ->get()
            ->groupBy('modul_id');

        $modules = $user->accessibleModules();
        $defaultModule = $modules->first();

        return view('layouts.modul.index', compact(
            'user',
            'userRoles',
            'assignedUserRoles',
            'modules',
            'defaultModule',
        ));
    }

    public function masuk(UserRole $userRole)
    {
        abort_unless($userRole->user_id === Auth::id(), 403);

        $userRole->load(['modul', 'role', 'unitKerja', 'jenisJabatanStruktural']);

        session([
            'active_user_role_id' => $userRole->id,
            'active_role_id' => $userRole->role_id,
            'active_role_name' => $userRole->displayTitle(),
            'active_unit_kerja_id' => $userRole->unit_kerja_id,
            'active_unit_name' => $userRole->displayUnit(),
        ]);

        return redirect()->to($userRole->modul?->resolvedUrl() ?? route('dashboard'));
    }

    public function pengaturan()
    {
        $modules = Modul::query()
            ->with('users:id')
            ->withCount('users')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $allUsers = User::orderBy('name')->get(['id', 'name', 'username', 'roles']);

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

        return view('layouts.pages.pengaturan.modul.index', compact('modules', 'allUsers', 'iconPresets'));
    }

    public function syncUsers(ModulRequest $request, Modul $modul)
    {
        $userIds = $request->validated()['user_ids'] ?? [];
        $modul->users()->sync($userIds);

        ActivityLog::record(
            Auth::user(),
            'security',
            'Mengatur akses pengguna modul',
            "Memperbarui daftar pengguna modul {$modul->nama} (".count($userIds).' pengguna).',
            'info',
        );

        return redirect()
            ->route('pengaturan.modul.index')
            ->with('success', "Akses pengguna untuk modul {$modul->nama} berhasil diperbarui.");
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
