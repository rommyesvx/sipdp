@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
    {{-- Style untuk status badge dan elemen kustom lainnya --}}
    <style>
        .stat-card { transition: all 0.2s ease-in-out; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important; }
        .history-row { background-color: #f8f9fa; transition: all 0.2s ease-in-out; }
        .history-row:hover { background-color: #f1f1f1; }
        .status-badge { padding: 0.4rem 0.9rem; font-size: 0.85rem; gap: 0.5rem; }
        .status-badge.bg-eskalasi { background-color: #e7e7ff !important; color: #6759ff !important; }
    </style>
@endpush

@section('content')
    <h1 class="h3 fw-bold mb-4">Dashboard</h1>

    <div class="row">
        {{-- Total Permohonan --}}
        <div class="col-lg col-md-6 col-12 mb-4">
            <div class="card stat-card shadow-sm border-2 border-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Permohonan</p>
                            <h3 class="fw-bold mb-0">{{ $totalPermohonan ?? 0 }}</h3>
                        </div>
                        <div class="fs-2 text-primary opacity-50"><i class="fas fa-file-alt"></i></div>
                    </div>
                    <small class="text-muted mt-2 d-block"><i class="fas fa-arrow-up text-success"></i> 5 dari bulan lalu</small>
                </div>
            </div>
        </div>
        {{-- Dalam Proses --}}
        <div class="col-lg col-md-6 col-12 mb-4">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Dalam Proses</p>
                            <h3 class="fw-bold mb-0">{{ $jumlahDiajukan ?? 0 }}</h3>
                        </div>
                        <div class="fs-2 text-warning opacity-50"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Disetujui --}}
        <div class="col-lg col-md-6 col-12 mb-4">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Disetujui</p>
                            <h3 class="fw-bold mb-0">{{ $jumlahSelesai ?? 0 }}</h3>
                        </div>
                        <div class="fs-2 text-success opacity-50"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Ditolak --}}
        <div class="col-lg col-md-6 col-12 mb-4">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Ditolak</p>
                            <h3 class="fw-bold mb-0">{{ $jumlahDitolak ?? 0 }}</h3>
                        </div>
                        <div class="fs-2 text-danger opacity-50"><i class="fas fa-times-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Eskalasi --}}
        <div class="col-lg col-md-6 col-12 mb-4">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Eskalasi</p>
                            <h3 class="fw-bold mb-0">{{ $jumlahDieskalasi ?? 0 }}</h3>
                        </div>
                        <div class="fs-2" style="color: #6759ff; opacity: 0.5;"><i class="fas fa-level-up-alt"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h4 class="card-title fw-bold mb-4">Permohonan Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="text-muted small">
                        <tr>
                            <th class="fw-semibold">ID Permohonan</th>
                            <th class="fw-semibold">Pemohon</th>
                            <th class="fw-semibold">Tanggal</th>
                            <th class="fw-semibold">Status</th>
                            <th class="fw-semibold text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permohonanTerbaru as $permohonan)
                            <tr class="history-row">
                                <td><strong>#{{ $permohonan->id }}</strong></td>
                                <td>{{ $permohonan->user->name ?? 'N/A' }}</td>
                                <td>{{ $permohonan->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($permohonan->status == 'selesai')
                                        <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                    @elseif($permohonan->status == 'ditolak')
                                        <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-danger-subtle text-danger-emphasis"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                    @elseif($permohonan->status == 'eskalasi')
                                        <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-eskalasi"><i class="fas fa-level-up-alt me-1"></i> Eskalasi</span>
                                    @else
                                        <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-warning-subtle text-warning-emphasis"><i class="fas fa-clock me-1"></i> Diproses</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.permohonan.show', $permohonan->id) }}" onclick="event.stopPropagation();" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada permohonan terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm border-0">
        <div class="card-body p-4 text-center">
            
            {{-- Menambahkan kelas .rounded-pill untuk membuat sudutnya bulat --}}
            <div class="progress rounded-pill mx-auto" style="height: 24px; max-width: 600px;">
                <div class="progress-bar fw-bold" 
                     role="progressbar" 
                     style="width: {{ $persentaseSelesai ?? 0 }}%; background-color: #20c997;" 
                     aria-valuenow="{{ $persentaseSelesai ?? 0 }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                     {{ $persentaseSelesai ?? 0 }}%
                </div>
            </div>

            <p class="text-center text-muted mt-3 mb-0">
                {{ $selesaiBulanIni ?? 0 }} dari {{ $totalBulanIni ?? 0 }} permohonan telah diselesaikan.
            </p>

        </div>
    </div>

@endsection

@push('scripts')
{{-- Jika Anda memerlukan script tambahan untuk dashboard, letakkan di sini --}}
@endpush