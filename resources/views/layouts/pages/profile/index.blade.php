@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content page-profile">
      <div class="page-profile">
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="card profile-shell mb-4">
          <div class="profile-hero">
            <div class="profile-hero-main">
              <div class="profile-avatar-wrap">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar" id="heroAvatarPreview">
                <span class="profile-status-dot"></span>
              </div>
              <div class="profile-hero-copy">
                <div class="profile-eyebrow">{{ ucwords($user->roles) }}</div>
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-role">Akun pengguna Sistem Informasi SIUISKU &bull; Universitas Ibnu Sina.</p>
                <div class="profile-meta-row">
                  <span><i class="bi bi-at"></i> {{ $user->username }}</span>
                  <span><i class="bi bi-envelope"></i> {{ $user->email }}</span>
                  <span><i class="bi bi-calendar3"></i> Bergabung {{ $user->created_at?->format('d M Y') ?? '-' }}</span>
                </div>
              </div>
            </div>
            <div class="profile-hero-actions d-flex gap-2">
              <a href="#edit-profile-section" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Profile
              </a>
              <a href="{{ route('modul') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-grid me-1"></i> Daftar Modul
              </a>
            </div>
          </div>

          <div class="profile-metrics">
            <div class="profile-metric">
              <span class="profile-metric-icon"><i class="bi bi-person-vcard"></i></span>
              <span class="profile-metric-label">User ID</span>
              <strong class="profile-metric-value">USR-{{ str_pad((string) $user->id, 3, '0', STR_PAD_LEFT) }}</strong>
            </div>
            <div class="profile-metric">
              <span class="profile-metric-icon"><i class="bi bi-shield-check"></i></span>
              <span class="profile-metric-label">Role Akses</span>
              <strong class="profile-metric-value">{{ ucwords($user->roles) }}</strong>
            </div>
            <div class="profile-metric">
              <span class="profile-metric-icon"><i class="bi bi-check-circle"></i></span>
              <span class="profile-metric-label">Status Akun</span>
              <strong class="profile-metric-value">Aktif</strong>
            </div>
            <div class="profile-metric">
              <span class="profile-metric-icon"><i class="bi bi-clock-history"></i></span>
              <span class="profile-metric-label">Update Terakhir</span>
              <strong class="profile-metric-value">{{ $user->updated_at?->diffForHumans() ?? '-' }}</strong>
            </div>
          </div>
        </div>

        <div class="row g-3">
          <!-- Left Column -->
          <div class="col-xl-4">
            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title">About</h5>
              </div>
              <div class="card-body">
                <div class="profile-contact-list">
                  <div class="profile-contact-item">
                    <i class="bi bi-person"></i> {{ $user->name }}
                  </div>
                  <div class="profile-contact-item">
                    <i class="bi bi-at"></i> {{ $user->username }}
                  </div>
                  <div class="profile-contact-item">
                    <i class="bi bi-envelope"></i> {{ $user->email }}
                  </div>
                  <div class="profile-contact-item">
                    <i class="bi bi-building"></i> Universitas Ibnu Sina
                  </div>
                  <div class="profile-contact-item">
                    <i class="bi bi-shield-lock"></i> {{ ucwords($user->roles) }}
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Foto Profil</h5>
              </div>
              <div class="card-body text-center">
                <div class="ue-avatar-upload mx-auto mb-2">
                  <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="ue-avatar-preview" id="profileAvatarPreview">
                  <div class="ue-avatar-overlay">
                    <i class="bi bi-camera"></i>
                    <span>Ganti Foto</span>
                  </div>
                  <input
                    type="file"
                    name="photo"
                    form="profileUpdateForm"
                    id="profilePhotoInput"
                    class="ue-avatar-input"
                    accept="image/png,image/jpeg,image/webp"
                  >
                </div>
                @error('photo')
                  <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror
                <p class="ue-avatar-hint mb-3">Klik gambar untuk memilih foto (JPG, PNG, WEBP maks. 2MB), lalu klik <strong>Simpan Profil</strong>.</p>

                @if ($user->photo)
                  <form action="{{ route('profile.photo.destroy') }}" method="POST" onsubmit="return confirm('Hapus foto profil dan kembali ke avatar inisial?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                      <i class="bi bi-trash me-1"></i> Hapus Foto Profil
                    </button>
                  </form>
                @endif
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-xl-8">
            <div class="card mb-3" id="edit-profile-section">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Informasi Profil</h5>
              </div>
              <div class="card-body">
                <form id="profileUpdateForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')

                  <div class="row g-3">
                    <div class="col-12">
                      <label for="name" class="form-label">Nama Lengkap</label>
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
                      <label for="username" class="form-label">Username</label>
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
                      <label for="email" class="form-label">Email Address</label>
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

                    <div class="col-12">
                      <label class="form-label">Role Akses</label>
                      <input type="text" class="form-control" value="{{ ucwords($user->roles) }}" disabled readonly>
                      <div class="form-text">Perubahan role hanya dapat dilakukan oleh administrator.</div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan Profil
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <div class="card" id="change-password-section">
              <div class="card-header">
                <h5 class="card-title mb-0">Ubah Password</h5>
              </div>
              <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                  @csrf
                  @method('PUT')

                  <div class="row g-3">
                    <div class="col-12">
                      <label for="current_password" class="form-label">Password Saat Ini</label>
                      <div class="input-group">
                        <input
                          type="password"
                          id="current_password"
                          name="current_password"
                          class="form-control @error('current_password') is-invalid @enderror"
                          placeholder="Masukkan password saat ini"
                          required
                        >
                        <button class="btn btn-outline-secondary" type="button" data-toggle-password>
                          <i class="bi bi-eye"></i>
                        </button>
                        @error('current_password')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="password" class="form-label">Password Baru</label>
                      <div class="input-group">
                        <input
                          type="password"
                          id="password"
                          name="password"
                          class="form-control @error('password') is-invalid @enderror"
                          placeholder="Minimal 6 karakter"
                          required
                        >
                        <button class="btn btn-outline-secondary" type="button" data-toggle-password>
                          <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                      <div class="input-group">
                        <input
                          type="password"
                          id="password_confirmation"
                          name="password_confirmation"
                          class="form-control"
                          placeholder="Ulangi password baru"
                          required
                        >
                        <button class="btn btn-outline-secondary" type="button" data-toggle-password>
                          <i class="bi bi-eye"></i>
                        </button>
                      </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-shield-lock me-1"></i> Update Password
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('script')
  <script>
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const profileAvatarPreview = document.getElementById('profileAvatarPreview');
    const heroAvatarPreview = document.getElementById('heroAvatarPreview');

    if (profilePhotoInput) {
      profilePhotoInput.addEventListener('change', function (event) {
        const file = event.target.files?.[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
          if (profileAvatarPreview) profileAvatarPreview.src = e.target.result;
          if (heroAvatarPreview) heroAvatarPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
      });
    }

    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
      button.addEventListener('click', function () {
        const input = this.closest('.input-group')?.querySelector('input');
        const icon = this.querySelector('i');
        if (!input) return;

        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        if (icon) {
          icon.classList.toggle('bi-eye', !isPassword);
          icon.classList.toggle('bi-eye-slash', isPassword);
        }
      });
    });
  </script>
@endpush
