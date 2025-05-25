@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Dashboard</h2>

<div class="row mb-4">

    <!-- Jumlah Pengguna -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-3 bg-info-subtle">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-people-fill text-info fs-2"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Jumlah Pengguna</h6>
                    <h4 class="mb-0">{{ $jumlahPengguna }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Pegawai -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-3 bg-secondary-subtle">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-person-badge-fill text-secondary fs-2"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Jumlah Pegawai</h6>
                    <h4 class="mb-0">{{ $jumlahPegawai }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Permohonan Diajukan -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-3 bg-light">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-send-fill text-primary fs-2"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Diajukan</h6>
                    <h4 class="mb-0">{{ $jumlahDiajukan }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Permohonan Dieskalasi -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-3 bg-warning-subtle">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-2"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Dieskalasi</h6>
                    <h4 class="mb-0">{{ $jumlahDieskalasi }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Permohonan Ditolak -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-3 bg-danger-subtle">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-x-circle-fill text-danger fs-2"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Ditolak</h6>
                    <h4 class="mb-0">{{ $jumlahDitolak }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Permohonan Selesai -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-3 bg-success-subtle">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-check-circle-fill text-success fs-2"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Selesai</h6>
                    <h4 class="mb-0">{{ $jumlahSelesai }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">Permohonan Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama Pemohon</th>
                        <th>Tanggal Diajukan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permohonanTerbaru as $permohonan)
                    <tr>
                        <td>{{ $permohonan->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($permohonan->created_at)->format('d M Y') }}</td>
                        <td>
                            <span class="badge 
                                    @if($permohonan->status == 'diajukan') bg-primary 
                                    @elseif($permohonan->status == 'dieskalasi') bg-warning 
                                    @elseif($permohonan->status == 'ditolak') bg-danger 
                                    @else bg-success 
                                    @endif">
                                {{ ucfirst($permohonan->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Tren Permohonan Bulanan</h5>
                <canvas id="trenPermohonanChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title text-center">Progress Pelayanan Bulan Ini</h5>
                <div class="progress mb-3" style="height: 30px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentasePelayanan }}%;" aria-valuenow="{{ $persentasePelayanan }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $persentasePelayanan }}%
                    </div>
                </div>
                <p class="text-center small text-muted mb-0">
                    {{ $permohonanSelesai }} dari {{ $permohonanBulanIni }} permohonan telah diselesaikan.
                </p>
            </div>
        </div>
    </div>
</div>


<!-- Script Chart.js -->
<script>
    const trenCtx = document.getElementById('trenPermohonanChart').getContext('2d');
    new Chart(trenCtx, {
        type: 'line',
        data: {
            labels: @json(array_map(fn($n) => \Carbon\Carbon::create()->month($n)->format('M'), array_keys($permohonanBulanan))),
            datasets: [{
                label: 'Jumlah Permohonan',
                data: @json(array_values($permohonanBulanan)),
                backgroundColor: 'rgba(54,162,235,0.2)',
                borderColor: '#36A2EB',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

</script>
@endsection