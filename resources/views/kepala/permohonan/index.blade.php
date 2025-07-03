@extends('layouts.kepala')

@section('title', 'Daftar Permohonan Eskalasi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .stat-card a { text-decoration: none; }
        .stat-card .card-body { transition: all 0.2s ease-in-out; }
        .stat-card:hover .card-body { transform: translateY(-5px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important; }
        .data-row:hover { background-color: #f8f9fa; }
        .table-container a { text-decoration: none; color: inherit; }
        .table-container a:hover { color: #6759ff; }
        .pagination .page-item.active .page-link { background-color: #6759ff; border-color: #6759ff; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <h1 class="h3 fw-bold mb-4 text-gray-800">Permohonan Eskalasi</h1>

    <div class="row">
        <div class="col-md-6 mb-4 stat-card">
             <div class="card shadow-sm border-0 h-100 py-2">
                <div class="card-body"><div class="row no-gutters align-items-center"><div class="col"><div class="text-xs fw-bold text-warning text-uppercase mb-1">Sedang Diproses (Staf)</div><div class="h5 mb-0 fw-bold text-gray-800">{{ $jumlahDiproses }}</div></div><div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div></div></div>
            </div>
        </div>
        <div class="col-md-6 mb-4 stat-card">
            {{-- Kartu ini dibuat clickable --}}
            <a href="{{ route('kepala.permohonan.index') }}">
                <div class="card shadow-sm border-left-primary h-100 py-2">
                    <div class="card-body"><div class="row no-gutters align-items-center"><div class="col"><div class="text-xs fw-bold text-primary text-uppercase mb-1">Menunggu Tindakan Anda (Eskalasi)</div><div class="h5 mb-0 fw-bold text-gray-800">{{ $jumlahEskalasi }}</div></div><div class="col-auto"><i class="fas fa-level-up-alt fa-2x text-gray-300"></i></div></div></div>
                </div>
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Daftar Permohonan untuk Ditindaklanjuti</h5>
        </div>
        <div class="table-responsive table-container">
            <table class="table table-borderless table-hover align-middle mb-0">
                <thead class="text-muted small">
                    <tr>
                        <th>#</th>
                        <th>Pemohon</th>
                        <th>Tujuan Permohonan</th>
                        <th>
                            @php $dateDirection = ($sort == 'updated_at' && $direction == 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ url()->current() }}?sort=updated_at&direction={{ $dateDirection }}">
                                Tgl Eskalasi @if($sort == 'updated_at')<i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ms-1"></i>@endif
                            </a>
                        </th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permohonans as $index => $permohonan)
                        <tr class="data-row">
                            <td class="fw-bold">{{ $permohonans->firstItem() + $index }}</td>
                            <td>{{ $permohonan->user->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($permohonan->tujuan, 70) }}</td>
                            <td>{{ $permohonan->updated_at->format('d M Y, H:i') }}</td>
                            <td class="text-center">
                                {{-- Link ini akan mengarah ke halaman detail admin yang sudah kita buat --}}
                                <a href="{{ route('kepala.permohonan.show', $permohonan->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                    Proses Sekarang
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fs-2 text-success mb-2"></i>
                                <p class="mb-0 fw-bold">Kerja Bagus!</p>
                                <p>Tidak ada permohonan eskalasi yang perlu ditindaklanjuti.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($permohonans->hasPages())
        <div class="card-footer bg-white">
            {{ $permohonans->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary { border-left: .25rem solid #6759ff !important; }
    .border-left-warning { border-left: .25rem solid #f6c23e !important; }
    .text-xs { font-size: .7rem; }
</style>
@endpush