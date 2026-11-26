<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\UserRequest;
use App\Models\ActivityLog;
use App\Models\JenisJabatanStruktural;
use App\Models\Modul;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private function otkOptions(): array
    {
        return [
            'modules' => Modul::orderBy('urutan')->orderBy('id')->get(),
            'roles' => Role::orderBy('nama')->get(),
            'unitKerjas' => UnitKerja::orderBy('urutan')->orderBy('id')->get(),
            'jabatans' => JenisJabatanStruktural::orderBy('urutan')->orderBy('id')->get(),
        ];
    }

    public function index(UsersDataTable $dataTable, Request $request)
    {
        $selectedRoleId = $request->query('role_id');
        $selectedRole = $request->query('role');

        $allUsers = User::with('userRoles.role')->get();
        $totalUsers = $allUsers->count();

        $superAdminCount = $allUsers->filter(fn (User $u) => $u->userRoles->contains(fn (UserRole $ur) => $ur->role?->kode === 'super_admin')
            || in_array(strtolower((string) $u->roles), ['super admin', 'superadmin'], true))->count();

        $adminCount = $allUsers->filter(fn (User $u) => $u->userRoles->contains(fn (UserRole $ur) => $ur->role?->kode === 'admin_kepegawaian')
            || strtolower((string) $u->roles) === 'admin')->count();

        $staffCount = $allUsers->filter(fn (User $u) => $u->userRoles->contains(fn (UserRole $ur) => $ur->role?->kode === 'staff')
            || strtolower((string) $u->roles) === 'staff')->count();

        return $dataTable->render('layouts.pages.users.index', array_merge(
            compact(
                'totalUsers',
                'superAdminCount',
                'adminCount',
                'staffCount',
                'selectedRoleId',
                'selectedRole',
            ),
            $this->otkOptions(),
        ));
    }

    public function create()
    {
        return view('layouts.pages.users.create', $this->otkOptions());
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('avatars', 'public');
        }

        $assignments = $validated['role_assignments'] ?? [];
        $primaryRoleLabel = $this->resolvePrimaryRoleLabel($assignments, $validated['roles'] ?? 'staff');

        $created = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'roles' => $primaryRoleLabel,
            'photo' => $photoPath,
            'password' => $validated['password'],
        ]);

        $this->syncUserRoles($created, $assignments);

        if (Schema::hasTable('user_moduls') && ($request->has('modul_ids') || $request->boolean('sync_moduls'))) {
            $created->moduls()->sync($validated['modul_ids'] ?? []);
        }

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menambahkan user baru',
            "Menambahkan akun {$created->name} ({$created->username}).",
            'success',
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load(['userRoles.modul', 'userRoles.role', 'userRoles.unitKerja', 'userRoles.jenisJabatanStruktural']);

        return view('layouts.pages.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $relations = ['userRoles.modul', 'userRoles.role', 'userRoles.unitKerja', 'userRoles.jenisJabatanStruktural'];
        if (Schema::hasTable('user_moduls')) {
            $relations[] = 'moduls';
        }
        $user->load($relations);
        if (! Schema::hasTable('user_moduls')) {
            $user->setRelation('moduls', collect());
        }

        return view('layouts.pages.users.edit', array_merge(
            compact('user'),
            $this->otkOptions(),
        ));
    }

    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();
        $assignments = $validated['role_assignments'] ?? null;

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'roles' => $assignments !== null
                ? $this->resolvePrimaryRoleLabel($assignments, $validated['roles'] ?? $user->roles)
                : ($validated['roles'] ?? $user->roles),
        ];

        if ($request->hasFile('photo')) {
            if (filled($user->photo) && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('avatars', 'public');
        }

        if (filled($validated['password'] ?? null)) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        if ($assignments !== null) {
            $this->syncUserRoles($user, $assignments);
        }

        if (Schema::hasTable('user_moduls') && ($request->has('modul_ids') || $request->boolean('sync_moduls'))) {
            $user->moduls()->sync($validated['modul_ids'] ?? []);
        }

        ActivityLog::record(
            Auth::user(),
            'system',
            'Memperbarui data user',
            "Memperbarui akun {$user->name} ({$user->username}).",
            'info',
        );

        return redirect()
            ->route('users.index')
            ->with('success', "Data user {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if (filled($user->photo) && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $name = $user->name;
        $user->delete();

        ActivityLog::record(
            Auth::user(),
            'system',
            'Menghapus user',
            "Menghapus akun {$name}.",
            'warning',
        );

        return redirect()
            ->route('users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }

    /**
     * @param  array<int, array<string, mixed>>  $assignments
     */
    private function syncUserRoles(User $user, array $assignments): void
    {
        $user->userRoles()->delete();

        foreach ($assignments as $item) {
            if (empty($item['modul_id']) || empty($item['role_id'])) {
                continue;
            }

            UserRole::create([
                'user_id' => $user->id,
                'modul_id' => $item['modul_id'],
                'role_id' => $item['role_id'],
                'unit_kerja_id' => $item['unit_kerja_id'] ?: null,
                'jenis_jabatan_struktural_id' => $item['jenis_jabatan_struktural_id'] ?: null,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $assignments
     */
    private function resolvePrimaryRoleLabel(array $assignments, string $fallback): string
    {
        $first = $assignments[0] ?? null;
        if (! $first) {
            return $fallback;
        }

        if (! empty($first['jenis_jabatan_struktural_id'])) {
            $jabatan = JenisJabatanStruktural::find($first['jenis_jabatan_struktural_id']);
            if ($jabatan) {
                return $jabatan->nama;
            }
        }

        if (! empty($first['role_id'])) {
            $role = Role::find($first['role_id']);
            if ($role) {
                return $role->nama;
            }
        }

        return $fallback;
    }
}
