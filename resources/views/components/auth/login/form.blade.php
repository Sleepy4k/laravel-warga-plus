<form id="formAuthentication" class="mb-3" action="{{ route('login.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="phone-identity" class="form-label">Phone or Identity Number</label>
        <input type="text" class="form-control @error('phone-identity') is-invalid @enderror" id="phone-identity"
            name="phone-identity" placeholder="Enter your phone or identity number" @disabled($disabled) required
            autofocus />

        <x-input.error for="phone-identity" />
    </div>

    <div class="mb-3 form-password-toggle">
        <div class="d-flex justify-content-between">
            <label class="form-label" for="password">Password</label>
            <a href="{{ route('password.request') }}">
                <small>Forgot Password?</small>
            </a>
        </div>
        <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                name="password" placeholder="************" aria-describedby="password" autocomplete="current-password"
                aria-label="Password" aria-required="true" required pattern=".{8,}" @disabled($disabled) />
            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        </div>

        <x-input.error for="password" />
    </div>

    <div class="mb-3">
        <button class="btn btn-primary d-grid w-100" type="submit" @disabled($disabled)>Sign in</button>
    </div>
</form>
