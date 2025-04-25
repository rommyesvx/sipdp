@extends('layouts.appUser')
@include('layouts.repeatHeader')
@section('content')
<section class="bg-white py-5">
  <div class="container">
    <div class="row align-items-center">
      <!-- Kiri: Teks -->
      <div class="col-md-6 text-center text-md-start">
        <h1 class="display-4 fw-bold mb-3">
          Layanan Permohonan Data<br>
          <span class="text-primary">Pemerintah Kota Blitar</span>
        </h1>
        <p class="lead text-muted mb-4">
          Ajukan permintaan data, eksplorasi katalog, dan akses informasi kesehatan nasional dengan mudah dan cepat melalui sistem digital yang terintegrasi.
        </p>
        <div class="d-flex justify-content-center justify-content-md-start gap-3">
          <a href="{{ route('permohonan.form') }}" class="btn btn-primary btn-lg rounded-pill px-4">Ajukan Permintaan</a>
          <a href="#" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Lihat Katalog</a>
        </div>
      </div>

      <!-- Kanan: Gambar atau Ikon -->
      <div class="col-md-6 text-center mt-4 mt-md-0">
        <img src="{{ asset('images/undraw_data-input_whqw.svg') }}" alt="Ilustrasi Layanan Data" class="img-fluid" style="max-height: 320px;">
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-lg rounded-4 h-100 hover-shadow transition">
          <div class="card-body py-5">
            <div class="mb-3">
              <i class="bi bi-database display-4 text-primary"></i>
            </div>
            <h5 class="card-title fw-bold">Katalog Data</h5>
            <p class="card-text text-muted">Jelajahi berbagai data yang tersedia secara terbuka.</p>
            <a href="#" class="btn btn-outline-primary rounded-pill mt-3">Lihat Katalog</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-lg rounded-4 h-100 hover-shadow transition">
          <div class="card-body py-5">
            <div class="mb-3">
              <i class="bi bi-graph-up display-4 text-success"></i>
            </div>
            <h5 class="card-title fw-bold">Lacak Permintaan</h5>
            <p class="card-text text-muted">Pantau status permintaan data Anda secara transparan dan cepat.</p>
            <a href="#" class="btn btn-outline-success rounded-pill mt-3">Lacak Sekarang</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-lg rounded-4 h-100 hover-shadow transition">
          <div class="card-body py-5">
            <div class="mb-3">
              <i class="bi bi-chat-dots display-4 text-danger"></i>
            </div>
            <h5 class="card-title fw-bold">Kontak Kami</h5>
            <p class="card-text text-muted">Hubungi kami jika ada pertanyaan, saran, atau permasalahan.</p>
            <a href="#" class="btn btn-outline-danger rounded-pill mt-3">Hubungi Kami</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@include('layouts.repeatFooter')
@endsection
