<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Judul diubah untuk Kepala Bidang --}}
    <title>@yield('title', 'Kepala Bidang Panel') - SIPDP Kota Blitar</title>

    {{-- Semua link CSS dan style tetap sama untuk konsistensi desain --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
        }

        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: #ffffff;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            font-size: 1rem;
            font-weight: 500;
            color: #5a6a85;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link .nav-icon {
            width: 24px;
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .sidebar .nav-link:hover {
            background-color: #f4f7fc;
            color: #3b76e1;
        }

        .sidebar .nav-link.active {
            background-color: #e9effd;
            color: #3b76e1;
            font-weight: 600;
        }

        .content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
    </style>
    @stack('styles')
</head>

<body>

    @php
    $unreadNotifications = \App\Models\PermohonanData::with('user')
    ->where('status', 'eskalasi')
    ->whereNull('admin_read_at')
    ->orderBy('updated_at', 'desc')
    ->take(5)
    ->get();
    $unreadCount = $unreadNotifications->count();

    $eskalasiCount = \App\Models\PermohonanData::where('status', 'eskalasi')->count();
    @endphp

    <div class="main-wrapper">
        <aside class="sidebar d-none d-lg-flex flex-column p-3">
            <h6 class="text-muted text-uppercase small px-3 pt-2 pb-1">Menu Utama</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kepala/dashboard*') ? 'active' : '' }}" href="{{ route('kepala.dashboard') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kepala/permohonan') ? 'active' : '' }}" href="{{ route('kepala.permohonan.index') }}">
                        <i class="nav-icon fas fa-file-import"></i>
                        <span>Permohonan Eskalasi</span>
                        @if($eskalasiCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $eskalasiCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kepala/generatelaporan*') ? 'active' : '' }}" href="{{ route('kepala.laporan.index') }}">
                        <i class="nav-icon fas fa-file-invoice"></i> Generate Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kepala/feedback*') ? 'active' : '' }}" href="{{ route('kepala.feedback.index') }}">
                        <i class="nav-icon fas fa-comment-dots"></i> Feedback
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kepala/statistik') ? 'active' : '' }}" href="{{ route('kepala.statistik') }}">
                        <i class="nav-icon fas fa-chart-bar"></i> Statistik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kepala/statistik-pegawai') ? 'active' : '' }}" href="{{ route('kepala.statistik-pegawai.index') }}">
                        <i class="nav-icon fas fa-chart-bar"></i> Statistik Pegawai
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kepala/permohonan/semua') ? 'active' : '' }}" href="{{ route('kepala.permohonan.semua') }}">
                        <i class="nav-icon fas fa-search"></i>
                        <span>Monitoring Permohonan</span>
                    </a>
                </li>
            </ul>
        </aside>

        <div class="content-wrapper">
            <nav class="navbar navbar-expand-lg bg-white shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-light d-lg-none"><i class="fas fa-bars"></i></button>
                    <a class="navbar-brand fw-bold text-primary d-none d-lg-block" href="#">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
                        SIPDP Kota Blitar
                    </a>
                    <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" title="Notifikasi">
                                <i class="fas fa-bell fs-5"></i>
                                @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                                    {{ $unreadCount }}
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="width: 350px;">
                                <li class="p-2 border-bottom">
                                    <h6 class="fw-bold mb-0">Notifikasi Baru</h6>
                                    @if($unreadCount > 0)
                                    <form action="{{ route('admin.notifikasi.bacaSemua') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-decoration-none p-0" style="font-size: 0.8em;">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                    @endif
                                </li>
                                @forelse ($unreadNotifications as $notif)
                                <li>
                                    <a class="dropdown-item py-2 d-flex" href="{{ route('admin.notifikasi.baca', $notif->id) }}">
                                        <div class="me-3">
                                            <i class="fas fa-level-up-alt text-primary fs-4"></i>
                                        </div>
                                        <div class="w-100">
                                            <p class="fw-bold mb-0 text-wrap">{{ $notif->user->name ?? 'User' }}</p>
                                            <p class="small mb-1 text-wrap">
                                                Permohonan dieskalasi oleh staf.
                                            </p>
                                            @if($notif->alasan_eskalasi)
                                            <div class="p-2 rounded" style="background-color: #f0f2f5;">
                                                <p class="small fst-italic mb-0 text-dark">
                                                    Alasan: "{{ Str::limit($notif->alasan_eskalasi, 50) }}"
                                                </p>
                                            </div>
                                            @endif

                                            <small class="text-muted d-block mt-2">{{ $notif->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                </li>
                                @empty
                                <li>
                                    <p class="text-center text-muted small my-3">Tidak ada notifikasi eskalasi baru.</p>
                                </li>
                                @endforelse
                                <li class="border-top"><a href="{{ route('admin.permohonan.index') }}" class="dropdown-item text-center small py-2">Lihat semua permohonan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fs-4 me-2"></i>
                                {{ Auth::user()->name ?? 'Kepala Bidang' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="flex-grow-1 p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>