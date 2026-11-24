@extends('layouts.dashboard.template')

@section('content')
  @php
    $roleLower = strtolower($user->roles);
    $roleClass = str_contains($roleLower, 'admin') ? 'admin' : (str_contains($roleLower, 'ka.') || str_contains($roleLower, 'manager') ? 'manager' : 'user');
    $roleIcon = $roleClass === 'admin' ? 'bi-shield-check' : ($roleClass === 'manager' ? 'bi-person-gear' : 'bi-person');
  @endphp

  <main class="main">
    <div class="main-content page-users-view">
      <div class="page-users-view">
        <div class="page-header uv-page-header">
          <div>
            <h1 class="page-title">User Command Profile</h1>
            <nav class="breadcrumb">
              <a href="{{ route('dashboard') }}" class="breadcrumb-item">Home</a>
              <a href="{{ route('users.index') }}" class="breadcrumb-item">Users</a>
              <span class="breadcrumb-item active">{{ $user->name }}</span>
            </nav>
          </div>
          <div class="page-header-actions d-flex gap-2">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
              <i class="bi bi-pencil me-1"></i> Edit User
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
                <button type="submit" class="btn btn-outline-danger btn-sm">
                  <i class="bi bi-trash me-1"></i> Delete
                </button>
              </form>
            @endif
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-xxl-8">
            <div class="card uv-identity-card h-100">
              <div class="card-body">
                <div class="uv-identity-head">
                  <div class="uv-identity-user">
                    <div class="uv-identity-avatar-wrap">
                      <img src="{{ asset('assets/img/profile-img.webp') }}" alt="{{ $user->name }}" class="uv-identity-avatar">
                      <span class="uv-identity-status"></span>
                    </div>
                    <div>
                      <h3 class="uv-identity-name">{{ $user->name }}</h3>
                      <p class="uv-identity-email">{{ $user->email }}</p>
                      <div class="uv-identity-tags">
                        <span class="users-role {{ $roleClass }}">
                          <i class="bi {{ $roleIcon }}"></i> {{ ucwords($user->roles) }}
                        </span>
                        <span class="uv-id-chip">#USR-{{ str_pad((string) $user->id, 3, '0', STR_PAD_LEFT) }}</span>
                        <span class="uv-id-chip">{{ $user->username }}</span>
                      </div>
                    </div>
                  </div>
                  <a href="{{ route('users.edit', $user) }}" class="uv-identity-cta">
                    Manage profile <i class="bi bi-arrow-right"></i>
                  </a>
                </div>

                <div class="uv-identity-details mt-4">
                  <div class="uv-detail-item">
                    <span>Username</span>
                    <strong>{{ $user->username }}</strong>
                  </div>
                  <div class="uv-detail-item">
                    <span>Email</span>
                    <strong>{{ $user->email }}</strong>
                  </div>
                  <div class="uv-detail-item">
                    <span>Role Akses</span>
                    <strong>{{ ucwords($user->roles) }}</strong>
                  </div>
                  <div class="uv-detail-item">
                    <span>Terdaftar Sejak</span>
                    <strong>{{ $user->created_at?->format('d M Y, H:i') ?? '-' }}</strong>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-4">
            <div class="card uv-health-card h-100">
              <div class="card-header">
                <h5 class="card-title">Account Health</h5>
              </div>
              <div class="card-body">
                <div class="uv-health-item">
                  <span class="uv-health-label">Status</span>
                  <span class="users-status active"><span class="users-status-dot"></span> Active</span>
                </div>
                <div class="uv-health-item">
                  <span class="uv-health-label">Email Verification</span>
                  @if ($user->email_verified_at)
                    <span class="uv-health-ok"><i class="bi bi-check-circle-fill"></i> Verified</span>
                  @else
                    <span class="uv-health-value text-muted">Belum diverifikasi</span>
                  @endif
                </div>
                <div class="uv-health-item">
                  <span class="uv-health-label">User ID</span>
                  <span class="uv-health-value">USR-{{ str_pad((string) $user->id, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="uv-health-item">
                  <span class="uv-health-label">Terakhir Diperbarui</span>
                  <span class="uv-health-value">{{ $user->updated_at?->diffForHumans() ?? '-' }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
