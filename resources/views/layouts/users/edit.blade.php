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
                <div class="card-header">
                  <h5 class="card-title">Role & Permissions</h5>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="roles" class="form-label required">Role</label>
                      <select id="roles" name="roles" class="form-select @error('roles') is-invalid @enderror" required>
                        <option value="">Pilih role...</option>
                        @foreach ($roleOptions as $value => $label)
                          <option value="{{ $value }}" {{ old('roles', $user->roles) === $value ? 'selected' : '' }}>
                            {{ $label }}
                          </option>
                        @endforeach
                      </select>
                      @error('roles')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <div class="form-text">Role akses ini tampil pada portal modul setelah login.</div>
                    </div>
                  </div>
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
  </script>
@endpush
