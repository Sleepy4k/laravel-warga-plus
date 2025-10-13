@if ($show)
    <link rel="stylesheet" href="{{ asset('vendor/libs/toastr/toastr.css') }}" @cspNonce />

    <div class="bs-toast toast toast-ex animate__animated my-2 fade bg-{{ $type ?? 'primary' }} animate__fade show"
        role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2500">
        <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">
                {{ $title }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ $message }}
        </div>
    </div>

    <script src="{{ asset('vendor/libs/toastr/toastr.js') }}" @cspNonce></script>
    <script @cspNonce>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelector('.toast').classList.remove('show');
            }, 2500);
        });
    </script>
@endif
