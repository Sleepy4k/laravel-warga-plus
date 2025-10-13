<x-layouts.dashboard title="Classification Reference">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('letter_references.classification.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Classification
                </button>
            @endcan
        </div>

        <p class="mb-4">
            <strong>Letter Classification Management</strong> allows you to define and organize different classification
            types for correspondence tracking.<br />
            <strong>Important:</strong> These classification definitions are system-wide and visible to all users.
            Choose meaningful and professional classification names.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record"
            canvasPermission="letter_references.classification.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.letter.reference.classification.store') }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="Administrasi" aria-label="Administrasi" aria-describedby="name"
                                value="{{ old('name') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="description">Description</label>
                        <div>
                            <textarea id="description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror" placeholder="Description"
                                aria-label="Description" aria-describedby="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="letter_references.classification.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-name" class="form-control dt-name" placeholder="Administrasi"
                                aria-label="Administrasi" aria-describedby="show-name" readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-description">Description</label>
                        <div>
                            <textarea id="show-description" class="form-control dt-description" placeholder="Description" aria-label="Description"
                                aria-describedby="show-description" readonly></textarea>
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="letter_references.classification.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="Administrasi" aria-label="Administrasi" aria-describedby="name" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-description">Description</label>
                        <div>
                            <textarea id="edit-description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror" placeholder="Description"
                                aria-label="Description" aria-describedby="description">{{ old('description') }}</textarea>
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
        <script src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['letter_references.classification.create', 'letter_references.classification.edit',
            'letter_references.classification.show', 'letter_references.classification.delete'])
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#description'));
                    autosize($('#show-description'));
                    autosize($('#edit-description'));
                });
            </script>
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#classification-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.letter.reference.classification.update', ':id') }}",
                            destroy: "{{ route('dashboard.letter.reference.classification.destroy', ':id') }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    name: '#show-name',
                                    description: '#show-description',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {}
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    name: '#edit-name',
                                    description: '#edit-description',
                                },
                                fieldMapBehavior: {}
                            }
                        }
                    });
                });
            </script>
        @endcanany
    @endPushOnce
</x-layouts.dashboard>
