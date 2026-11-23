<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Sistem Informasi SIUISKU</title>
  <meta name="description" content="Sistem Informasi SIUISKU">
  <meta name="keywords" content="sistem informasi siuisku">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/logouis.png') }}" rel="icon">
  <link href="{{ asset('assets/img/logouis.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts - Manrope + Sora + JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/phosphor-icons/phosphor-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/lucide-icons/lucide.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/choices.js/choices.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/flatpickr/flatpickr.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body>
  <div class="fauth fauth-split">
    <aside class="fauth-visual">
      <a href="{{ route('login') }}" class="fauth-logo">
        <img src="{{ asset('assets/img/logouis.png') }}" alt="SIUISKU">
        <span>SIUISKU</span>
      </a>
      <h2 class="fauth-visual-title">Command your operations from one modern control center.</h2>
      <p class="fauth-visual-text">Track growth, team activity, and operational risk with a dashboard built for fast decisions.</p>
      <div class="fauth-visual-points">
        <div class="fauth-point"><i class="bi bi-graph-up-arrow"></i> Real-time business insights</div>
        <div class="fauth-point"><i class="bi bi-shield-lock"></i> Enterprise-grade account protection</div>
        <div class="fauth-point"><i class="bi bi-lightning-charge"></i> Fast collaboration workflows</div>
      </div>
    </aside>

    <main class="fauth-main">
      <div class="fauth-main-inner">
        <div class="fauth-card">
          <div class="fauth-card-head">
            <h1 class="fauth-title">Create an account</h1>
            <p class="fauth-subtitle">Daftar akun baru untuk mulai menggunakan SIUISKU.</p>
          </div>

          <form class="fauth-form" action="{{ route('registerproses') }}" method="POST">
            @csrf

            <div class="fauth-field">
              <label for="name" class="form-label">Nama Lengkap</label>
              <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Masukkan nama lengkap"
                required
                autofocus
              >
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="fauth-field">
              <label for="username" class="form-label">Username</label>
              <input
                type="text"
                class="form-control @error('username') is-invalid @enderror"
                id="username"
                name="username"
                value="{{ old('username') }}"
                placeholder="Masukkan username"
                required
              >
              @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="fauth-field">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="name@example.com"
                required
              >
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="fauth-field">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input
                  type="password"
                  class="form-control @error('password') is-invalid @enderror"
                  id="password"
                  name="password"
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

            <div class="fauth-field">
              <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
              <div class="input-group">
                <input
                  type="password"
                  class="form-control"
                  id="password_confirmation"
                  name="password_confirmation"
                  placeholder="Ulangi password"
                  required
                >
                <button class="btn btn-outline-secondary" type="button" data-toggle-password>
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
          </form>

          <p class="fauth-foot-text">Already have an account? <a href="{{ route('login') }}" class="fauth-link">Sign in</a></p>
        </div>

        <footer class="footer-centered">
          <div class="footer-copyright">&copy; 2026 <a href="#">SIUISKU</a>. All Rights Reserved.</div>
          <div class="footer-links"><a href="#">Privacy</a><a href="#">Terms</a><a href="#">Help</a></div>
        </footer>
      </div>
    </main>
  </div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/choices.js/choices.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS Files -->
  <script src="{{ asset('assets/js/theme.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- App Sidebar Toggle (for app pages with sidebars) -->
  <script src="{{ asset('assets/js/apps-sidebar-toggle.js') }}"></script>
</body>

</html>
