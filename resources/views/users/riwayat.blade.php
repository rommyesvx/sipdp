@extends('layouts.newAppUser')

@section('title', 'Riwayat Permohonan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .history-table .header-row a {
        text-decoration: none;
        color: inherit;
    }

    .history-table .header-row a:hover {
        color: #6759ff;
    }

    .history-row {
        background-color: #f7f5ff;
        padding: 1.25rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .history-row:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .1);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        gap: 0.5rem;
    }

    .status-badge.bg-eskalasi {
        background-color: #e7e7ff !important;
        color: #6759ff !important;
    }

    .action-icons a {
        font-size: 1.25rem;
        color: #6c757d;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .action-icons a:hover {
        color: #6759ff;
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
<div class="container py-5">
    <h2 class="fw-bold mb-4">Riwayat Permohonan</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($permohonans->isEmpty())
    <div class="alert alert-secondary text-center">
        <h4 class="alert-heading">Kosong!</h4>
        <p>Anda belum pernah mengajukan permohonan data apapun.</p>
    </div>
    @else
    <div class="history-table">
        {{-- HEADER BARU DENGAN KOLOM TUJUAN --}}
        <div class="row header-row text-muted small fw-bold mb-3 d-none d-lg-flex">
            <div class="col-lg-1">#</div>
            <div class="col-lg-3">Tujuan Permohonan</div>
            <div class="col-lg-3">Kriteria Data</div>
            <div class="col-lg-2">Tgl Diajukan</div>
            <div class="col-lg-2">Status</div>
            <div class="col-lg-1 text-center">Aksi</div>
        </div>

        @foreach($permohonans as $index => $data)
        <div class="row history-row align-items-start mb-3 position-relative">
            <div class="col-lg-1 mb-2 mb-lg-0">
            <strong class="d-lg-none">No: </strong>
            <span class="fw-bold">{{ $permohonans->firstItem() + $index }}</span>
        </div>

            {{-- KOLOM BARU DITAMBAHKAN DI SINI --}}
            <div class="col-lg-3 mb-3 mb-lg-0">
                <strong class="d-lg-none">Tujuan: </strong>
                <span class="fw-semibold">{{ $data->tujuan }}</span>
            </div>

            <div class="col-lg-3 mb-3 mb-lg-0">
                <strong class="d-lg-none d-block mb-1">Kriteria: </strong>
                {!! $data->formatted_jenis_data !!}
            </div>

            <div class="col-lg-2 mb-3 mb-lg-0">
                <strong class="d-lg-none">Tanggal: </strong>
                {{ $data->created_at->format('d M Y') }}
            </div>

            <div class="col-lg-2 mb-3 mb-lg-0">
                <strong class="d-lg-none me-2">Status: </strong>
                @if($data->status == 'selesai')
                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-success-subtle text-success-emphasis"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                @elseif($data->status == 'ditolak')
                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-danger-subtle text-danger-emphasis"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                @elseif($data->status == 'eskalasi')
                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-eskalasi"><i class="fas fa-level-up-alt me-1"></i> Eskalasi</span>
                @else
                <span class="badge rounded-pill fw-medium status-badge d-inline-flex align-items-center bg-warning-subtle text-warning-emphasis"><i class="fas fa-clock me-1"></i> Diproses</span>
                @endif
            </div>

            <div class="col-lg-1 text-center">
                <div class="action-icons d-flex justify-content-start justify-content-lg-center">
                    <a href="{{ route('permohonan.show', $data->id) }}" title="Lihat Detail" onclick="event.stopPropagation();" class="position-relative z-1">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if ($data->status == 'selesai' && $data->file_hasil)
                    <a href="{{ route('permohonan.downloadHasil', ['id' => $data->id]) }}" title="Unduh Hasil">
                        <i class="fas fa-download"></i>
                    </a>
                    @endif
                </div>
            </div>
            {{-- Link tak terlihat untuk membuat seluruh baris bisa diklik --}}
            <a href="{{ route('permohonan.show', $data->id) }}" class="stretched-link"></a>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $permohonans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection