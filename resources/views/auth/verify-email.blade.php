{{-- Menggunakan layout pengguna Anda --}}
@extends('layouts.newAppUser')

@section('title', 'Verifikasi Alamat Email')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope-open-text fa-3x text-primary"></i>
                        <h3 class="fw-bold mt-3">Verifikasi Alamat Email Anda</h3>
                    </div>

                    <p class="text-muted text-center">
                        Terima kasih telah mendaftar! Sebelum melanjutkan, mohon verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan.
                    </p>
                    <p class="text-muted text-center small">
                        Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkannya kembali.
                    </p>

                    {{-- Menampilkan notifikasi jika link verifikasi baru telah dikirim --}}
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success mt-4">
                            Link verifikasi baru telah berhasil dikirim ke alamat email yang Anda berikan saat pendaftaran.
                        </div>
                    @endif

                    <div class="mt-4 d-flex flex-wrap justify-content-center gap-3">
                        {{-- Tombol untuk mengirim ulang email verifikasi --}}
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary fw-semibold px-4">
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        {{-- Tombol untuk Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection