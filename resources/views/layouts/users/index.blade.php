@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content page-users">
      <div class="page-users">
        <div class="page-header users-page-header">
          <div>
            <h1 class="page-title">People Directory</h1>
            <p class="users-page-subtitle">Manajemen data pengguna, hak akses role, dan status akun SIUISKU.</p>
          </div>
          <div class="page-header-actions d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
              <i class="bi bi-lightning-charge me-1"></i> Quick Add
            </button>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
              <i class="bi bi-plus-lg me-1"></i> Add User
            </a>
          </div>
        </div>

        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan input:</strong>
            <ul class="mb-0 mt-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="users-insight-row mb-3">
          <div class="users-insight users-insight-total">
            <span class="users-insight-icon"><i class="bi bi-people"></i></span>
            <span class="users-insight-label">Total Users</span>
            <span class="users-insight-value">{{ $totalUsers }}</span>
            <span class="users-insight-meta">Seluruh akun terdaftar</span>
          </div>
          <div class="users-insight users-insight-active">
            <span class="users-insight-icon"><i class="bi bi-shield-lock"></i></span>
            <span class="users-insight-label">Super Admin</span>
            <span class="users-insight-value">{{ $superAdminCount }}</span>
            <span class="users-insight-meta">Akses penuh sistem</span>
          </div>
          <div class="users-insight users-insight-pending">
            <span class="users-insight-icon"><i class="bi bi-person-gear"></i></span>
            <span class="users-insight-label">Admin</span>
            <span class="users-insight-value">{{ $adminCount }}</span>
            <span class="users-insight-meta">Pengelola operasional</span>
          </div>
          <div class="users-insight users-insight-inactive">
            <span class="users-insight-icon"><i class="bi bi-person-badge"></i></span>
            <span class="users-insight-label">Staff</span>
            <span class="users-insight-value">{{ $staffCount }}</span>
            <span class="users-insight-meta">Pengguna unit kerja</span>
          </div>
        </div>

        <div class="card users-list-card">
          <div class="users-toolbar">
            <div class="users-toolbar-left">
              <div class="users-filter-tabs">
                <a href="{{ route('users.index') }}" class="users-filter-tab {{ blank($selectedRole) ? 'active' : '' }}">
                  All <span class="users-filter-count">{{ $totalUsers }}</span>
                </a>
                <a href="{{ route('users.index', ['role' => 'super admin']) }}" class="users-filter-tab {{ $selectedRole === 'super admin' ? 'active' : '' }}">
                  Super Admin <span class="users-filter-count">{{ $superAdminCount }}</span>
                </a>
                <a href="{{ route('users.index', ['role' => 'admin']) }}" class="users-filter-tab {{ $selectedRole === 'admin' ? 'active' : '' }}">
                  Admin <span class="users-filter-count">{{ $adminCount }}</span>
                </a>
                <a href="{{ route('users.index', ['role' => 'staff']) }}" class="users-filter-tab {{ $selectedRole === 'staff' ? 'active' : '' }}">
                  Staff <span class="users-filter-count">{{ $staffCount }}</span>
                </a>
              </div>
            </div>

            <div class="users-toolbar-right">
              <div class="dropdown">
                <button class="users-toolbar-btn dropdown-toggle" data-bs-toggle="dropdown">
                  <i class="bi bi-sliders"></i> {{ $selectedRole ? ucwords($selectedRole) : 'Filter Role' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="{{ route('users.index') }}">All Roles</a></li>
                  @foreach ($availableRoles as $role)
                    <li>
                      <a class="dropdown-item {{ $selectedRole === $role ? 'active' : '' }}" href="{{ route('users.index', ['role' => $role]) }}">
                        {{ ucwords($role) }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>

          <div class="table-responsive users-table-wrap p-3">
            <table id="usersTable" class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th style="width: 55px;">#</th>
                  <th>User</th>
                  <th>Username</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Joined</th>
                  <th class="users-th-actions">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($users as $index => $user)
                  @php
                    $roleLower = strtolower($user->roles);
                    $roleClass = str_contains($roleLower, 'admin') ? 'admin' : (str_contains($roleLower, 'ka.') || str_contains($roleLower, 'manager') ? 'manager' : 'user');
                    $roleIcon = $roleClass === 'admin' ? 'bi-shield-check' : ($roleClass === 'manager' ? 'bi-person-gear' : 'bi-person');
                  @endphp
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                      <div class="users-user">
                        <div class="users-avatar-wrap">
                          <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="users-avatar">
                          <span class="users-avatar-status online"></span>
                        </div>
                        <div class="users-user-info">
                          <a href="{{ route('users.show', $user) }}" class="users-user-name">{{ $user->name }}</a>
                          <span class="users-user-email">{{ $user->email }}</span>
                        </div>
                      </div>
                    </td>
                    <td class="users-meta"><code>{{ $user->username }}</code></td>
                    <td>
                      <span class="users-role {{ $roleClass }}">
                        <i class="bi {{ $roleIcon }}"></i> {{ ucwords($user->roles) }}
                      </span>
                    </td>
                    <td>
                      <span class="users-status active">
                        <span class="users-status-dot"></span> Active
                      </span>
                    </td>
                    <td class="users-meta">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                    <td>
                      <div class="users-actions">
                        <a href="{{ route('users.show', $user) }}" class="users-action-btn" title="Detail">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="users-action-btn" title="Edit">
                          <i class="bi bi-pencil"></i>
                        </a>
                        @if (Auth::id() !== $user->id)
                          <form
                            action="{{ route('users.destroy', $user) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')"
                          >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="users-action-btn text-danger border-0 bg-transparent" title="Hapus">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada data user.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Quick Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                  <h5 class="modal-title">Add New User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="row g-3">
                    <div class="col-12">
                      <label class="form-label">Nama Lengkap</label>
                      <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="col-sm-6">
                      <label class="form-label">Username</label>
                      <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Masukkan username" required>
                    </div>
                    <div class="col-sm-6">
                      <label class="form-label">Role</label>
                      <select name="roles" class="form-select" required>
                        <option value="">Pilih role...</option>
                        @foreach ($roleOptions as $value => $label)
                          <option value="{{ $value }}" {{ old('roles') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12">
                      <label class="form-label">Email Address</label>
                      <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@uis.ac.id" required>
                    </div>
                    <div class="col-sm-6">
                      <label class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="col-sm-6">
                      <label class="form-label">Confirm Password</label>
                      <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Add User</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const usersTable = document.getElementById('usersTable');
      if (usersTable && typeof simpleDatatables !== 'undefined') {
        new simpleDatatables.DataTable(usersTable, {
          perPage: 10,
          perPageSelect: [5, 10, 25, 50],
          labels: {
            placeholder: 'Cari user, username, email, role...',
            noRows: 'Data user tidak ditemukan',
            info: 'Menampilkan {start} - {end} dari {rows} user',
          }
        });
      }
    });
  </script>
@endpush
