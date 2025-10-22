<!DOCTYPE html>
<html dir="ltr" data-assets-path="{{ config('app.url') }}/" class="layout-navbar-fixed"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark" data-template="front-pages" data-skin="default">
    <head>
        <meta charset="utf-8">
        <meta name="title" content="{{ empty($title) ? $appSettings['app_name'] : $title . ' | ' . $appSettings['app_name'] }}">
        <meta name="description" content="{{ $appSettings['app_description'] }}">
        <meta name="author" content="{{ $appSettings['seo_author'] }}">
        <meta name="coverage" content="Worldwide">
        <meta name="distribution" content="Global">
        <meta name="robots" content="index, follow">
        <meta name="keywords" content="{{ $appSettings['seo_keywords'] }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <link rel="canonical" href="{{ config('app.url') }}">

        <link rel="icon" href="{{ $appSettings['app_favicon'] }}" />
        <link rel="shortcut icon" href="{{ $appSettings['app_favicon'] }}" />
        <link rel="apple-touch-icon" href="{{ $appSettings['app_favicon'] }}" />
        <link rel="apple-touch-icon-precomposed" href="{{ $appSettings['app_favicon'] }}" />

        <title>{{ empty($title) ? $appSettings['app_name'] : $title . ' | ' . $appSettings['app_name'] }}</title>

        @cspMetaTag

        <meta property="csp-nonce" content="{{ app('csp-nonce') }}">

        <meta property="og:locale" content="{{ app()->getLocale() }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $appSettings['app_name'] }}">
        <meta property="og:title" content="{{ empty($title) ? $appSettings['app_name'] : $title . ' | ' . $appSettings['app_name'] }}">
        <meta property="og:description" content="{{ $appSettings['app_description'] }}">
        <meta property="og:image" content="{{ $appSettings['app_logo'] }}">
        <meta property="og:image:width" content="{{ $appSettings['seo_image_width'] }}" />
        <meta property="og:image:height" content="{{ $appSettings['seo_image_height'] }}" />
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:alt" content="{{ empty($title) ? $appSettings['app_name'] : $title . ' | ' . $appSettings['app_name'] }}">

        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:domain" content="{{ url()->current() }}">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:locale" content="{{ app()->getLocale() }}">
        <meta property="twitter:title" content="{{ empty($title) ? $appSettings['app_name'] : $title . ' | ' . $appSettings['app_name'] }}">
        <meta property="twitter:description" content="{{ $appSettings['app_description'] }}">
        <meta property="twitter:image" content="{{ $appSettings['app_logo'] }}">
        <meta property="twitter:image:width" content="{{ $appSettings['seo_image_width'] }}" />
        <meta property="twitter:image:height" content="{{ $appSettings['seo_image_height'] }}" />
        <meta property="twitter:image:type" content="image/png">
        <meta property="twitter:image:alt" content="{{ empty($title) ? $appSettings['app_name'] : $title . ' | ' . $appSettings['app_name'] }}">

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" />

        <link rel="stylesheet" href="{{ asset('vendor/fonts/iconify-icons.css') }}" @cspNonce />

        <link rel="stylesheet" href="{{ asset('vendor/css/front-pages/core.css') }}" class="template-customizer-core-css"
            @cspNonce />
        <link rel="stylesheet" href="{{ asset('css/demo.min.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/css/front-pages/pages/front.css') }}" class="template-customizer-theme-css"
            @cspNonce />

        @stack('plugin-styles')

        <link rel="stylesheet" href="{{ asset('vendor/css/front-pages/pages/front-landing.css') }}" class="template-customizer-theme-css"
            @cspNonce />

        <script type="text/javascript" src="{{ asset('vendor/js/front-page/helpers.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/js/front-page/template-customizer.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('js/front-config.js') }}" @cspNonce></script>
    </head>
    <body>
        <nav class="layout-navbar shadow-none py-0">
            <div class="container">
                <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
                <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8">
                    <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="icon-base bx bx-menu icon-lg align-middle text-heading fw-medium"></i>
                    </button>
                    <a href="landing-page.html" class="app-brand-link">
                    <span class="app-brand-logo demo">
            <span class="text-primary">

            <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <defs>
                <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                </defs>
                <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                    <g id="Icon" transform="translate(27.000000, 15.000000)">
                    <g id="Mask" transform="translate(0.000000, 8.000000)">
                        <mask id="mask-2" fill="white">
                        <use xlink:href="#path-1"></use>
                        </mask>
                        <use fill="currentColor" xlink:href="#path-1"></use>
                        <g id="Path-3" mask="url(#mask-2)">
                        <use fill="currentColor" xlink:href="#path-3"></use>
                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                        </g>
                        <g id="Path-4" mask="url(#mask-2)">
                        <use fill="currentColor" xlink:href="#path-4"></use>
                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                        </g>
                    </g>
                    <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                        <use fill="currentColor" xlink:href="#path-5"></use>
                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                    </g>
                    </g>
                </g>
                </g>
            </svg>
            </span>
            </span>
                    <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1">Sneat</span>
                    </a>
                </div>

                <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                    <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="icon-base bx bx-x icon-lg"></i>
                    </button>
                    <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-medium active" aria-current="page" href="landing-page.html#landingHero">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="landing-page.html#landingFeatures">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="landing-page.html#landingTeam">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="landing-page.html#landingFAQ">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="landing-page.html#landingContact">Contact us</a>
                    </li>
                    </ul>
                </div>
                <div class="landing-menu-overlay d-lg-none"></div>

                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                        <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown" aria-label="Toggle theme (system)">
                        <i class="bx-desktop icon-base bx icon-lg theme-icon-active"></i>
                        <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                        <li>
                            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="light" aria-pressed="false">
                            <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>Light</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                            <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>Dark</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="system" aria-pressed="true">
                            <span><i class="icon-base bx bx-desktop icon-md me-3" data-icon="desktop"></i>System</span>
                            </button>
                        </li>
                        </ul>
                    </li>

                    <li>
                    <a href="../vertical-menu-template/auth-login-cover.html" class="btn btn-primary" target="_blank"><span class="tf-icons icon-base bx bx-log-in-circle scaleX-n1-rtl me-md-1"></span><span class="d-none d-md-block">Login/Register</span></a>
                    </li>
                </ul>
                </div>
            </div>
            </nav>

        <div data-bs-spy="scroll" class="scrollspy-example">
            {{ $slot }}
        </div>

        <footer class="landing-footer bg-body footer-text">
          <div class="footer-top position-relative overflow-hidden z-1">
            <img src="{{ asset('img/front-pages/backgrounds/footer-bg.png') }}" alt="footer bg" class="footer-bg banner-bg-img z-n1">
            <div class="container">
              <div class="row gx-0 gy-6 g-lg-10">
                <div class="col-lg-5">
                  <a href="landing-page.html" class="app-brand-link mb-6">
                    <span class="app-brand-logo demo">
                      <span class="text-primary">
                        <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <defs>
                            <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                            <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                            <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                            <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                          </defs>
                          <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                              <g id="Icon" transform="translate(27.000000, 15.000000)">
                                <g id="Mask" transform="translate(0.000000, 8.000000)">
                                  <mask id="mask-2" fill="white">
                                    <use xlink:href="#path-1"></use>
                                  </mask>
                                  <use fill="currentColor" xlink:href="#path-1"></use>
                                  <g id="Path-3" mask="url(#mask-2)">
                                    <use fill="currentColor" xlink:href="#path-3"></use>
                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                  </g>
                                  <g id="Path-4" mask="url(#mask-2)">
                                    <use fill="currentColor" xlink:href="#path-4"></use>
                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                  </g>
                                </g>
                                <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                  <use fill="currentColor" xlink:href="#path-5"></use>
                                  <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                      </span>
                    </span>
                    <span class="app-brand-text demo text-white fw-bold ms-2 ps-1">Sneat</span>
                  </a>
                  <p class="footer-text footer-logo-description mb-6">Most developer friendly &amp; highly customisable Admin Dashboard Template.</p>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                  <h6 class="footer-title mb-6">Demos</h6>
                  <ul class="list-unstyled">
                    <li class="mb-4">
                      <a href="../vertical-menu-template/" target="_blank" class="footer-link">Vertical Layout</a>
                    </li>
                  </ul>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6">
                  <h6 class="footer-title mb-6">Pages</h6>
                  <ul class="list-unstyled">
                    <li class="mb-4">
                      <a href="pricing-page.html" class="footer-link">Pricing</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </footer>

        <noscript>
            <style @cspNonce>
                .noscript-overlay {position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: #f8f9fa;z-index: 9999;display: flex;align-items: center;justify-content: center;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;}.noscript-container {text-align: center;max-width: 500px;padding: 40px 20px;background: white;border-radius: 8px;box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);}.noscript-icon {width: 60px;height: 60px;margin: 0 auto 20px;background-color: #dc3545;border-radius: 50%;display: flex;align-items: center;justify-content: center;color: white;font-size: 24px;font-weight: bold;}.noscript-title {color: #212529;margin: 0 0 16px;font-size: 24px;font-weight: 600;}.noscript-description {color: #6c757d;margin: 0 0 24px;font-size: 16px;line-height: 1.5;}.noscript-instructions {padding: 16px;background-color: #f8f9fa;border-radius: 4px;text-align: left;font-size: 14px;color: #495057;}
            </style>

            <div class="noscript-overlay">
                <div class="noscript-container">
                    <div class="noscript-icon">!</div>
                    <h2 class="noscript-title">JavaScript Required</h2>
                    <p class="noscript-description">This website requires JavaScript to function properly. Please enable JavaScript in your browser settings and reload the page.</p>
                    <div class="noscript-instructions">
                        <strong>How to enable JavaScript:</strong><br>
                        • Chrome/Edge: Settings → Privacy and security → Site settings → JavaScript<br>
                        • Firefox: about:config → javascript.enabled<br>
                        • Safari: Preferences → Security → Enable JavaScript
                    </div>
                </div>
            </div>
        </noscript>

        <script type="text/javascript" src="{{ asset('vendor/js/dropdown-hover.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/js/mega-dropdown.js') }}" @cspNonce></script>

        <script type="text/javascript" src="{{ asset('vendor/libs/popper/popper.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/js/bootstrap.js') }}" @cspNonce></script>

        <script type="text/javascript" src="{{ asset('js/front-main.js') }}" @cspNonce></script>

        @stack('plugin-scripts')

        <script type="text/javascript" src="{{ asset('js/pages/layout-landing.js') }}" @cspNonce></script>

        @stack('page-scripts')
    </body>
</html>
