<x-layouts.landing title="Tentang Kami">
    <section class="section-py landing-hero position-relative">
        <img src="{{ asset('img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background"
            class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100" loading="lazy" />
        <div class="container">
            <div class="hero-text-box text-center position-relative">
                <h1 class="text-primary hero-title display-6 fw-extrabold">Tentang Kami</h1>
                <h2 class="hero-sub-title h6 mb-6">Warga<sup>+</sup> adalah portal komunitas yang menghubungkan warga, pengurus RT, dan pemangku kepentingan untuk menyelesaikan masalah lingkungan secara cepat, transparan, dan kolaboratif.</h2>
                <div class="landing-hero-btn d-inline-block position-relative">
                    <a href="#ourMission" class="btn btn-primary btn-lg">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section-py landing-features">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-12 col-lg-6 d-flex justify-content-center">
                    <img src="{{ asset('warga-plus.png') }}" alt="About illustration" class="img-fluid rounded" loading="lazy" />
                </div>
                <div class="col-lg-6">
                    <h4 class="fw-extrabold">Misi & Visi</h4>
                    <p class="text-muted">Kami bertujuan membangun lingkungan yang aman, nyaman, dan responsif dengan mempermudah warga untuk melapor, berpartisipasi, dan memantau tindak lanjut laporan.</p>

                    <h6 class="mt-4">Visi</h6>
                    <p class="text-body-secondary">Menjadi platform rujukan utama untuk partisipasi warga dan solusi permukiman yang berkelanjutan.</p>

                    <h6 class="mt-3">Misi</h6>
                    <ul class="list-unstyled">
                        <li>• Mempermudah laporan warga dan tindak lanjutnya.</li>
                        <li>• Meningkatkan transparansi proses penanganan masalah lingkungan.</li>
                        <li>• Mendorong kolaborasi antar warga, RT, dan instansi terkait.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="section-py bg-body" style="border-radius: 3.75rem;">
        <div class="container">
            <h4 class="text-center fw-extrabold mb-4">Sejarah Singkat</h4>
            <div class="card shadow-sm border-0 mx-auto" style="max-width:900px; max-height:140px; overflow:auto;">
                <div class="card-body py-3">
                    <p class="mb-0 text-muted" style="text-align: justify;">
                        Warga<sup>+</sup> lahir dari inisiatif komunitas lokal yang ingin menghadirkan saluran pelaporan yang mudah, cepat, dan dapat dipertanggungjawabkan bagi setiap warga; berawal dari beberapa pertemuan RT untuk menanggapi masalah kebersihan dan keamanan lingkungan, platform ini berkembang pesat menjadi alat kolaborasi yang komprehensif—membantu pengurus RT mengelola laporan, menjadwalkan kegiatan, memantau tindak lanjut, dan memperkuat keterlibatan warga dengan transparansi serta akuntabilitas yang lebih baik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-py">
        <div class="container">
            <h4 class="text-center fw-extrabold mb-3">Nilai Kami</h4>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-xl border-0 outline outline-primary outline-offset-3 outline-1 hover:outline-2 p-3">
                        <div class="card-body text-center">
                            <i class="bx bx-shield fs-1 text-primary"></i>
                            <h5 class="mt-3">Keamanan</h5>
                            <p class="text-muted mb-0">Kami menjaga data warga dan memastikan proses pelaporan aman dan terkontrol.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-xl border-0 outline outline-primary outline-offset-3 outline-1 hover:outline-2 p-3">
                        <div class="card-body text-center">
                            <i class="bx bx-conversation fs-1 text-success"></i>
                            <h5 class="mt-3">Kolaborasi</h5>
                            <p class="text-muted mb-0">Kami memfasilitasi komunikasi efektif antar warga dan pengurus RT.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-xl border-0 outline outline-primary outline-offset-3 outline-1 hover:outline-2 p-3">
                        <div class="card-body text-center">
                            <i class="bx bx-bulb fs-1 text-warning"></i>
                            <h5 class="mt-3">Inovasi</h5>
                            <p class="text-muted mb-0">Kami terus mengembangkan fitur yang memudahkan partisipasi warga dan mempercepat solusi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.landing>
