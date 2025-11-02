<x-layouts.dashboard title="User Setting">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard.breadcrumb />

        <div class="row mt-4">
            <div class="col-md-12">
                <x-profile.navbar />

                <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.setting.update') }}" id="formAccountSettings"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                                <img src="{{ $personal->userAvatar() }}" alt="user-avatar" class="d-block rounded"
                                    height="100" width="100" id="uploadedAvatar" loading="lazy" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" class="account-file-input" name="avatar"
                                            hidden accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>
                                    <p class="text-muted mb-0">Allowed JPG, JPEG or PNG. Max size of 8 MB</p>
                                </div>
                            </div>
                            <hr class="my-0" />
                            <div class="row mt-3">
                                <div class="mb-3 col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input class="form-control" type="text" id="first_name" name="first_name"
                                        value="{{ old('first_name', $personal->first_name) }}" autofocus="">
                                    <x-input.error for="first_name" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input class="form-control" type="text" name="last_name" id="last_name"
                                        value="{{ old('last_name', $personal->last_name) }}">
                                    <x-input.error for="last_name" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="job" class="form-label">Job</label>
                                    <input class="form-control" type="text" id="job" name="job"
                                        value="{{ old('job', $personal->job) }}">
                                    <x-input.error for="job" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="phone">Phone</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">IDN (+62)</span>
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            placeholder="813 1234 5678" value="{{ old('phone', $user->phone) }}">
                                        <x-input.error for="phone" />
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="birth_date" class="form-label">Birth Date</label>
                                    <input type="text" id="birth_date"
                                        class="form-control @error('birth_date') is-invalid @enderror"
                                        placeholder="YYYY-MM-DD" aria-label="YYYY-MM-DD" aria-label="Birth Date"
                                        aria-describedby="birth_date" name="birth_date"
                                        value="{{ old('birth_date', $personal->birth_date) }}" />
                                    <x-input.error for="birth_date" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select id="gender" name="gender" class="form-select select2">
                                        @foreach ($genders as $genderOption)
                                            <option value="{{ $genderOption->value }}"
                                                {{ old('gender', $personal->gender) === $genderOption->value ? 'selected' : '' }}>
                                                {{ $genderOption->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input.error for="gender" />
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="autosize-address" class="form-label">Address</label>
                                    <textarea id="autosize-address" name="address" class="form-control">{{ old('address', $personal->address) }}</textarea>
                                    <x-input.error for="address" />
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <h5 class="card-header">Delete Account</h5>
                    <div class="card-body">
                        <div class="mb-3 col-12 mb-0">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?
                                </h6>
                                <p class="mb-0">Once you delete your account, there is no going back. Please be
                                    certain.</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('profile.setting.destroy') }}"
                            class="fv-plugins-bootstrap5 fv-plugins-framework" id="formAccountDeactivation">
                            @csrf
                            @method('DELETE')

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="confirm_delete"
                                    id="confirm_delete">
                                <label class="form-check-label" for="confirm_delete">I confirm my account
                                    deletion
                                </label>
                                <div class="invalid-feedback"></div>
                            </div>
                            <button type="submit" class="btn btn-danger deactivate-account">Delete My
                                Account</button>
                            <input type="hidden">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        <script type="text/javascript" src="{{ asset('js/pages/profile-setting.min.js') }}" @cspNonce></script>
        <script @cspNonce>
            $(document).ready(function() {
                autosize($('#autosize-address'));
            });
        </script>
        <script @cspNonce>
            $(document).ready(function() {
                var select2 = $('.select2');
                select2.length && select2.each(function() {
                    var e = $(this);
                    e.select2({
                        dropdownParent: e.parent(),
                        placeholder: 'Select an option',
                        allowClear: false,
                    });
                });
            });
        </script>
        <script @cspNonce>
            $(document).ready(function() {
                var flatpickr = $('#birth_date');
                flatpickr.length && flatpickr.each(function() {
                    var e = $(this);
                    e.flatpickr({
                        enableTime: false,
                        dateFormat: 'Y-m-d',
                        allowInput: false,
                        altInput: true,
                        altFormat: 'j F, Y'
                    });
                });
            });
        </script>
    @endPushOnce
</x-layouts.dashboard>
