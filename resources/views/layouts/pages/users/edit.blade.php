@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content page-users-edit">
      <div class="page-users-edit">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Edit User</h1>
            <nav class="breadcrumb">
              <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
              <a href="{{ route('users.index') }}" class="breadcrumb-item">Users</a>
              <span class="breadcrumb-item active">Edit {{ $user->name }}</span>
            </nav>
          </div>
          <div class="page-header-actions d-flex gap-2">
            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-eye me-1"></i> View Profile
            </a>
            <button type="submit" form="userEditForm" class="btn btn-primary btn-sm">
              <i class="bi bi-check-lg me-1"></i> Save Changes
            </button>
          </div>
        </div>

        <div class="ue-summary-row mb-3">
          <div class="ue-summary-item">
            <span class="ue-summary-icon"><i class="bi bi-person-vcard"></i></span>
            <span class="ue-summary-label">User ID</span>
            <strong class="ue-summary-value">USR-{{ str_pad((string) $user->id, 3, '0', STR_PAD_LEFT) }}</strong>
          </div>
          <div class="ue-summary-item">
            <span class="ue-summary-icon"><i class="bi bi-shield-check"></i></span>
            <span class="ue-summary-label">Role</span>
            <strong class="ue-summary-value">{{ ucwords($user->roles) }}</strong>
          </div>
          <div class="ue-summary-item">
            <span class="ue-summary-icon"><i class="bi bi-at"></i></span>
            <span class="ue-summary-label">Username</span>
            <strong class="ue-summary-value">{{ $user->username }}</strong>
          </div>
          <div class="ue-summary-item">
            <span class="ue-summary-icon"><i class="bi bi-calendar-check"></i></span>
            <span class="ue-summary-label">Joined</span>
            <strong class="ue-summary-value">{{ $user->created_at?->format('d M Y') ?? '-' }}</strong>
          </div>
        </div>

        <form id="userEditForm" action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row g-4">
            <!-- Left Column -->
            <div class="col-xl-4">
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title">Profile Image</h5>
                </div>
                <div class="card-body text-center">
                  <div class="ue-avatar-upload mx-auto">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="ue-avatar-preview" id="editAvatarPreview">
                    <div class="ue-avatar-overlay">
                      <i class="bi bi-camera"></i>
                      <span>Change Photo</span>
                    </div>
                    <input type="file" name="photo" id="editPhotoInput" class="ue-avatar-input" accept="image/png,image/jpeg,image/webp">
                  </div>
                  @error('photo')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                  @enderror
                  <p class="ue-avatar-hint mt-2 mb-0">JPG, PNG atau WEBP. Maksimal 2MB.</p>
                </div>
              </div>

              @if (Auth::id() !== $user->id)
                <div class="card ue-danger-card">
                  <div class="card-header">
                    <h5 class="card-title"><i class="bi bi-exclamation-triangle me-1"></i> Danger Zone</h5>
                  </div>
                  <div class="card-body">
                    <p class="ue-danger-text">Menghapus user bersifat permanen dan tidak dapat dibatalkan.</p>
                    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                      <i class="bi bi-trash me-1"></i> Delete User
                    </button>
                  </div>
                </div>
              @endif
            </div>

            <!-- Right Column -->
            <div class="col-xl-8">
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title">Personal Information</h5>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-12">
                      <label for="name" class="form-label required">Nama Lengkap</label>
                      <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                      >
                      @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <label for="username" class="form-label required">Username</label>
                      <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username', $user->username) }}"
                        required
                      >
                      @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <label for="email" class="form-label required">Email Address</label>
                      <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}"
                        required
                      >
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>

              <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title mb-0">Role &amp; Permissions (OTK)</h5>
                  <button type="button" class="btn btn-outline-primary btn-sm" id="addRoleAssignmentBtn">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Role OTK
                  </button>
                </div>
                <div class="card-body">
                  @php
                    $existingAssignments = old('role_assignments');
                    if ($existingAssignments === null) {
                      $existingAssignments = $user->userRoles->map(fn ($ur) => [
                        'modul_id' => $ur->modul_id,
                        'role_id' => $ur->role_id,
                        'unit_kerja_id' => $ur->unit_kerja_id,
                        'jenis_jabatan_struktural_id' => $ur->jenis_jabatan_struktural_id,
                      ])->all();
                    }
                    if (empty($existingAssignments)) {
                      $defaultSimpegId = $modules->firstWhere('kode', 'simpeg')?->id ?? $modules->first()?->id;
                      $existingAssignments = [[
                        'modul_id' => $defaultSimpegId,
                        'role_id' => '',
                        'unit_kerja_id' => '',
                        'jenis_jabatan_struktural_id' => '',
                      ]];
                    }
                  @endphp

                  <div id="roleAssignmentsContainer" class="d-flex flex-column gap-3">
                    @foreach ($existingAssignments as $idx => $assignment)
                      <div class="border rounded-3 p-3 bg-light-subtle role-assignment-row">
                        <div class="row g-2 align-items-end">
                          <div class="col-md-6">
                            <label class="form-label small mb-1">Modul <span class="text-danger">*</span></label>
                            <select name="role_assignments[{{ $idx }}][modul_id]" class="form-select form-select-sm" required>
                              @foreach ($modules as $mod)
                                <option value="{{ $mod->id }}" {{ (string) ($assignment['modul_id'] ?? '') === (string) $mod->id ? 'selected' : '' }}>
                                  {{ $mod->nama }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label small mb-1">Role Hak Akses <span class="text-danger">*</span></label>
                            <select name="role_assignments[{{ $idx }}][role_id]" class="form-select form-select-sm select2" data-placeholder="Pilih role..." required>
                              <option value="">Pilih role...</option>
                              @foreach ($roles as $r)
                                <option value="{{ $r->id }}" {{ (string) ($assignment['role_id'] ?? '') === (string) $r->id ? 'selected' : '' }}>
                                  {{ $r->nama }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-5">
                            <label class="form-label small mb-1">Unit Kerja (OTK)</label>
                            <select name="role_assignments[{{ $idx }}][unit_kerja_id]" class="form-select form-select-sm select2" data-placeholder="Pilih unit kerja...">
                              <option value="">Universitas Ibnu Sina</option>
                              @foreach ($unitKerjas as $unit)
                                <option value="{{ $unit->id }}" {{ (string) ($assignment['unit_kerja_id'] ?? '') === (string) $unit->id ? 'selected' : '' }}>
                                  [{{ $unit->kode }}] {{ $unit->nama }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label small mb-1">Jabatan Struktural (OTK)</label>
                            <select name="role_assignments[{{ $idx }}][jenis_jabatan_struktural_id]" class="form-select form-select-sm select2" data-placeholder="-- Opsional --">
                              <option value="">-- Opsional --</option>
                              @foreach ($jabatans as $jab)
                                <option value="{{ $jab->id }}" {{ (string) ($assignment['jenis_jabatan_struktural_id'] ?? '') === (string) $jab->id ? 'selected' : '' }}>
                                  [{{ $jab->kode }}] {{ $jab->nama }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-role-row" title="Hapus Baris">
                              <i class="bi bi-trash"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <div class="form-text mt-2">Pengguna dapat memiliki lebih dari satu role/jabatan OTK yang tampil di portal modul saat login.</div>
                </div>
              </div>

              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title">Change Password</h5>
                </div>
                <div class="card-body">
                  <div class="ue-password-note">
                    <i class="bi bi-info-circle"></i>
                    <span>Kosongkan jika tidak ingin mengubah password saat ini.</span>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="password" class="form-label">New Password</label>
                      <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimal 6 karakter"
                      >
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="col-md-6">
                      <label for="password_confirmation" class="form-label">Confirm Password</label>
                      <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Ulangi password baru"
                      >
                    </div>
                  </div>
                </div>
              </div>

              <div class="ue-form-actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-lg me-1"></i> Save Changes
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>

      @if (Auth::id() !== $user->id)
        <!-- Delete User Modal -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-body text-center p-4">
                <div class="ue-delete-icon">
                  <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h5 class="mb-2">Delete User</h5>
                <p class="text-muted mb-4">Yakin ingin menghapus <strong>{{ $user->name }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex justify-content-center gap-2">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <form action="{{ route('users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
    </div>
  </main>
@endsection

@push('script')
  <script>
    const editPhotoInput = document.getElementById('editPhotoInput');
    const editAvatarPreview = document.getElementById('editAvatarPreview');
    if (editPhotoInput && editAvatarPreview) {
      editPhotoInput.addEventListener('change', function (event) {
        const file = event.target.files?.[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
          editAvatarPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
      });
    }

    const roleContainer = document.getElementById('roleAssignmentsContainer');
    const addRoleBtn = document.getElementById('addRoleAssignmentBtn');

    function reindexRoleRows() {
      if (!roleContainer) return;
      const rows = roleContainer.querySelectorAll('.role-assignment-row');
      rows.forEach(function (row, index) {
        row.querySelectorAll('select').forEach(function (select) {
          select.name = select.name.replace(/role_assignments\[\d+\]/, `role_assignments[${index}]`);
        });
        const removeBtn = row.querySelector('.remove-role-row');
        if (removeBtn) {
          removeBtn.disabled = rows.length <= 1;
        }
      });
    }

    if (addRoleBtn && roleContainer) {
      addRoleBtn.addEventListener('click', function () {
        const firstRow = roleContainer.querySelector('.role-assignment-row');
        if (!firstRow) return;

        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
          $(firstRow).find('select.select2').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) {
              $(this).select2('destroy');
            }
          });
        }

        const clone = firstRow.cloneNode(true);
        clone.querySelectorAll('select').forEach(function (select, idx) {
          if (idx > 0) select.value = '';
        });
        roleContainer.appendChild(clone);
        reindexRoleRows();

        if (window.initSelect2) {
          window.initSelect2(firstRow);
          window.initSelect2(clone);
        }
      });

      roleContainer.addEventListener('click', function (event) {
        const btn = event.target.closest('.remove-role-row');
        if (!btn) return;
        const rows = roleContainer.querySelectorAll('.role-assignment-row');
        if (rows.length <= 1) return;
        btn.closest('.role-assignment-row')?.remove();
        reindexRoleRows();
      });

      reindexRoleRows();
    }
  </script>
@endpush
