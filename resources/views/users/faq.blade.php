@extends('layouts.appUser')
@include('layouts.repeatHeader')
@section('content')



    <!-- Hero Section -->
    <section class="bg-primary text-white py-5 text-center">
        <div class="container">
            <h1 class="display-5 fw-bold">Frequently Asked Questions</h1>
            <p class="lead">Jawaban atas pertanyaan umum mengenai sistem layanan data</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion accordion-flush" id="faqAccordion">

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button fw-semibold collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false"
                                    aria-controls="collapse1">
                                    Bagaimana cara mengajukan permohonan data?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="faq1"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Anda dapat mengajukan permohonan data dengan terlebih dahulu mendaftar akun, kemudian
                                    login dan isi formulir permohonan data pada menu “Pengajuan Permohonan”.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button fw-semibold collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false"
                                    aria-controls="collapse2">
                                    Apakah saya bisa melacak status pengajuan saya?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya. Setelah Anda mengajukan permohonan, Anda dapat melacak statusnya melalui menu “Lacak
                                    Permintaan”.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button fw-semibold collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false"
                                    aria-controls="collapse3">
                                    Siapa saja yang bisa mengakses data ini?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Pengguna yang telah diverifikasi oleh sistem dapat mengakses data yang tersedia, sesuai
                                    dengan hak akses yang diberikan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button fw-semibold collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false"
                                    aria-controls="collapse4">
                                    Bagaimana jika saya lupa password akun saya?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Silakan klik "Lupa Password" di halaman login, lalu ikuti instruksi pemulihan yang
                                    dikirim ke email Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Start of Tawk.to Script-->
            <script type="text/javascript">
                var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
                (function () {
                    var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                    s1.async = true;
                    s1.src = 'https://embed.tawk.to/67ff58053b6a28190b6309e5/1ioumvm6k';
                    s1.charset = 'UTF-8';
                    s1.setAttribute('crossorigin', '*');
                    s0.parentNode.insertBefore(s1, s0);
                })();
            </script>
            <!--End of Tawk.to Script-->
        </div>
    </section>
    @include('layouts.repeatFooter')
@endsection