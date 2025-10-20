<div id="accountDetailsValidation" class="content">
    <div class="content-header mb-3">
        <h3 class="mb-1">Account Information</h3>
        <span>Enter Your Account Details</span>
    </div>

    <div class="row g-3">
        <div class="col-sm-6">
            <label class="form-label" for="phone">Whatsapp Number</label>
            <div class="input-group input-group-merge">
                <span class="input-group-text">IDN (+62)</span>
                <input type="text" id="phone" name="phone"
                    class="form-control multi-steps-mobile" placeholder="8xx xxxx xxxx" maxlength="14"
                    value="{{ old('phone') }}" />
            </div>

            <x-input.error for="phone" />
        </div>

        <div class="col-sm-6">
            <label class="form-label" for="identity_number">Identity Number</label>
            <input type="text" name="identity_number" id="identity_number" class="form-control"
                placeholder="330123456789" aria-label="330123456789" value="{{ old('identity_number') }}" />

            <x-input.error for="identity_number" />
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
