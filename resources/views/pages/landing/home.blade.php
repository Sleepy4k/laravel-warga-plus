<x-layouts.landing>
    <x-landing.hero>
        <div class="hero-text-box text-center position-relative">
            <h1 class="text-primary hero-title display-6 fw-extrabold">
                Satu portal untuk suara warga dan aksi nyata
            </h1>
            <h2 class="hero-sub-title h6 mb-6">
                Warga<sup>+</sup> memudahkan warga untuk melapor, berpartisipasi, dan memantau tindak lanjut dari setiap
                laporan secara transparan dan cepat
            </h2>
            <div class="landing-hero-btn d-inline-block position-relative">
                <span class="hero-btn-item position-absolute d-none d-md-flex fw-medium">
                    Memiliki kendala?
                    <img src="{{ asset('img/front-pages/icons/Join-community-arrow.png') }}" alt="Join community arrow"
                        class="scaleX-n1-rtl" loading="lazy" />
                </span>
                <a href="{{ route('dashboard.report.index') }}" class="btn btn-primary btn-lg">Laporkan Sekarang!</a>
            </div>
        </div>
    </x-landing.hero>

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
                @forelse ($reports as $index => $item)
                    <div class="col-lg-4 col-sm-6">
                        <div
                            class="card h-100 shadow-xl border-0 outline outline-primary outline-offset-3 outline-1 hover:outline-2">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="{{ $item->user->avatar_url ?? asset('warga-plus.png') }}"
                                            alt="Avatar" class="rounded-circle" loading="lazy">
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $item->user->personal->full_name ?? 'N/A' }}</h6>
                                        <small class="text-muted">
                                            {{ $item->category->name ?? 'N/A' }} •
                                            {{ date('d M Y', strtotime($item->created_at)) }}
                                        </small>
                                    </div>
                                    <div class="ms-auto">
                                        <span
                                            class="badge bg-label-{{ $reportIcons[$item->status]['bg'] }} d-flex align-items-center gap-1">
                                            <i class="{{ $reportIcons[$item->status]['icon'] }}"></i>
                                            <span>{{ $reportIcons[$item->status]['text'] }}</span>
                                        </span>
                                    </div>
                                </div>

                                <h5 class="mb-2">{{ $item->title }}</h5>
                                <p class="mb-3 text-muted">
                                    {{ Str::limit($item->content, 100, '...') }}
                                </p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-label-info">{{ $item->category->name ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('landing.report.show', $item->id) }}"
                                            class="btn btn-sm btn-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4">
                        <div class="text-center p-4 bg-light-primary rounded-3">
                            <h5 class="mb-0">Belum ada laporan terbaru</h5>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($isReportMoreThanSix)
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
                @forelse($informations as $index => $item)
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm border-0 hover:shadow-lg transition-all">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="avatar avatar-sm d-flex align-items-center justify-content-center bg-label-primary rounded me-3 mt-1">
                                        <img src="{{ $item->user->avatar_url ?? asset('warga-plus.png') }}"
                                            alt="{{ $item->user->personal->full_name ?? 'N/A' }}"
                                            class="object-fit-contain" loading="lazy">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ $item->title }}</h5>
                                        <p class="mb-1 text-muted">
                                            {{ $item->content }}
                                        </p>
                                        <div class="d-flex align-items-center mt-3">
                                            <span
                                                class="badge bg-label-primary me-2">{{ $item->category->name ?? 'N/A' }}</span>
                                            <span
                                                class="ms-2 text-muted small">{{ $item->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4">
                        <div class="text-center p-4 bg-light-primary rounded-3">
                            <h5 class="mb-0">Tidak ada informasi yang tersedia</h5>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($isInformationMoreThanSix)
                <div class="text-center mt-8">
                    <a href="{{ route('landing.information') }}" class="btn btn-primary">Lihat Semua Informasi RT</a>
                </div>
            @endif
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
                @foreach ($teams as $team)
                    <div class="col">
                        <div class="card mt-3 mt-lg-0 shadow-none">
                            <div
                                class="{{ $team['bg_class'] }} border border-bottom-0 {{ $team['border_class'] }} position-relative team-image-box">
                                <img src="{{ asset($team['image']) }}"
                                    class="position-absolute card-img-position bottom-0 start-50"
                                    alt="Team {{ $team['name'] }}" loading="lazy">
                            </div>
                            <div class="card-body border border-top-0 {{ $team['border_class'] }} text-center py-5">
                                <h5 class="card-title mb-0">{{ $team['name'] }}</h5>
                                <p class="text-body-secondary mb-0">{{ $team['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @pushOnce('page-scripts')
        <script @cspNonce>
            document.addEventListener('DOMContentLoaded', function() {
                gsap.from(".features-icon-wrapper .card", {
                    opacity: 0,
                    y: 100,
                    duration: 1,
                    stagger: 0.1,
                    scrollTrigger: {
                        trigger: "#landingFeatures",
                        start: "top 70%",
                        toggleActions: "play none none reverse"
                    }
                });

                gsap.from(".landing-faq .card", {
                    opacity: 0,
                    y: 100,
                    duration: 1,
                    stagger: 0.1,
                    scrollTrigger: {
                        trigger: "#landingFAQ",
                        start: "top 70%",
                        toggleActions: "play none none reverse"
                    }
                });

                gsap.from(".landing-team .card", {
                    opacity: 0,
                    y: 100,
                    duration: 1,
                    stagger: 0.2,
                    scrollTrigger: {
                        trigger: "#landingTeam",
                        start: "top 70%",
                        toggleActions: "play none none reverse"
                    }
                });
            });
        </script>
    @endPushOnce
</x-layouts.landing>
