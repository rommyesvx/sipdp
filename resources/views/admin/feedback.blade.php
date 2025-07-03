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
                                {{-- Menampilkan rating dalam bentuk bintang --}}
                                <span class="text-warning">
                                    @for ($i = 0; $i < $feedback->rating; $i++)<i class="fas fa-star"></i>@endfor
                                </span>
                                <span class="text-body-tertiary">
                                    @for ($i = $feedback->rating; $i < 5; $i++)<i class="far fa-star"></i>@endfor
                                </span>
                            </td>
                            <td>{{ Str::limit($feedback->pesan ?? '-', 40) }}</td>
                            <td>
                                {{-- Menampilkan kolom baru: catatan_evaluasi --}}
                                {{ Str::limit($feedback->catatan_evaluasi ?? '-', 40) }}
                            </td>
                            <td>{{ $feedback->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @if ($feedback->permohonan)
                                <a href="{{ route('admin.permohonan.show', $feedback->permohonan->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Lihat Detail Permohonan #{{ $feedback->permohonan->id }}">
                                    Proses
                                </a>
                                @else
                                {{-- Tampil jika data permohonan tidak ditemukan/sudah dihapus --}}
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