<div id="accountDetailsValidation" class="content">
    <div class="content-header mb-3">
        <h3 class="mb-1">Account Information</h3>
        <span>Enter Your Account Details</span>
    </div>

    <div class="row g-3">
        <div class="col-sm-6">
            <label class="form-label" for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="nurhadi123"
                value="{{ old('username') }}" />

            <x-input.error for="username" />
        </div>

        <div class="col-sm-6">
            <label class="form-label" for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                placeholder="nurhadi.aldo@example.com" aria-label="nurhadi.aldo" value="{{ old('email') }}" />

            <x-input.error for="email" />
        </div>

        <div class="col-sm-6 form-password-toggle">
            <label class="form-label" for="password">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" autocomplete />
                <span class="input-group-text cursor-pointer" id="password"><i class="bx bx-hide"></i></span>
            </div>

            <x-input.error for="password" />
        </div>

        <div class="col-sm-6 form-password-toggle">
            <label class="form-label" for="password_confirmation">Confirm
                Password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password_confirmation" autocomplete />
                <span class="input-group-text cursor-pointer" id="password_confirmation"><i
                        class="bx bx-hide"></i></span>
            </div>

            <x-input.error for="password_confirmation" />
        </div>

        <div class="col-12 d-flex justify-content-between">
            <button class="btn btn-label-secondary btn-prev" disabled>
                <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                <span class="align-middle d-sm-inline-block d-none">Previous</span>
            </button>
            <button class="btn btn-primary btn-next">
                <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
            </button>
        </div>
    </div>
</div>
