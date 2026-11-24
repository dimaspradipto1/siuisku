<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private function roleOptions(): array
    {
        return [
            'super admin' => 'Super Admin',
            'admin' => 'Admin',
            'staff' => 'Staff',
            'Ka.Lembaga Pengembangan Teknologi Informasi' => 'Ka.Lembaga Pengembangan Teknologi Informasi',
        ];
    }

    public function index(Request $request)
    {
        $selectedRole = $request->query('role');

        $usersQuery = User::query()->latest();

        if (filled($selectedRole)) {
            $usersQuery->where('roles', $selectedRole);
        }

        $users = $usersQuery->get();

        $allUsers = User::all();
        $totalUsers = $allUsers->count();
        $superAdminCount = $allUsers->filter(fn (User $u) => in_array(strtolower($u->roles), ['super admin', 'superadmin'], true))->count();
        $adminCount = $allUsers->filter(fn (User $u) => strtolower($u->roles) === 'admin')->count();
        $staffCount = $allUsers->filter(fn (User $u) => strtolower($u->roles) === 'staff')->count();

        $roleOptions = $this->roleOptions();
        $availableRoles = $allUsers->pluck('roles')->filter()->unique()->values();

        return view('layouts.users.index', compact(
            'users',
            'totalUsers',
            'superAdminCount',
            'adminCount',
            'staffCount',
            'selectedRole',
            'roleOptions',
            'availableRoles',
        ));
    }

    public function create()
    {
        $roleOptions = $this->roleOptions();

        return view('layouts.users.create', compact('roleOptions'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'roles' => $validated['roles'],
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('layouts.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roleOptions = $this->roleOptions();

        if (! array_key_exists($user->roles, $roleOptions) && filled($user->roles)) {
            $roleOptions[$user->roles] = $user->roles;
        }

        return view('layouts.users.edit', compact('user', 'roleOptions'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'roles' => $validated['roles'],
        ];

        if (filled($validated['password'] ?? null)) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

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

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }
}
