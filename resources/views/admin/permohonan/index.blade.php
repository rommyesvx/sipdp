@extends('layouts.admin')

@section('title', 'Daftar Permohonan')

@push('styles')
{{-- Font Awesome diperlukan untuk ikon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .table-container a {
        text-decoration: none;
        color: inherit;
    }

    .table-container a:hover {
        color: #6759ff;
    }

    /* Warna hover untuk link sorting */

    .data-row {
        cursor: pointer;
        /* Mengubah kursor menjadi tangan saat diarahkan ke baris */
        transition: all 0.2s ease-in-out;
    }

    .data-row:hover {
        transform: translateY(-2px);
        /* Efek sedikit terangkat saat hover */
        box-shadow: 0 0.3rem .8rem rgba(0, 0, 0, .075) !important;
        z-index: 2;
    }

    .status-badge.bg-eskalasi {
        background-color: #e7e7ff !important;
        color: #6759ff !important;
    }

    .pagination .page-item.active .page-link {
        background-color: #6759ff;
        color: #fff;
        border-color: #6759ff;
        box-shadow: 0 4px 8px rgba(103, 89, 255, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0 text-gray-800">Daftar Permohonan</h1>
        <span class="text-muted">Total: {{ $permohonans->total() }} permohonan</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive table-container">
                <table class="table table-borderless table-hover align-middle mb-0">
                    <thead class="text-muted small">
                        <tr>
                            <th class="fw-semibold">#</th>
                            <th class="fw-semibold">Pemohon</th>
                            <th class="fw-semibold">Jenis Data</th>
                            <th class="fw-semibold">
                                @php $dateDirection = ($sort == 'created_at' && $direction == 'asc') ? 'desc' : 'asc'; @endphp
                                <a href="{{ url()->current() }}?sort=created_at&direction={{ $dateDirection }}">
                                    Tgl Diajukan @if($sort == 'created_at')<i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ms-1"></i>@endif
                                </a>
                            </th>
                            <th class="fw-semibold">
                                @php $statusDirection = ($sort == 'status' && $direction == 'asc') ? 'desc' : 'asc'; @endphp
                                <a href="{{ url()->current() }}?sort=status&direction={{ $statusDirection }}">
                                    Status @if($sort == 'status')<i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ms-1"></i>@endif
                                </a>
                            </th>
                            <th class="fw-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permohonans as $index => $permohonan)
                        <tr class="data-row" data-href="{{ route('admin.permohonan.show', $permohonan->id) }}" style="cursor: pointer;">
                            <td class="fw-bold">{{ $permohonans->firstItem() + $index }}</td>
                            <td>{{ $permohonan->user->name ?? 'N/A' }}</td>

                            <td>{!! $permohonan->formatted_jenis_data !!}</td>

                            <td>{{ $permohonan->created_at->format('d M Y') }}</td>
                            <td>
                                @if($permohonan->status == 'selesai')
                                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                @elseif($permohonan->status == 'ditolak')
                                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-danger-subtle text-danger-emphasis"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                @elseif($permohonan->status == 'eskalasi')
                                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-eskalasi"><i class="fas fa-level-up-alt me-1"></i> Eskalasi</span>
                                @elseif($permohonan->status == 'sudah eskalasi')
                                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-info-subtle text-info-emphasis">
                                    <i class="fas fa-check-double me-1"></i> Disetujui Kepala Bidang
                                </span>
                                @else
                                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-warning-subtle text-warning-emphasis"><i class="fas fa-clock me-1"></i> Diproses</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Menambahkan onclick untuk mencegah event bubbling --}}
                                <a href="{{ route('admin.permohonan.show', $permohonan->id) }}" onclick="event.stopPropagation();" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada permohonan data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination Links --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $permohonans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Menambahkan event listener ke semua baris yang memiliki atribut data-href
    document.querySelectorAll('tr[data-href]').forEach(row => {
        row.addEventListener('click', function() {
            // Arahkan ke URL yang ada di atribut data-href
            window.location.href = this.dataset.href;
        });
    });
</script>
@endpush