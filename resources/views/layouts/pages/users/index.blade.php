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
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
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
                                <a href="{{ route('users.index') }}"
                                    class="users-filter-tab {{ blank($selectedRoleId) && blank($selectedRole) ? 'active' : '' }}">
                                    All <span class="users-filter-count">{{ $totalUsers }}</span>
                                </a>
                                @foreach ($roles->take(3) as $roleItem)
                                    <a href="{{ route('users.index', ['role_id' => $roleItem->id]) }}"
                                        class="users-filter-tab {{ (string) $selectedRoleId === (string) $roleItem->id ? 'active' : '' }}">
                                        {{ $roleItem->nama }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="users-toolbar-right">
                            <div class="dropdown">
                                @php
                                    $activeRoleFilter = $roles->firstWhere('id', (int) $selectedRoleId);
                                @endphp
                                <button class="users-toolbar-btn dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-sliders"></i>
                                    {{ $activeRoleFilter ? $activeRoleFilter->nama : 'Filter Role' }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}">Semua Role</a></li>
                                    @foreach ($roles as $roleItem)
                                        <li>
                                            <a class="dropdown-item {{ (string) $selectedRoleId === (string) $roleItem->id ? 'active' : '' }}"
                                                href="{{ route('users.index', ['role_id' => $roleItem->id]) }}">
                                                {{ $roleItem->nama }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive users-table-wrap p-3">
                        {!! $dataTable->table() !!}
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
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control"
                                                value="{{ old('username') }}" placeholder="Masukkan username" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">Modul</label>
                                            <select name="role_assignments[0][modul_id]" class="form-select" required>
                                                @foreach ($modules as $mod)
                                                    <option value="{{ $mod->id }}" {{ $mod->kode === 'simpeg' ? 'selected' : '' }}>
                                                        {{ $mod->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">Role Hak Akses (OTK)</label>
                                            <select name="role_assignments[0][role_id]" class="form-select select2" data-placeholder="Pilih role..." required>
                                                <option value="">Pilih role...</option>
                                                @foreach ($roles as $r)
                                                    <option value="{{ $r->id }}">{{ $r->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">Unit Kerja (OTK)</label>
                                            <select name="role_assignments[0][unit_kerja_id]" class="form-select select2" data-placeholder="Pilih unit kerja...">
                                                <option value="">Universitas Ibnu Sina</option>
                                                @foreach ($unitKerjas as $unit)
                                                    <option value="{{ $unit->id }}">[{{ $unit->kode }}] {{ $unit->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Jabatan Struktural (OTK)</label>
                                            <select name="role_assignments[0][jenis_jabatan_struktural_id]" class="form-select select2" data-placeholder="-- Opsional --">
                                                <option value="">-- Opsional --</option>
                                                @foreach ($jabatans as $jab)
                                                    <option value="{{ $jab->id }}">[{{ $jab->kode }}] {{ $jab->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email') }}" placeholder="name@uis.ac.id" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Minimal 6 karakter" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                placeholder="Ulangi password" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
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

@push('style')
    <style>
        .users-table-wrap,
        .users-table-wrap .dt-container,
        #users-table,
        #users-table th,
        #users-table td,
        #users-table code,
        #users-table .badge {
            font-size: 11px !important;
        }

        #users-table th,
        #users-table td {
            padding: 0.45rem 0.6rem;
            white-space: nowrap;
        }
    </style>
@endpush

@push('scripts')
    @if (app()->environment('production'))
        {!! str_replace('http:', 'https:', $dataTable->scripts()) !!}
    @else
        {!! $dataTable->scripts() !!}
    @endif
@endpush
