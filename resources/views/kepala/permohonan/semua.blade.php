@extends('layouts.kepala') {{-- Sesuaikan dengan nama layout Anda --}}

@section('title', 'Monitoring Semua Permohonan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 fw-bold mb-4 text-gray-800">Monitoring Semua Permohonan</h1>

    {{-- Panel Filter --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('kepala.permohonan.semua') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama pemohon..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        <a href="{{ route('kepala.permohonan.semua') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pemohon</th>
                            <th>Tujuan</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permohonans as $permohonan)
                        <tr>
                            <td class="fw-bold">{{ $loop->iteration }}</td>
                            <td>{{ $permohonan->user->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($permohonan->tujuan, 40) }}</td>
                            <td>{{ $permohonan->created_at->format('d M Y') }}</td>
                            <td>
                                {{-- Logika badge status bisa Anda copy dari view lain --}}
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $permohonan->status)) }}</span>
                            </td>
                            <td class="text-center">
                                {{-- HANYA ADA TOMBOL LIHAT DETAIL (READ-ONLY) --}}
                                <a href="{{ route('kepala.permohonan.show', $permohonan->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Tidak ada data permohonan yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $permohonans->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
