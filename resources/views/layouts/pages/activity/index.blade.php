@extends('layouts.dashboard.template')

@section('content')
  <main class="main">
    <div class="main-content page-activity">
      <div class="page-activity">
        <div class="page-header">
          <div>
            <h1 class="page-title">Activity &amp; Sessions</h1>
            <p class="settings-page-subtitle">Pantau riwayat aktivitas akun, login, perubahan profil, dan sesi aktif Anda.</p>
          </div>
          <div class="page-header-actions d-flex gap-2">
            <a href="{{ route('activity.export') }}" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-download me-1"></i> Export Log
            </a>
            <a href="{{ route('profile.index') }}" class="btn btn-primary btn-sm">
              <i class="bi bi-person me-1"></i> My Profile
            </a>
          </div>
        </div>

        <div class="settings-shell row g-3">
          <div class="col-xl-3 col-lg-4">
            <div class="card settings-side-card mb-3">
              <div class="card-body p-2">
                <nav class="settings-nav">
                  <a href="{{ route('profile.index') }}" class="settings-nav-item">
                    <i class="bi bi-person"></i>
                    <div class="settings-nav-text">
                      <span class="settings-nav-label">My Profile</span>
                      <span class="settings-nav-desc">Profil dan keamanan akun</span>
                    </div>
                  </a>
                  <a href="{{ route('activity.index') }}" class="settings-nav-item active">
                    <i class="bi bi-clock-history"></i>
                    <div class="settings-nav-text">
                      <span class="settings-nav-label">Activity Log</span>
                      <span class="settings-nav-desc">Riwayat aktivitas dan sesi</span>
                    </div>
                  </a>
                </nav>
              </div>
            </div>

            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title mb-0">Summary</h5>
              </div>
              <div class="card-body">
                <div class="act-kpi-grid">
                  <div class="act-kpi">
                    <span class="act-kpi-icon"><i class="bi bi-box-arrow-in-right"></i></span>
                    <span class="act-kpi-label">Logins</span>
                    <strong class="act-kpi-value">{{ $loginCount }}</strong>
                  </div>
                  <div class="act-kpi">
                    <span class="act-kpi-icon"><i class="bi bi-shield-check"></i></span>
                    <span class="act-kpi-label">Security</span>
                    <strong class="act-kpi-value">{{ $securityCount }}</strong>
                  </div>
                  <div class="act-kpi">
                    <span class="act-kpi-icon"><i class="bi bi-pencil-square"></i></span>
                    <span class="act-kpi-label">Changes</span>
                    <strong class="act-kpi-value">{{ $changeCount }}</strong>
                  </div>
                  <div class="act-kpi">
                    <span class="act-kpi-icon"><i class="bi bi-x-octagon"></i></span>
                    <span class="act-kpi-label">Failed</span>
                    <strong class="act-kpi-value">{{ $failedCount }}</strong>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Active Sessions</h5>
                <span class="badge bg-primary-subtle text-primary">{{ $sessions->count() }}</span>
              </div>
              <div class="card-body">
                <div class="act-session-list">
                  @foreach ($sessions as $session)
                    <div class="act-session-item">
                      <span class="act-session-icon"><i class="bi {{ $session['icon'] }}"></i></span>
                      <div class="act-session-copy">
                        <div class="act-session-name">{{ $session['name'] }}</div>
                        <div class="act-session-meta">
                          {{ $session['ip'] }} &middot; {{ $session['is_current'] ? 'Current' : $session['last_active'] }}
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-9 col-lg-8 settings-main">
            <div class="card act-stream-card">
              <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0">Recent Activity Timeline</h5>
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('activity.index') }}" class="btn btn-sm {{ blank($selectedCategory) ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
                  <a href="{{ route('activity.index', ['category' => 'auth']) }}" class="btn btn-sm {{ $selectedCategory === 'auth' ? 'btn-primary' : 'btn-outline-secondary' }}">Login</a>
                  <a href="{{ route('activity.index', ['category' => 'security']) }}" class="btn btn-sm {{ $selectedCategory === 'security' ? 'btn-primary' : 'btn-outline-secondary' }}">Security</a>
                  <a href="{{ route('activity.index', ['category' => 'profile']) }}" class="btn btn-sm {{ $selectedCategory === 'profile' ? 'btn-primary' : 'btn-outline-secondary' }}">Profile</a>
                  <a href="{{ route('activity.index', ['category' => 'system']) }}" class="btn btn-sm {{ $selectedCategory === 'system' ? 'btn-primary' : 'btn-outline-secondary' }}">System</a>
                </div>
              </div>
              <div class="card-body p-0">
                @forelse ($groupedLogs as $dateLabel => $items)
                  <div class="act-stream-group">
                    <div class="act-stream-date">{{ $dateLabel }}</div>
                    @foreach ($items as $log)
                      <div class="act-stream-item">
                        <span class="act-stream-dot {{ $log->type }}"></span>
                        <div class="act-stream-content">
                          <div class="act-stream-title">{{ $log->title }}</div>
                          <div class="act-stream-text">
                            {{ $log->description }}
                            @if ($log->ip_address)
                              &middot; IP {{ $log->ip_address }}
                            @endif
                          </div>
                        </div>
                        <div class="act-stream-time" title="{{ $log->created_at?->format('d M Y H:i:s') }}">
                          {{ $log->created_at?->diffForHumans() }}
                        </div>
                      </div>
                    @endforeach
                  </div>
                @empty
                  <div class="text-center py-5 text-muted">
                    <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                    Belum ada catatan aktivitas untuk kategori ini.
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
