@extends('layouts.appUser')

@section('content')

{{-- Hero Section --}}
<section class="bg-white py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 text-center text-md-start">
        <h1 class="display-4 fw-bold mb-3">
          Layanan Permohonan Data<br>
          <span class="text-primary">Pemerintah Kota Blitar</span>
        </h1>
        <p class="lead text-muted mb-4">
          Akses data resmi secara cepat, aman, dan transparan untuk kebutuhan penelitian, administrasi, dan pengambilan keputusan.
        </p>
        <div class="d-flex justify-content-center justify-content-md-start gap-3">
          <a href="{{ route('permohonan.form') }}" class="btn btn-primary btn-lg rounded-pill px-4">Ajukan Permintaan</a>
          <a href="{{ route('katalog.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Lihat Katalog</a>
        </div>
      </div>
      <div class="col-md-6 text-center mt-4 mt-md-0">
        <img src="{{ asset('images/undraw_data-input_whqw.svg') }}" alt="Ilustrasi Layanan Data" class="img-fluid" style="max-height: 320px;">
      </div>
    </div>
  </div>
</section>

{{-- Fitur Utama --}}
<section class="py-5 bg-light">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body py-5">
            <i class="bi bi-database display-4 text-primary mb-3"></i>
            <h5 class="fw-bold">Katalog Data</h5>
            <p class="text-muted">Jelajahi dan temukan kumpulan data dari berbagai sektor secara terbuka.</p>
            <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary rounded-pill mt-2">Lihat Katalog</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body py-5">
            <i class="bi bi-graph-up display-4 text-success mb-3"></i>
            <h5 class="fw-bold">Lacak Permintaan</h5>
            <p class="text-muted">Pantau status pengajuan data Anda secara real-time dan transparan.</p>
            <a href="{{ route('users.riwayat') }}" class="btn btn-outline-success rounded-pill mt-2">Lacak Sekarang</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body py-5">
            <i class="bi bi-chat-dots display-4 text-danger mb-3"></i>
            <h5 class="fw-bold">Kontak Kami</h5>
            <p class="text-muted">Hubungi kami untuk bantuan, informasi, atau masukan terkait layanan.</p>
            <a href="#" class="btn btn-outline-danger rounded-pill mt-2">Hubungi Kami</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Statistik Singkat --}}
<section class="py-5 bg-white">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-md-3">
        <h2 class="text-primary fw-bold">{{ $totalData ?? 12 }}</h2>
        <p class="text-muted">Dataset Tersedia</p>
      </div>
      <div class="col-md-3">
        <h2 class="text-success fw-bold">{{ $totalPermohonan ?? 450 }}</h2>
        <p class="text-muted">Permohonan Masuk</p>
      </div>
      <div class="col-md-3">
        <h2 class="text-warning fw-bold">{{ $totalSelesai ?? 390 }}</h2>
        <p class="text-muted">Permohonan Diselesaikan</p>
      </div>
      <div class="col-md-3">
        <h2 class="text-danger fw-bold">4.8/5</h2>
        <p class="text-muted">Tingkat Kepuasan</p>
      </div>
    </div>
  </div>
</section>

{{-- Alur Permohonan --}}
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Alur Pengajuan Permohonan</h2>
      <p class="text-muted">Langkah-langkah mudah untuk mendapatkan data yang Anda butuhkan</p>
    </div>
    <div class="row text-center g-4">
      <div class="col-md-3">
        <div class="p-4 bg-white shadow-sm rounded-4 h-100">
          <i class="bi bi-person-plus display-5 text-primary mb-2"></i>
          <h6 class="fw-bold">1. Daftar / Login</h6>
          <p class="text-muted small">Masuk ke sistem untuk mulai mengajukan permintaan.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 bg-white shadow-sm rounded-4 h-100">
          <i class="bi bi-journal-plus display-5 text-success mb-2"></i>
          <h6 class="fw-bold">2. Ajukan Permintaan</h6>
          <p class="text-muted small">Isi form permohonan data dan unggah surat pengantar.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 bg-white shadow-sm rounded-4 h-100">
          <i class="bi bi-hourglass-split display-5 text-warning mb-2"></i>
          <h6 class="fw-bold">3. Proses Verifikasi</h6>
          <p class="text-muted small">Tim kami akan memeriksa dan memproses permintaan Anda.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 bg-white shadow-sm rounded-4 h-100">
          <i class="bi bi-download display-5 text-danger mb-2"></i>
          <h6 class="fw-bold">4. Unduh Data</h6>
          <p class="text-muted small">Jika disetujui, data dapat langsung diunduh dari sistem.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Testimoni Singkat --}}
<section class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Apa Kata Pengguna?</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="p-4 border rounded-4 shadow-sm h-100">
          <p class="text-muted fst-italic">“Pelayanan cepat dan informatif. Data yang saya butuhkan tersedia dan sangat membantu penelitian saya.”</p>
          <p class="mb-0"><strong>- Dosen Universitas Negeri</strong></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-4 border rounded-4 shadow-sm h-100">
          <p class="text-muted fst-italic">“Sistem ini sangat mempermudah kami dalam mengakses informasi untuk keperluan pelaporan kinerja instansi.”</p>
          <p class="mb-0"><strong>- ASN Pemerintah Kota</strong></p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CTA Akhir --}}
<section class="py-5 bg-primary text-white text-center">
  <div class="container">
    <h2 class="fw-bold">Butuh Data Sekarang?</h2>
    <p class="lead mb-4">Ajukan permintaan data secara resmi dan dapatkan akses yang Anda perlukan dengan mudah dan cepat.</p>
    <a href="{{ route('permohonan.form') }}" class="btn btn-light btn-lg rounded-pill px-4">Ajukan Sekarang</a>
  </div>
</section>

@include('layouts.repeatFooter')
@endsection
