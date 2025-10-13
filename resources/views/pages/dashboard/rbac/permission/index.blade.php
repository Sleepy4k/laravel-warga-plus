<x-layouts.dashboard title="Permissions">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @canany(['rbac.permission.create', 'rbac.permission.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('rbac.permission.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Permission
                </button>
            @endcan
        </div>

        <p class="mb-4">
            <strong>Permissions</strong> control what actions users can perform in the application, such as creating,
            reading, updating, or deleting resources.<br>
            Assign permissions to roles or users to manage access to different parts of the system.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="rbac.permission.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.rbac.permission.store') }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="dashboard.rbac.role.index" aria-label="dashboard.rbac.role.index"
                                aria-describedby="name" value="{{ old('name') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="guard_name">Guard Name</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('guard_name') is-invalid @enderror"
                                id="guard_name" name="guard_name">
                                @foreach ($guardList as $guard)
                                    <option value="{{ $guard['value'] }}">{{ $guard['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="rbac.permission.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-name" class="form-control dt-name"
                                placeholder="dashboard.rbac.role.index" aria-label="dashboard.rbac.role.index"
                                aria-describedby="show-name" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-guard_name">Guard Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-guard_name" class="form-control dt-description"
                                placeholder="Web" aria-label="Web" aria-describedby="show-guard_name" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-roles">Assigned Roles</label>
                        <div class="input-group input-group-merge" id="show-roles"></div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="rbac.permission.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="dashboard.rbac.role.index" aria-label="dashboard.rbac.role.index"
                                aria-describedby="name" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-guard_name">Guard Name</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('guard_name') is-invalid @enderror"
                                id="edit-guard_name" name="guard_name">
                                @foreach ($guardList as $guard)
                                    <option value="{{ $guard['value'] }}">{{ $guard['label'] }}</option>
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
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce>
        </script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}"
            @cspNonce></script>
        @canany(['rbac.permission.create', 'rbac.permission.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce>
            </script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['rbac.permission.create', 'rbac.permission.edit'])
            <script type="text/javascript" src="{{ asset('js/pages/rbac-permission.min.js') }}" @cspNonce></script>
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
        @canany(['rbac.permission.create', 'rbac.permission.edit', 'rbac.permission.show', 'rbac.permission.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#permission-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.rbac.permission.update', ':id') }}",
                            destroy: "{{ route('dashboard.rbac.permission.destroy', ':id') }}"
                        },
                        forms: {
                            add: {
                                formId: '#form-add-new-record',
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
                                    guard_name: '#show-guard_name',
                                    assigned_to: '#show-roles',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    assigned_to: function(el, data, rowData) {
                                        el.html(data);
                                    }
                                }
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    name: '#edit-name',
                                    guard_name: '#edit-guard_name'
                                },
                                fieldMapBehavior: {
                                    guard_name: function(el, data, rowData) {
                                        el.val(data.toLowerCase()).trigger('change');
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
