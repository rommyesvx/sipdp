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
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@400;700&display=swap');

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
        }

        .high-contrast .dropdown-menu {
            background-color: #212529 !important;
        }

        .high-contrast .dropdown-item {
            color: #00ff00 !important;
        }

        .custom-banner {
            background-color: #e0eaff;
        }

        body {
            transition: all 0.2s ease-in-out;
        }

        body.dyslexic-font {
            font-family: 'Lexend', sans-serif !important;
        }

        body.highlight-links a {
            background-color: #ffeb3b;
            color: #000 !important;
            text-decoration: underline !important;
            padding: 2px 4px;
            border-radius: 3px;
        }

        body.reading-mode .main-content p,
        body.reading-mode .main-content h1,
        body.reading-mode .main-content h2,
        body.reading-mode .main-content h3,
        body.reading-mode .main-content li {
            cursor: pointer;
            position: relative;
        }

        body.reading-mode .main-content .speaking {
            background-color: #f0e68c;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s;
        }

        body.high-contrast {
            background-color: #000;
            color: #fff;
        }

        body.high-contrast a {
            color: #ffff00;
        }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-white shadow-sm py-2 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="32" class="me-2">
                SIPDP Kota Blitar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold fs-6" href="{{ route('dashboardUser') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold fs-6" href="{{ route('tampilFaq') }}">FAQ</a>
                    </li>

                    @guest
                    <li class="nav-item">
                        <a class="btn btn-outline-primary ms-lg-3" href="{{ route('login') }}">Login</a>
                    </li>
                    @endguest

                    @auth
                    <li class="nav-item">
                        <a class="nav-link fw-semibold fs-6" href="{{ route('users.riwayat') }}">Riwayat Permohonan</a>
                    </li>
                    <li class="nav-item dropdown">
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
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link position-relative" id="chat-dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-chat-dots"></i>
                            <span id="chat-badge-navbar" class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="display:none;"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" id="chat-preview-list">
                            <li><span class="dropdown-item-text text-muted">Tidak ada pesan baru</span></li>
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
                        <li class="mb-2"><a href="{{ route('dashboardUser') }}" class="text-decoration-none link-light">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('permohonan.form') }}" class="text-decoration-none link-light">Ajukan Permohonan</a></li>
                        <li><a href="{{ route('tampilFaq') }}" class="text-decoration-none link-light">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> bkdkotablitar@gmail.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> (0342)813902</li>
                        <li class="mb-2"><i class="fas fa-building me-2"></i> Badan Kepegawaian Kota Blitar</li>
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
                <strong class="mb-2 d-block">Tampilan:</strong>
                <div class="btn-group w-100 mb-2" role="group">
                    <button class="btn btn-sm btn-outline-dark" onclick="adjustFontSize(1)" title="Perbesar Teks">A+</button>
                    <button class="btn btn-sm btn-outline-dark" onclick="adjustFontSize(-1)" title="Perkecil Teks">A-</button>
                </div>
                <button class="btn btn-sm btn-outline-dark w-100 mb-2" onclick="toggleHighContrast()">🌓 Kontras Tinggi</button>
                <button class="btn btn-sm btn-outline-dark w-100 mb-3" onclick="toggleDyslexicFont()">🔤 Font Disleksia</button>

                <strong class="mb-2 d-block">Navigasi:</strong>
                <button class="btn btn-sm btn-outline-dark w-100 mb-3" onclick="toggleHighlightLinks()">🔗 Sorot Tautan</button>

                <hr>
                <button class="btn btn-sm btn-danger w-100" onclick="resetAccessibility()">🔁 Reset Semua</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ===============================
        // 1. Fungsi Aksesibilitas
        // ===============================
        function toggleAccessibilityPanel() {
            document.getElementById("accessibilityPanel").classList.toggle("d-none");
        }

        function adjustFontSize(amount) {
            const htmlEl = document.documentElement;
            let currentSize = parseFloat(getComputedStyle(htmlEl).fontSize);
            let newSize = currentSize + amount;
            htmlEl.style.fontSize = newSize + "px";
            saveSetting("fontSize", newSize);
        }

        function toggleHighContrast() {
            document.body.classList.toggle("high-contrast");
            saveSetting("highContrast", document.body.classList.contains("high-contrast"));
        }

        function toggleDyslexicFont() {
            document.body.classList.toggle("dyslexic-font");
            saveSetting("dyslexicFont", document.body.classList.contains("dyslexic-font"));
        }

        function toggleHighlightLinks() {
            document.body.classList.toggle("highlight-links");
            saveSetting("highlightLinks", document.body.classList.contains("highlight-links"));
        }

        function resetAccessibility() {
            document.documentElement.style.fontSize = "";
            document.body.classList.remove("high-contrast", "dyslexic-font", "highlight-links");
            localStorage.removeItem("fontSize");
            localStorage.removeItem("highContrast");
            localStorage.removeItem("dyslexicFont");
            localStorage.removeItem("highlightLinks");
        }

        function saveSetting(key, value) {
            localStorage.setItem(key, value);
        }

        function loadSettings() {
            const fontSize = localStorage.getItem("fontSize");
            if (fontSize) document.documentElement.style.fontSize = fontSize + "px";
            if (localStorage.getItem("highContrast") === "true") document.body.classList.add("high-contrast");
            if (localStorage.getItem("dyslexicFont") === "true") document.body.classList.add("dyslexic-font");
            if (localStorage.getItem("highlightLinks") === "true") document.body.classList.add("highlight-links");
        }

        window.addEventListener('load', loadSettings);

        // ===============================
        // 2. Modal Sukses
        // ===============================
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModalUser'));
            successModal.show();
        });
        @endif

        // ===============================
        // 3. Chat Preview / Notifikasi
        // ===============================
        function waktuLalu(dateString) {
        const now = new Date();
        const past = new Date(dateString);
        const seconds = Math.floor((now - past) / 1000);

        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + " tahun lalu";
        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + " bulan lalu";
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " hari lalu";
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " jam lalu";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " menit lalu";
        return "Baru saja";
    }

        document.addEventListener('DOMContentLoaded', function() {
            const badge = document.getElementById('chat-badge-navbar');
            const previewList = document.getElementById('chat-preview-list');

            function fetchChatPreview() {
            fetch("{{ route('user.chat.preview') }}")
                .then(res => res.json())
                .then(data => {
                    if (!badge || !previewList) return;

                    // Update Badge
                    if (data.length > 0) {
                        badge.style.display = 'inline-block';
                        badge.innerText = data.length;
                    } else {
                        badge.style.display = 'none';
                    }

                    // Update Dropdown List
                    let listHtml = '';
                    if (data.length > 0) {
                        data.forEach((chat, index) => {
                            const messagePreview = chat.message.length > 40 ? chat.message.substring(0, 40) + '...' : chat.message;
                            const chatUrl = `/chat/${chat.permohonan_data_id}`;
                            
                            // ### STRUKTUR HTML BARU UNTUK DROPDOWN ###
                            listHtml += `
                                <li>
                                    <a class="dropdown-item py-2" href="${chatUrl}">
                                        <div class="fw-bold text-dark">Permohonan #${chat.nomor_permohonan}</div>
                                        <div class="small text-muted text-wrap">${messagePreview}</div>
                                        <div class="small text-primary mt-1">${waktuLalu(chat.created_at)}</div>
                                    </a>
                                </li>`;
                            if (index < data.length - 1) {
                                listHtml += '<li><hr class="dropdown-divider my-0"></li>';
                            }
                        });
                    } else {
                        listHtml = '<li><span class="dropdown-item-text text-muted">Tidak ada pesan baru</span></li>';
                    }
                    previewList.innerHTML = listHtml;
                })
                .catch(err => console.error('Fetch chat preview error:', err));
        }

        // polling tiap 5 detik
        setInterval(fetchChatPreview, 5000);
        fetchChatPreview();

        // Logika Modal Sukses
        @if(session('success'))
            var successModal = new bootstrap.Modal(document.getElementById('successModalUser'));
            successModal.show();
        @endif
    });
    </script>



    @stack('scripts')
</body>

</html>