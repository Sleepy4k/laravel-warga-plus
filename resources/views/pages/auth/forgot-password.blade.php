<x-layouts.auth title="Forgot Password">
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('login') }}" class="d-inline-flex align-items-center text-muted text-decoration-none mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Login
                        </a>

                        <h4 class="mb-2">Forgot Password? 🔒</h4>
                        <p class="mb-4">Enter your phone and we'll send you instructions to reset your password</p>
                        <form id="formForgotPassword" class="mb-3" action="{{ route('password.email') }}"
                            method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Enter your phone number" autofocus />
                            </div>
                            <button class="btn btn-primary d-grid w-100">Send Reset Link</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('page-scripts')
        <script src="{{ asset('js/pages/auth-forgot-password.min.js') }}" @cspNonce></script>
    @endPushOnce
</x-layouts.auth>
