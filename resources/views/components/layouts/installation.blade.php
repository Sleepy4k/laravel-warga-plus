<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="title" content="{{ $title ?? '' }} | {{ config('app.name') }}">
        <meta name="description" content="Website resmi Himpunan Pengusaha Muda Indonesia (HIPMI) PT Telkom University Purwokerto.">        <meta name="author" content="benjamin4k">
        <meta name="coverage" content="Worldwide">
        <meta name="distribution" content="Global">
        <meta name="robots" content="noindex, nofollow">
        <meta name="keywords" content="cms, hipmi, hipmi pt, pt, tup, telkom, university, universitas, telkom university, universitas telkom, purwokerto, banyumas, bpc, hipmi bpc, jawa tengah, hipmi jawa tengah, hipmi jateng">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <link rel="icon" href="{{ asset('favicon.ico') }}" />
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
        <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}" />
        <link rel="apple-touch-icon-precomposed" href="{{ asset('favicon.ico') }}" />

        <title>{{ $title ?? '' }} | {{ config('app.name') }}</title>

        @cspMetaTag

        <meta property="csp-nonce" content="{{ app('csp-nonce') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            <div class="flex min-h-screen flex-col justify-center bg-neutral-100 py-12 sm:px-6 lg:px-8">
                <div class="sm:mx-auto sm:w-full sm:max-w-md">
                    <div class="mt-5 text-center" data-aos="fade-down">
                        <x-install.logo divClasses="justify-center w-auto h-auto" textClasses="text-black" />
                    </div>
                </div>
                <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-5xl">
                    <div class="border border-neutral-200 bg-white p-7 shadow-sm sm:rounded-lg">
                        <x-install.menu :step="$step" />

                        @if ($errors && $errors !== null && $errors->any())
                            <div class="mt-5 rounded-md bg-warning-50 p-4">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <svg class="h-5 w-5 text-warning-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-warning-800">
                                            There was an error with your submission
                                        </h3>
                                        <div class="mt-2 text-sm text-warning-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{ $slot }}
                    </div>
                </div>
            </div>
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
    </body>
</html>
