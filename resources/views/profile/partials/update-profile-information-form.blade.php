<section>
    <header>
        <h2 class="h4 fw-bold text-dark">
            Informasi Profil
        </h2>
        <p class="mt-1 text-muted">
            Perbarui informasi profil dan alamat email akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 small text-muted">
                Alamat email Anda belum terverifikasi.
                <button form="send-verification" class="btn btn-link p-0 text-decoration-none">
                    Klik di sini untuk mengirim ulang email verifikasi.
                </button>
            </div>
            @if (session('status') === 'verification-link-sent')
            <div class="mt-2 small fw-medium text-success">
                Link verifikasi baru telah dikirim ke alamat email Anda.
            </div>
            @endif
            @endif
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            @if (session('status') === 'profile-updated')
            <p class="m-0 text-success fw-medium">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>