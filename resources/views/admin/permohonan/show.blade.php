@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">Detail Permohonan Data</h4>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Nama Pengguna:</div>
                <div class="col-md-9">{{ $permohonan->user->name }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Tujuan Permohonan:</div>
                <div class="col-md-9">{{ $permohonan->tujuan }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Jenis Data:</div>
                <div class="col-md-9">{{ $permohonan->jenis_data }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3 fw-bold">Catatan:</div>
                <div class="col-md-9">{{ $permohonan->catatan ?? '-' }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3 fw-bold">Status:</div>
                <div class="col-md-9">
                    <span class="badge bg-{{ $permohonan->status === 'selesai' ? 'success' : 'warning' }}">
                        {{ ucfirst($permohonan->status) }}
                    </span>
                </div>
            </div>

            @if ($permohonan->status !== 'selesai'&& $permohonan->status !== 'ditolak')
            <form method="POST" action="{{ route('admin.permohonan.update', $permohonan->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Ubah Status</label>
                    <select name="status" class="form-select" id="status-select">
                        <option value="diproses" {{ $permohonan->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ $permohonan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ $permohonan->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="dieskalasi" {{ $permohonan->status === 'dieskalasi' ? 'selected' : '' }}>Eskalasi ke Kepala Bidang</option>
                    </select>
                </div>

                <div id="non-escalation-fields">
                    <div class="mb-3 d-none" id="alasan-penolakan">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" class="form-control" rows="3">{{ old('alasan_penolakan', $permohonan->alasan_penolakan) }}</textarea>
                    </div>

                    <div class="mb-3 d-none" id="alasan-penolakan">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" class="form-control" rows="3">{{ old('alasan_penolakan', $permohonan->alasan_penolakan) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload File Hasil</label>
                        <input type="file" name="file_hasil" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            </form>

            {{-- If status selesai --}}
            @elseif ($permohonan->status === 'selesai')
            <div class="alert alert-info">
                Permohonan ini telah <strong>selesai</strong>. Tidak dapat diubah.
            </div>
            @if ($permohonan->file_hasil)
            <a href="{{ asset('storage/' . $permohonan->file_hasil) }}" target="_blank" class="btn btn-outline-primary">
                <i class="bi bi-download"></i> Lihat File Hasil
            </a>
            @endif


            @elseif ($permohonan->status === 'ditolak')
            <div class="alert alert-danger">
                Permohonan ini telah <strong>ditolak</strong>. Tidak dapat diubah.
            </div>
            @if ($permohonan->alasan_penolakan)
            <div class="mb-3">
                <label class="form-label text-danger">Alasan Penolakan</label>
                <div class="border rounded p-2 bg-light text-danger">
                    {{ $permohonan->alasan_penolakan }}
                </div>
            </div>
            @endif
            @endif
            <script>
                const statusSelect = document.getElementById('status-select');
                const alasanDiv = document.getElementById('alasan-penolakan');
                const nonEscalationFields = document.getElementById('non-escalation-fields');

                function toggleAlasanPenolakan() {
                    if (statusSelect && alasanDiv) {
                        if (statusSelect.value === 'ditolak') {
                            alasanDiv.classList.remove('d-none');
                        } else {
                            alasanDiv.classList.add('d-none');
                        }
                    }
                }

                if (statusSelect) {
                    statusSelect.addEventListener('change', toggleAlasanPenolakan);
                    window.addEventListener('DOMContentLoaded', toggleAlasanPenolakan);
                }

                function toggleEskalasiView() {
                    if (statusSelect.value === 'dieskalasi') {
                        nonEscalationFields.classList.add('d-none');
                    } else {
                        nonEscalationFields.classList.remove('d-none');
                    }
                }
                if (statusSelect) {
                    statusSelect.addEventListener('change', () => {
                        toggleAlasanPenolakan();
                        toggleEskalasiView();
                    });
                    window.addEventListener('DOMContentLoaded', () => {
                        toggleAlasanPenolakan();
                        toggleEskalasiView();
                    });
                }
            </script>

            <a href="{{ route('admin.permohonan.index') }}" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection