<x-layouts.dashboard title="User Details">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/lightbox/css/lightbox.min.css') }}" @cspNonce />
        <style @cspNonce>
            .product-image {
                max-width: 100px;
                max-height: 100px;
                border-radius: 10%;
                object-fit: cover;
            }
        </style>
        @can('user.edit')
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcan
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            <a href="{{ route('dashboard.user.index') }}" class="btn btn-primary">Back to User List</a>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded my-4" src="{{ $personal->userAvatar() }}" height="110"
                                    width="110" alt="User avatar" loading="lazy" />
                                <div class="user-info text-center">
                                    <h4 class="mb-2">{{ $personal->full_name }}</h4>
                                    <span class="badge bg-label-secondary">{{ $role }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">About</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-user"></i><span class="fw-bold mx-2">Identity Number:</span>
                                <span>{{ $user->identity_number }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-check"></i><span class="fw-bold mx-2">Status:</span>
                                <span
                                    class="badge bg-label-{{ $user->is_active ? 'primary' : 'secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-star"></i><span class="fw-bold mx-2">Role:</span>
                                <span>{{ ucfirst($role) }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-flag"></i><span class="fw-bold mx-2">Country:</span>
                                <span>Indonesia</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-detail"></i><span class="fw-bold mx-2">Language:</span>
                                <span>English</span>
                            </li>
                        </ul>
                        <small class="text-muted text-uppercase">Contacts</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-phone"></i><span class="fw-bold mx-2">Phone:</span>
                                @php
                                    $parsedNumber = preg_replace('/\D/', '', $user->phone);
                                    $parsedNumber = '62' . ltrim($parsedNumber, '0');
                                @endphp
                                <a href="https://wa.me/{{ $parsedNumber }}"
                                    target="_blank">wa.me/{{ $parsedNumber }}</a>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-chat"></i><span class="fw-bold mx-2">Job:</span>
                                <span>{{ $personal->job }}</span>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-center pt-3">
                            @can('user.edit')
                                <button class="btn btn-primary me-3" data-bs-target="#editUser"
                                    data-bs-toggle="modal">Edit</button>
                            @endcan
                            @can('user.delete')
                                <button class="btn btn-label-danger suspend-user" id="delete-user-btn">Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <ul class="nav nav-pills flex-column flex-md-row mb-2" role="tablist" id="detail-account-tabs">
                    <li class="nav-item">
                        <button class="nav-link" role="tab" data-bs-toggle="tab" id="account-tab"
                            data-bs-target="#account-settings" aria-controls="account-settings" aria-selected="true">
                            <i class="bx bx-user me-1"></i>
                            Account
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" role="tab" data-bs-toggle="tab" id="security-tab"
                            data-bs-target="#security-settings" aria-controls="security-settings" aria-selected="false">
                            <i class="bx bx-lock-alt me-1"></i>
                            Security
                        </button>
                    </li>
                </ul>

                <div class="tab-content mb-4" style="padding: 0; padding-top: 1rem;">
                    <div class="tab-pane fade" id="account-settings" role="tabpanel" aria-labelledby="account-tab">
                        <div class="card mb-4">
                            <h5 class="card-header">User Reports</h5>
                            <div class="card-body table-responsive">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="security-settings" role="tabpanel" aria-labelledby="security-tab">
                        <div class="card mb-4">
                            <h5 class="card-header">Change Password</h5>
                            <div class="card-body">
                                <form method="POST" action="{{ route('profile.security.update', $uid) }}"
                                    class="fv-plugins-bootstrap5 fv-plugins-framework" id="formChangePassword">
                                    @csrf
                                    @method('PUT')

                                    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
                                        role="alert">
                                        <div>
                                            <h6 class="alert-heading mb-1">Password Requirements</h6>
                                            <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                                <li>Minimum <strong>8 characters</strong></li>
                                                <li>At least <strong>1 uppercase letter</strong></li>
                                                <li>At least <strong>1 symbol</strong> (e.g. <code>@ # $ % !</code>)
                                                </li>
                                            </ul>
                                        </div>
                                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-md-6 form-password-toggle">
                                            <label class="form-label" for="password">New Password</label>
                                            <div class="input-group input-group-merge has-validation">
                                                <input class="form-control" type="password" id="password"
                                                    name="password" placeholder="············">
                                                <span class="input-group-text cursor-pointer"><i
                                                        class="bx bx-hide"></i></span>
                                            </div>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                        <div class="mb-3 col-md-6 form-password-toggle">
                                            <label class="form-label" for="password_confirmation">Confirm New
                                                Password</label>
                                            <div class="input-group input-group-merge has-validation">
                                                <input class="form-control" type="password"
                                                    name="password_confirmation" id="password_confirmation"
                                                    placeholder="············">
                                                <span class="input-group-text cursor-pointer"><i
                                                        class="bx bx-hide"></i></span>
                                            </div>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                        </div>

                                        <div class="col-12 mt-1">
                                            <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <h5 class="card-header">
                                Recent Devices
                                <br />
                                <small class="text-muted" style="font-size: 0.75em;">
                                    If you see any unfamiliar devices, please change your password immediately.
                                    <br>
                                    You can also click on the IP address to view more details about the device.
                                </small>
                            </h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border-top">
                                        <thead>
                                            <tr>
                                                <th>Browser & Device</th>
                                                <th>Device Model</th>
                                                <th>IP Address</th>
                                                <th>Recent Activity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentLogins as $index => $data)
                                                @if (count($recentLogins) > 10 && $index >= 10)
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            <small>
                                                                We only show the 10 latest devices for security reasons.
                                                                <br>
                                                                If you need to see more, please look up the user's
                                                                activity log.
                                                            </small>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="bx {{ $deviceIcon[$data['device_family']] ?? 'bx-windows' }} text-info fs-4 me-2"></i>
                                                                <div>
                                                                    <div class="fw-semibold">
                                                                        {{ $data['browser_family'] }}</div>
                                                                    <small
                                                                        class="text-muted">{{ $data['device_family'] }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge rounded-pill bg-primary text-white px-3 py-2">
                                                                {{ $data['device_model'] }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button
                                                                class="badge rounded-pill bg-label-secondary px-3 py-2 border-0"
                                                                id="ipAddressButton"
                                                                data-ip="{{ $data['ip_address'] }}">
                                                                {{ $data['ip_address'] }}
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-muted">{{ \Carbon\Carbon::parse($data['plain_login_at'])->diffForHumans() }}</span>
                                                            <br>
                                                            <small
                                                                class="text-secondary">{{ $data['login_at'] }}</small>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No recent device
                                                        activity
                                                        found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @can('user.edit')
            <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3>Edit User Information</h3>
                                <p>
                                    You can edit the user's personal information here. Make sure to fill in all
                                </p>
                            </div>
                            <form id="editUserForm" class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-first_name">First Name</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="edit-first_name"
                                            class="form-control dt-first_name @error('first_name') is-invalid @enderror"
                                            name="first_name" placeholder="Irham" aria-label="Irham"
                                            aria-describedby="first_name" value="{{ $personal->first_name }}" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-last_name">Last Name</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="edit-last_name"
                                            class="form-control dt-last_name @error('last_name') is-invalid @enderror"
                                            name="last_name" placeholder="Fauzi" aria-label="Fauzi"
                                            aria-describedby="last_name" value="{{ $personal->last_name }}" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-gender">Gender</label>
                                    <div class="input-group input-group-merge">
                                        <select id="edit-gender" name="gender"
                                            class="form-select dt-gender select2 @error('gender') is-invalid @enderror">
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->value }}" @selected($personal->gender == $gender->value)>
                                                    {{ $gender->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-is_active">Is Active</label>
                                    <div class="input-group input-group-merge">
                                        <select id="edit-is_active" name="is_active"
                                            class="form-select dt-is_active select2 @error('is_active') is-invalid @enderror">
                                            <option value="1" @selected($user->is_active == 1)>Active</option>
                                            <option value="0" @selected($user->is_active == 0)>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-role">Role</label>
                                    <div class="input-group input-group-merge">
                                        <select id="edit-role" name="role"
                                            class="form-select dt-role select2 @error('role') is-invalid @enderror">
                                            @foreach ($assignableRoles as $role)
                                                <option value="{{ $role }}" @selected($user->hasRole($role))>
                                                    {{ ucfirst($role) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-birth_date">Birth Date</label>
                                    <div class="input-group input-group-merge">
                                        <input type="date" id="edit-birth_date"
                                            class="form-control dt-birth_date @error('birth_date') is-invalid @enderror"
                                            name="birth_date" placeholder="YYYY-MM-DD" aria-label="YYYY-MM-DD"
                                            aria-describedby="birth_date"
                                            value="{{ $personal->birth_date }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label" for="edit-job">Job</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="edit-job"
                                            class="form-control dt-job @error('job') is-invalid @enderror"
                                            name="job" placeholder="Software Engineer" aria-label="Software Engineer"
                                            aria-describedby="job" value="{{ $personal->job }}" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label" for="edit-address">Address</label>
                                    <div class="input-group input-group-merge">
                                        <textarea id="edit-address" name="address" class="form-control" placeholder="1234 Main St, City, Country"
                                            rows="3">{{ $personal->address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/lightbox/js/lightbox.min.js') }}" @cspNonce></script>
        @can('user.edit')
            <script type="text/javascript" src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcan
    @endPushOnce

    @pushOnce('page-scripts')
        <script src="{{ asset('js/pages/profile-security.min.js') }}" @cspNonce></script>
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        <script @cspNonce>
            lightbox.option({
                'resizeDuration': 500,
                'wrapAround': true
            })
        </script>
        <script @cspNonce>
            $(document).ready(function() {
                $('button[id="ipAddressButton"]').on('click', function(e) {
                    e.preventDefault();
                    const ip = $(this).data('ip');
                    if (ip) {
                        Swal.fire({
                            title: 'View IP Info?',
                            text: `Do you want to view details for IP ${ip}? This will open a new tab.`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, view',
                            cancelButtonText: 'Cancel',
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-primary'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open(`https://ipinfo.io/${ip}`, '_blank');
                            }
                        });
                    }
                });
            });
        </script>
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                $('#detail-account-tabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    const tabId = $(e.target).attr('id');
                    let tab = '';
                    switch (tabId) {
                        case 'account-tab':
                            tab = 'account';
                            break;
                        case 'security-tab':
                            tab = 'security';
                            break;
                    }
                    if (tab) {
                        const url = new URL(window.location);
                        url.searchParams.set('tab', tab);
                        window.history.replaceState({}, '', url);
                        $('.tab-pane.active form').each(function() {
                            let $input = $(this).find('input[name="tab"]');
                            if ($input.length === 0) {
                                $input = $('<input type="hidden" name="tab" />').appendTo($(this));
                            }
                            $input.val(tab);
                        });
                    }
                });

                const params = new URLSearchParams(window.location.search);
                let tab = params.get('tab');
                if (!tab) {
                    tab = 'account';
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url);
                }
                if (tab) {
                    $(`#${tab}-tab`).tab('show');
                    setTimeout(function() {
                        $(`#${tab}-settings form`).each(function() {
                            let $input = $(this).find('input[name="tab"]');
                            if ($input.length === 0) {
                                $input = $('<input type="hidden" name="tab" />').appendTo($(this));
                            }
                            $input.val(tab);
                        });
                    }, 100);
                }
            });
        </script>
        @can('user.edit')
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#edit-address'));
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    const select2Elements = $('.select2');
                    if (select2Elements.length) {
                        select2Elements.each(function() {
                            $(this).select2({
                                dropdownParent: $(this).closest('.modal'),
                                placeholder: 'Select an option',
                                allowClear: false,
                                width: '100%',
                            });
                        });
                    }
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    $('#editUserForm').on('submit', async function(e) {
                        e.preventDefault();
                        const form = $(this);
                        const url = "{{ route('dashboard.user.update', $uid) }}";
                        const formData = new FormData(form[0]);

                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('_method', 'PUT');

                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                Accept: "application/json",
                            },
                        });

                        const responseData = await response.json();

                        if (response.ok) {
                            Swal.fire({
                                title: 'Success',
                                text: 'User information updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred while updating user information.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    });
                });
            </script>
        @endcan
        @can('user.delete')
            <script @cspNonce>
                $(document).ready(function() {
                    $('#delete-user-btn').on('click', function() {
                        const url = "{{ route('dashboard.user.destroy', $uid) }}";

                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'This action cannot be undone.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, cancel!',
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-primary'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Deleting Record...",
                                    text: "Please wait while the record is being deleted.",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                    Swal.showLoading();
                                    },
                                });
                                $.ajax({
                                    url: url,
                                    type: 'DELETE',
                                    data: {
                                        '_token': '{{ csrf_token() }}',
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'User deleted successfully.',
                                            icon: 'success',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'btn btn-primary'
                                            }
                                        }).then(() => {
                                            window.location.href =
                                                "{{ route('dashboard.user.index') }}";
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'An error occurred while deleting user.',
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'btn btn-danger'
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    });
                });
            </script>
        @endcan
    @endPushOnce
</x-layouts.dashboard>
