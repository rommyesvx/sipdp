@extends('layouts.newAppUser')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="container my-4 my-md-5">
    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-lg-4 mb-5 mb-lg-0">
          
            <div class="custom-banner p-4 rounded-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                
                <div class="mb-3 mb-md-0">
                    <h4 class="fw-bold" style="color: #0d6efd;">Masih Butuh Bantuan?</h4>
                    <p class="mb-0 text-secondary">Tim kami siap menjawab pertanyaan Anda.</p>
                </div>
                
                <div>
                    <a href="{{ route('contactus') }}" class="btn btn-primary fw-semibold px-3 py-2">
                        <i class="bi bi-headset me-2"></i>Hubungi Kami
                    </a>
                </div>

            </div>
        </div>
        </div>

        {{-- Kolom Kanan (Accordion FAQ) --}}
        <div class="col-lg-8">
         
            <div class="accordion accordion-flush" id="faqAccordion">

                {{-- Item FAQ 1 --}}
                <div class="accordion-item mb-4">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Bagaimana cara mengajukan permohonan data?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Anda dapat mengajukan permohonan data dengan terlebih dahulu mendaftar akun, kemudian login dan isi formulir permohonan data pada menu "Pengajuan Permohanan".
                        </div>
                    </div>
                </div>

                {{-- Item FAQ 2 --}}
                <div class="accordion-item mb-4">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Apakah saya bisa melacak status pengajuan saya?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Tentu. Setelah Anda mengajukan permohonan, Anda dapat melacak statusnya secara real-time melalui menu "Riwayat Permohonan" di dashboard akun Anda. Kami akan memberikan pembaruan status mulai dari pengajuan diterima, diproses, hingga selesai.
                        </div>
                    </div>
                </div>

                {{-- Item FAQ 3 --}}
                <div class="accordion-item mb-4">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Siapa saja yang bisa mengakses data ini?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Akses terhadap data pegawai bersifat terbatas dan hanya diberikan kepada pihak-pihak yang berwenang dan telah melalui proses verifikasi. Setiap permohonan akan dievaluasi untuk memastikan bahwa data digunakan untuk tujuan yang sah dan sesuai dengan kebijakan privasi yang berlaku.
                        </div>
                    </div>
                </div>

                {{-- Item FAQ 4 --}}
                <div class="accordion-item mb-4">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Bagaimana jika saya lupa password akun saya?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Jika Anda lupa password, silakan klik tautan "Lupa Password" yang tersedia di halaman login. Anda akan diminta untuk memasukkan alamat email yang terdaftar, dan kami akan mengirimkan instruksi untuk mereset password Anda melalui email tersebut.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>

    .accordion-flush .accordion-item {
        border-bottom: 1px solid #dee2e6; /* Garis pemisah antar item */
    }
    
    .accordion-flush .accordion-item:last-child {
        border-bottom: 0;
    }

    .accordion-button {
        padding-left: 2.25rem; /* Memberi ruang untuk ikon kustom di kiri */
        position: relative;
        background-color: transparent !important;
        color: #212529 !important;
        box-shadow: none !important;
    }
    
    /* Tombol saat aktif/terbuka */
    .accordion-button:not(.collapsed) {
        color: #0d6efd !important;
    }
    
    /* Sembunyikan ikon panah default dari Bootstrap */
    .accordion-button::after {
        display: none;
    }

    /* Buat ikon panah kustom menggunakan pseudo-element ::before */
    .accordion-button::before {
        content: '\f078'; /* Kode ikon panah bawah dari Font Awesome */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 0.5rem;
        top: 50%;
        transform: translateY(-50%) rotate(0deg);
        transition: transform 0.2s ease-in-out;
        font-size: 0.9rem;
        color: #6c757d; /* Warna ikon */
    }

    /* Putar ikon saat accordion terbuka */
    .accordion-button:not(.collapsed)::before {
        transform: translateY(-50%) rotate(-180deg);
        color: #0d6efd; /* Warna ikon saat aktif */
    }

    /* Padding untuk body accordion agar lebih lega */
    .accordion-body {
        padding: 0.5rem 1rem 1.75rem 2.25rem;
    }
</style>
@endpush