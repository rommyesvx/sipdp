@extends('layouts.kepala')
@section('title', 'Generate Laporan')

@section('content')
<h4 class="mb-4 fw-bold">📝 Generate Laporan</h4>

{{-- Card: Parameter Laporan --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold d-flex align-items-center">
            <i class="fas fa-filter me-2"></i> Parameter Laporan
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('kepala.laporan.index') }}" method="GET">
            {{-- Baris untuk Input Filter --}}
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-6 col-lg-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-6 col-lg-3">
                    <label for="status" class="form-label">Status Permohonan</label>
                    <select name="status" id="status" class="form-select">
                        <option value="semua" @selected(request('status')=='semua' )>Semua Status</option>
                        <option value="selesai" @selected(request('status')=='selesai' )>Disetujui</option>
                        <option value="ditolak" @selected(request('status')=='ditolak' )>Ditolak</option>
                        <option value="eskalasi" @selected(request('status')=='eskalasi' )>Eskalasi</option>
                        <option value="diproses" @selected(request('status')=='diproses' )>Diproses</option>
                    </select>
                </div>
            <div class="col-md-3">
            <label for="kriteria" class="form-label">Jenis Data Diminta</label>
            <select name="kriteria" id="kriteria" class="form-select">
                <option value="semua">Semua Jenis</option>
                @foreach($kriteriaOptions as $kriteria)
                    <option value="{{ $kriteria }}" @selected(request('kriteria') == $kriteria)>{{ $kriteria }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('kepala.laporan.index') }}" class="btn btn-light border fw-semibold"><i class="fas fa-sync-alt me-1"></i> Reset</a>
        <button type="submit" class="btn btn-primary fw-semibold"><i class="fas fa-eye me-2"></i> Tampilkan Pratinjau</button>
    </div>
</form>
    </div>
</div>


{{-- Card: Pratinjau Laporan --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">📊 Pratinjau Laporan</h5>
        @if(request()->has('tanggal_mulai') && $permohonans->isNotEmpty())
        <div>
            <a href="{{ route('kepala.laporan.export', request()->query() + ['tipe' => 'pdf']) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Export ke PDF
            </a>
            <a href="{{ route('kepala.laporan.export', request()->query() + ['tipe' => 'excel']) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> Export ke Excel
            </a>
        </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID Permohonan</th>
                    <th>Pemohon</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permohonans as $permohonan)
                <tr>
                    <td>#{{ $permohonan->id }}</td>
                    <td>{{ $permohonan->user->name ?? 'N/A' }}</td>
                    <td>{{ Str::limit($permohonan->tujuan, 50) }}</td>
                    <td>
                        @php
                        $status = $permohonan->status;
                        $badgeClass = match($status) {
                        'selesai' => 'success',
                        'diproses' => 'warning',
                        'ditolak' => 'danger',
                        'eskalasi' => 'primary',
                        default => 'secondary'
                        };
                        $statusLabel = ucfirst($status);
                        @endphp
                        <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }}-emphasis text-capitalize">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td>{{ $permohonan->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Silakan isi parameter laporan dan klik <strong>Tampilkan Pratinjau</strong>.
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
@endsection