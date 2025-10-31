<x-layouts.auth title="Verify your account">
    <div class="authentication-wrapper authentication-basic px-4">
        <div class="authentication-inner">
            <div class="card">
                <div class="card-body">
                    <x-auth.verification.header />

                    <x-auth.verification.resend :remaining="$remaining" />
                </div>
            </div>
        </div>
    </div>

    @pushOnce('page-scripts')
        @if ($remaining === 0)
            <script @cspNonce>
                $(document).ready(function() {
                    let timeRemaining = {{ $reset_at }};
                    const $button = $('#resend-verification-code-button');

                    const interval = setInterval(() => {
                        if (timeRemaining <= 0) {
                            clearInterval(interval);
                            $button.prop('disabled', false);
                            $button.text('Resend Verification Code');
                            return;
                        }
                        if (timeRemaining < 60) {
                            $button.text(`Resend in ${timeRemaining} second${timeRemaining !== 1 ? 's' : ''}`);
                        } else {
                            const minutes = Math.floor(timeRemaining / 60);
                            const seconds = timeRemaining % 60;
                            $button.text(
                                `Resend in ${minutes} minute${minutes !== 1 ? 's' : ''}${seconds > 0 ? ' ' + seconds + ' second' + (seconds !== 1 ? 's' : '') : ''}`
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
