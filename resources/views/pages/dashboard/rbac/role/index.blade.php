<x-layouts.dashboard title="Roles">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @canany(['rbac.role.create', 'rbac.role.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('rbac.role.create')
                <button class="btn btn-primary add-new-role" type="button" data-bs-toggle="modal"
                    data-bs-target="#addRoleModal">Add New Role</button>
            @endcan
        </div>

        <p class="mb-4">
            A <strong>Role</strong> provided access to predifined menus and actions in the system.<br />
            <strong>Note:</strong> Roles are shared across all users. Please use clear and
        </p>

        <div class="row g-4">
            @foreach ($cardRoles as $cardRole)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-2">
                                <h4 class="mb-0">{{ ucfirst($cardRole['name']) ?? '-' }}</h4>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <span class="fw-normal">Total users: {{ $cardRole['total_users'] ?? 0 }}</span>
                                </div>
                                @can('rbac.role.edit')
                                    <button class="btn btn-sm btn-outline-primary role-edit-modal edit-record"
                                        data-id="{{ $cardRole['id'] }}" data-target="#edit-record">
                                        Edit Role
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @can('rbac.role.create')
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card h-100">
                        <div class="row h-100 g-0 align-items-center">
                            <div class="col-sm-5 d-flex align-items-center justify-content-center rounded-start">
                                <img src="{{ asset('img/illustrations/sitting-girl-with-laptop-light.png') }}"
                                    class="img-fluid p-3" alt="Add Role Illustration" width="110"
                                    data-app-light-img="illustrations/sitting-girl-with-laptop-light.png"
                                    data-app-dark-img="illustrations/sitting-girl-with-laptop-dark.png" loading="lazy" />
                            </div>
                            <div class="col-sm-7">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center h-100">
                                    <button data-bs-target="#addRoleModal" data-bs-toggle="modal"
                                        class="btn btn-primary mb-2 px-4 fw-semibold add-new-role">
                                        <i class="bx bx-plus me-1"></i> Add New Role
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>

        @can('rbac.role.create')
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="role-title">Add New Role</h3>
                            </div>
                            <form id="addRoleForm" class="row g-3" method="POST"
                                action="{{ route('dashboard.rbac.role.store') }}">
                                @csrf
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="roleName">Role Name</label>
                                    <input type="text" id="roleName" name="name" class="form-control"
                                        placeholder="Enter a role name" required />
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="roleGuardName">Guard Name</label>
                                    <select id="roleGuardName" name="guard_name" class="form-select select2" required>
                                        @foreach ($guardList as $guard)
                                            <option value="{{ $guard['value'] }}">
                                                {{ $guard['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label mb-2">Assign Permissions</label>
                                    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="selectAllPermissions">Select All</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="deselectAllPermissions">Deselect All</button>
                                        <input type="text" class="form-control form-control-sm ms-auto"
                                            id="permissionSearch" placeholder="Search permissions..."
                                            style="max-width: 220px;">
                                    </div>
                                    <div id="permissionSearchLoader" class="text-center my-2 mb-4" style="display:none;">
                                        <span class="spinner-border spinner-border-sm align-middle" role="status"
                                            aria-hidden="true"></span>
                                        <span class="ms-1">Searching...</span>
                                    </div>
                                    <div class="row mt-2" id="permissionsList">
                                        @foreach ($permissions as $group => $groupPermissions)
                                            <div class="col-md-6 mb-3 permission-group"
                                                data-group="{{ strtolower($group) }}">
                                                <div class="border rounded p-2 h-100">
                                                    <div class="fw-semibold mb-2 group-title">
                                                        {{ strtoupper(str_replace('.', ' ', $group)) }}</div>
                                                    @foreach ($groupPermissions as $permission)
                                                        <div class="form-check mb-1 permission-item"
                                                            data-permission="{{ strtolower($permission->display_name ?? $permission->name) }}">
                                                            <input class="form-check-input permission-checkbox"
                                                                type="checkbox" id="permission_{{ $permission->id }}"
                                                                name="permissions[]" value="{{ $permission->name }}">
                                                            <label class="form-check-label"
                                                                for="permission_{{ $permission->id }}">
                                                                {{ $permission->display_name ?? $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1"
                                        id="add-new-record-submit-btn">Submit</button>
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
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

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="rbac.role.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="col-12 mb-3">
                    <label class="form-label" for="show-name">Role Name</label>
                    <input type="text" id="show-name" class="form-control dt-name"
                        placeholder="Enter a role name" disabled />
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="show-guard-name">Guard Name</label>
                    <input type="text" id="show-guard-name" class="form-control dt-guard-name"
                        placeholder="Enter guard name" disabled />
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Assigned Permissions</label>
                    <div id="show-permissions" class="d-flex flex-wrap gap-2"></div>
                    <!-- Permissions will be populated here -->
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="rbac.role.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-12 mb-3">
                        <label class="form-label" for="edit-name">Role Name</label>
                        <input type="text" id="edit-name" class="form-control dt-name"
                            placeholder="Enter a role name" name="name" />
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label" for="edit-guard-name">Guard Name</label>
                        <select id="edit-guard-name" name="guard_name" class="form-select select2">
                            @foreach ($guardList as $guard)
                                <option value="{{ $guard['value'] }}">{{ $guard['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Assigned Permissions</label>
                        <div id="edit-permissions" class="d-flex flex-wrap gap-2 mb-3">
                            <!-- Permissions will be populated here -->
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="edit" />
        </x-dashboard.canvas.wrapper>

        <form class="d-inline" id="form-delete-record" method="DELETE" action="#"></form>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce>
        </script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}"
            @cspNonce></script>
        @canany(['rbac.role.create', 'rbac.role.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce>
            </script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['rbac.role.create', 'rbac.role.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    const select2Elements = $('.select2');
                    if (select2Elements.length) {
                        select2Elements.each(function() {
                            const parent = $(this).closest('.modal, .offcanvas');
                            $(this).select2({
                                dropdownParent: parent,
                                placeholder: 'Select an option',
                                allowClear: false,
                                width: '100%'
                            });
                        });
                    }
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    $('#selectAllPermissions').on('click', function() {
                        $('.permission-checkbox').prop('checked', true);
                    });

                    $('#deselectAllPermissions').on('click', function() {
                        $('.permission-checkbox').prop('checked', false);
                    });

                    function debounce(fn, delay) {
                        let timer = null;
                        return function(...args) {
                            clearTimeout(timer);
                            timer = setTimeout(() => fn.apply(this, args), delay);
                        };
                    }

                    const filterPermissions = function() {
                        const query = $('#permissionSearch').val().trim().toLowerCase();
                        $('#permissionSearchLoader').show();
                        setTimeout(function() {
                            $('#permissionsList .permission-group').each(function() {
                                let groupHasVisible = false;
                                $(this).find('.permission-item').each(function() {
                                    const text = $(this).data('permission');
                                    const match = !query || (text && text.includes(query));
                                    $(this).toggle(match);
                                    if (match) groupHasVisible = true;
                                });
                                $(this).toggle(groupHasVisible);
                            });
                            $('#permissionSearchLoader').hide();
                        }, 200);
                    };

                    $('#permissionSearch').on('input', debounce(filterPermissions, 200));
                });
            </script>
        @endcanany
        @canany(['rbac.role.create', 'rbac.role.edit', 'rbac.role.show', 'rbac.role.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#role-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.rbac.role.update', ':id') }}",
                            destroy: "{{ route('dashboard.rbac.role.destroy', ':id') }}"
                        },
                        forms: {
                            add: {
                                formId: '#addRoleForm',
                                submitBtnId: '#add-new-record-submit-btn'
                            },
                            edit: {
                                formId: '#form-edit-record',
                                submitBtnId: '#edit-record-submit-btn'
                            },
                            delete: {
                                formId: '#form-delete-record'
                            }
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    name: '#show-name',
                                    guard_name: '#show-guard-name',
                                    permissions: '#show-permissions',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    permissions: function(el, data, rowData) {
                                        el.empty();
                                        const permissionsMap = @json($permissions);
                                        const assignedPermissions = rowData.permissions_grouped;

                                        Object.keys(permissionsMap).forEach(function(group) {
                                            const groupPermissions = permissionsMap[group];
                                            const filteredPermissions = groupPermissions.filter(
                                                function(permission) {
                                                    return assignedPermissions.includes(permission.name);
                                                }
                                            );
                                            if (filteredPermissions.length === 0) return;

                                            const col = $(`
                                                <div class="col-12 mb-3 permission-group" data-group="${group.toLowerCase()}">
                                                    <div class="border rounded p-3 h-100">
                                                        <div class="fw-semibold mb-2 group-title">${group.replace(/\./g, ' ').toUpperCase()}</div>
                                                    </div>
                                                </div>
                                            `);
                                            const container = col.find('.border');
                                            filteredPermissions.forEach(function(permission) {
                                                container.append(`
                                                    <div class="form-check mb-1 permission-item" data-permission="${(permission.display_name || permission.name).toLowerCase()}">
                                                        <input class="form-check-input permission-checkbox" type="checkbox" disabled checked>
                                                        <label class="form-check-label">${permission.display_name || permission.name}</label>
                                                    </div>
                                                `);
                                            });
                                            el.append(col);
                                        });
                                    }
                                }
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    name: '#edit-name',
                                    guard_name: '#edit-guard-name',
                                    permissions: '#edit-permissions'
                                },
                                fieldMapBehavior: {
                                    guard_name: function(el, data, rowData) {
                                        el.val(rowData.guard_name.toLowerCase()).trigger('change');
                                    },
                                    permissions: function(el, data, rowData) {
                                        el.empty();
                                        const permissionsMap = @json($permissions);
                                        const assignedPermissions = rowData.permissions_grouped;

                                        Object.keys(permissionsMap).forEach(function(group) {
                                            const groupPermissions = permissionsMap[group];
                                            const col = $(`
                                                <div class="col-12 mb-3 permission-group" data-group="${group.toLowerCase()}">
                                                    <div class="border rounded p-3 h-100">
                                                        <div class="fw-semibold mb-2 group-title">${group.replace(/\./g, ' ').toUpperCase()}</div>
                                                    </div>
                                                </div>
                                            `);
                                            const container = col.find('.border');
                                            groupPermissions.forEach(function(permission) {
                                                const checked = assignedPermissions.includes(permission.name) ? 'checked' : '';
                                                container.append(`
                                                    <div class="form-check mb-1 permission-item" data-permission="${(permission.display_name || permission.name).toLowerCase()}">
                                                        <input class="form-check-input permission-checkbox" name="permissions[]" value="${permission.name}" type="checkbox" ${checked}>
                                                        <label class="form-check-label">${permission.display_name || permission.name}</label>
                                                    </div>
                                                `);
                                            });
                                            el.append(col);
                                        });
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endcanany
    @endPushOnce
</x-layouts.dashboard>
