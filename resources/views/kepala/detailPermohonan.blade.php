@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h5 class="mb-0">Detail Permohonan</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Nama Pengguna:</strong> {{ $permohonan->user->name }}<br>
                <strong>Tujuan:</strong> {{ $permohonan->tujuan }}<br>
                <strong>Jenis Data:</strong> {{ $permohonan->jenis_data }}<br>
                <strong>Catatan:</strong> {{ $permohonan->catatan ?? '-' }}<br>
                <strong>Status Saat Ini:</strong>
                <span class="badge bg-warning text-dark">{{ ucfirst($permohonan->status) }}</span>
            </div>

            <form method="POST" action="{{ route('kepala.permohonan.proses', $permohonan->id) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Keputusan</label>
                    <select name="status" class="form-select" required>
                        <option value="">-- Pilih Tindakan --</option>
                        <option value="disetujui_kepala">Setujui Permohonan</option>
                        <option value="ditolak_kepala">Tolak Permohonan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan / Alasan</label>
                    <textarea name="catatan_kepala" rows="3" class="form-control" placeholder="Tulis alasan atau catatan jika perlu..."></textarea>
                </div>

                <button type="submit" class="btn btn-success">Kirim Keputusan</button>
                <a href="{{ route('kepala.permohonan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
