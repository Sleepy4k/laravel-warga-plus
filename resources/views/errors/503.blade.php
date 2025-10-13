<x-layouts.error code="503" title="Under Maintenance!"
    message="Sorry for the inconvenience but we're performing some maintenance at the moment">
    <div class="mt-4">
        <img src="{{ asset('img/illustrations/girl-doing-yoga-light.png') }}" alt="girl-doing-yoga-light" width="500"
            class="img-fluid" data-app-dark-img="illustrations/girl-doing-yoga-dark.png"
            data-app-light-img="illustrations/girl-doing-yoga-light.png" loading="lazy" />
    </div>

    @pushOnce('page-scripts')
        <script @cspNonce>
            document.addEventListener('DOMContentLoaded', function() {
                const reloadButton = document.getElementById('reload-button');
                if (reloadButton) {
                    setTimeout(() => {
                        reloadButton.disabled = false;
                        reloadButton.innerHTML = 'Reload Page';
                        reloadButton.classList.remove('disabled');
                        reloadButton.addEventListener('click', function() {
                            location.reload();
                        });
                    }, 1500);
                }
            });
        </script>
    @endPushOnce
</x-layouts.error>
