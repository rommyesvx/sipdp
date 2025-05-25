@extends('layouts.admin')

@section('content')
    <h2 class="mb-4">Dashboard</h2>

    <div class="row">
        <!-- Chart Jenis Kelamin -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center">Jenis Kelamin</h5>
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart Agama -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center">Agama</h5>
                    <canvas id="agamaChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart Tingkat Pendidikan -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center">Tingkat Pendidikan</h5>
                    <canvas id="pendidikanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script>
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: @json([$jumlah_laki, $jumlah_perempuan]),
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    borderColor: ['#36A2EB', '#FF6384'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                }
            }
        });

        const agamaCtx = document.getElementById('agamaChart').getContext('2d');
        new Chart(agamaCtx, {
            type: 'pie',
            data: {
                labels: @json(array_keys($agama_data->toArray())),
                datasets: [{
                    data: @json(array_values($pendidikan_data->toArray())),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        const pendidikanCtx = document.getElementById('pendidikanChart').getContext('2d');
        new Chart(pendidikanCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($pendidikan_data->toArray())),
                datasets: [{
                    label: 'Jumlah',
                    data: @json(array_values($pendidikan_data->toArray())),
                    backgroundColor: '#36A2EB',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
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
