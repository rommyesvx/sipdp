<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Layanan Data</title>

  <!-- Fonts & Styles -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/bootstrap-icons.svg" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <style>
    .high-contrast {
        background-color: #000 !important;
        color: #fff !important;
    }

    .high-contrast a,
    .high-contrast button,
    .high-contrast label {
        color: #00ff00 !important;
    }

    body {
        transition: all 0.2s ease-in-out;
    }
</style>

</head>

<body>
  {{-- Navbar/Header --}}
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
        <img src="{{ asset('logo.png') }}" alt="Logo" height="30" class="me-2">
        Sistem Permohonan Data Pegawai
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('tampilLogin') }}">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('tampilFaq') }}">FAQ</a>
          </li>

          @guest
          <li class="nav-item">
            <a class="btn btn-outline-primary ms-3" href="{{ route('login') }}">Login</a>
          </li>
          @endguest

          @auth
          <li class="nav-item">
            <a class="nav-link" href="{{ route('users.riwayat') }}">Riwayat Permohonan</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
              data-bs-toggle="dropdown">
              <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                class="rounded-circle me-2" height="32" alt="avatar">
              {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow">
              <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item text-danger">Logout</button>
                </form>
              </li>
            </ul>
          </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>


  {{-- Main Content --}}
  <main class="py-4">
    @yield('content')
    @if (session('success'))
    <script>
      alert("{{ session('success') }}");
    </script>
    @endif
  </main>

  <!-- Accessibility Widget -->
<div id="accessibilityWidget" class="position-fixed bottom-0 end-0 m-3" style="z-index: 9999;">
    <button class="btn btn-primary rounded-circle shadow" onclick="toggleAccessibilityPanel()" title="Aksesibilitas">
        <i class="bi bi-universal-access-circle fs-4"></i>
    </button>

    <div id="accessibilityPanel" class="card mt-2 shadow d-none" style="width: 280px;">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center">
            <strong class="text-dark">Aksesibilitas</strong>
            <button class="btn btn-sm btn-close" onclick="toggleAccessibilityPanel()"></button>
        </div>
        <div class="card-body small">
            <button class="btn btn-sm btn-outline-dark w-100 mb-2" onclick="toggleHighContrast()">🌓 Kontras Tinggi</button>
            <button class="btn btn-sm btn-outline-dark w-100 mb-2" onclick="adjustFontSize(1)">A+ Perbesar Teks</button>
            <button class="btn btn-sm btn-outline-dark w-100 mb-2" onclick="adjustFontSize(-1)">A- Perkecil Teks</button>
            <button class="btn btn-sm btn-outline-dark w-100" onclick="resetAccessibility()">🔁 Reset Tampilan</button>
        </div>
    </div>
</div>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleAccessibilityPanel() {
        document.getElementById('accessibilityPanel').classList.toggle('d-none');
    }

    function toggleHighContrast() {
        document.body.classList.toggle('high-contrast');
        saveSetting('contrast', document.body.classList.contains('high-contrast'));
    }

    function adjustFontSize(change) {
        const root = document.querySelector('html');
        let currentSize = parseFloat(getComputedStyle(root).fontSize);
        currentSize += change;
        root.style.fontSize = currentSize + 'px';
        saveSetting('fontSize', currentSize);
    }

    function resetAccessibility() {
        document.body.classList.remove('high-contrast');
        document.querySelector('html').style.fontSize = '16px';
        localStorage.removeItem('contrast');
        localStorage.removeItem('fontSize');
    }

    function saveSetting(key, value) {
        localStorage.setItem(key, value);
    }

    function loadSettings() {
        if (localStorage.getItem('contrast') === 'true') {
            document.body.classList.add('high-contrast');
        }
        const fontSize = localStorage.getItem('fontSize');
        if (fontSize) {
            document.querySelector('html').style.fontSize = fontSize + 'px';
        }
    }

    window.onload = loadSettings;
</script>

</body>

</html>
