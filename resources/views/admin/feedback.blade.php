@extends('layouts.admin')

@section('title', 'Data Feedback Pengguna')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .table-container a {
        text-decoration: none;
        color: inherit;
    }

    .table-container a:hover {
        color: #6759ff;
    }

    .data-row {
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease-in-out;
    }

    .data-row:hover {
        background-color: #f8f9fa;
        transform: scale(1.02);
        z-index: 2;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .1);
    }

    .pagination .page-item .page-link {
        border-radius: 0.5rem;
        margin: 0 0.25rem;
        border: none;
        color: #6c757d;
    }

    .pagination .page-item.active .page-link {
        background-color: #6759ff;
        color: #fff;
        box-shadow: 0 4px 8px rgba(103, 89, 255, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        background-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0 text-gray-800">Data Feedback Pengguna</h1>
        <span class="text-muted">Total: {{ $feedbacks->total() }} feedback</span>
    </div>

    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Rata-rata Rating Keseluruhan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($overallAverageRating ?? 0, 1) }} / 5.0
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(request('rating'))
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Rata-rata Sesuai Filter (Bintang {{ request('rating') }})</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($filteredAverageRating ?? 0, 1) }} / 5.0
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-filter fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-start align-items-center flex-wrap">
            <span class="fw-semibold me-3 text-muted">Filter Rating:</span>
            <div class="btn-group" role="group" aria-label="Filter Rating">
                <a href="{{ route('admin.feedback.index', array_diff_key(request()->query(), ['rating' => ''])) }}" 
                   class="btn btn-sm {{ !request('rating') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Semua
                </a>
                
                @for ($i = 5; $i >= 1; $i--)
                    <a href="{{ route('admin.feedback.index', array_merge(request()->query(), ['rating' => $i])) }}" 
                       class="btn btn-sm {{ request('rating') == $i ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $i }} <i class="fas fa-star text-warning"></i>
                    </a>
                @endfor
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive table-container">
                <table class="table table-borderless table-hover align-middle mb-0">
                    <thead class="text-muted small">
                        <tr>
                            <th class="fw-semibold">#</th>
                            <th class="fw-semibold">Pemohon</th>
                            <th class="fw-semibold">
                                @php $ratingDirection = ($sort == 'rating' && $direction == 'asc') ? 'desc' : 'asc'; @endphp
                                <a href="{{ url()->current() }}?sort=rating&direction={{ $ratingDirection }}">
                                    Rating @if($sort == 'rating')<i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ms-1"></i>@endif
                                </a>
                            </th>
                            <th class="fw-semibold">Pesan</th>
                            <th class="fw-semibold">Catatan Evaluasi</th>
                            <th class="fw-semibold">
                                @php $dateDirection = ($sort == 'created_at' && $direction == 'asc') ? 'desc' : 'asc'; @endphp
                                <a href="{{ url()->current() }}?sort=created_at&direction={{ $dateDirection }}">
                                    Tanggal @if($sort == 'created_at')<i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ms-1"></i>@endif
                                </a>
                            </th>
                            <th class="fw-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $index => $feedback)
                        <tr class="data-row">
                            <td class="fw-bold">{{ $feedbacks->firstItem() + $index }}</td>
                            <td>{{ $feedback->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="text-warning">
                                    @for ($i = 0; $i < $feedback->rating; $i++)<i class="fas fa-star"></i>@endfor
                                </span>
                                <span class="text-body-tertiary">
                                    @for ($i = $feedback->rating; $i < 5; $i++)<i class="far fa-star"></i>@endfor
                                </span>
                            </td>
                            <td>{{ Str::limit($feedback->pesan ?? '-', 40) }}</td>
                            <td>
                                {{ Str::limit($feedback->catatan_evaluasi ?? '-', 40) }}
                            </td>
                            <td>{{ $feedback->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @if ($feedback->permohonan)
                                <a href="{{ route('admin.permohonan.show', $feedback->permohonan->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Lihat Detail Permohonan #{{ $feedback->permohonan->nomor_permohonan ?? $feedback->permohonan->id }}">
                                    Proses
                                </a>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">Permohonan Dihapus</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-comment-slash fs-2 mb-2"></i>
                                <p class="mb-0">Belum ada feedback yang masuk.</p>
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
        {{ $feedbacks->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
