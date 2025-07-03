@extends('layouts.kepala') {{-- Menggunakan layout khusus untuk Kepala Bidang --}}

@section('title', 'Dashboard Kepala Bidang')

@push('styles')
{{-- Style minimal untuk hover effect yang konsisten --}}
<style>
    .stat-card {
        transition: all 0.2s ease-in-out;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    .data-row:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center mb-4">
    <i class="fas fa-tachometer-alt fs-2 me-3"></i>
    <h1 class="h3 fw-bold mb-0 text-gray-800">Dashboard</h1>
</div>

<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <a href="#" class="card stat-card shadow-sm border-0 h-100 py-2 text-decoration-none">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Permohonan Dieskalasi</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $jumlahEskalasi }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-circle" style="background-color: #e7e7ff;"><i class="fas fa-level-up-alt fa-lg" style="color: #6759ff;"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card stat-card shadow-sm border-0 h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Disetujui</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $jumlahDisetujui }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-circle bg-success-subtle"><i class="fas fa-check fa-lg text-success"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card stat-card shadow-sm border-0 h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">Ditolak</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $jumlahDitolak }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="p-3 rounded-circle bg-danger-subtle"><i class="fas fa-times fa-lg text-danger"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold d-flex align-items-center"><i class="fas fa-level-up-alt me-2"></i> Permohonan Terbaru Dieskalasi</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle">
                <thead class="text-muted small">
                    <tr>
                        <th>ID Permohonan</th>
                        <th>Pemohon</th>
                        <th>Tanggal</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permohonanEskalasiTerbaru as $permohonan)
                    <tr class="data-row">
                        <td><strong>#{{ $permohonan->id }}</strong></td>
                        <td>{{ $permohonan->user->name ?? 'N/A' }}</td>
                        <td>{{ $permohonan->updated_at->format('d/m/Y') }}</td>
                        <td>{{ $permohonan->tujuan }}</td>
                        <td><span class="badge rounded-pill fw-medium d-inline-flex align-items-center p-2" style="background-color: #e7e7ff; color: #6759ff;"><i class="fas fa-level-up-alt me-1"></i> Eskalasi</span></td>
                        <td class="text-end"><a href="{{ route('admin.permohonan.show', $permohonan->id) }}" class="btn btn-primary btn-sm rounded-pill">Detail</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada permohonan yang perlu ditindaklanjuti.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold d-flex align-items-center"><i class="fas fa-history me-2"></i> Aktivitas Terkini Anda</h5>
    </div>
    <div class="card-body">
        @forelse ($aktivitasTerkini as $log)
        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
            <div>
                <p class="fw-semibold mb-0">{{ $log->description }}</p>
                <small class="text-muted">Pada permohonan #{{ $log->subject_id }}</small>
            </div>
            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
        </div>
        @empty
        <p class="text-muted text-center my-3">Anda belum melakukan aktivitas apapun.</p>
        @endforelse
    </div>
</div>

@endsection