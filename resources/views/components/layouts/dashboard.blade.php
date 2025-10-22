<!DOCTYPE html>
<html dir="ltr" data-assets-path="{{ config('app.url') }}/" class="layout-navbar-fixed dark-style layout-menu-fixed"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="title" content="{{ $title }} | {{ $appSettings['app_name'] }}">
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

        <title>{{ $title }} | {{ $appSettings['app_name'] }}</title>

        @cspMetaTag

        <meta property="csp-nonce" content="{{ app('csp-nonce') }}">

        <meta property="og:locale" content="{{ app()->getLocale() }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $appSettings['app_name'] }}">
        <meta property="og:title" content="{{ $title }} | {{ $appSettings['app_name'] }}">
        <meta property="og:description" content="{{ $appSettings['app_description'] }}">
        <meta property="og:image" content="{{ $appSettings['app_logo'] }}">
        <meta property="og:image:width" content="{{ $appSettings['seo_image_width'] }}" />
        <meta property="og:image:height" content="{{ $appSettings['seo_image_height'] }}" />
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:alt" content="{{ $title }} | {{ $appSettings['app_name'] }}">

        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:domain" content="{{ url()->current() }}">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:locale" content="{{ app()->getLocale() }}">
        <meta property="twitter:title" content="{{ $title }} | {{ $appSettings['app_name'] }}">
        <meta property="twitter:description" content="{{ $appSettings['app_description'] }}">
        <meta property="twitter:image" content="{{ $appSettings['app_logo'] }}">
        <meta property="twitter:image:width" content="{{ $appSettings['seo_image_width'] }}" />
        <meta property="twitter:image:height" content="{{ $appSettings['seo_image_height'] }}" />
        <meta property="twitter:image:type" content="image/png">
        <meta property="twitter:image:alt" content="{{ $title }} | {{ $appSettings['app_name'] }}">

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" />

        <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icons.css') }}" @cspNonce />

        <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" class="template-customizer-core-css"
            @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}" class="template-customizer-theme-css"
            @cspNonce />
        <link rel="stylesheet" href="{{ asset('css/demo.min.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('css/scrollbar.min.css') }}" @cspNonce />

        <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/animate-css/animate.css') }}" @cspNonce />

        @stack('plugin-styles')

        <link rel="stylesheet" href="{{ asset('vendor/libs/formvalidation/dist/css/formValidation.min.css') }}"
            @cspNonce />

        <link rel="stylesheet" href="{{ asset('vendor/libs/sweetalert2/sweetalert2.css') }}" @cspNonce />

        <script type="text/javascript" src="{{ asset('vendor/js/helpers.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/js/template-customizer.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('js/config.min.js') }}" @cspNonce></script>
    </head>
    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <x-dashboard.sidebar />

                <div class="layout-page">
                    <x-dashboard.navbar />

                    <div class="content-wrapper">
                        <div id="main-content" style="display: none;">
                            {{ $slot }}
                        </div>

                        <div id="offline-message" class="top-0 start-0 w-100 h-100" style="display: none;">
                            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                <div class="text-center">
                                    <i class="bx bx-wifi-off display-1 text-warning mb-4"></i>
                                    <h2 class="fw-bold text-dark mb-3">Connection Lost</h2>
                                    <p class="text-muted fs-5 mb-4">You're offline. Please check your internet connection and try again.</p>
                                    <p class="text-muted fs-6">This page will automatically reload when the connection is restored.</p>
                                </div>
                            </div>
                        </div>

                        <div id="loader" style="position: fixed; inset: 0; display: flex; justify-content: center; align-items: center; height: 100dvh; width: 100dvw; z-index: 10; background-color: rgba(0, 0, 0, 0.25);">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <x-dashboard.footer />

                        <div class="content-backdrop fade"></div>
                    </div>
                </div>
            </div>

            <div class="layout-overlay layout-menu-toggle"></div>

            <div class="drag-target"></div>
        </div>

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

        <x-utils.toast />

        <script type="text/javascript" src="{{ asset('vendor/js/core.js') }}" @cspNonce></script>

        @stack('plugin-scripts')

        <script type="text/javascript" src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/sweetalert2/sweetalert2.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}" @cspNonce>
        </script>
        <script type="text/javascript" src="{{ asset('vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"
            @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"
            @cspNonce></script>

        <script type="text/javascript" src="{{ asset('js/main.min.js') }}" @cspNonce></script>

        @stack('page-scripts')

        <script type="text/javascript" src="{{ asset('js/loader.min.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('js/pages/layout-dashboard.min.js') }}" @cspNonce></script>

        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                let isFocused = true;

                function sendHeartbeat() {
                    const loginUrl = "{{ route('login') }}";

                    fetch("{{ route('profile.heartbeat') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            isFocused: isFocused,
                        })
                    }).then(response => {
                        if (response.ok) {
                            if (response.redirected && response.url === loginUrl) {
                                console.log('User is not authenticated, redirecting to login...');
                                window.location.href = loginUrl;
                            } else {
                                response.json().then(result => {
                                    $class = 'avatar avatar-' + result.data.last_seen + ' position-relative';
                                    $('#user-avatar').removeClass().addClass($class);
                                    $('#user-avatar-drop').removeClass().addClass($class);
                                });
                            }
                        } else {
                            console.error('Heartbeat failed:', response.statusText);
                            if (response.status === 401) {
                                window.location.href = loginUrl;
                            }
                        }
                    }).catch(error => {
                        console.error('Heartbeat failed:', error);
                    });
                }

                window.addEventListener('visibilitychange', function() {
                    if (document.visibilityState === 'visible') {
                        isFocused = true;
                        sendHeartbeat();
                    } else {
                        isFocused = false;
                    }
                });

                sendHeartbeat();

                setInterval(sendHeartbeat, 2 * 60 * 1000);
            });
        </script>
    </body>
</html>
