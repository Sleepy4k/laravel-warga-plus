<h4 class="mb-2">Welcome to {{ $appSettings['app_name'] }}! 👋</h4>
<p class="mb-4">Please sign-in to your account and start the adventure</p>

@if ($remaining === 0)
    <p class="text-danger mb-4" id="rate-limit-warning">
        You've reached the maximum number of login attempts. Please wait
        <strong id="time-remaining">0 seconds</strong>
        before trying again.
    </p>
@endif
