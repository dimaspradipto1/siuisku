<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use App\Http\Requests\HakAksesRequest;
use App\Models\ActivityLog;
use App\Models\JenisJabatanStruktural;
use App\Models\Modul;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class HakAksesController extends Controller
{
    public function index(RoleDataTable $dataTable)
    {
        $users = User::orderBy('name')->get();
        $modules = Modul::orderBy('urutan')->orderBy('id')->get();
        $roles = Role::orderBy('nama')->get();
        $unitKerjas = UnitKerja::orderBy('nama')->get();
        $jabatans = JenisJabatanStruktural::orderBy('kode')->get();

        $userRoles = UserRole::with(['user', 'modul', 'role', 'unitKerja', 'jenisJabatanStruktural'])
            ->latest()
            ->get();

        return $dataTable->render('layouts.pages.hak-akses.index', compact(
            'users',
            'modules',
            'roles',
            'unitKerjas',
            'jabatans',
            'userRoles',
        ));
    }

    public function create()
    {
        $permissionsByMenu = Permission::orderBy('id')->get()->groupBy('menu');

        return view('layouts.pages.hak-akses.create', compact('permissionsByMenu'));
    }

    public function store(HakAksesRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = strtolower(trim($validated['kode']));

        $role = Role::create([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'scope' => $validated['scope'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        ActivityLog::record(
            Auth::user(),
            'security',
            'Menambahkan role hak akses',
            "Menambahkan role {$role->nama} (scope: {$role->scope}).",
            'success',
        );

        return redirect()
            ->route('hak-akses.index')
            ->with('success', "Role {$role->nama} berhasil ditambahkan.");
    }

    public function edit(Role $hakAkse)
    {
        $hakAkse->load('permissions');
        $permissionsByMenu = Permission::orderBy('id')->get()->groupBy('menu');
        $selectedPermissions = $hakAkse->permissions->pluck('id')->all();

        return view('layouts.pages.hak-akses.edit', [
            'role' => $hakAkse,
            'permissionsByMenu' => $permissionsByMenu,
            'selectedPermissions' => $selectedPermissions,
        ]);
    }

    public function update(HakAksesRequest $request, Role $hakAkse)
    {
        $validated = $request->validated();
        $validated['kode'] = strtolower(trim($validated['kode']));

        $hakAkse->update([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'scope' => $validated['scope'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        $hakAkse->permissions()->sync($validated['permissions'] ?? []);

        ActivityLog::record(
            Auth::user(),
            'security',
            'Memperbarui hak akses role',
            "Memperbarui role {$hakAkse->nama} beserta matrix permission.",
            'info',
        );

        return redirect()
            ->route('hak-akses.index')
            ->with('success', "Hak akses role {$hakAkse->nama} berhasil diperbarui.");
    }

    public function destroy(Role $hakAkse)
    {
        if ($hakAkse->kode === 'super_admin') {
            return redirect()
                ->route('hak-akses.index')
                ->with('error', 'Role Super Admin tidak dapat dihapus.');
        }

        $nama = $hakAkse->nama;
        $hakAkse->delete();

        ActivityLog::record(
            Auth::user(),
            'security',
            'Menghapus role hak akses',
            "Menghapus role {$nama}.",
            'warning',
        );

        return redirect()
            ->route('hak-akses.index')
            ->with('success', "Role {$nama} berhasil dihapus.");
    }

    public function storePlotting(HakAksesRequest $request)
    {
        $validated = $request->validated();

        $plot = UserRole::create($validated);
        $plot->load(['user', 'role', 'unitKerja', 'modul']);

        ActivityLog::record(
            Auth::user(),
            'security',
            'Plotting hak akses user',
            "Menetapkan {$plot->user?->name} sebagai {$plot->displayTitle()} ({$plot->displayUnit()}) pada modul {$plot->modul?->nama}.",
            'success',
        );

        return redirect()
            ->route('hak-akses.index', ['tab' => 'plotting'])
            ->with('success', 'Plotting hak akses pengguna berhasil ditambahkan.');
    }

    public function destroyPlotting(UserRole $userRole)
    {
        $userRole->load(['user', 'role']);
        $label = "{$userRole->user?->name} - {$userRole->displayTitle()}";
        $userRole->delete();

        ActivityLog::record(
            Auth::user(),
            'security',
            'Menghapus plotting hak akses user',
            "Menghapus plotting {$label}.",
            'warning',
        );

        return redirect()
            ->route('hak-akses.index', ['tab' => 'plotting'])
            ->with('success', "Plotting hak akses ({$label}) berhasil dihapus.");
    }
}
