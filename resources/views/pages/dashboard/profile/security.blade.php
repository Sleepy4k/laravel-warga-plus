<x-layouts.dashboard title="User Security">
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard.breadcrumb />

        <div class="row mt-4">
            <div class="col-md-12">
                <x-profile.navbar />

                <div class="card mb-4">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.security.update') }}"
                            class="fv-plugins-bootstrap5 fv-plugins-framework" id="formChangePassword">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="current_password">Current Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input class="form-control" type="password" name="current_password"
                                            id="current_password" placeholder="············">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="password">New Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input class="form-control" type="password" id="password" name="password"
                                            placeholder="············">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input class="form-control" type="password" name="password_confirmation"
                                            id="password_confirmation" placeholder="············">
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                                <div class="col-12 mb-4">
                                    <p class="fw-semibold mt-2">Password Requirements:</p>
                                    <ul class="ps-3 mb-0">
                                        <li class="mb-1">Minimum 8 characters long - the more, the better</li>
                                        <li class="mb-1">At least one lowercase character</li>
                                        <li>At least one number, symbol, or special character</li>
                                    </ul>
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
                                        @if (count($recentLogins) > 5 && $index >= 5)
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    <small>
                                                        We only show the 5 latest devices for security reasons.
                                                        <br>
                                                        If you need to see more, please contact support.
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
                                                            <div class="fw-semibold">{{ $data['browser_family'] }}</div>
                                                            <small
                                                                class="text-muted">{{ $data['device_family'] }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill bg-primary text-white px-3 py-2">
                                                        {{ $data['device_model'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button
                                                        class="badge rounded-pill bg-label-secondary px-3 py-2 border-0"
                                                        id="ipAddressButton" data-ip="{{ $data['ip_address'] }}">
                                                        {{ $data['ip_address'] }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-muted">{{ \Carbon\Carbon::parse($data['plain_login_at'])->diffForHumans() }}</span>
                                                    <br>
                                                    <small class="text-secondary">{{ $data['login_at'] }}</small>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No recent device activity
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

    @pushOnce('page-scripts')
        <script src="{{ asset('js/pages/profile-security.min.js') }}" @cspNonce></script>
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
    @endPushOnce
</x-layouts.dashboard>
