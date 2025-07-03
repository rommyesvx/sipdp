<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .main-container {
            min-height: 100vh;
        }
        .form-container {
            max-width: 450px;
            width: 100%;
        }
        .form-control-lg {
            min-height: 3.5rem;
            font-size: 1rem;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .btn-register {
            background-color: #6759ff;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
        }
        .btn-register:hover {
            background-color: #584cde;
        }
        .link-login {
            color: #ff4d4d;
            font-weight: 600;
            text-decoration: none;
        }
        .link-login:hover {
            text-decoration: underline;
        }
        .image-column {
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-column img {
            max-width: 80%;
            height: auto;
        }
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 main-container">

            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                <div class="form-container p-2 p-md-5">
                    <h1 class="fw-bold mb-2">Buat Akun Baru</h1>
                    <p class="text-muted mb-4">Silakan isi data untuk mendaftar.</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_hp" class="form-label">Nomor HP</label>
                            <input id="no_hp" type="text" class="form-control form-control-lg @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp') }}" required>
                             @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                             @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <span class="input-group-text password-toggle" id="togglePassword">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </span>
                            </div>
                             @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <input id="password_confirmation" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password">
                                <span class="input-group-text password-toggle" id="togglePasswordConfirm">
                                     <i class="fa-solid fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-register btn-lg text-white">Daftar Sekarang</button>
                        </div>
                    </form>
                    
                    <p class="text-center">
                        Sudah punya akun? <a href="{{ route('login') }}" class="link-login">Login di sini</a>
                    </p>

                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-flex image-column">
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/sign-up-4922762-4097237.png" alt="Register Illustration">
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi umum untuk toggle password
            function setupPasswordToggle(toggleId, passwordId) {
                const toggleElement = document.getElementById(toggleId);
                const passwordElement = document.getElementById(passwordId);
                if (!toggleElement || !passwordElement) return;

                const icon = toggleElement.querySelector('i');
                toggleElement.addEventListener('click', function() {
                    const type = passwordElement.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordElement.setAttribute('type', type);
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            // Terapkan pada kedua field password
            setupPasswordToggle('togglePassword', 'password');
            setupPasswordToggle('togglePasswordConfirm', 'password_confirmation');
        });
    </script>
</body>
</html>