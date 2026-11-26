  <!-- Header -->
  <header class="header">
    <div class="header-left">
      <a href="index.html" class="header-logo">
        <img src="{{ asset('assets/img/logouis.png') }}" alt="SIUISKU">
        <span>SIUISKU</span>
      </a>
    </div>

    <button class="sidebar-toggle" title="Toggle Sidebar">
      <i class="bi bi-layout-sidebar-inset"></i>
    </button>

    <div class="header-search">
      <form class="search-form" action="search-results.html" method="GET">
        <i class="bi bi-search search-icon"></i>
        <input type="search" name="q" placeholder="Search users, invoices, tickets..." autocomplete="off">
        <kbd class="search-shortcut">/</kbd>
      </form>
    </div>

    <div class="header-right">
      <div class="header-actions-desktop">
        <div class="header-action-cluster">
          <div class="header-action-wrap dropdown lang-dropdown">
            <button class="header-action lang-trigger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Language">
              <img src="https://flagcdn.com/w40/us.png" alt="EN" class="lang-flag">
            </button>
            <div class="dropdown-menu dropdown-menu-end lang-menu">
              <div class="menu-title">Language</div>
              <a class="lang-item active" href="#">
                <img src="https://flagcdn.com/w40/us.png" alt="EN" class="lang-flag">
                <span>English</span>
                <i class="bi bi-check2"></i>
              </a>
              <a class="lang-item" href="#">
                <img src="https://flagcdn.com/w40/fr.png" alt="FR" class="lang-flag">
                <span>Français</span>
              </a>
              <a class="lang-item" href="#">
                <img src="https://flagcdn.com/w40/de.png" alt="DE" class="lang-flag">
                <span>Deutsch</span>
              </a>
              <a class="lang-item" href="#">
                <img src="https://flagcdn.com/w40/es.png" alt="ES" class="lang-flag">
                <span>Español</span>
              </a>
            </div>
          </div>

          <div class="header-action-wrap dropdown quickaccess-dropdown">
            <button class="header-action dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Daftar Modul">
              <i class="bi bi-grid-3x3-gap"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end quickaccess-menu">
              <div class="menu-title d-flex align-items-center justify-content-between">
                <span>Daftar Modul</span>
                <a href="{{ route('modul') }}" class="small text-decoration-none">Semua</a>
              </div>
              <div class="quickaccess-grid">
                @forelse ($headerModules ?? [] as $modul)
                  <a
                    href="{{ $modul->resolvedUrl() }}"
                    class="quickaccess-item"
                    title="{{ $modul->nama }}"
                    @if ($modul->isExternal()) target="_blank" rel="noopener noreferrer" @endif
                  >
                    <span class="quickaccess-icon" style="background: {{ $modul->warna_bg }}; color: {{ $modul->warna }};">
                      <i class="bi {{ $modul->icon }}"></i>
                    </span>
                    <span class="quickaccess-label">{{ $modul->singkatan ?: $modul->nama }}</span>
                  </a>
                @empty
                  <div class="text-center text-muted small py-2" style="grid-column: 1 / -1;">
                    Belum ada modul
                  </div>
                @endforelse
              </div>
            </div>
          </div>

          <button class="header-action theme-toggle" title="Toggle Theme">
            <i class="ph-light ph-moon-stars theme-icon-dark"></i>
            <i class="ph-light ph-sun theme-icon-light"></i>
          </button>

          <div class="header-action-wrap dropdown messages-dropdown">
            <button class="header-action dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Messages">
              <i class="bi bi-chat-left-text"></i>
              <span class="header-badge">5</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end messages-menu">
              <div class="notification-header">
                <div>
                  <h6>Messages</h6>
                  <span class="notification-count">5 new messages</span>
                </div>
                <a href="apps-chat.html" class="notification-mark-read">Open chat</a>
              </div>
              <div class="notification-list">
                <a href="apps-chat.html" class="notification-item unread">
                  <span class="notification-dot"></span>
                  <img src="assets/img/avatars/avatar-2.webp" alt="" class="notification-avatar">
                  <div class="notification-content">
                    <div class="notification-title">Mia Rodriguez</div>
                    <div class="notification-text">Can you review the analytics wireframe today?</div>
                    <span class="notification-time">2m ago</span>
                  </div>
                </a>
                <a href="apps-chat.html" class="notification-item unread">
                  <span class="notification-dot"></span>
                  <img src="assets/img/avatars/avatar-3.webp" alt="" class="notification-avatar">
                  <div class="notification-content">
                    <div class="notification-title">Dev Channel</div>
                    <div class="notification-text">Build passed. Ready for production deploy.</div>
                    <span class="notification-time">12m ago</span>
                  </div>
                </a>
                <a href="apps-chat.html" class="notification-item unread">
                  <span class="notification-dot"></span>
                  <img src="assets/img/avatars/avatar-4.webp" alt="" class="notification-avatar">
                  <div class="notification-content">
                    <div class="notification-title">Sarah Kim</div>
                    <div class="notification-text">Shared a file: Q1-forecast-report.pdf</div>
                    <span class="notification-time">35m ago</span>
                  </div>
                </a>
              </div>
              <div class="notification-footer">
                <a href="apps-chat.html">View all messages <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>

          <div class="header-action-wrap dropdown notification-dropdown">
            <button class="header-action dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
              <i class="bi bi-bell"></i>
              <span class="header-badge">4</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-menu">
              <div class="notification-header">
                <div>
                  <h6>Notifications</h6>
                  <span class="notification-count">4 unread</span>
                </div>
                <a href="#" class="notification-mark-read" data-notification-action="mark-all-read">Mark all read</a>
              </div>

              <div class="notification-list">
                <div class="notification-item unread">
                  <span class="notification-dot"></span>
                  <div class="notification-icon info"><i class="bi bi-rocket-takeoff"></i></div>
                  <div class="notification-content">
                    <div class="notification-title">Deploy Ready</div>
                    <div class="notification-text">Sprint 24 release has passed QA checks.</div>
                    <span class="notification-time">5m ago</span>
                  </div>
                </div>
                <div class="notification-item unread">
                  <span class="notification-dot"></span>
                  <img src="assets/img/avatars/avatar-2.webp" alt="" class="notification-avatar">
                  <div class="notification-content">
                    <div class="notification-title">Mia sent feedback</div>
                    <div class="notification-text">Please review the updated dashboard card hierarchy.</div>
                    <span class="notification-time">21m ago</span>
                  </div>
                </div>
                <div class="notification-item unread">
                  <span class="notification-dot"></span>
                  <div class="notification-icon warning"><i class="bi bi-exclamation-triangle"></i></div>
                  <div class="notification-content">
                    <div class="notification-title">Storage threshold</div>
                    <div class="notification-text">Media bucket reached 81% utilization.</div>
                    <span class="notification-time">58m ago</span>
                  </div>
                </div>
                <div class="notification-item unread">
                  <span class="notification-dot"></span>
                  <div class="notification-icon success"><i class="bi bi-check2-circle"></i></div>
                  <div class="notification-content">
                    <div class="notification-title">Payment received</div>
                    <div class="notification-text">Invoice #INV-3921 was settled successfully.</div>
                    <span class="notification-time">2h ago</span>
                  </div>
                </div>
              </div>

              <div class="notification-footer">
                <a href="notifications.html">Open notification center <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>

        <span class="header-divider"></span>

        <div class="header-action-wrap dropdown user-dropdown">
          <button class="dropdown-toggle user-trigger" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ Auth::user()?->avatar_url }}" alt="User" class="user-avatar">
            <div class="user-brief">
              <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
              <span class="user-role">{{ session('active_role_name') ?? ucwords(Auth::user()->roles ?? '') }}</span>
            </div>
            <i class="bi bi-chevron-down user-chevron"></i>
          </button>

          <div class="dropdown-menu dropdown-menu-end user-menu">
            <div class="user-menu-header">
              <img src="{{ Auth::user()?->avatar_url }}" alt="User" class="user-menu-avatar">
              <div class="user-menu-info">
                <div class="user-menu-name">{{ Auth::user()->name ?? 'User' }}</div>
                <div class="user-menu-email">{{ Auth::user()->email ?? '' }}</div>
              </div>
            </div>
            <div class="user-menu-body">
              <a class="user-menu-item" href="{{ route('modul') }}">
                <span class="user-menu-icon"><i class="bi bi-grid"></i></span>
                <span>Daftar Modul</span>
              </a>
              <a class="user-menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                <span class="user-menu-icon"><i class="bi bi-person"></i></span>
                <span>My Profile</span>
              </a>
              <a class="user-menu-item" href="settings.html">
                <span class="user-menu-icon"><i class="bi bi-sliders"></i></span>
                <span>Preferences</span>
              </a>
              <a class="user-menu-item {{ request()->routeIs('activity.*') ? 'active' : '' }}" href="{{ route('activity.index') }}">
                <span class="user-menu-icon"><i class="bi bi-activity"></i></span>
                <span>Activity Log</span>
              </a>
            </div>
            <div class="user-menu-footer">
              <a class="user-menu-logout" href="{{ route('logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="header-actions-mobile">
        <button class="header-action search-toggle" title="Search">
          <i class="bi bi-search"></i>
        </button>

        <button class="header-action mobile-menu-toggle" title="More">
          <i class="bi bi-three-dots"></i>
        </button>
      </div>
    </div>
  </header>