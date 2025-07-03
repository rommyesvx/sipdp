@extends('layouts.newAppUser')

@section('title', 'Beranda - SIPDP Kota Blitar')

@section('content')

<!-- Hero Section -->
<section class="position-relative py-5 text-white" style="background: url('{{ asset('images/cover.jpg') }}') no-repeat center center; background-size: cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5); z-index: 1;"></div>
    <div class="container position-relative z-2 py-5">
        <div class="row">
            <div class="col-lg-6">
                <div class="bg-light p-4 rounded" style="background-color: rgba(217, 217, 217, 0.75) !important;">
                    <h1 class="display-5 fw-bold mb-4 text-dark">Permohonan Data Pegawai Lebih Mudah, Lebih Aman</h1>
                    <p class="lead mb-4 text-dark">
                        Gunakan sistem digital untuk mengajukan, melacak, dan menerima data pegawai dengan lebih cepat dan transparan.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('permohonan.form') }}" class="btn btn-dark px-4 py-2">Ajukan Sekarang</a>
                        <a href="#" class="btn btn-outline-dark px-4 py-2">Lacak Status</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Alur Pengajuan Section -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row align-items-center">

            <!-- Kolom Kiri: Langkah-langkah -->
            <div class="col-lg-6">
                <h2 class="mb-4 fw-bold">Alur Pengajuan Permohonan</h2>
                <p class="text-muted mb-5">Langkah-langkah yang harus diikuti</p>

                @php
                $steps = [
                ['img' => 'images/add-document.png', 'title' => 'Masuk ke Sistem', 'desc' => 'Masuk ke sistem untuk mulai mengajukan permintaan.'],
                ['img' => 'images/add-document.png', 'title' => 'Ajukan Permohonan', 'desc' => 'Isi form permohonan data dan unggah surat pengantar.'],
                ['img' => 'images/hourglass.png', 'title' => 'Tunggu Proses', 'desc' => 'Tim kami akan memeriksa dan memproses permintaan Anda.'],
                ['img' => 'images/download.png', 'title' => 'Unduh Data', 'desc' => 'Jika disetujui, data dapat langsung diunduh dari sistem.'],
                ];
                @endphp

                <div class="d-flex flex-column gap-4">
                    @foreach ($steps as $step)
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <img src="{{ asset($step['img']) }}" alt="{{ $step['title'] }}" width="50" height="50">
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">{{ $step['title'] }}</h5>
                            <p class="text-muted mb-0">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Kolom Kanan: Gambar -->
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/gambaralu.png') }}" class="img-fluid rounded shadow" alt="Alur Pengajuan">
            </div>

        </div>
    </div>
</section>


<!-- Proses Cepat Section -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <div id="prosesCepatCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <h2 class="fw-bold mb-3">PROSES CEPAT</h2>
                    <p class="lead">Permohonan diproses dalam waktu singkat dengan sistem kami.</p>
                </div>
                <div class="carousel-item">
                    <h2 class="fw-bold mb-3">PELAYANAN 24 JAM</h2>
                    <p class="lead">Ajukan permohonan kapan saja, sistem kami siap melayani.</p>
                </div>
                <div class="carousel-item">
                    <h2 class="fw-bold mb-3">NOTIFIKASI REAL-TIME</h2>
                    <p class="lead">Dapatkan pemberitahuan perkembangan permohonan Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Review Section -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Review Pengguna</h2>
        <div class="row g-4 justify-content-center">
            @forelse ($latestFeedbacks as $feedback)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center d-flex flex-column">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->user->name ?? 'U') }}&background=E9EFFD&color=3B76E1"
                            class="rounded-circle mx-auto mb-3"
                            width="60"
                            height="60"
                            alt="Avatar">
                        <h5 class="fw-bold">{{ $feedback->user->name ?? 'Pengguna Anonim' }}</h5>

                        <div class="text-warning mb-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $feedback->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                        </div>

                        <p class="text-muted fst-italic flex-grow-1">
                            “{{ $feedback->pesan }}”
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">
                <p>Belum ada review dari pengguna.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Statistik Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <div class="row g-4">
            <div class="col-md-4">
                <h3 class="display-4 fw-bold">{{ $totalPermohonan }}</h3>
                <p class="text-muted">Permohonan Masuk</p>
            </div>
            <div class="col-md-4">
                <h3 class="display-4 fw-bold">{{ $kepuasanPengguna }}/5</h3>
                <p class="text-muted">Kepuasan Pengguna</p>
            </div>
            <div class="col-md-4">
                <h3 class="display-4 fw-bold">{{ $permohonanSelesai }}</h3>
                <p class="text-muted">Permohonan Selesai</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Siap untuk memulai permohonan?</h2>
        <p class="lead text-muted mb-4">Proses yang cepat dan mudah hanya beberapa klik di depan Anda. Dapatkan data yang Anda butuhkan sekarang juga.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#" class="btn btn-dark px-5 py-3">Ajukan Permohonan Sekarang</a>
            <a href="#" class="btn btn-outline-dark px-5 py-3">Hubungi Kami</a>
        </div>
    </div>
</section>

@endsection