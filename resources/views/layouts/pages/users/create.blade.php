@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content page-users-edit">
      <div class="page-users-edit">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Tambah User</h1>
            <nav class="breadcrumb">
              <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
              <a href="{{ route('users.index') }}" class="breadcrumb-item">Users</a>
              <span class="breadcrumb-item active">Tambah User</span>
            </nav>
          </div>
          <div class="page-header-actions">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button type="submit" form="userCreateForm" class="btn btn-primary btn-sm">
              <i class="bi bi-check-lg me-1"></i> Simpan User
            </button>
          </div>
        </div>

        <form id="userCreateForm" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row g-4">
            <!-- Left Column -->
            <div class="col-xl-4">
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title">Profile Image</h5>
                </div>
                <div class="card-body text-center">
                  @php
                    $defaultNewAvatar = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><rect width="128" height="128" rx="64" fill="#dbeafe"/><text x="50%" y="53%" dominant-baseline="middle" text-anchor="middle" font-family="Manrope, Arial, sans-serif" font-size="44" font-weight="700" fill="#1d4ed8">NEW</text></svg>');
                  @endphp
                  <div class="ue-avatar-upload mx-auto">
                    <img src="{{ $defaultNewAvatar }}" alt="User Avatar" class="ue-avatar-preview" id="createAvatarPreview">
                    <div class="ue-avatar-overlay">
                      <i class="bi bi-camera"></i>
                      <span>Upload Photo</span>
                    </div>
                    <input type="file" name="photo" id="createPhotoInput" class="ue-avatar-input" accept="image/png,image/jpeg,image/webp">
                  </div>
                  @error('photo')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                  @enderror
                  <p class="ue-avatar-hint mt-2 mb-0">Opsional. Jika kosong akan otomatis menggunakan avatar inisial nama.</p>
                </div>
              </div>

              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Hak Akses &amp; OTK</h5>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label class="form-label required">Modul</label>
                    <select name="role_assignments[0][modul_id]" class="form-select" required>
                      @foreach ($modules as $mod)
                        <option value="{{ $mod->id }}" {{ $mod->kode === 'simpeg' ? 'selected' : '' }}>
                          {{ $mod->nama }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="form-label required">Role Hak Akses</label>
                    <select name="role_assignments[0][role_id]" class="form-select @error('role_assignments.0.role_id') is-invalid @enderror" required>
                      <option value="">Pilih role...</option>
                      @foreach ($roles as $r)
                        <option value="{{ $r->id }}" {{ (string) old('role_assignments.0.role_id') === (string) $r->id ? 'selected' : '' }}>
                          {{ $r->nama }} ({{ $r->scopeLabel() }})
                        </option>
                      @endforeach
                    </select>
                    @error('role_assignments.0.role_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Unit Kerja (OTK)</label>
                    <select name="role_assignments[0][unit_kerja_id]" class="form-select">
                      <option value="">Universitas Ibnu Sina (Default)</option>
                      @foreach ($unitKerjas as $unit)
                        <option value="{{ $unit->id }}" {{ (string) old('role_assignments.0.unit_kerja_id') === (string) $unit->id ? 'selected' : '' }}>
                          [{{ $unit->kode }}] {{ $unit->nama }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-0">
                    <label class="form-label">Jabatan Struktural (OTK)</label>
                    <select name="role_assignments[0][jenis_jabatan_struktural_id]" class="form-select">
                      <option value="">-- Opsional --</option>
                      @foreach ($jabatans as $jab)
                        <option value="{{ $jab->id }}" {{ (string) old('role_assignments.0.jenis_jabatan_struktural_id') === (string) $jab->id ? 'selected' : '' }}>
                          [{{ $jab->kode }}] {{ $jab->nama }}
                        </option>
                      @endforeach
                    </select>
                    <div class="form-text">Role &amp; unit kerja OTK ini otomatis tampil di portal modul saat login.</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="col-xl-8">
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title">Informasi Akun</h5>
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
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
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
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
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
                        value="{{ old('email') }}"
                        placeholder="name@uis.ac.id"
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
                  <h5 class="card-title">Password</h5>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="password" class="form-label required">Password</label>
                      <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimal 6 karakter"
                        required
                      >
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="col-md-6">
                      <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
                      <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Ulangi password"
                        required
                      >
                    </div>
                  </div>
                </div>
              </div>

              <div class="ue-form-actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-lg me-1"></i> Simpan User
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
@endsection

@push('script')
  <script>
    const createPhotoInput = document.getElementById('createPhotoInput');
    const createAvatarPreview = document.getElementById('createAvatarPreview');
    if (createPhotoInput && createAvatarPreview) {
      createPhotoInput.addEventListener('change', function (event) {
        const file = event.target.files?.[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
          createAvatarPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
      });
    }
  </script>
@endpush
