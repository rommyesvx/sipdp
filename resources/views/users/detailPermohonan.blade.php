@extends('layouts.appUser')

@section('content')

@section('content')
<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-body p-5">
                        <h3 class="fw-bold mb-4 text-center text-primary">Detail Permohonan Data</h3>

                        <div class="mb-4">
                            <h5 class="text-muted">Tujuan Permohonan</h5>
                            <p class="fs-5">{{ $permohonan->tujuan }}</p>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-muted">Tipe File</h5>
                            <p class="fs-5 text-uppercase">{{ $permohonan->tipe }}</p>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-muted">Jenis Data yang Diminta</h5>
                            <p class="fs-5">{{ $permohonan->jenis_data }}</p>
                        </div>

                        @if ($permohonan->file_permohonan)
                        <div class="mb-4">
                            <h5 class="text-muted">Surat Pengantar</h5>
                            <a href="{{ route('permohonan.downloadHasilPengantar', ['id' => $permohonan->id]) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm">Lihat Surat</a>
                        </div>
                        @endif

                        @if ($permohonan->catatan)
                        <div class="mb-4">
                            <h5 class="text-muted">Catatan Tambahan</h5>
                            <p class="fs-5">{{ $permohonan->catatan }}</p>
                        </div>
                        @endif

                        <div class="text-muted text-end">
                            <small>Diajukan pada: {{ $permohonan->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5>Status Permohonan:
                                    <span class="badge 
                                                @if($permohonan->status == 'selesai') bg-success 
                                                @elseif($permohonan->status == 'diproses') bg-warning 
                                                @elseif($permohonan->status == 'ditolak') bg-danger 
                                                @else bg-secondary @endif">
                                        {{ ucfirst($permohonan->status) }}
                                    </span>
                                </h5>

                                @if($permohonan->status == 'selesai' && $permohonan->file_hasil)
                                <a href="{{ route('permohonan.downloadHasil', ['id' => $permohonan->id]) }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-download"></i> Download Hasil
                                </a>
                                @endif
                            </div>
                        </div>

                        @if ($permohonan->status === 'selesai')
                        @if ($permohonan->feedback)
                        <!-- Jika sudah ada feedback -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="mb-3">Feedback Anda</h5>
                                <p><strong>Pesan:</strong> {{ $permohonan->feedback->pesan }}</p>
                                <p><strong>Rating:</strong> {{ $permohonan->feedback->rating }} / 5</p>
                            </div>
                        </div>
                        @else
                        <!-- Jika belum ada feedback -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="mb-3">Beri Feedback</h5>
                                <form action="{{ route('feedback.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="permohonan_id" value="{{ $permohonan->id }}">

                                    <div class="mb-3">
                                        <label for="pesan" class="form-label">Pesan</label>
                                        <textarea class="form-control" name="pesan" rows="3" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating</label>
                                        <select class="form-select" name="rating" required>
                                            <option value="">Pilih rating</option>
                                            <option value="1">1 - Sangat Buruk</option>
                                            <option value="2">2 - Buruk</option>
                                            <option value="3">3 - Cukup</option>
                                            <option value="4">4 - Baik</option>
                                            <option value="5">5 - Sangat Baik</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Kirim Feedback</button>
                                </form>
                            </div>
                        </div>
                        @endif
                        @endif

                        @if($permohonan->status === 'ditolak' && $permohonan->alasan_penolakan)
                        <div class="card mt-4 border-danger">
                            <div class="card-body">
                                <h5 class="text-danger">Permohonan Ditolak</h5>
                                <p class="text-danger"><strong>Alasan Penolakan:</strong> {{ $permohonan->alasan_penolakan }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="text-center mt-5">
                            <a href="{{ route('users.riwayat') }}" class="btn btn-secondary rounded-pill px-4">Kembali
                                ke Riwayat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('layouts.repeatFooter')
@endsection