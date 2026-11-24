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

        <form id="userCreateForm" action="{{ route('users.store') }}" method="POST">
          @csrf
          <div class="row g-4">
            <!-- Left Column -->
            <div class="col-xl-4">
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title">Profil Default</h5>
                </div>
                <div class="card-body text-center">
                  <div class="ue-avatar-upload mx-auto">
                    <img src="{{ asset('assets/img/profile-img.webp') }}" alt="User Avatar" class="ue-avatar-preview">
                  </div>
                  <p class="ue-avatar-hint mt-2 mb-0">User baru dapat login menggunakan <strong>username</strong> atau <strong>email</strong>.</p>
                </div>
              </div>

              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Hak Akses Role</h5>
                </div>
                <div class="card-body">
                  <div class="mb-0">
                    <label for="roles" class="form-label required">Role</label>
                    <select id="roles" name="roles" class="form-select @error('roles') is-invalid @enderror" required>
                      <option value="">Pilih role...</option>
                      @foreach ($roleOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('roles') === $value ? 'selected' : '' }}>{{ $label }}</option>
                      @endforeach
                    </select>
                    @error('roles')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Role akan ditampilkan pada halaman portal modul setelah login.</div>
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
