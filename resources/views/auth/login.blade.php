@extends('layouts.appUser')

@section('content')

  <section class="min-vh-100 d-flex">
    <!-- Left Side -->
    <div
    class="d-none d-lg-flex flex-column justify-content-center align-items-center bg-primary text-white col-lg-6 p-5">
    <div class="text-center">
      <img src="{{ asset('logo.png') }}" alt="Logo" class="mb-4" width="80">
      <h2 class="fw-bold">Sistem Informasi</h2>
      <p class="mt-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Unde deserunt nostrum laudantium sunt, esse fugiat, sit a id voluptates alias nam quo architecto possimus! Nisi voluptatibus numquam omnis commodi saepe?</p>
    </div>
    </div>

    <!-- Right Side -->
    <div class="container d-flex align-items-center justify-content-center col-lg-6">
    <div class="w-100 p-4 p-md-5">
      <div class="text-center mb-4">
      <h3 class="fw-bold">Login ke Sistem</h3>
      <p class="text-muted">Masukkan email dan kata sandi untuk masuk</p>
      </div>

      <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" class="form-control rounded-3" name="email" value="{{ old('email') }}" required
        autofocus>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input id="password" type="password" class="form-control rounded-3" name="password" required>
      </div>

      <div class="mb-4 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Ingat saya</label>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">Login</button>
      </div>
      </form>

      <p class="mt-4 text-center text-muted">
      Belum punya akun?
      <a href="{{ route('register') }}" class="text-decoration-none text-primary fw-semibold">Daftar Sekarang</a>
      </p>
    </div>
    </div>
  </section>

@endsection