<x-layouts.dashboard title="Status Reference">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('letter_references.status.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Status
                </button>
            @endcan
        </div>

        <p class="mb-4">
            <strong>Letter Status Management</strong> allows you to define and organize different status types for
            correspondence tracking.<br />
            <strong>Important:</strong> These status definitions are system-wide and visible to all users. Choose
            meaningful and professional status names.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="letter_references.status.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.letter.reference.status.store') }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="status">Status</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="status"
                                class="form-control dt-name @error('status') is-invalid @enderror" name="status"
                                placeholder="Segera" aria-label="Segera" aria-describedby="status"
                                value="{{ old('status') }}" />
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="letter_references.status.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-status">Status</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-status" class="form-control dt-name" placeholder="hiling"
                                aria-label="hiling" aria-describedby="show-status" disabled />
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="letter_references.status.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-status">Status</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-status"
                                class="form-control dt-status @error('status') is-invalid @enderror" name="status"
                                placeholder="Segera" aria-label="Segera" aria-describedby="status" />
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
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['letter_references.status.create', 'letter_references.status.edit', 'letter_references.status.show',
            'letter_references.status.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#status-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.letter.reference.status.update', ':id') }}",
                            destroy: "{{ route('dashboard.letter.reference.status.destroy', ':id') }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    status: '#show-status',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {}
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    status: '#edit-status',
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
