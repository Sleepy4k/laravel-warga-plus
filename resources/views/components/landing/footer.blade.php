<footer class="landing-footer bg-body footer-text">
    <div class="footer-top position-relative overflow-hidden z-1">
        <img src="{{ asset('img/front-pages/backgrounds/footer-bg.png') }}" alt="footer bg"
            class="footer-bg banner-bg-img z-n1" loading="lazy" />
        <div class="container">
            <div class="row gx-0 gy-6 g-lg-10">
                <div class="col-lg-5">
                    <a href="{{ route('landing.home') }}" class="app-brand-link mb-6">
                        <span class="app-brand-logo demo">
                            <span class="text-primary">
                                <img src="{{ $appSettings['app_logo'] }}" alt="Logo" loading="lazy"
                                    width="35" />
                            </span>
                        </span>
                        <span class="app-brand-text demo text-white fw-bold ms-2 ps-1"
                            style="font-size: {{ $appSettings['sidebar_name_size'] - 0.2 }}rem;">{{ $appSettings['sidebar_name'] }}</span>
                    </a>
                    <p class="footer-text footer-logo-description mb-6">
                        Dengan satu platform, semua warga dapat secara terbuka menyampaikan pengaduan, ide, dan solusi
                        untuk kemajuan bersama.
                    </p>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <h6 class="footer-title mb-6">Pages</h6>
                    <ul class="list-unstyled">
                        @foreach ($pages as $page)
                            <li class="mb-4">
                                <a href="{{ route($page['route']) }}" class="footer-link">{{ $page['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                    <h6 class="footer-title mb-6">Useful Links</h6>
                    <ul class="list-unstyled">
                        @foreach ($usefulLinks as $link)
                            <li class="mb-4">
                                <a href="{{ route($link['route']) }}" target="_blank" rel="noopener noreferrer"
                                    class="footer-link">{{ $link['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <h6 class="footer-title mb-6">Related Website</h6>
                    <ul class="list-unstyled">
                        @foreach ($relatedWebsites as $website)
                            <li class="mb-4">
                                <a href="{{ $website['url'] }}" target="_blank" rel="noopener noreferrer"
                                    class="footer-link">{{ $website['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
