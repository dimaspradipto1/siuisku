<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Modul - SIUISKU Universitas Ibnu Sina</title>

  <!-- Favicons -->
  <link href="{{ asset('assets/img/logouis.png') }}" rel="icon">
  <link href="{{ asset('assets/img/logouis.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Manrope', sans-serif;
      background:
        radial-gradient(circle at 15% 20%, rgba(30, 75, 148, 0.22), transparent 45%),
        radial-gradient(circle at 85% 80%, rgba(14, 165, 233, 0.18), transparent 45%),
        linear-gradient(135deg, #dce6f5 0%, #eef3fa 50%, #d4e2f4 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }

    .portal-shell {
      width: 100%;
      max-width: 960px;
      padding: 12px;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.45);
      backdrop-filter: blur(8px);
      box-shadow: 0 24px 60px rgba(15, 35, 75, 0.16);
    }

    .portal-card {
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(15, 35, 75, 0.06);
    }

    /* Header Banner */
    .portal-header {
      background:
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
        repeating-linear-gradient(
          45deg,
          rgba(255, 255, 255, 0.03) 0px,
          rgba(255, 255, 255, 0.03) 12px,
          transparent 12px,
          transparent 24px
        ),
        linear-gradient(135deg, #1a4489 0%, #2254a8 100%);
      color: #ffffff;
      padding: 20px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }

    .portal-brand {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .portal-logo-wrap {
      width: 58px;
      height: 58px;
      border-radius: 14px;
      background: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 6px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.14);
      flex-shrink: 0;
    }

    .portal-logo-wrap img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .portal-brand-sub {
      font-size: 13px;
      opacity: 0.9;
      margin: 0;
      line-height: 1.3;
    }

    .portal-brand-title {
      font-size: 17px;
      font-weight: 800;
      letter-spacing: 0.3px;
      margin: 2px 0 0;
      line-height: 1.3;
    }

    .portal-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .portal-btn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #ffffff;
      background: rgba(10, 26, 61, 0.32);
      border: 1px solid rgba(255, 255, 255, 0.14);
      text-decoration: none;
      transition: background 0.18s ease, transform 0.15s ease;
    }

    .portal-btn:hover {
      background: rgba(10, 26, 61, 0.52);
      color: #ffffff;
      transform: translateY(-1px);
    }

    /* Body Split */
    .portal-body {
      display: grid;
      grid-template-columns: 1.35fr 1fr;
      min-height: 410px;
    }

    .portal-modules {
      padding: 26px 28px 30px;
      background: #f8fafc;
    }

    .portal-roles {
      padding: 26px 28px 30px;
      background: #f1f5f9;
      border-left: 1px solid #e2e8f0;
    }

    .section-title {
      font-size: 16px;
      font-weight: 700;
      color: #1e293b;
      margin: 0 0 18px;
    }

    .role-module-subtitle {
      font-size: 14px;
      font-weight: 600;
      color: #475569;
      margin: -12px 0 18px;
    }

    /* Module Grid */
    .module-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 14px;
    }

    .module-item {
      background: #ffffff;
      border: 1.5px solid #e5e7eb;
      border-radius: 10px;
      padding: 18px 12px 16px;
      text-align: center;
      cursor: pointer;
      transition: all 0.18s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 10px;
      position: relative;
      min-height: 124px;
      width: 100%;
    }

    .module-item:hover {
      border-color: #93c5fd;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(30, 75, 148, 0.08);
    }

    .module-item.active {
      background: #e2e8f0;
      border-color: #1e4b94;
      box-shadow: 0 4px 14px rgba(30, 75, 148, 0.14);
    }

    .module-item.disabled-module {
      opacity: 0.72;
    }

    .module-icon {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 23px;
    }

    .module-name {
      font-size: 12.5px;
      font-weight: 600;
      color: #334155;
      line-height: 1.35;
    }

    .module-badge {
      position: absolute;
      top: 8px;
      right: 8px;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 999px;
      background: #1e4b94;
      color: #ffffff;
    }

    /* Role Cards */
    .role-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .role-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 16px 18px;
      text-decoration: none;
      transition: all 0.18s ease;
      box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }

    .role-card:hover {
      border-color: #1e4b94;
      transform: translateY(-2px);
      box-shadow: 0 8px 18px rgba(30, 75, 148, 0.12);
    }

    .role-title {
      font-size: 14.5px;
      font-weight: 700;
      color: #1d4ed8;
      margin: 0 0 3px;
    }

    .role-unit {
      font-size: 12.5px;
      color: #64748b;
      margin: 0;
    }

    .role-arrow {
      color: #94a3b8;
      font-size: 16px;
      transition: transform 0.18s ease, color 0.18s ease;
    }

    .role-card:hover .role-arrow {
      color: #1d4ed8;
      transform: translateX(3px);
    }

    .module-unavailable {
      background: #ffffff;
      border: 1px dashed #cbd5e1;
      border-radius: 10px;
      padding: 20px 18px;
      text-align: center;
      color: #64748b;
      font-size: 13px;
    }

    .module-unavailable i {
      font-size: 26px;
      color: #94a3b8;
      display: block;
      margin-bottom: 8px;
    }

    @media (max-width: 768px) {
      .portal-body {
        grid-template-columns: 1fr;
      }

      .portal-roles {
        border-left: 0;
        border-top: 1px solid #e2e8f0;
      }

      .module-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .portal-header {
        padding: 18px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="portal-shell">
    <div class="portal-card">
      <!-- Header -->
      <header class="portal-header">
        <div class="portal-brand">
          <div class="portal-logo-wrap">
            <img src="{{ asset('assets/img/logouis.png') }}" alt="Logo Universitas Ibnu Sina">
          </div>
          <div>
            <p class="portal-brand-sub">Sistem Informasi SIUISKU</p>
            <h1 class="portal-brand-title">UNIVERSITAS IBNU SINA</h1>
          </div>
        </div>

        <div class="portal-actions">
          <a href="{{ route('profile.index') }}" class="portal-btn">
            <i class="bi bi-person-fill"></i>
            <span>Halaman Profil</span>
          </a>
          <a href="{{ route('logout') }}" class="portal-btn">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar</span>
          </a>
        </div>
      </header>

      <!-- Body -->
      <div class="portal-body">
        <!-- Left: Daftar Modul -->
        <section class="portal-modules">
          <h2 class="section-title">Daftar Modul</h2>

          <div class="module-grid">
            @forelse ($modules as $module)
              @php
                $isSelected = $defaultModule && $defaultModule->id === $module->id;
                $displayTitle = $module->singkatan ? "{$module->nama} ({$module->singkatan})" : $module->nama;
              @endphp
              <button
                type="button"
                class="module-item {{ $isSelected ? 'active' : '' }} {{ !$module->is_active ? 'disabled-module' : '' }}"
                data-module-key="{{ $module->kode }}"
                data-module-title="{{ $displayTitle }}"
                data-module-available="{{ $module->is_active ? '1' : '0' }}"
                data-module-url="{{ $module->resolvedUrl() }}"
              >
                @if ($module->is_active)
                  <span class="module-badge">Aktif</span>
                @endif
                <span class="module-icon" style="background: {{ $module->warna_bg }}; color: {{ $module->warna }};">
                  <i class="bi {{ $module->icon }}"></i>
                </span>
                <span class="module-name">{{ $module->nama }}</span>
              </button>
            @empty
              <div class="module-unavailable" style="grid-column: 1 / -1;">
                <i class="bi bi-grid"></i>
                Belum ada modul yang terdaftar.
              </div>
            @endforelse
          </div>
        </section>

        <!-- Right: Daftar Role -->
        <aside class="portal-roles">
          <h2 class="section-title">Daftar Role</h2>
          @php
            $initialTitle = $defaultModule
              ? ($defaultModule->singkatan ? "{$defaultModule->nama} ({$defaultModule->singkatan})" : $defaultModule->nama)
              : 'Belum ada modul';
            $initialAvailable = $defaultModule ? $defaultModule->is_active : false;
            $initialUrl = $defaultModule ? $defaultModule->resolvedUrl() : route('dashboard');
          @endphp
          <p class="role-module-subtitle" id="selectedModuleTitle">{{ $initialTitle }}</p>

          <div id="roleListActive" class="role-list {{ $initialAvailable ? '' : 'd-none' }}">
            @forelse ($userRoles as $role)
              <a href="{{ $initialUrl }}" class="role-card" data-role-link>
                <div>
                  <p class="role-title">{{ ucwords($role) }}</p>
                  <p class="role-unit">Universitas Ibnu Sina</p>
                </div>
                <i class="bi bi-chevron-right role-arrow"></i>
              </a>
            @empty
              <a href="{{ $initialUrl }}" class="role-card" data-role-link>
                <div>
                  <p class="role-title">Staff</p>
                  <p class="role-unit">Universitas Ibnu Sina</p>
                </div>
                <i class="bi bi-chevron-right role-arrow"></i>
              </a>
            @endforelse
          </div>

          <div id="roleUnavailable" class="module-unavailable {{ $initialAvailable ? 'd-none' : '' }}">
            <i class="bi bi-lock"></i>
            Modul ini belum aktif. Silakan pilih modul yang berstatus <strong>Aktif</strong> untuk masuk ke sistem.
          </div>
        </aside>
      </div>
    </div>
  </div>

  <script>
    const moduleButtons = document.querySelectorAll('.module-item');
    const selectedModuleTitle = document.getElementById('selectedModuleTitle');
    const roleListActive = document.getElementById('roleListActive');
    const roleUnavailable = document.getElementById('roleUnavailable');
    const roleLinks = document.querySelectorAll('[data-role-link]');

    moduleButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        moduleButtons.forEach(function (btn) {
          btn.classList.remove('active');
        });
        this.classList.add('active');

        const moduleTitle = this.dataset.moduleTitle;
        const isAvailable = this.dataset.moduleAvailable === '1';
        const moduleUrl = this.dataset.moduleUrl;

        selectedModuleTitle.textContent = moduleTitle;
        roleListActive.classList.toggle('d-none', !isAvailable);
        roleUnavailable.classList.toggle('d-none', isAvailable);

        if (isAvailable && moduleUrl) {
          roleLinks.forEach(function (link) {
            link.setAttribute('href', moduleUrl);
          });
        }
      });
    });
  </script>
</body>

</html>
