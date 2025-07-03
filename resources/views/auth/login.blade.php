<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ke Akun Anda</title>

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

        .btn-login {
            background-color: #6759ff;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: #584cde;
        }

        .link-register {
            color: #ff4d4d;
            font-weight: 600;
            text-decoration: none;
        }

        .link-register:hover {
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
                <div class="form-container p-4 p-md-5">
                    <h1 class="fw-bold mb-2">Masuk ke Akun anda</h1>
                    <p class="text-muted mb-4">Masuk untuk memulai.</p>

                    <form method="post" action="{{ route('login') }}">
                        @csrf
                        {{-- Field Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- Field Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Masukkan password Anda">
                                <span class="input-group-text password-toggle" id="togglePassword">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Login --}}
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-login btn-lg text-white">Login</button>
                        </div>
                    </form>
                    {{-- Link Daftar --}}
                    <p class="text-center">
                        Belum punya akun? <a href="{{ route('register') }}" class="link-register">Daftar Sekarang</a>
                    </p>

                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-flex image-column">
                {{-- Anda bisa mengganti URL gambar ini dengan gambar milik Anda --}}
                <img src="https://cdni.iconscout.com/illustration/premium/thumb/login-3305943-2757111.png" alt="Login Illustration">
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#password');
            const icon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function() {
                // Ganti tipe input dari password ke text atau sebaliknya
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Ganti ikon mata
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });
    </script>

</body>

</html>