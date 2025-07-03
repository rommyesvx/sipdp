@extends('layouts.kepala') {{-- Kepala Bidang juga bisa menggunakan layout ini --}}

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

    .data-row:hover {
        background-color: #f8f9fa;
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
                            <th class="fw-semibold">Rating</th>
                            <th class="fw-semibold">Pesan</th>
                            <th class="fw-semibold">Catatan Evaluasi</th>
                            <th class="fw-semibold">
                                <a href="{{-- link sorting --}}">Tanggal</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $index => $feedback)
                        <tr class="data-row">
                            <td class="fw-bold">{{ $feedbacks->firstItem() + $index }}</td>
                            <td>
                                {{-- Menambahkan pengecekan @if untuk mencegah error --}}
                                @if ($feedback->permohonan)
                                {{-- Link ini sekarang aman karena kita sudah memastikan $feedback->permohonan ada --}}
                                {{ $feedback->user->name ?? 'N/A' }}
                                @else
                                {{-- Tampilan jika data permohonan tidak ditemukan/sudah dihapus --}}
                                <span class="text-muted fst-italic">
                                    {{ $feedback->user->name ?? 'N/A' }} (Permohonan telah dihapus)
                                </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $feedback->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                </span>
                            </td>
                            <td>{{ Str::limit($feedback->pesan ?? '-', 40) }}</td>
                            <td>
                                @if($feedback->catatan_evaluasi)
                                <i class="fas fa-check-circle text-success me-1" title="Sudah dievaluasi"></i>
                                <span class="text-muted fst-italic">{{ Str::limit($feedback->catatan_evaluasi, 40) }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $feedback->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
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