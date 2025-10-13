<x-layouts.dashboard title="Navbar Menu">
    @pushOnce('plugin-styles')
        <style @cspNonce>
            .dragging {
                opacity: 0.5;
                border: 2px dashed #6366f1 !important;
            }

            .drop-target-hover {
                border: 2px solid #a78bfa !important;
                background-color: #696cff70 !important;
            }

            @keyframes fade-in-down {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-down {
                animation: fade-in-down 0.3s ease-out forwards;
            }

            .list-group-parent>.list-group-item:not(:last-child) {
                margin-bottom: 1.25rem;
            }
        </style>
        @canany(['menu.navbar.create', 'menu.navbar.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('menu.navbar.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Menu
                </button>
            @endcan
        </div>

        <p class="mb-4">
            Organize your navbar menu items by dragging and dropping them into the desired order.<br />
            You can also create new menu items, edit existing ones, or delete them as needed.
        </p>

        <div class="card shadow-lg mx-auto border-0 rounded-3">
            <div class="card-header d-flex align-items-center justify-content-between py-2 px-3 border-bottom-0 mt-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle"
                        style="width:2.25rem;height:2.25rem;">
                        <i class="bx bx-menu-alt-left text-primary fs-4"></i>
                    </span>
                    <div>
                        <h5 class="mb-0 fw-semibold text-primary">Navbar Menu Manager</h5>
                        <small class="text-muted">Organize your navbar navigation with drag &amp; drop</small>
                    </div>
                </div>
                @can('menu.navbar.update')
                    <button id="save-button" class="btn btn-primary fw-medium px-4 py-2 me-3">
                        <i class="bx bx-save me-2"></i>
                        <span id="save-button-text">Save Order</span>
                    </button>
                @endcan
            </div>
            <div class="card-body p-4">
                <ul id="menu-list" class="list-group list-group-parent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </ul>
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="menu.navbar.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.menu.navbar.store') }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="name"
                                class="form-control @error('name') is-invalid @enderror" name="name"
                                placeholder="Menu Name" aria-label="Menu Name" aria-describedby="name"
                                value="{{ old('name') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="is_spacer">Is Spacer</label>
                        <div class="input-group input-group-merge">
                            <select id="is_spacer" class="form-select select2 @error('is_spacer') is-invalid @enderror"
                                name="is_spacer">
                                <option value="1" @selected(old('is_spacer', 1) == 1)>Yes</option>
                                <option value="0" @selected(old('is_spacer', 0) == 0)>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 ">
                        <label class="form-label" for="route">Route Name</label>
                        <div class="input-group input-group-merge">
                            <select id="route" class="form-select select2 @error('route') is-invalid @enderror"
                                name="route">
                                @foreach ($routeNameList as $routeName)
                                    <option value="{{ $routeName }}"
                                        @selected(old('route') == $routeName)>
                                        {{ $routeName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="active_routes" value="{{ old('active_routes', '[]') }}" />
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="icon">Icon</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">bx bx-</span>
                            <input type="text" id="icon"
                                class="form-control @error('icon') is-invalid @enderror" name="icon"
                                placeholder="Menu Icon" aria-label="Menu Icon" aria-describedby="icon"
                                value="{{ old('icon') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="is_sortable">Is Sortable</label>
                        <div class="input-group input-group-merge">
                            <select id="is_sortable"
                                class="form-select select2 @error('is_sortable') is-invalid @enderror"
                                name="is_sortable">
                                <option value="1" @selected(old('is_sortable', 1) == 1)>Yes</option>
                                <option value="0" @selected(old('is_sortable', 0) == 0)>No</option>
                            </select>
                        </div>
                        <small class="form-text text-muted">
                            If disabled, this menu item cannot be deleted or reordered.
                        </small>
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
                    <div class="col-sm-12">
                        <label class="form-label" for="parameters">Parameters</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="parameters"
                                class="form-control @error('parameters') is-invalid @enderror" name="parameters"
                                placeholder="Menu Parameters" aria-label="Menu Parameters"
                                aria-describedby="parameters" value="{{ old('parameters') }}" />
                        </div>
                        <small class="form-text text-muted">
                            Parameters should be a JSON string, e.g. {"key": "value"}.
                        </small>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="menu.navbar.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2" id="show-record-content">
                    <div class="col-sm-12">
                        <label class="form-label fw-bold" for="show-name">Name</label>
                        <input type="text" class="form-control" id="show-name" readonly>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label fw-bold" for="show-is_spacer">Is Spacer</label>
                        <input type="text" class="form-control" id="show-is_spacer" readonly>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label fw-bold" for="show-route">Route Name</label>
                        <input type="text" class="form-control" id="show-route" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-bold" for="show-icon">Icon</label>
                        <div class="input-group">
                            <span class="input-group-text" id="show-icon-preview"></span>
                            <input type="text" class="form-control" id="show-icon-text" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-bold" for="show-is_sortable">Is Sortable</label>
                        <input type="text" class="form-control" id="show-is_sortable" readonly>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label fw-bold">Permissions</label>
                        <p class="form-control-plaintext" id="show-permissions">-</p>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label fw-bold">Parameters</label>
                        <p class="form-control-plaintext" id="show-parameters">-</p>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="menu.navbar.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-name" class="form-control" name="name"
                                placeholder="Menu Name" aria-label="Menu Name" aria-describedby="edit-name" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-is_spacer">Is Spacer</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-is_spacer" class="form-select select2" name="is_spacer">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-route">Route Name</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-route" class="form-select select2" name="route">
                                @foreach ($routeNameList as $routeName)
                                    <option value="{{ $routeName }}">{{ $routeName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="active_routes" value="[]" />
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-icon">Icon</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">bx bx-</span>
                            <input type="text" id="edit-icon" class="form-control" name="icon"
                                placeholder="Menu Icon" aria-label="Menu Icon" aria-describedby="edit-icon" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-is_sortable">Is Sortable</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-is_sortable" class="form-select select2" name="is_sortable">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <small class="form-text text-muted">
                            If disabled, this menu item cannot be deleted or reordered.
                        </small>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-permissions">Permissions</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-permissions" class="form-select select2" name="permissions[]" multiple>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-parameters">Parameters</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-parameters" class="form-control" name="parameters"
                                placeholder="Menu Parameters" aria-label="Menu Parameters"
                                aria-describedby="edit-parameters" />
                        </div>
                        <small class="form-text text-muted">
                            Parameters should be a JSON string, e.g. {"key": "value"}.
                        </small>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="edit" />
        </x-dashboard.canvas.wrapper>

        <form class="d-inline" id="form-delete-record" method="DELETE" action="#"></form>
    </div>

    @pushOnce('plugin-scripts')
        @canany(['menu.navbar.create', 'menu.navbar.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        <script type="text/javascript" src="{{ asset('js/handler/drag-manager.min.js') }}" @cspNonce></script>
        <script @cspNonce>
            const navbarData = @json($menus);
            $(document).ready(() => {
                const dragManager = new DragManager({
                    debug: "{{ config('app.debug') }}",
                    data: navbarData,
                    saveUrl: "{{ route('dashboard.menu.navbar.saveOrder') }}",
                    csrfToken: '{{ csrf_token() }}',
                    permissions: {
                        show: @json(auth('web')->user()->can('menu.navbar.show')),
                        edit: @json(auth('web')->user()->can('menu.navbar.edit')),
                        update: @json(auth('web')->user()->can('menu.navbar.update')),
                        delete: @json(auth('web')->user()->can('menu.navbar.delete')),
                    },
                    handleCreateMenu: function(item, permissions) {
                        const $parentLi = $("<li>")
                            .addClass(
                                "list-group-item border border-primary-subtle rounded-3 shadow-sm d-flex flex-column"
                            )
                            .attr({
                                draggable: "true",
                                "data-id": item.id,
                                "data-parent-id": "null",
                            });

                        const $parentHeader = $("<div>")
                            .addClass(
                                "d-flex align-items-center gap-2 pb-2 mt-2 cursor-grab"
                            )
                            .css("cursor", "grab").html(`
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 1.5rem; height: 1.5rem; color: #6366f1;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="flex-grow-1 fw-bold fs-5">${item.name}</span>
                                <span class="badge bg-primary text-white fs-6">
                                    ${
                                    item.is_spacer
                                        ? "Spacer"
                                        : "Menu"
                                    }
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0 shadow-none d-flex align-items-center gap-1"
                                        type="button" id="dropdownMenuButton-${item.id}" data-bs-toggle="dropdown" aria-expanded="false"
                                        title="More actions" style="border: none; background: none;">
                                            <i class="bx bx-dots-vertical-rounded fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton-${item.id}">
                                        ${
                                            permissions.show
                                            ? `<li><button class="dropdown-item d-flex align-items-center gap-2 show-record" data-id="${item.id}" data-target="#show-record"><i class="bx bx-info-circle text-primary"></i> Detail</button></li>`
                                            : ""
                                        }
                                        ${
                                            permissions.edit
                                            ? `<li><button class="dropdown-item d-flex align-items-center gap-2 edit-record" data-id="${item.id}" data-target="#edit-record"><i class="bx bx-edit-alt text-warning"></i> Edit</button></li>`
                                            : ""
                                        }
                                        ${
                                            item.meta.is_sortable && permissions.delete
                                            ? `
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item d-flex align-items-center gap-2 text-danger delete-record" data-id="${item.id}" data-target="#delete-record"><i class="bx bx-trash"></i> Delete</button></li>
                                            `
                                            : ""
                                        }
                                    </ul>
                                </div>
                            `);

                        $parentLi.append($parentHeader);

                        return $parentLi;
                    },
                });
            });
        </script>
        @canany(['menu.navbar.create', 'menu.navbar.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    function toggleFormFields(prefix) {
                        const $icon = $(`#${prefix}icon`);
                        const $route = $(`#${prefix}route`);
                        const $isSpacer = $(`#${prefix}is_spacer`);

                        function updateVisibility() {
                            const isSpacer = $isSpacer.val() === '1';

                            $icon.closest('.col-sm-12').toggle(!isSpacer);
                            $route.closest('.col-sm-12').toggle(!isSpacer);
                        }

                        updateVisibility();

                        $isSpacer.off('change.toggleFields').on('change.toggleFields', function() {
                            if ($(this).val() === '1') {
                                $icon.val('').trigger('change');
                                $route.val('').trigger('change');
                            }
                            updateVisibility();
                        });

                        $route.off('change.toggleFields').on('change.toggleFields', function() {
                            const selectedRoutes = $(this).val();
                            $(`input[name="active_routes"]`, $(this).closest('form')).val(selectedRoutes);
                        });
                    }

                    toggleFormFields('');
                    toggleFormFields('edit-');
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
        @canany(['menu.navbar.create', 'menu.navbar.store', 'menu.navbar.show', 'menu.navbar.edit', 'menu.navbar.delete'])
            <script src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        pageData: navbarData,
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.menu.navbar.update', ':id') }}",
                            destroy: "{{ route('dashboard.menu.navbar.destroy', ':id') }}"
                        },
                        findFunction: (id) => {
                            return crudManager.pageData.find(item => item.id === id) || null;
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    name: '#show-name',
                                    is_spacer: '#show-is_spacer',
                                    is_sortable: '#show-is_sortable',
                                    route: '#show-route',
                                    icon: '#show-icon-text',
                                    permissions: '#show-permissions',
                                    parameters: '#show-parameters',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    name: function(el, data, rowData) {
                                        el.val(data || '-');
                                    },
                                    is_spacer: function(el, data, rowData) {
                                        el.val(data ? 'Yes' : 'No');
                                    },
                                    is_sortable: function(el, data, rowData) {
                                        el.val(rowData.meta && rowData.meta.is_sortable ? 'Yes' : 'No');
                                    },
                                    route: function(el, data, rowData) {
                                        if (rowData.meta && rowData.meta.route) {
                                            el.val(rowData.meta.route);
                                        } else {
                                            el.val('-');
                                        }
                                    },
                                    icon: function(el, data, rowData) {
                                        if (rowData.meta && rowData.meta.icon) {
                                            el.val(rowData.meta.icon);
                                            $('#show-icon-preview').html(
                                                `<i class="bx bx-${rowData.meta.icon} me-2"></i>`);
                                        } else {
                                            el.val('-');
                                            $('#show-icon-preview').html('<i class="bx bx-no-entry me-2"></i>');
                                        }
                                    },
                                    permissions: function(el, data, rowData) {
                                        if (rowData.meta && rowData.meta.permissions.length > 0) {
                                            el.html(rowData.meta.permissions.map(p =>
                                                `<span class="badge bg-primary me-1">${p}</span>`).join(
                                                ''));
                                        } else {
                                            el.text('-');
                                        }
                                    },
                                    parameters: function(el, data, rowData) {
                                        if (rowData.meta && rowData.meta.parameters.length > 0) {
                                            el.html(rowData.meta.parameters.map(p =>
                                                    `<span class="badge bg-primary me-1">${p}</span>`)
                                                .join(''));
                                        } else {
                                            el.text('-');
                                        }
                                    },
                                }
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    name: '#edit-name',
                                    is_spacer: '#edit-is_spacer',
                                    is_sortable: '#edit-is_sortable',
                                    route: '#edit-route',
                                    icon: '#edit-icon',
                                    permissions: '#edit-permissions',
                                    parameters: '#edit-parameters',
                                },
                                fieldMapBehavior: {
                                    name: function(el, data, rowData) {
                                        el.val(data || '-');
                                    },
                                    is_spacer: function(el, data, rowData) {
                                        el.val(data).trigger('change');
                                    },
                                    is_sortable: function(el, data, rowData) {
                                        el.val(rowData.meta?.is_sortable ? 1 : 0).trigger('change');
                                    },
                                    route: function(el, data, rowData) {
                                        if (rowData.is_spacer) {
                                            $('#edit-route').closest('.col-sm-12').hide();
                                        } else {
                                            $('#edit-route').closest('.col-sm-12').show();
                                        }

                                        if (rowData.meta && rowData.meta.route) {
                                            el.val(rowData.meta.route).trigger('change');
                                        }
                                    },
                                    icon: function(el, data, rowData) {
                                        if (rowData.is_spacer) {
                                            $('#edit-icon').closest('.col-sm-12').hide();
                                        } else {
                                            $('#edit-icon').closest('.col-sm-12').show();
                                        }

                                        if (rowData.meta && rowData.meta.icon) {
                                            el.val(rowData.meta.icon);
                                        }
                                    },
                                    permissions: function(el, data, rowData) {
                                        if (rowData.meta && rowData.meta.permissions.length > 0) {
                                            el.val(rowData.meta.permissions).trigger('change');
                                        }
                                    },
                                    parameters: function(el, data, rowData) {
                                        if (rowData.meta && rowData.meta.parameters.length > 0) {
                                            el.val(rowData.meta.parameters).trigger('change');
                                        }
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
