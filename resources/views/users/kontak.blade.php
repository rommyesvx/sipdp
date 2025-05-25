@extends('layouts.appUser')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Hubungi Kami</h2>
        <p class="text-muted">Kami siap membantu Anda dalam proses permohonan data pegawai.</p>
    </div>

    <div class="row g-4">
        <!-- Informasi Kontak -->
        <div class="col-md-6">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-4">Informasi Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-primary me-3 fs-5"></i>
                            <div>
                                <strong>Alamat:</strong><br>
                                Jl. Merdeka No. 1, Kota Blitar, Jawa Timur
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-envelope-fill text-primary me-3 fs-5"></i>
                            <div>
                                <strong>Email:</strong><br>
                                <a href="mailto:layanan@datapegawai.blitarkota.go.id">layanan@datapegawai.blitarkota.go.id</a>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-telephone-fill text-primary me-3 fs-5"></i>
                            <div>
                                <strong>Telepon:</strong><br>
                                (0342) 123456
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-whatsapp text-success me-3 fs-5"></i>
                            <div>
                                <strong>WhatsApp:</strong><br>
                                <a href="https://wa.me/6281234567890" target="_blank">+62 812-3456-7890</a>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-clock-fill text-primary me-3 fs-5"></i>
                            <div>
                                <strong>Jam Layanan:</strong><br>
                                Senin - Jumat, 08.00 - 16.00 WIB
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="col-md-6">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-body p-0 overflow-hidden">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.3967452366896!2d112.15521121432684!3d-8.09292009417764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78f69125f7f1d3%3A0x4a6dc63ebd5f2707!2sKantor%20Pemerintah%20Kota%20Blitar!5e0!3m2!1sid!2sid!4v1680000000000"
                        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
