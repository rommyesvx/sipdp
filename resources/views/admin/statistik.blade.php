@extends('layouts.admin')

@section('content')
<h2 class="mb-4">Statistik Permohonan Data</h2>

<div class="row mb-4">
    <!-- Tren Perkembangan Permohonan -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Tren Perkembangan Permohonan</h5>
                <canvas id="trenPermohonanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Jumlah Permohonan per Bulan -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Jumlah Permohonan per Bulan</h5>
                <canvas id="permohonanPerBulanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Distribusi Status Permohonan -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Distribusi Status Permohonan</h5>
                <canvas id="statusDistribusiChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Rata-rata Waktu Penyelesaian -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Rata-rata Waktu Penyelesaian (Hari)</h5>
                <canvas id="rataWaktuChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Permohonan per Jenis Data -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Permohonan per Jenis Data</h5>
                <canvas id="jenisDataChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top 5 User Teraktif -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Top 5 User Teraktif</h5>
                <canvas id="topUserChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Feedback Rating -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Feedback Rating Pengguna</h5>
                <canvas id="feedbackChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Script Chart.js Dummy -->
<script>
    // Dummy data
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const permohonanTrend = [5, 10, 8, 15, 12, 18];
    const statusDistribusi = ['Diajukan', 'Dieskalasi', 'Ditolak', 'Selesai'];
    const statusCount = [12, 4, 2, 20];
    const jenisData = ['SK Pegawai', 'Riwayat Jabatan', 'Gaji', 'Pendidikan'];
    const jenisCount = [10, 8, 6, 4];
    const userNames = ['Andi', 'Budi', 'Citra', 'Dina', 'Eka'];
    const userCount = [8, 7, 6, 5, 5];
    const feedbackRating = ['1', '2', '3', '4', '5'];
    const feedbackCount = [1, 2, 3, 6, 10];

    // Chart tren permohonan
    new Chart(document.getElementById('trenPermohonanChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Permohonan',
                data: permohonanTrend,
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54,162,235,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true }
    });

    // Jumlah permohonan per bulan
    new Chart(document.getElementById('permohonanPerBulanChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Permohonan',
                data: permohonanTrend,
                backgroundColor: '#4BC0C0'
            }]
        },
        options: { responsive: true }
    });

    // Status distribusi (pie)
    new Chart(document.getElementById('statusDistribusiChart'), {
        type: 'pie',
        data: {
            labels: statusDistribusi,
            datasets: [{
                data: statusCount,
                backgroundColor: ['#007bff', '#ffc107', '#dc3545', '#28a745']
            }]
        },
        options: { responsive: true }
    });

    // Rata-rata waktu penyelesaian
    new Chart(document.getElementById('rataWaktuChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Rata-rata Hari',
                data: [3, 4, 5, 3.5, 2, 6],
                backgroundColor: '#9966FF'
            }]
        },
        options: { responsive: true }
    });

    // Jenis data
    new Chart(document.getElementById('jenisDataChart'), {
        type: 'doughnut',
        data: {
            labels: jenisData,
            datasets: [{
                data: jenisCount,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#8BC34A']
            }]
        },
        options: { responsive: true }
    });

    // Top 5 user teraktif
    new Chart(document.getElementById('topUserChart'), {
        type: 'horizontalBar',
        data: {
            labels: userNames,
            datasets: [{
                label: 'Jumlah Permohonan',
                data: userCount,
                backgroundColor: '#FF9F40'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true
        }
    });

    // Feedback rating
    new Chart(document.getElementById('feedbackChart'), {
        type: 'bar',
        data: {
            labels: feedbackRating,
            datasets: [{
                label: 'Jumlah Feedback',
                data: feedbackCount,
                backgroundColor: '#00BCD4'
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection
