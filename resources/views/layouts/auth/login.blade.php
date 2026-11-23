<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>sistem informasi siuisku</title>
  <meta name="description" coent="sistem informasi siskute">
  <meta name="keywords" cont="sistem informasi siuirap">

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
      <a href="index.html" class="fauth-logo">
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
            <h1 class="fauth-title">Welcome back</h1>
            <p class="fauth-subtitle">Sign in to continue to your SIUISKU workspace.</p>
          </div>

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form class="fauth-form" action="{{ route('loginproses') }}" method="POST">
            @csrf
            <div class="fauth-field">
              <label for="login" class="form-label">Username atau Email</label>
              <input
                type="text"
                class="form-control @error('login') is-invalid @enderror"
                id="login"
                name="login"
                value="{{ old('login') }}"
                placeholder="Masukkan username atau email"
                required
                autofocus
              >
              @error('login')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="fauth-field">
              <div class="fauth-row-between">
                <label for="password" class="form-label">Password</label>
                <a href="auth-forgot-password.html" class="fauth-link">Forgot password?</a>
              </div>
              <div class="input-group">
                <input
                  type="password"
                  class="form-control @error('password') is-invalid @enderror"
                  id="password"
                  name="password"
                  placeholder="Enter your password"
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

            <button type="submit" class="btn btn-primary w-100">Sign In</button>

            <div class="fauth-divider"><span>or continue with</span></div>

            <div class="fauth-social">
              <button type="button" class="btn btn-outline-secondary"><i class="bi bi-google"></i> Google</button>
              <button type="button" class="btn btn-outline-secondary"><i class="bi bi-github"></i> GitHub</button>
            </div>
          </form>

          <p class="fauth-foot-text">Don't have an account? <a href="{{ route('register') }}" class="fauth-link">Create one</a></p>
        </div>

        <footer class="footer-centered">
          <div class="footer-copyright">&copy; 2026 <a href="#">FlexAdmin</a>. All Rights Reserved.</div>
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

  <script>
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

</body>

</html>