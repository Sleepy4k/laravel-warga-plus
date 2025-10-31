<x-layouts.landing title="Tentang Kami">
    <x-landing.hero>
        <div class="hero-text-box text-center position-relative">
            <h1 class="text-primary hero-title display-6 fw-extrabold">Tentang Kami</h1>
            <h2 class="hero-sub-title h6 mb-6">Warga<sup>+</sup> adalah portal komunitas yang menghubungkan warga,
                pengurus RT, dan pemangku kepentingan untuk menyelesaikan masalah lingkungan secara cepat, transparan,
                dan kolaboratif.</h2>
            <div class="landing-hero-btn d-inline-block position-relative">
                <button class="btn btn-primary btn-lg">Pelajari Lebih Lanjut</button>
            </div>
        </div>
    </x-landing.hero>

    <section class="section-py landing-features" id="landingFeatures">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-12 col-lg-6 d-flex justify-content-center">
                    <img src="{{ asset('warga-plus.png') }}" alt="About illustration" class="img-fluid rounded"
                        loading="lazy" />
                </div>
                <div class="col-lg-6">
                    <div class="col-12 mb-3">
                        <h4 class="fw-extrabold">Misi & Visi</h4>
                        <div class="p-3 rounded bg-body-tertiary">
                            <p class="text-muted mb-0">
                                Warga<sup>+</sup> memperkuat gotong-royong melalui pelaporan dan koordinasi yang cepat,
                                transparan, dan mudah diakses — membantu warga dan pengurus RT menyelesaikan masalah
                                bersama.
                            </p>
                        </div>
                    </div>

                    <div class="col-12">
                        <h6 class="mb-2">Visi</h6>
                        <p class="mb-0">
                            Menjadi platform digital andalan komunitas lokal untuk mewujudkan lingkungan yang aman,
                            bersih, dan berkelanjutan.
                        </p>
                    </div>
                    <div class="col-12">
                        <h6 class="mt-3 mb-2">Misi Utama</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-start mb-2">
                                <i class="bx bx-check-circle text-success me-2 fs-5"></i>
                                <span>Memudahkan warga melaporkan masalah dengan antarmuka sederhana dan pelacakan
                                    status.</span>
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <i class="bx bx-check-circle text-success me-2 fs-5"></i>
                                <span>Memberi alat bagi pengurus RT untuk mengelola laporan, menjadwalkan penanganan,
                                    dan berkoordinasi.</span>
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <i class="bx bx-check-circle text-success me-2 fs-5"></i>
                                <span>Meningkatkan transparansi dengan bukti tindak lanjut dan notifikasi yang
                                    jelas.</span>
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <i class="bx bx-check-circle text-success me-2 fs-5"></i>
                                <span>Mendorong partisipasi warga lewat kegiatan kolektif, edukasi, dan berbagi solusi
                                    lokal.</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="bx bx-check-circle text-success me-2 fs-5"></i>
                                <span>Menjaga keamanan data dan privasi warga sebagai prioritas utama.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="landingCTA" class="section-py landing-cta position-relative p-lg-0 pb-0 bg-body mt-12">
        <img src="{{ asset('img/front-pages/backgrounds/cta-bg-dark.png') }}"
            class="position-absolute bottom-0 end-0 scaleX-n1-rtl h-100 w-100" alt="cta image" loading="lazy" />
        <div class="container">
            <div class="row align-items-center gy-12">
                <div class="col-lg-6 text-center text-sm-center text-lg-center">
                    <h3 class="cta-title text-primary fw-bold mb-1">Sejarah Singkat</h3>
                </div>
                <div class="col-lg-6 pt-lg-12 text-center text-lg-end h-100 align-self-stretch">
                    <div class="card shadow-sm border-0 bg-transparent mx-auto mx-lg-0 h-100"
                        style="max-width: 680px; margin-bottom: 3rem;">
                        <div class="card-body p-4 d-flex flex-column justify-content-center h-100">
                            <p class="mb-0 text-muted"
                                style="text-align: justify; font-size: 1.05rem; line-height: 1.7;">
                                <strong>Warga<sup>+</sup></strong> lahir dari inisiatif komunitas lokal yang
                                menghadirkan saluran pelaporan
                                yang mudah, cepat, dan dapat dipertanggungjawabkan bagi setiap warga. Berawal dari
                                pertemuan RT untuk
                                menanggapi masalah kebersihan dan keamanan lingkungan, platform ini berkembang menjadi
                                alat kolaborasi
                                komprehensif—membantu pengurus RT mengelola laporan, menjadwalkan kegiatan, memantau
                                tindak lanjut, dan
                                memperkuat keterlibatan warga dengan transparansi serta akuntabilitas yang lebih baik.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-py" id="landingValues">
        <div class="container">
            <h4 class="text-center fw-extrabold mb-3">Nilai Kami</h4>
            <div class="row g-4">
                <div class="col-md-4">
                    <div
                        class="card h-100 shadow-xl border-0 outline outline-primary outline-offset-3 outline-1 hover:outline-2 p-3">
                        <div class="card-body text-center">
                            <i class="bx bx-shield fs-1 text-primary"></i>
                            <h5 class="mt-3">Keamanan</h5>
                            <p class="text-muted mb-0">Kami menjaga data warga dan memastikan proses pelaporan aman dan
                                terkontrol.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div
                        class="card h-100 shadow-xl border-0 outline outline-primary outline-offset-3 outline-1 hover:outline-2 p-3">
                        <div class="card-body text-center">
                            <i class="bx bx-conversation fs-1 text-success"></i>
                            <h5 class="mt-3">Kolaborasi</h5>
                            <p class="text-muted mb-0">Kami memfasilitasi komunikasi efektif antar warga dan pengurus
                                RT.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div
                        class="card h-100 shadow-xl border-0 outline outline-primary outline-offset-3 outline-1 hover:outline-2 p-3">
                        <div class="card-body text-center">
                            <i class="bx bx-bulb fs-1 text-warning"></i>
                            <h5 class="mt-3">Inovasi</h5>
                            <p class="text-muted mb-0">Kami terus mengembangkan fitur yang memudahkan partisipasi warga
                                dan mempercepat solusi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @pushOnce('page-scripts')
        <script @cspNonce>
            document.addEventListener('DOMContentLoaded', () => {
                const button = document.querySelector('.landing-hero-btn button');
                button.addEventListener('click', () => {
                    const ourMissionSection = document.getElementById('landingFeatures');
                    if (ourMissionSection) {
                        ourMissionSection.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        </script>
        <script @cspNonce>
            document.addEventListener('DOMContentLoaded', function () {
                gsap.from("#landingFeatures img", {
                    opacity: 0,
                    x: -100,
                    duration: 1,
                    ease: "power3.out",
                    scrollTrigger: {
                        trigger: "#landingFeatures img",
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    }
                });

                gsap.from("#landingFeatures .col-lg-6:nth-child(2) > div", {
                    opacity: 0,
                    x: 100,
                    duration: 1,
                    ease: "power3.out",
                    delay: 0.3,
                    scrollTrigger: {
                        trigger: "#landingFeatures .col-lg-6:nth-child(2)",
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    }
                });

                gsap.from("#landingCTA .cta-title", {
                    opacity: 0,
                    x: -50,
                    duration: 1,
                    ease: "power3.out",
                    delay: 0.1,
                    scrollTrigger: {
                        trigger: "#landingCTA .cta-title",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    }
                });

                gsap.from("#landingCTA .card", {
                    opacity: 0,
                    y: 50,
                    duration: 1,
                    ease: "power3.out",
                    delay: 0.2,
                    scrollTrigger: {
                        trigger: "#landingCTA .card",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    }
                });

                gsap.from("#landingValues .card", {
                    opacity: 0,
                    y: 50,
                    duration: 1,
                    ease: "power3.out",
                    stagger: 0.3,
                    delay: 0.2,
                    scrollTrigger: {
                        trigger: "#landingValues",
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    }
                });
            });
        </script>
    @endPushOnce
</x-layouts.landing>
