@extends('layouts.kepala')

@section('title', 'Statistik Permohonan')

@section('content')
<h2 class="mb-4 fw-bold">Statistik Permohonan Data</h2>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Rata-rata Waktu Penyelesaian Permohonan</h5>
                <p class="display-4 fw-bold text-success">{{ $rataWaktu ?? 0 }} <span class="fs-4 text-muted">Hari</span></p>
                <small class="text-muted">Waktu rata-rata yang dibutuhkan dari permohonan diajukan hingga diselesaikan.</small>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-center">Tren Permohonan (6 Bulan Terakhir)</h5><canvas id="trenPermohonanChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-center">Distribusi Status Permohonan</h5><canvas id="statusDistribusiChart" style="margin: auto; max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-center">Top 5 Jenis Data Diminta</h5><canvas id="jenisDataChart" style="margin: auto; max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-center">Distribusi Rating Feedback</h5><canvas id="feedbackChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-center">Permohonan Berdasarkan Asal Instansi</h5>
                <canvas id="asalInstansiChart" style="margin: auto; max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari Controller Laravel
            const trenLabels = @json($trenLabels);
            const trenData = @json($trenData);
            const statusLabels = @json($statusLabels);
            const statusData = @json($statusData);
            const jenisDataLabels = @json($jenisDataLabels);
            const jenisDataCount = @json($jenisDataCount);
            const topUserLabels = @json($topUserLabels);
            const topUserCount = @json($topUserCount);
            const feedbackLabels = @json($feedbackLabels);
            const feedbackData = @json($feedbackData);
            const asalLabels = @json($asalLabels);
            const asalData = @json($asalData);
            const perbandinganDitolakLabels = @json($perbandinganDitolakLabels);
            const perbandinganDitolakData = @json($perbandinganDitolakData);

            // Chart: Tren Permohonan (Line)
            new Chart(document.getElementById('trenPermohonanChart'), {
                type: 'line',
                data: {
                    labels: trenLabels,
                    datasets: [{
                        label: 'Permohonan',
                        data: trenData,
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54,162,235,0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Chart: Permohonan per Bulan (Bar)
            new Chart(document.getElementById('permohonanPerBulanChart'), {
                type: 'bar',
                data: {
                    labels: trenLabels,
                    datasets: [{
                        label: 'Jumlah Permohonan',
                        data: trenData,
                        backgroundColor: '#4BC0C0'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Chart: Distribusi Status (Pie)
            new Chart(document.getElementById('statusDistribusiChart'), {
                type: 'pie',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#6759ff', '#6c757d']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Chart: Jenis Data (Doughnut)
            new Chart(document.getElementById('jenisDataChart'), {
                type: 'doughnut',
                data: {
                    labels: jenisDataLabels,
                    datasets: [{
                        data: jenisDataCount,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#8BC34A', '#9966FF']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Chart: Top 5 User (Bar)
            new Chart(document.getElementById('topUserChart'), {
                type: 'bar',
                data: {
                    labels: topUserLabels,
                    datasets: [{
                        label: 'Jumlah Permohonan',
                        data: topUserCount,
                        backgroundColor: '#FF9F40'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Chart: Feedback Rating (Bar)
            new Chart(document.getElementById('feedbackChart'), {
                type: 'bar',
                data: {
                    labels: feedbackLabels,
                    datasets: [{
                        label: 'Jumlah Feedback',
                        data: feedbackData,
                        backgroundColor: '#00BCD4'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            new Chart(document.getElementById('asalInstansiChart'), {
                type: 'pie', // Tipe chart adalah Pie
                data: {
                    labels: asalLabels,
                    datasets: [{
                        data: asalData,
                        backgroundColor: [ // Sediakan beberapa warna untuk potongan pie
                            '#36A2EB', // Biru
                            '#FFCE56', // Kuning
                            '#FF6384', // Merah Muda
                            '#4BC0C0', // Hijau Tosca
                            '#9966FF', // Ungu
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Penting agar ukuran chart fleksibel
                    plugins: {
                        legend: {
                            position: 'bottom', // Letakkan legenda di bawah
                        }
                    }
                }
            });
            new Chart(document.getElementById('perbandinganDitolakChart'), {
                type: 'bar', // Tipe Bar Chart untuk perbandingan
                data: {
                    labels: perbandinganDitolakLabels,
                    datasets: [{
                        label: 'Jumlah Ditolak',
                        data: perbandinganDitolakData,
                        backgroundColor: [
                            'rgba(255, 159, 64, 0.5)', // Warna oranye untuk bulan lalu
                            'rgba(255, 99, 132, 0.5)' // Warna merah untuk bulan ini
                        ],
                        borderColor: [
                            'rgb(255, 159, 64)',
                            'rgb(255, 99, 132)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false // Sembunyikan legenda agar chart lebih bersih
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Memastikan sumbu Y hanya menampilkan angka bulat
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush