<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Permohonan Data')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .high-contrast {
            background-color: #000 !important;
            color: #fff !important;
        }

        .high-contrast a,
        .high-contrast .btn,
        .high-contrast h1,
        .high-contrast h2,
        .high-contrast h3,
        .high-contrast h4,
        .high-contrast h5,
        .high-contrast p,
        .high-contrast span,
        .high-contrast li,
        .high-contrast label {
            color: #00ff00 !important;
            /* Warna teks hijau terang */
        }

        .high-contrast .dropdown-menu {
            background-color: #212529 !important;
        }

        .high-contrast .dropdown-item {
            color: #00ff00 !important;
        }

        body {
            transition: all 0.2s ease-in-out;
        }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-white shadow-sm py-2 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/users/dashboardbaru') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="32" class="me-2">
                SIPDP Kota Blitar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        {{-- Menambahkan kelas fw-semibold dan fs-6 --}}
                        <a class="nav-link fw-semibold fs-6" href="{{ route('dashboardUser') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        {{-- Menambahkan kelas fw-semibold dan fs-6 --}}
                        <a class="nav-link fw-semibold fs-6" href="{{ route('tampilFaq') }}">FAQ</a>
                    </li>

                    @guest
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-lg-3" href="{{ route('login') }}">Login</a>
                    </li>
                    @endguest

                    @auth
                    <li class="nav-item">
                        {{-- Menambahkan kelas fw-semibold dan fs-6 --}}
                        <a class="nav-link fw-semibold fs-6" href="{{ route('users.riwayat') }}">Riwayat Permohonan</a>
                    </li>
                    <li class="nav-item dropdown">
                        {{-- Menambahkan kelas fw-semibold dan fs-6 pada toggle dropdown --}}
                        <a class="nav-link dropdown-toggle d-flex align-items-center fw-semibold fs-6" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D6EFD&color=fff" class="rounded-circle me-2" height="32" alt="avatar">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-5 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">SIPDP Kota Blitar</h5>
                <p class="text-white-50"> 
                    Layanan digital untuk permohonan data pegawai secara online dengan proses cepat dan aman.
                </p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Tautan Cepat</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none link-light">Beranda</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none link-light">Ajukan Permohonan</a></li>
                    <li><a href="#" class="text-decoration-none link-light">FAQ</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Kontak</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@datapegawai.example</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> 08113044019</li>
                    <li class="mb-2"><i class="fas fa-building me-2"></i> Pemerintah Kota Blitar</li>
                </ul>
            </div>
        </div>
    </div>
</footer>

    <div id="accessibilityWidget" class="position-fixed bottom-0 end-0 m-3" style="z-index: 9999;">
        <button class="btn btn-primary rounded-circle shadow p-0" style="width: 50px; height: 50px;" onclick="toggleAccessibilityPanel()" title="Aksesibilitas">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
    @if (session('success'))
    <div class="modal fade" id="successModalUser" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body text-center p-5">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold">Berhasil!</h4>
                    <p class="text-muted">{{ session('success') }}</p>
                    <button type="button" class="btn btn-primary rounded-pill px-4 mt-2" data-bs-dismiss="modal">Oke</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Notifikasi Sukses
       document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModalUser'));
            successModal.show();
        });

        // Fungsi Aksesibilitas
        function toggleAccessibilityPanel() {
            document.getElementById("accessibilityPanel").classList.toggle("d-none")
        }

        function toggleHighContrast() {
            document.body.classList.toggle("high-contrast"), saveSetting("contrast", document.body.classList.contains("high-contrast"))
        }

        function adjustFontSize(e) {
            const t = document.querySelector("html");
            let o = parseFloat(getComputedStyle(t).fontSize);
            o += e, t.style.fontSize = o + "px", saveSetting("fontSize", o)
        }

        function resetAccessibility() {
            document.body.classList.remove("high-contrast"), document.querySelector("html").style.fontSize = "16px", localStorage.removeItem("contrast"), localStorage.removeItem("fontSize")
        }

        function saveSetting(e, t) {
            localStorage.setItem(e, t)
        }

        function loadSettings() {
            "true" === localStorage.getItem("contrast") && document.body.classList.add("high-contrast");
            const e = localStorage.getItem("fontSize");
            e && (document.querySelector("html").style.fontSize = e + "px")
        }
        window.onload = loadSettings;
    </script>
    @endif
    @stack('scripts')
</body>

</html>