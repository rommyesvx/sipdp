@extends('layouts.admin')

@section('title', 'Statistik Permohonan')

@section('content')
<h2 class="mb-4 fw-bold">Statistik Permohonan Data</h2>

<div class="row">
    {{-- Saya gabungkan Rata-rata Waktu Penyelesaian ke sini agar lebih ringkas --}}
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
            <div class="card-body"><h5 class="card-title text-center">Tren Permohonan (6 Bulan Terakhir)</h5><canvas id="trenPermohonanChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body"><h5 class="card-title text-center">Jumlah Permohonan per Bulan</h5><canvas id="permohonanPerBulanChart"></canvas></div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100"><div class="card-body"><h5 class="card-title text-center">Distribusi Status Permohonan</h5><canvas id="statusDistribusiChart" style="margin: auto; max-height: 300px;"></canvas></div></div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100"><div class="card-body"><h5 class="card-title text-center">Top 5 Jenis Data Diminta</h5><canvas id="jenisDataChart" style="margin: auto; max-height: 300px;"></canvas></div></div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100"><div class="card-body"><h5 class="card-title text-center">Top 5 User Teraktif</h5><canvas id="topUserChart"></canvas></div></div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100"><div class="card-body"><h5 class="card-title text-center">Distribusi Rating Feedback</h5><canvas id="feedbackChart"></canvas></div></div>
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

    // Chart: Tren Permohonan (Line)
    new Chart(document.getElementById('trenPermohonanChart'), {
        type: 'line', data: { labels: trenLabels, datasets: [{ label: 'Permohonan', data: trenData, borderColor: '#36A2EB', backgroundColor: 'rgba(54,162,235,0.2)', fill: true, tension: 0.3 }] }, options: { responsive: true }
    });

    // Chart: Permohonan per Bulan (Bar)
    new Chart(document.getElementById('permohonanPerBulanChart'), {
        type: 'bar', data: { labels: trenLabels, datasets: [{ label: 'Jumlah Permohonan', data: trenData, backgroundColor: '#4BC0C0' }] }, options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Chart: Distribusi Status (Pie)
    new Chart(document.getElementById('statusDistribusiChart'), {
        type: 'pie', data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#6759ff', '#6c757d'] }] }, options: { responsive: true, maintainAspectRatio: false }
    });

    // Chart: Jenis Data (Doughnut)
    new Chart(document.getElementById('jenisDataChart'), {
        type: 'doughnut', data: { labels: jenisDataLabels, datasets: [{ data: jenisDataCount, backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#8BC34A', '#9966FF'] }] }, options: { responsive: true, maintainAspectRatio: false }
    });

    // Chart: Top 5 User (Bar)
    new Chart(document.getElementById('topUserChart'), {
        type: 'bar', data: { labels: topUserLabels, datasets: [{ label: 'Jumlah Permohonan', data: topUserCount, backgroundColor: '#FF9F40' }] }, options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
    });

    // Chart: Feedback Rating (Bar)
    new Chart(document.getElementById('feedbackChart'), {
        type: 'bar', data: { labels: feedbackLabels, datasets: [{ label: 'Jumlah Feedback', data: feedbackData, backgroundColor: '#00BCD4' }] }, options: { responsive: true, plugins: { legend: { display: false } } }
    });
});
</script>
@endpush