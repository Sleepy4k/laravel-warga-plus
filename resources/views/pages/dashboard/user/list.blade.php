<x-layouts.dashboard title="User List">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @canany(['user.create', 'user.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('user.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create User
                </button>
            @endcan
        </div>

        <div class="row g-4 mb-4" id="user-stats">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Registered Users</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2" id="total-registered">{{ $totalUsers }}</h4>
                                    <small id="text-registered">{{ Str::plural('User', $totalUsers) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Total Management</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2" id="total-management">{{ $totalManagers }}</h4>
                                    <small id="text-management">{{ Str::plural('User', $totalManagers) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Active Users</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2" id="total-active">{{ $totalActiveUsers }}</h4>
                                    <small id="text-active">{{ Str::plural('User', $totalActiveUsers) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Inactive Users</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2" id="total-inactive">{{ $totalInactiveUsers }}</h4>
                                    <small id="text-inactive">{{ Str::plural('User', $totalInactiveUsers) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="product.category.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.user.store') }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="username">Username</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="username"
                                class="form-control dt-username @error('username') is-invalid @enderror" name="username"
                                placeholder="benjamin4k" aria-label="benjamin4k" aria-describedby="username"
                                value="{{ old('username') }}" />
                        </div>
                        <small class="text-muted">Make sure it's correct, user can't change it later.</small>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-group input-group-merge">
                            <input type="email" id="email"
                                class="form-control dt-email @error('email') is-invalid @enderror" name="email"
                                placeholder="benjamin4k@example.com" aria-label="benjamin4k@example.com"
                                aria-describedby="email" value="{{ old('email') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="send-to">Send Email To</label>
                        <div class="input-group input-group-merge">
                            <select id="send-to" name="send-to"
                                class="form-control dt-send-to select2 @error('send-to') is-invalid @enderror">
                                <option value="inputed">Inputted Email</option>
                                <option value="other">Different Email</option>
                            </select>
                        </div>
                        <small class="text-muted">Choose where to send notifications.</small>
                    </div>
                    <div class="col-sm-12" id="other-email-group" style="display:none;">
                        <label class="form-label" for="other-email">Other Email</label>
                        <div class="input-group input-group-merge">
                            <input type="email" id="other-email"
                                class="form-control dt-other-email @error('other-email') is-invalid @enderror"
                                name="other-email" placeholder="other@example.com" aria-label="other@example.com"
                                aria-describedby="other-email" value="{{ old('other-email') }}" />
                        </div>
                        <small class="text-muted">Enter a different email address to send notifications.</small>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="role">Role</label>
                        <div class="input-group input-group-merge">
                            <select id="role" name="role"
                                class="form-control dt-role select2 @error('role') is-invalid @enderror">
                                @foreach ($assignableRoles as $role)
                                    <option value="{{ $role }}" @selected($role === config('rbac.role.default'))>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="product.category.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-first_name">First Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-first_name"
                                class="form-control dt-first_name @error('first_name') is-invalid @enderror"
                                name="first_name" placeholder="Irham" aria-label="Irham"
                                aria-describedby="first_name" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-last_name">Last Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-last_name"
                                class="form-control dt-last_name @error('last_name') is-invalid @enderror"
                                name="last_name" placeholder="Fauzi" aria-label="Fauzi"
                                aria-describedby="last_name" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-telkom_batch">Telkom Batch</label>
                        <div class="input-group input-group-merge">
                            <input type="number" id="edit-telkom_batch"
                                class="form-control dt-telkom_batch @error('telkom_batch') is-invalid @enderror"
                                name="telkom_batch" placeholder="{{ date('Y') }}"
                                aria-label="{{ date('Y') }}" aria-describedby="telkom_batch" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-is_active">Is Active</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-is_active" name="is_active"
                                class="form-select dt-is_active select2 @error('is_active') is-invalid @enderror">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-role">Role</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-role" name="role"
                                class="form-control dt-role select2 @error('role') is-invalid @enderror">
                                @foreach ($assignableRoles as $role)
                                    <option value="{{ $role }}">
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-whatsapp_number">Whatsapp Number</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">IDN (+62)</span>
                            <input type="text" id="edit-whatsapp_number"
                                class="form-control dt-whatsapp_number @error('whatsapp_number') is-invalid @enderror"
                                name="whatsapp_number" placeholder="8123456789" aria-label="8123456789"
                                aria-describedby="whatsapp_number" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-address">Address</label>
                        <div class="input-group input-group-merge">
                            <textarea id="edit-address" name="address" class="form-control" placeholder="1234 Main St, City, Country"
                                rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="edit" />
        </x-dashboard.canvas.wrapper>

        <form class="d-inline" id="form-delete-record" method="DELETE" action="#"></form>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}" @cspNonce></script>
        @canany(['user.create', 'user.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        <script @cspNonce>
            $(document).ready(function() {
                const otherEmailGroup = $('#other-email-group');
                $('#send-to').change(function() {
                    if ($(this).val() === 'other') {
                        otherEmailGroup.show();
                    } else {
                        otherEmailGroup.hide();
                    }
                });
            });
        </script>
        @canany(['user.create', 'user.edit'])
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
                                dropdownParent: $(this).closest('.offcanvas'),
                                placeholder: 'Select an option',
                                allowClear: false,
                                width: '100%',
                            });
                        });
                    }
                });
            </script>
        @endcanany
        @canany(['user.create', 'user.edit', 'user.show', 'user.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#user-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.user.update', ':id') }}",
                            destroy: "{{ route('dashboard.user.destroy', ':id') }}"
                        },
                        offcanvas: {
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    first_name: '#edit-first_name',
                                    last_name: '#edit-last_name',
                                    telkom_batch: '#edit-telkom_batch',
                                    is_active: '#edit-is_active',
                                    role: '#edit-role',
                                    whatsapp_number: '#edit-whatsapp_number',
                                    address: '#edit-address'
                                },
                                fieldMapBehavior: {
                                    first_name: function(el, data, rowData) {
                                        el.val(rowData.personal.first_name);
                                    },
                                    last_name: function(el, data, rowData) {
                                        el.val(rowData.personal.last_name);
                                    },
                                    telkom_batch: function(el, data, rowData) {
                                        el.val(rowData.personal.telkom_batch);
                                    },
                                    is_active: function(el, data, rowData) {
                                        el.val(data.toLowerCase() == "no" ? 0 : 1).trigger('change');
                                    },
                                    role: function(el, data, rowData) {
                                        el.val(data.toLowerCase()).trigger('change');
                                    },
                                    whatsapp_number: function(el, data, rowData) {
                                        el.val(rowData.personal.whatsapp_number);
                                    },
                                    address: function(el, data, rowData) {
                                        el.val(rowData.personal.address);
                                    }
                                }
                            }
                        },
                        onSuccess: function(response, formId) {
                            const getPluralText = (count, singular, plural) => {
                                return count === 1 ? singular : plural;
                            };

                            const totalRegistered = response.data.totalUsers || 0;
                            const totalManagement = response.data.totalManagers || 0;
                            const totalActive = response.data.totalActiveUsers || 0;
                            const totalInactive = response.data.totalInactiveUsers || 0;

                            $('#user-stats #total-registered').text(totalRegistered);
                            $('#user-stats #text-registered').html(getPluralText(totalRegistered, 'User',
                                'Users'));
                            $('#user-stats #total-management').text(totalManagement);
                            $('#user-stats #text-management').html(getPluralText(totalManagement, 'User',
                                'Users'));
                            $('#user-stats #total-active').text(totalActive);
                            $('#user-stats #text-active').html(getPluralText(totalActive, 'User', 'Users'));
                            $('#user-stats #total-inactive').text(totalInactive);
                            $('#user-stats #text-inactive').html(getPluralText(totalInactive, 'User', 'Users'));

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: response.message || "Operation completed successfully.",
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            } else {
                                alert(response.message || 'Operation completed successfully.');
                            }
                        }
                    });
                });
            </script>
        @endcanany
    @endPushOnce
</x-layouts.dashboard>
