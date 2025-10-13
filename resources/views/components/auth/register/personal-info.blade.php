<div id="personalInfoValidation" class="content">
    <div class="content-header mb-3">
        <h3 class="mb-1">Personal Information</h3>
        <span>Enter Your Personal Information</span>
    </div>

    <div class="row g-3">
        <div class="col-sm-6">
            <label class="form-label" for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" placeholder="John"
                value="{{ old('first_name') }}" />

            <x-input.error for="first_name" />
        </div>

        <div class="col-sm-6">
            <label class="form-label" for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Doe"
                value="{{ old('last_name') }}" />

            <x-input.error for="last_name" />
        </div>

        <div class="col-sm-6">
            <label class="form-label" for="whatsapp_number">Whatsapp Number</label>
            <div class="input-group input-group-merge">
                <span class="input-group-text">IDN (+62)</span>
                <input type="text" id="whatsapp_number" name="whatsapp_number"
                    class="form-control multi-steps-mobile" placeholder="8xx xxxx xxxx" maxlength="14"
                    value="{{ old('whatsapp_number') }}" />
            </div>

            <x-input.error for="whatsapp_number" />
        </div>

        <div class="col-sm-6">
            <label class="form-label" for="telkom_batch">Telkom Batch</label>
            <select id="telkom_batch" name="telkom_batch" class="form-select select2"
                data-placeholder="Select your batch">
                @foreach ($years as $year)
                    <option value="{{ $year }}" @selected(old('telkom_batch', date('Y')) == $year)>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

            <x-input.error for="telkom_batch" />
        </div>

        <div class="col-md-12">
            <label class="form-label" for="address">Address</label>
            <textarea id="address" name="address" class="form-control" placeholder="1234 Main St, City, Country" rows="3">{{ old('address') }}</textarea>

            <x-input.error for="address" />
        </div>

        <div class="col-12 d-flex justify-content-between">
            @if ($isCustomRegistration)
                <button class="btn btn-label-secondary btn-prev" disabled>
                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
            @else
                <button class="btn btn-primary btn-prev">
                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
            @endif
            <button class="btn btn-primary btn-next">
                <span class="align-middle d-sm-inline-block d-none me-sm-1 me-0">Next</span>
                <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
            </button>
        </div>
    </div>
</div>
