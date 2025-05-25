@extends('layouts.appUser')

@section('content')

<section class="min-vh-100 d-flex">
  <!-- Left Side Info / Branding -->
  <div class="d-none d-lg-flex flex-column justify-content-center align-items-center bg-primary text-white col-lg-6 p-5">
    <div class="text-center">
      <img src="{{ asset('logo.png') }}" alt="Logo" class="mb-4" width="80">
      <h2 class="fw-bold">Selamat Datang</h2>
      <p class="mt-3">Buat akun untuk mulai mengajukan permohonan data dengan mudah dan aman melalui sistem informasi kami.</p>
    </div>
  </div>

  <!-- Right Side Form -->
  <div class="container d-flex align-items-center justify-content-center col-lg-6">
    <div class="w-100 p-4 p-md-5">
      <div class="text-center mb-4">
        <h3 class="fw-bold">Daftar Akun Baru</h3>
        <p class="text-muted">Silakan isi data di bawah untuk mendaftar</p>
      </div>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
          <label for="name" class="form-label">Nama Lengkap</label>
          <input type="text" id="name" name="name" class="form-control rounded-3" value="{{ old('name') }}" required autofocus autocomplete="name">
          @error('name')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Nomor HP -->
        <div class="mb-3">
          <label for="name" class="form-label">Nomor HP</label>
          <input type="text" id="no_hp" name="no_hp" class="form-control rounded-3" value="{{ old('no_hp') }}" required autofocus autocomplete="no_hp">
          @error('no_hp')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Alamat Email</label>
          <input type="email" id="email" name="email" class="form-control rounded-3" value="{{ old('email') }}" required autocomplete="username">
          @error('email')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Kata Sandi</label>
          <input type="password" id="password" name="password" class="form-control rounded-3" required autocomplete="new-password">
          @error('password')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control rounded-3" required autocomplete="new-password">
          @error('password_confirmation')
            <div class="text-danger mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-grid mt-4">
          <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">Daftar Sekarang</button>
        </div>

        <div class="text-center mt-3">
          Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-semibold">Login di sini</a>
        </div>
      </form>
    </div>
  </div>
</section>

@endsection
