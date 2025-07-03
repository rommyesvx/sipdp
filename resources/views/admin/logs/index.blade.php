@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .table-container a { text-decoration: none; color: inherit; }
        .table-container a:hover { color: #6759ff; }
        .data-row { background-color: #fff; border-bottom: 1px solid #f0f0f0; transition: all 0.2s ease-in-out; }
        .data-row:hover { background-color: #f8f9fa; transform: scale(1.02); z-index: 2; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.1); }
        .pagination .page-item .page-link { border-radius: 0.5rem; margin: 0 0.25rem; border: none; color: #6c757d; }
        .pagination .page-item.active .page-link { background-color: #6759ff; color: #fff; box-shadow: 0 4px 8px rgba(103, 89, 255, 0.3); }
        .pagination .page-item.disabled .page-link { background-color: transparent; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <h1 class="h3 fw-bold mb-4 text-gray-800">Log Aktivitas Sistem</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive table-container">
                <table class="table table-borderless table-hover align-middle mb-0">
                    <thead class="text-muted small">
                        <tr>
                            <th class="fw-semibold">#</th>
                            <th class="fw-semibold">User</th>
                            <th class="fw-semibold">Aktivitas</th>
                            <th class="fw-semibold">
                                @php $dateDirection = ($sort == 'created_at' && $direction == 'asc') ? 'desc' : 'asc'; @endphp
                                <a href="{{ url()->current() }}?sort=created_at&direction={{ $dateDirection }}">
                                    Waktu @if($sort == 'created_at')<i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ms-1"></i>@endif
                                </a>
                            </th>
                            <th class="fw-semibold">Log Name</th>
                            <th class="fw-semibold text-center">Properti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr class="data-row">
                                <td class="fw-bold">{{ $logs->firstItem() + $index }}</td>
                                <td>{{ $log->causer?->name ?? 'System' }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                <td><span class="badge bg-primary-subtle text-primary-emphasis">{{ $log->log_name }}</span></td>
                                <td class="text-center">
                                    {{-- Tombol pemicu modal tetap di sini --}}
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#logDetailModal{{ $log->id }}">
                                        Detail
                                    </button>
                                    {{-- =============================================== --}}
                                    {{-- Kode Modal yang sebelumnya di sini, SEKARANG DIPINDAHKAN --}}
                                    {{-- =============================================== --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-file-alt fs-2 mb-2"></i>
                                    <p class="mb-0">Belum ada aktivitas yang terekam.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Pagination Links --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

@foreach ($logs as $log)
<div class="modal fade" id="logDetailModal{{ $log->id }}" tabindex="-1" aria-labelledby="logDetailModalLabel{{ $log->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Perubahan Log #{{ $log->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $properties = $log->properties;
                    $old = $properties['old'] ?? [];
                    $new = $properties['attributes'] ?? $properties->toArray();
                @endphp

                @if (empty($old) && empty(array_diff_key($new, ['catatan' => ''])))
                    <div class="alert alert-info">Log ini tidak memiliki data perubahan atribut, namun mungkin memiliki data properti kustom.</div>
                    <pre class="bg-light p-3 rounded small"><code>{{ json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                @else
                    <table class="table table-bordered table-sm">
                        <thead class="table-light"><tr><th style="width: 25%;">Kolom</th><th>Sebelum</th><th>Sesudah</th></tr></thead>
                        <tbody>
                            @foreach ($new as $key => $valueBaru)
                                <tr>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></td>
                                    <td><span class="text-danger">{{ $old[$key] ?? '-' }}</span></td>
                                    <td><span class="text-success">{{ $valueBaru ?? '-' }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection