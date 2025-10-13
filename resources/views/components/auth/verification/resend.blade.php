<form id="twoStepsForm" action="{{ route('verification.send') }}" method="POST" class="mb-4">
    @csrf

    <div>
        <label for="verification_code" class="form-label">
            Didn't receive the code?
        </label>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-2" id="resend-verification-code-button"
        @disabled($remaining === 0)>
        Resend Verification Code
    </button>
</form>
