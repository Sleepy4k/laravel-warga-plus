<x-layouts.dashboard title="Shortcut Menu">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @canany(['menu.shortcut.create', 'menu.shortcut.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('menu.shortcut.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Shortcut
                </button>
            @endcan
        </div>

        <p class="mb-4">
            This page allows you to manage the shortcut menu items that appear in the dashboard navbar.<br />
            You can create, edit, and delete shortcuts to quickly access frequently used pages or features.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="menu.shortcut.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.menu.shortcut.store') }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="label">Label</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="label"
                                class="form-control dt-label @error('label') is-invalid @enderror" name="label"
                                placeholder="Shortcut Label" aria-label="Shortcut Label" aria-describedby="label"
                                value="{{ old('label') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="icon">Icon</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">bx bx-</span>
                            <input type="text" id="icon"
                                class="form-control dt-icon @error('icon') is-invalid @enderror" name="icon"
                                placeholder="Menu Icon" aria-label="Menu Icon" aria-describedby="icon"
                                value="{{ old('icon') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="route">Route</label>
                        <select id="route" class="form-select select2 @error('route') is-invalid @enderror"
                            name="route">
                            @foreach ($routeNameList as $routeName)
                                <option value="{{ $routeName }}" @selected(old('route') == $routeName)>
                                    {{ $routeName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="permissions">Permissions</label>
                        <div class="input-group input-group-merge">
                            <select id="permissions"
                                class="form-select select2 @error('permissions') is-invalid @enderror"
                                name="permissions[]" multiple>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}" @selected(in_array($permission->name, old('permissions', [])))>
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="menu.shortcut.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-md-12">
                        <label class="form-label" for="show-label">Label</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-label" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="show-route">Route</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-route" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="show-icon">Icon</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">bx bx-</span>
                            <input type="text" id="show-icon" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="show-description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea id="show-description" class="form-control" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="show-permissions">Permissions</label>
                        <div class="input-group input-group-merge" id="show-permissions">
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="menu.shortcut.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-md-12">
                        <label class="form-label" for="edit-label">Label</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-label" class="form-control" name="label" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="edit-route">Route</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-route" class="form-select select2 @error('route') is-invalid @enderror"
                                name="route">
                                @foreach ($routeNameList as $routeName)
                                    <option value="{{ $routeName }}">
                                        {{ $routeName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="edit-icon">Icon</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">bx bx-</span>
                            <input type="text" id="edit-icon" class="form-control" name="icon"
                                id="edit-icon" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="edit-description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea id="edit-description" class="form-control" id="edit-description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="edit-permissions">Permissions</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-permissions"
                                class="form-select select2 @error('permissions') is-invalid @enderror"
                                name="permissions[]" multiple>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}">
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
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
        @canany(['menu.shortcut.create', 'menu.shortcut.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['menu.shortcut.create', 'menu.shortcut.edit'])
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
        @canany(['menu.shortcut.create', 'menu.shortcut.store', 'menu.shortcut.show', 'menu.shortcut.edit',
            'menu.shortcut.delete'])
            <script src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#shortcut-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.menu.shortcut.update', ':id') }}",
                            destroy: "{{ route('dashboard.menu.shortcut.destroy', ':id') }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    label: '#show-label',
                                    route: '#show-route',
                                    icon: '#show-icon',
                                    description: '#show-description',
                                    permissions: '#show-permissions',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    route: function(el, data, rowData) {
                                        el.val(
                                            rowData.route_display
                                        );
                                    },
                                    permissions: function(el, data, rowData) {
                                        el.html(
                                            rowData.permissions_display
                                        );
                                    },
                                }
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    label: '#edit-label',
                                    route: '#edit-route',
                                    icon: '#edit-icon',
                                    description: '#edit-description',
                                    permissions: '#edit-permissions',
                                },
                                fieldMapBehavior: {
                                    permissions: function(el, data, rowData) {
                                        el.val(rowData.permissions).trigger('change');
                                    },
                                }
                            }
                        },
                        onSuccess: function(response, formId) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: response.message || "Operation completed successfully.",
                                    showConfirmButton: false,
                                    timer: 1500,
                                }).then(() => {
                                    window.location.reload();
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
