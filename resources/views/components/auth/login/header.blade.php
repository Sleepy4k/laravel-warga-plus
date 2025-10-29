<a href="{{ route('landing.home') }}" class="d-inline-flex align-items-center text-muted text-decoration-none mb-4">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
    Back to Home
</a>

<h4 class="mb-2">Welcome to {{ $appSettings['app_name'] }}! 👋</h4>
<p class="mb-4">Please sign-in to your account and start the adventure</p>

@if ($remaining === 0)
    <p class="text-danger mb-4" id="rate-limit-warning">
        You've reached the maximum number of login attempts. Please wait
        <strong id="time-remaining">0 seconds</strong>
        before trying again.
    </p>
@endif
