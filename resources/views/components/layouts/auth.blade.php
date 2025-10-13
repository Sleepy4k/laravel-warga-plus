<!DOCTYPE html>
<html dir="ltr" data-assets-path="{{ config('app.url') }}/" class="dark-style customizer-hide"
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

        <link rel="stylesheet" href="{{ asset('vendor/libs/animate-css/animate.css') }}" @cspNonce />

        @stack('plugin-styles')

        <link rel="stylesheet" href="{{ asset('vendor/libs/formvalidation/dist/css/formValidation.min.css') }}"
            @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/css/pages/page-auth.css') }}" @cspNonce" />

        <script type="text/javascript" src="{{ asset('vendor/js/helpers.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/js/template-customizer.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('js/config.min.js') }}" @cspNonce></script>
    </head>
    <body>
        {{ $slot }}

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

        <script type="text/javascript" src="{{ asset('vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}" @cspNonce>
        </script>
        <script type="text/javascript" src="{{ asset('vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"
            @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"
            @cspNonce></script>

        <script type="text/javascript" src="{{ asset('js/main.min.js') }}" @cspNonce></script>

        @stack('page-scripts')
    </body>
</html>
