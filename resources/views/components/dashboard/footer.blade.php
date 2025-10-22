<footer id="footer-dashboard" class="content-footer footer bg-footer-theme" style="display: none;">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            © {{ date('Y') }}, made with ❤️ by
            <a href="{{ $appSettings['footer_copyright_url'] }}" target="_blank" rel="noopener noreferrer" class="footer-link fw-bolder">
                {{ $appSettings['footer_copyright'] }}
            </a>
        </div>
        <div>
            @foreach ($appSettings as $key => $value)
                @if (str_starts_with($key, 'footer_') && strpos($key, 'copyright') === false && str_ends_with($key, '_url'))
                    <a href="{{ $value }}" target="_blank" rel="noopener noreferrer"
                        class="footer-link me-4">{{ ucfirst($appSettings[str_replace('_url', '_title', $key)]) }}</a>
                @endif
            @endforeach
        </div>
    </div>
</footer>
