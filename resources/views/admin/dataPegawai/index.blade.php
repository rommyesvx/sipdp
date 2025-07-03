@extends('layouts.admin')

@section('title', 'Data Pegawai')

@push('styles')
    <style>
        .data-row { background-color: #fff; border-bottom: 1px solid #f0f0f0; }
        .data-row:hover { background-color: #f8f9fa; }
        .pagination .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <h1 class="h3 fw-bold mb-4 text-gray-800">Data Pegawai</h1>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.dataPegawai.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="departemen" class="form-select">
                            <option value="">Semua Departemen</option>
                             @foreach ($departemens as $departemen)
                                <option value="{{ $departemen }}" {{ request('departemen') == $departemen ? 'selected' : '' }}>{{ $departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="posisi" class="form-select">
                            <option value="">Semua Posisi</option>
                            @foreach ($posisis as $posisi)
                                <option value="{{ $posisi }}" {{ request('posisi') == $posisi ? 'selected' : '' }}>{{ $posisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari pegawai berdasarkan nama atau NIP..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.dataPegawai.index') }}" class="btn btn-secondary">Reset</a>
                        <button type="submit" class="btn btn-primary">Terapkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Daftar Pegawai</h5>
            <a href="#" class="btn btn-outline-dark btn-sm"><i class="fas fa-file-export me-2"></i>Export</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-hover align-middle">
                    <thead>
                        <tr class="text-muted small">
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Nama Jabatan</th>
                            <th>Golongan</th>
                            <th>Pendidikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pegawais as $pegawai)
                            <tr class="data-row">
                                <td class="fw-bold">#{{ $pegawai->nipBaru }}</td>
                                <td>{{ $pegawai->nama }}</td>
                                <td>{{ $pegawai->satuanKerjaKerjaNama }}</td>
                                <td>{{ $pegawai->jabatanNama }}</td>
                                <td>{{ $pegawai->golRuangAkhir }}</td>
                                <td>{{ $pegawai->pendidikanTerakhirNama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <p class="mb-0">Data tidak ditemukan.</p>
                                    <small>Coba sesuaikan filter pencarian Anda atau reset filter.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <small class="text-muted mb-2 mb-md-0">
                    Menampilkan {{ $pegawais->firstItem() }} - {{ $pegawais->lastItem() }} dari {{ $pegawais->total() }} data
                </small>
                {{-- Menambahkan parameter filter ke link pagination --}}
                {{ $pegawais->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection