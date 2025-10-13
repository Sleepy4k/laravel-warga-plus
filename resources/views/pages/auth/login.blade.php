<x-layouts.auth title="Login">
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <x-auth.login.header :remaining="$rateLimiter['remaining']" :resetAt="$rateLimiter['reset_at']" />

                        <x-auth.login.form :remaining="$rateLimiter['remaining']" />

                        <x-auth.login.footer />
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('page-scripts')
        <script src="{{ asset('js/pages/auth.min.js') }}" @cspNonce></script>
        @if ($rateLimiter['remaining'] === 0)
            <script @cspNonce>
                $(document).ready(function() {
                    let timeRemaining = {{ $rateLimiter['reset_at'] }};
                    const $timeRemaining = $('#time-remaining');
                    const $form = $('#formAuthentication');
                    const $rateLimitWarning = $('#rate-limit-warning');
                    const $inputs = $form.find('input, button');

                    const interval = setInterval(() => {
                        if (timeRemaining <= 0) {
                            clearInterval(interval);
                            $timeRemaining.text('0 seconds');
                            $inputs.prop('disabled', false);
                            $rateLimitWarning.removeClass('text-danger').addClass('text-success');
                            $rateLimitWarning.text('You can now try logging in again.');
                            $rateLimitWarning.hide().fadeIn(500);
                            return;
                        }
                        if (timeRemaining < 60) {
                            $timeRemaining.text(`${timeRemaining} second${timeRemaining !== 1 ? 's' : ''}`);
                        } else {
                            const minutes = Math.floor(timeRemaining / 60);
                            const seconds = timeRemaining % 60;
                            $timeRemaining.text(
                                `${minutes} minute${minutes !== 1 ? 's' : ''}${seconds > 0 ? ' ' + seconds + ' second' + (seconds !== 1 ? 's' : '') : ''}`
                            );
                        }
                        timeRemaining--;
                    }, 1000);

                    $form.on('submit', function() {
                        clearInterval(interval);
                    });
                });
            </script>
        @endif
    @endPushOnce
</x-layouts.auth>
