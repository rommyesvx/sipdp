<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

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
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #212529;
            color: #fff;
            position: fixed;
            width: 240px;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .sidebar h4 {
            padding: 20px;
            background-color: #343a40;
            margin: 0;
            font-size: 18px;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #fff !important;
        }

        .sidebar .nav-link {
            padding: 10px 20px;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .navbar {
            margin-left: 240px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .navbar,
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <h4 class="text-white text-center">Sistem Informasi Pegawai</h4>
        <ul class="nav nav-pills flex-column mb-auto mt-2">
            <li><a href="{{ route('admin.index') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Dashboard</a></li>

            <li><a href="{{ route('admin.permohonan.index') }}" class="nav-link {{ request()->is('admin/permohonan*') ? 'active' : '' }}">
                <i class="bi bi-archive"></i> Permohonan</a></li>

            <li>
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#dataMasterMenu" role="button"
                    aria-expanded="false" aria-controls="dataMasterMenu">
                    <i class="bi bi-folder"></i> Data Master
                </a>
                <div class="collapse" id="dataMasterMenu">
                    <ul class="list-unstyled ms-3">
                        <li><a href="{{ route('admin.index') }}" class="nav-link">Pegawai</a></li>
                        <li><a href="{{ route('admin.jeniskelamin') }}" class="nav-link">Jenis Kelamin</a></li>
                        <li><a href="{{ route('admin.agama') }}" class="nav-link">Agama</a></li>
                        <li><a href="{{ route('admin.tingkatpendidikan') }}" class="nav-link">Pendidikan</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="{{ route('admin.feedback') }}" class="nav-link {{ request()->is('admin/feedback*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i> Feedback</a></li>

            <li><a href="{{ route('admin.log') }}" class="nav-link {{ request()->is('admin/logs*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Log Aktivitas</a></li>

            <li><a href="{{ route('admin.statistik') }}" class="nav-link {{ request()->is('admin/charts*') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Statistik</a></li>
        </ul>
    </div>

    <!-- Navbar (Top) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid justify-content-end">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
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
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
