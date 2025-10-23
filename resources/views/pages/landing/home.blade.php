<x-layouts.landing>
    @pushOnce('plugin-styles')
    @endPushOnce

    <section id="hero-animation">
        <div id="landingHero" class="section-py landing-hero position-relative">
            <img src="{{ asset('img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background"
                class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100" loading="lazy" />
            <div class="container">
                <div class="hero-text-box text-center position-relative">
                    <h1 class="text-primary hero-title display-6 fw-extrabold">
                        Satu portal untuk suara warga dan aksi nyata
                    </h1>
                    <h2 class="hero-sub-title h6 mb-6">
                        Warga<sup>+</sup> memudahkan warga untuk melapor, berpartisipasi, dan memantau tindak lanjut dari setiap laporan secara transparan dan cepat
                    </h2>
                    <div class="landing-hero-btn d-inline-block position-relative">
                        <span class="hero-btn-item position-absolute d-none d-md-flex fw-medium">
                            Memiliki kendala?
                            <img src="{{ asset('img/front-pages/icons/Join-community-arrow.png') }}"
                                alt="Join community arrow" class="scaleX-n1-rtl" loading="lazy" />
                        </span>
                        <a href="#landingPricing" class="btn btn-primary btn-lg">Laporkan Sekarang!</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="landingFeatures" class="section-py landing-features">
        <div class="container">
            <h4 class="text-center mb-1">
                <span class="position-relative fw-extrabold z-1">Laporan Terbaru
                    <img src="{{ asset('img/front-pages/icons/section-title-icon.png') }}" alt="laptop charging"
                        class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" loading="lazy">
                </span>
            </h4>
            <p class="text-center mb-12">
                Berikut adalah beberapa laporan terbaru dari warga sekitar.
            </p>
            <div class="features-icon-wrapper row gx-0 gy-6 g-sm-12">
                @php
                    $items = [];
                @endphp

                @forelse ($items as $item)
                    <div class="col-lg-4 col-sm-6">
                        <div
                            class="card h-100 shadow-xl border-0 outline outline-primary outline-offset-3 outline-1 hover:outline-2">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="{{ asset('img/avatars/1.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </div>
                                    <div>
                                        <h6 class="mb-0">John Doe</h6>
                                        <small class="text-muted">
                                            {{ $item % 2 == 0 ? 'Warga' : 'Pengurus' }} RT
                                        </small>
                                    </div>
                                    <div class="ms-auto">
                                        <span
                                            class="badge bg-label-primary">{{ date('d M Y', strtotime('-' . $item . ' days')) }}</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <span class="badge bg-label-info">
                                        {{ $item % 2 == 0 ? 'Infrastruktur' : 'Lingkungan' }}
                                    </span>
                                </div>

                                <h5 class="mb-2">Jalan Rusak di Perumahan Blok C</h5>
                                <p class="mb-3">Jalan di sekitar perumahan blok C mengalami kerusakan parah yang
                                    membahayakan pengendara, terutama pada malam hari. Mohon segera diperbaiki.</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $statusMap = [
                                                    0 => [
                                                        'text' => 'Selesai',
                                                        'bg' => 'success',
                                                        'icon' => 'bx bx-check-circle',
                                                    ],
                                                    1 => [
                                                        'text' => 'Dalam Proses',
                                                        'bg' => 'warning',
                                                        'icon' => 'bx bx-time-five',
                                                    ],
                                                    2 => [
                                                        'text' => 'Menunggu',
                                                        'bg' => 'secondary',
                                                        'icon' => 'bx bx-hourglass',
                                                    ],
                                                ];
                                                $status = $statusMap[$item % 3];
                                            @endphp
                                            <span
                                                class="badge bg-label-{{ $status['bg'] }} d-flex align-items-center gap-1">
                                                <i class="{{ $status['icon'] }}"></i>
                                                <span>{{ $status['text'] }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4">
                        <div class="text-center p-4 bg-light-primary rounded-3">
                            <h5>Belum Ada Laporan Terbaru</h5>
                        </div>
                    </div>
                @endforelse
            </div>

            @if (count($items) > 0)
                <div class="text-center mt-12">
                    <a href="{{ route('landing.report') }}" class="btn btn-primary">Lihat Semua Laporan Warga</a>
                </div>
            @endif
        </div>
    </section>

    <section id="landingFAQ" class="section-py bg-body landing-faq">
        <div class="container">
            <h4 class="text-center mb-1">
                <span class="position-relative fw-extrabold z-1">
                    Informasi RT
                    <img src="{{ asset('img/front-pages/icons/section-title-icon.png') }}" alt="laptop charging"
                        class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" loading="lazy">
                </span>
            </h4>
            <p class="text-center mb-12 pb-md-4">
                Berikut beberapa informasi terbaru seputar RT kami.
            </p>
            <div class="row g-4">
                @foreach(range(1, 5) as $index)
                    @php
                        $types = ['Kegiatan', 'Pengumuman', 'Peraturan', 'Keuangan', 'Keamanan'];
                        $titles = [
                            'Jadwal Kerja Bakti Lingkungan RT 03',
                            'Rapat Koordinasi Warga Bulan Agustus',
                            'Pembayaran Iuran Keamanan Bulanan',
                            'Pembaruan Data Kependudukan RT',
                            'Himbauan Keamanan Lingkungan'
                        ];
                        $dates = [
                            now()->addDays(3)->format('d M Y'),
                            now()->addDays(7)->format('d M Y'),
                            now()->addDays(14)->format('d M Y'),
                            now()->addDay()->format('d M Y'),
                            now()->addDays(5)->format('d M Y'),
                        ];
                        $icons = ['bx-calendar', 'bx-megaphone', 'bx-book', 'bx-money', 'bx-shield'];
                        $colors = ['primary', 'success', 'danger', 'warning', 'info'];

                        $type = $types[$index % 5];
                        $title = $titles[$index % 5];
                        $date = $dates[$index % 5];
                        $icon = $icons[$index % 5];
                        $color = $colors[$index % 5];
                    @endphp

                    <div class="col-12 mb-3">
                        <div class="card shadow-sm border-0 hover:shadow-lg transition-all">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-label-{{ $color }} rounded me-3">
                                        <i class="bx {{ $icon }} fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ $title }}</h5>
                                        <p class="mb-1 text-muted">
                                            @if($type == 'Kegiatan')
                                                Kerja bakti pembersihan selokan dan lingkungan sekitar RT 03 akan dilaksanakan pada hari Minggu.
                                            @elseif($type == 'Pengumuman')
                                                Rapat koordinasi warga akan diadakan di Balai RT untuk membahas rencana renovasi pos ronda.
                                            @elseif($type == 'Peraturan')
                                                Pengingat untuk pembayaran iuran keamanan bulan ini paling lambat tanggal 15.
                                            @elseif($type == 'Keuangan')
                                                Seluruh warga dimohon memperbarui data kependudukan untuk keperluan administrasi RT.
                                            @else
                                                Warga diimbau untuk meningkatkan kewaspadaan dan selalu mengunci rumah/kendaraan dengan baik.
                                            @endif
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-label-{{ $color }} me-2">{{ $type }}</span>
                                            <span class="ms-2 text-muted small">{{ $date }}</span>
                                            <div class="ms-auto">
                                                <a href="#" class="btn btn-sm btn-primary">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('landing.information') }}" class="btn btn-primary">Lihat Semua Informasi RT</a>
            </div>
        </div>
    </section>

    <section id="landingTeam" class="section-py landing-team">
        <div class="container">
            <h4 class="text-center mb-1">
                <span class="position-relative fw-extrabold z-1">Supported
                    <img src="{{ asset('img/front-pages/icons/section-title-icon.png') }}" alt="laptop charging"
                        class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" loading="lazy">
                </span>
                by Real People
            </h4>
            <p class="text-center mb-md-11 pb-0 pb-xl-12">Who is behind these excellent services?</p>
            <div class="row gy-4 mt-2">
                <div class="col">
                    <div class="card mt-3 mt-lg-0 shadow-none">
                        <div
                            class="bg-label-primary border border-bottom-0 border-primary-subtle position-relative team-image-box">
                            <img src="{{ asset('img/front-pages/teams/syabananta-faqih-m-l.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50" alt="human image" loading="lazy">
                        </div>
                        <div class="card-body border border-top-0 border-primary-subtle text-center py-5">
                            <h5 class="card-title mb-0">Sya’bananta Faqih M L</h5>
                            <p class="text-body-secondary mb-0">Project Manager</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mt-3 mt-lg-0 shadow-none">
                        <div
                            class="bg-label-info border border-bottom-0 border-info-subtle position-relative team-image-box">
                            <img src="{{ asset('img/front-pages/teams/apri-pandu-w.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50" alt="human image" loading="lazy">
                        </div>
                        <div class="card-body border border-top-0 border-info-subtle text-center py-5">
                            <h5 class="card-title mb-0">Apri Pandu Wicaksono</h5>
                            <p class="text-body-secondary mb-0">Fullstack Developer</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mt-3 mt-lg-0 shadow-none">
                        <div
                            class="bg-label-danger border border-bottom-0 border-danger-subtle position-relative team-image-box">
                            <img src="{{ asset('img/front-pages/teams/warga-plus-member.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50" alt="human image" loading="lazy">
                        </div>
                        <div class="card-body border border-top-0 border-danger-subtle text-center py-5">
                            <h5 class="card-title mb-0">Muhammad Zaki Fauzan</h5>
                            <p class="text-body-secondary mb-0">UI/UX Designer</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mt-3 mt-lg-0 shadow-none">
                        <div
                            class="bg-label-success border border-bottom-0 border-success-subtle position-relative team-image-box">
                            <img src="{{ asset('img/front-pages/teams/warga-plus-member.png') }}"
                                class="position-absolute card-img-position bottom-0 start-50" alt="human image" loading="lazy">
                        </div>
                        <div class="card-body border border-top-0 border-success-subtle text-center py-5">
                            <h5 class="card-title mb-0">M Hamzah Haifan M</h5>
                            <p class="text-body-secondary mb-0">UI/UX Designer</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mt-3 mt-lg-0 shadow-none">
                        <div
                            class="bg-label-warning border border-bottom-0 border-warning-subtle position-relative team-image-box">
                            <img src="{{ asset('img/front-pages/teams/alip.jpg') }}"
                                class="position-absolute card-img-position bottom-0 start-50" alt="human image" loading="lazy">
                        </div>
                        <div class="card-body border border-top-0 border-warning-subtle text-center py-5">
                            <h5 class="card-title mb-0">Alif Zaujati Randri</h5>
                            <p class="text-body-secondary mb-0">QA Engineer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @pushOnce('plugin-scripts')
    @endPushOnce

    @pushOnce('page-scripts')
    @endPushOnce
</x-layouts.landing>
