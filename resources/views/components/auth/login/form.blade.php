<form id="formAuthentication" class="mb-3" action="{{ route('login.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Email or Username</label>
        <input type="text" class="form-control @error('email-username') is-invalid @enderror" id="email"
            name="email-username" placeholder="Enter your email or username" @disabled($disabled) required
            autofocus />

        <x-input.error for="email-username" />
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
