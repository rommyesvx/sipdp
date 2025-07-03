<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - SIPDP Kota Blitar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            /* Latar belakang abu-abu muda */
        }

        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            /* Lebar sidebar */
            background-color: #ffffff;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
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
    ->where(function($query) {
    $query->where('status', 'diajukan')
    ->orWhere('status', 'eskalasi');
    })
    ->whereNull('admin_read_at')
    ->orderBy('updated_at', 'desc')
    ->take(5)
    ->get();

    $unreadCount = $unreadNotifications->count();
    @endphp
    <div class="main-wrapper">
        <aside class="sidebar d-none d-lg-flex flex-column p-3">
            <h6 class="text-muted text-uppercase small px-3 pt-2 pb-1">Menu Utama</h6>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/permohonan*') ? 'active' : '' }}" href="{{ route('admin.permohonan.index') }}">
                        <i class="nav-icon fas fa-file-import"></i> Permohonan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/statistik*') ? 'active' : '' }}" href="{{ route('admin.statistik') }}">
                        <i class="nav-icon fas fa-chart-bar"></i> Statistik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#laporanSubmenu" role="button" aria-expanded="false" aria-controls="laporanSubmenu">
                        <i class="nav-icon fas fa-print"></i>
                        Data OPD Berdasarkan Statistik
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="laporanSubmenu">
                        <ul class="nav flex-column ps-4">
                            <li class="nav-item">
                                <a href="{{ route('admin.agama') }}" class="nav-link">Agama</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.jeniskelamin') }}" class="nav-link">Jenis Kelamin</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.tingkatpendidikan') }}" class="nav-link">Tingkat Pendidikan</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/feedback*') ? 'active' : '' }}" href="{{ route('admin.feedback.index') }}">
                        <i class="nav-icon fas fa-comment-dots"></i> Feedback
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/log*') ? 'active' : '' }}" href="{{ route('admin.log') }}">
                        <i class="nav-icon fas fa-clock-rotate-left"></i> Log Aktivitas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/datapegawai*') ? 'active' : '' }}" href="{{ route('admin.dataPegawai.index') }}">
                        <i class="nav-icon fas fa-database"></i> Data Pegawai
                    </a>
                </li>
            </ul>
        </aside>

        <div class="content-wrapper">
            <nav class="navbar navbar-expand-lg bg-white shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-light d-lg-none">
                        <i class="fas fa-bars"></i>
                    </button>

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
                                            @if ($notif->status == 'eskalasi')
                                            <i class="fas fa-level-up-alt text-primary fs-4"></i>
                                            @else
                                            <i class="fas fa-file-import text-secondary fs-4"></i>
                                            @endif
                                        </div>
                                        <div class="w-100">
                                            <p class="fw-bold mb-0 text-wrap">{{ $notif->user->name ?? 'User' }}</p>
                                            <p class="small mb-0 text-wrap">Mengajukan permohonan baru dengan status: <strong>{{ ucfirst($notif->status) }}</strong></p>
                                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                </li>
                                @empty
                                <li>
                                    <p class="text-center text-muted small my-3">Tidak ada notifikasi baru.</p>
                                </li>
                                @endforelse
                                <li class="border-top"><a href="{{ route('admin.permohonan.index') }}" class="dropdown-item text-center small py-2">Lihat semua permohonan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fs-4 me-2"></i>
                                {{ Auth::user()->name ?? 'Admin' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> 
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    @stack('scripts')

</body>

</html>