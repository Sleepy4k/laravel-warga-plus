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
        <x-landing.navbar />

        <div data-bs-spy="scroll" class="scrollspy-example">
            {{ $slot }}
        </div>

        <x-landing.footer />

        <x-utils.noscript />

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
