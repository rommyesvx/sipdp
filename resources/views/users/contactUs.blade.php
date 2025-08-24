@extends('layouts.newAppUser')

@section('title', 'Hubungi Kami')

@section('content')

<div class="container py-5 my-4">
    <div class="row align-items-center">

        <div class="col-lg-4 mb-5 mb-lg-0 text-center text-lg-start">
            <h3 class="fw-bold">Hubungi Kami</h3>
            <p class="fs-7 text-muted">
                Kami siap menjawab pertanyaan Anda atau jika Anda membutuhkan bantuan.
            </p>
        </div>

        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="contact-item d-flex align-items-center">
                        <div class="contact-icon me-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Email</h6>
                            <p class="text-muted mb-0 small">mail@pemkotblitar.go.id</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="contact-item d-flex align-items-center">
                        <div class="contact-icon me-3">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Nomor Telepon</h6>
                            <p class="text-muted mb-0 small">+628113044019</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="contact-item d-flex align-items-center">
                        <div class="contact-icon me-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Alamat</h6>
                            <p class="text-muted mb-0 small">Pemkot Blitar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid p-0">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.998438129701!2d112.16489327596189!3d-8.102069881188372!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78ec6f26425939%3A0x57f35a4f3e6a184e!2sKantor%20Wali%20Kota%20Blitar!5e0!3m2!1sen!2sid!4v1718800050858!5m2!1sen!2sid"
        width="100%"
        height="450"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>

@endsection

@push('styles')
<style>
    .contact-item {
        padding: 1.5rem;
        background-color: #ffffff;
        border-radius: 0.75rem;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    @media (min-width: 768px) {
        .contact-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .1);
        }
    }

    .contact-icon {
        flex-shrink: 0;
        width: 4rem;
        height: 4rem;
        background-color: #e9f5ff;
        /* Warna biru muda */
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #0d6efd;
        /* Warna biru utama Bootstrap */
    }
</style>
@endpush


<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/67ff58053b6a28190b6309e5/1ioumvm6k';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>