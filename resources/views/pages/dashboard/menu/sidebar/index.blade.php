<x-layouts.dashboard title="Sidebar Menu">
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

            .children-list {
                margin-left: 1.5rem;
                padding-left: 1.5rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
        </style>
        @canany(['menu.sidebar.create', 'menu.sidebar.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('menu.sidebar.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Menu
                </button>
            @endcan
        </div>

        <p class="mb-4">
            Organize your sidebar menu items by dragging and dropping them into the desired order.<br />
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
                        <h5 class="mb-0 fw-semibold text-primary">Sidebar Menu Manager</h5>
                        <small class="text-muted">Organize your sidebar navigation with drag &amp; drop</small>
                    </div>
                </div>
                @can('menu.sidebar.update')
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

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="menu.sidebar.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.menu.sidebar.store') }}">
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
                            <select id="is_spacer"
                                class="form-select select2 @error('is_spacer') is-invalid @enderror" name="is_spacer">
                                <option value="1" @selected(old('is_spacer', 1) == 1)>Yes</option>
                                <option value="0" @selected(old('is_spacer', 0) == 0)>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="is_children">Is Children</label>
                        <div class="input-group input-group-merge">
                            <select id="is_children"
                                class="form-select select2 @error('is_children') is-invalid @enderror"
                                name="is_children">
                                <option value="1" @selected(old('is_children', 0) == 1)>Yes</option>
                                <option value="0" @selected(old('is_children', 0) == 0)>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="parent_id">Parent Menu</label>
                        <div class="input-group input-group-merge">
                            <select id="parent_id"
                                class="form-select select2 @error('parent_id') is-invalid @enderror" name="parent_id">
                                @foreach ($parentMenus as $menu)
                                    <option value="{{ $menu->id }}" @selected(old('parent_id') == $menu->id)>
                                        {{ $menu->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 ">
                        <label class="form-label" for="route">Route Name</label>
                        <div class="input-group input-group-merge">
                            <select id="route" class="form-select select2 @error('route') is-invalid @enderror"
                                name="route">
                                @foreach ($routeNameList as $routeName)
                                    <option value="{{ $routeName }}" @selected(old('route') == $routeName)>
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
                                    <option value="{{ $permission->name }}"
                                        @selected(in_array($permission->name, old('permissions', [])))
                                        >{{ $permission->name }}
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

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="menu.sidebar.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2" id="show-record-content">
                    <div class="col-sm-12">
                        <label class="form-label fw-bold" for="show-name">Name</label>
                        <input type="text" class="form-control" id="show-name" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-bold" for="show-is_spacer">Is Spacer</label>
                        <input type="text" class="form-control" id="show-is_spacer" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-bold" for="show-is_children">Is Children</label>
                        <input type="text" class="form-control" id="show-is_children" readonly>
                    </div>
                    <div class="col-sm-12" id="show-parent-group">
                        <label class="form-label fw-bold" for="show-parent_name">Parent Menu</label>
                        <input type="text" class="form-control" id="show-parent_name" readonly>
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

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="menu.sidebar.edit">
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
                        <label class="form-label" for="edit-is_children">Is Children</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-is_children" class="form-select select2" name="is_children">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12" id="edit-parent-group">
                        <label class="form-label" for="edit-parent_id">Parent Menu</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-parent_id" class="form-select select2" name="parent_id">
                                @foreach ($parentMenus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
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
        @canany(['menu.sidebar.create', 'menu.sidebar.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        <script type="text/javascript" src="{{ asset('js/handler/drag-manager.min.js') }}" @cspNonce></script>
        <script @cspNonce>
            const sidebarData = @json($menus);
            $(document).ready(() => {
                const dragManager = new DragManager({
                    debug: "{{ config('app.debug') }}",
                    data: sidebarData,
                    saveUrl: "{{ route('dashboard.menu.sidebar.saveOrder') }}",
                    csrfToken: '{{ csrf_token() }}',
                    permissions: {
                        show: @json(auth('web')->user()->can('menu.sidebar.show')),
                        edit: @json(auth('web')->user()->can('menu.sidebar.edit')),
                        update: @json(auth('web')->user()->can('menu.sidebar.update')),
                        delete: @json(auth('web')->user()->can('menu.sidebar.delete')),
                    },
                    handleCreateMenu: function(item, permissions) {
                        const $parentLi = $("<li>")
                            .addClass(
                                "list-group-item border border-primary-subtle rounded-3 shadow-sm d-flex flex-column"
                            )
                            .attr({
                                draggable: item.meta.is_sortable ? "true" : "false",
                                "data-id": item.id,
                                "data-parent-id": "null",
                            });

                        const $parentHeader = $("<div>")
                            .addClass(
                                "d-flex align-items-center gap-2 pb-2 mt-2" +
                                (item.meta.is_sortable ? " cursor-grab" : "")
                            )
                            .css("cursor", item.meta.is_sortable ? "grab" : "default").html(`
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 1.5rem; height: 1.5rem; color: #6366f1;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span class="flex-grow-1 fw-bold fs-5">${item.name}</span>
                                <span class="badge bg-primary text-white fs-6">
                                    ${
                                        item.is_spacer
                                            ? "Spacer"
                                            : item.children.length > 0
                                            ? item.children.length + " items"
                                            : "No children"
                                    }
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0 shadow-none d-flex align-items-center gap-1"
                                        type="button" id="dropdownMenuButton-${
                                            item.id
                                        }" data-bs-toggle="dropdown" aria-expanded="false"
                                        title="More actions" style="border: none; background: none;">
                                        <i class="bx bx-dots-vertical-rounded fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton-${
                                        item.id
                                    }">
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

                        if (!item.is_spacer && item.children && item.children.length > 0) {
                            const $childrenUl = $("<ul>")
                                .addClass("list-group children-list mt-2")
                                .attr("data-droppable-id", item.id);

                            item.children.forEach((childItem) => {
                                const $childLi = $("<li>")
                                    .addClass(
                                        "list-group-item border border-secondary-subtle rounded-2 shadow-sm d-flex align-items-center gap-2 mb-2"
                                    )
                                    .attr({
                                        draggable: "true",
                                        "data-id": childItem.id,
                                        "data-parent-id": item.id,
                                    })
                                    .css("cursor", "grab").html(`
                                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 1.25rem; height: 1.25rem; color: #6c757d;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        <span class="flex-grow-1 fw-medium fs-6">${childItem.name}</span>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border-0 shadow-none d-flex align-items-center gap-1"
                                                type="button" id="dropdownMenuButton-child-${
                                                    childItem.id
                                                }" data-bs-toggle="dropdown" aria-expanded="false"
                                                title="More actions" style="border: none; background: none;">
                                                    <i class="bx bx-dots-vertical-rounded fs-5"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton-${
                                                    childItem.id
                                                }">
                                                ${
                                                    permissions.show
                                                        ? `<li><button class="dropdown-item d-flex align-items-center gap-2 show-record" data-id="${childItem.id}" data-target="#show-record"><i class="bx bx-info-circle text-primary"></i> Detail</button></li>`
                                                        : ""
                                                }
                                                ${
                                                    permissions.edit
                                                        ? `<li><button class="dropdown-item d-flex align-items-center gap-2 edit-record" data-id="${childItem.id}" data-target="#edit-record"><i class="bx bx-edit-alt text-warning"></i> Edit</button></li>`
                                                        : ""
                                                }
                                                ${
                                                    item.meta.is_sortable && permissions.delete
                                                        ? `
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><button class="dropdown-item d-flex align-items-center gap-2 text-danger delete-record" data-id="${childItem.id}" data-target="#delete-record"><i class="bx bx-trash"></i> Delete</button></li>
                                                                `
                                                        : ""
                                                }
                                            </ul>
                                        </div>
                                    `);

                                $childrenUl.append($childLi);
                            });

                            $parentLi.append($childrenUl);
                        }

                        return $parentLi;
                    }
                });
            });
        </script>
        @canany(['menu.sidebar.create', 'menu.sidebar.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    function toggleFormFields(prefix) {
                        const $icon = $(`#${prefix}icon`);
                        const $route = $(`#${prefix}route`);
                        const $isSpacer = $(`#${prefix}is_spacer`);
                        const $parentId = $(`#${prefix}parent_id`);
                        const $isChildren = $(`#${prefix}is_children`);

                        $parentId.closest('.col-sm-12').addClass(`${prefix}parent-id-group`);
                        $isChildren.closest('.col-sm-12').addClass(`${prefix}is-children-group`);

                        function updateVisibility() {
                            const isSpacer = $isSpacer.val() === '1';
                            const isChildren = $isChildren.val() === '1';

                            $(`.${prefix}parent-id-group`).toggle(isChildren);
                            $(`.${prefix}is-children-group`).toggle(!isSpacer);

                            $icon.closest('.col-sm-12').toggle(!isSpacer && !isChildren);
                            $route.closest('.col-sm-12').toggle(!isSpacer && isChildren);
                        }

                        updateVisibility();

                        $isSpacer.off('change.toggleFields').on('change.toggleFields', function() {
                            if ($(this).val() === '1') {
                                $isChildren.val('0').trigger('change');
                                $parentId.val('').trigger('change');
                            }
                            updateVisibility();
                        });

                        $isChildren.off('change.toggleFields').on('change.toggleFields', function() {
                            updateVisibility();
                            if ($(this).val() === '0') {
                                $route.val('').trigger('change');
                            } else {
                                $icon.val('');
                            }
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
        @canany(['menu.sidebar.create', 'menu.sidebar.store', 'menu.sidebar.show', 'menu.sidebar.edit',
            'menu.sidebar.delete'])
            <script src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        pageData: sidebarData,
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.menu.sidebar.update', ':id') }}",
                            destroy: "{{ route('dashboard.menu.sidebar.destroy', ':id') }}"
                        },
                        findFunction: (id) => {
                            for (let parent of crudManager.pageData) {
                                if (parent.id == id) {
                                    return parent;
                                }
                                if (parent.children) {
                                    for (let child of parent.children) {
                                        if (child.id == id) {
                                            return child;
                                        }
                                    }
                                }
                            }
                            return null;
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    name: '#show-name',
                                    is_spacer: '#show-is_spacer',
                                    is_children: '#show-is_children',
                                    is_sortable: '#show-is_sortable',
                                    route: '#show-route',
                                    icon: '#show-icon-text',
                                    parent_id: '#show-parent_name',
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
                                    is_children: function(el, data, rowData) {
                                        el.val(rowData.parent_id ? 'Yes' : 'No');
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
                                    parent_id: function(el, data, rowData) {
                                        if (rowData.parent_id) {
                                            const parentMenu = crudManager.findFunction(rowData.parent_id);
                                            el.val(parentMenu ? parentMenu.name : '-');
                                            $('#show-parent-group').show();
                                        } else {
                                            $('#show-parent-group').hide();
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
                                    is_children: '#edit-is_children',
                                    is_sortable: '#edit-is_sortable',
                                    route: '#edit-route',
                                    icon: '#edit-icon',
                                    parent_id: '#edit-parent_id',
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
                                    is_children: function(el, data, rowData) {
                                        if (rowData.is_spacer) {
                                            el.val(0).trigger('change');
                                            $('#edit-is_children').closest('.col-sm-12').hide();
                                            $('#edit-parent_id').val('').trigger('change');
                                            $('#edit-parent-group').hide();
                                        } else {
                                            el.val(rowData.parent_id ? 1 : 0).trigger('change');
                                            $('#edit-is_children').closest('.col-sm-12').show();
                                            $('#edit-parent-group').show();
                                        }
                                    },
                                    is_sortable: function(el, data, rowData) {
                                        el.val(rowData.meta?.is_sortable ? 1 : 0).trigger('change');
                                    },
                                    route: function(el, data, rowData) {
                                        if (rowData.meta && rowData.meta.route) {
                                            el.val(rowData.meta.route).trigger('change');
                                        }
                                    },
                                    icon: function(el, data, rowData) {
                                        if (rowData.is_children || rowData.is_spacer) {
                                            $('#edit-icon').closest('.col-sm-12').hide();
                                        } else {
                                            $('#edit-icon').closest('.col-sm-12').show();
                                        }

                                        if (rowData.meta && rowData.meta.icon) {
                                            el.val(rowData.meta.icon);
                                        }
                                    },
                                    parent_id: function(el, data, rowData) {
                                        if (rowData.parent_id) {
                                            el.val(data).trigger('change');
                                            $('#edit-parent-group').show();
                                        } else {
                                            el.val(0).trigger('change');
                                            $('#edit-parent-group').hide();
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
