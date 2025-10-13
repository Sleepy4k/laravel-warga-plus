<x-layouts.dashboard title="Document Category">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('document.category.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Category
                </button>
            @endcan
        </div>

        <p class="mb-4">
            Document across the system are organized into <strong>Categories</strong> for easier management and reporting.<br />
            <strong>Note:</strong> Make categories clear and descriptive, as they are shared across all users.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="document.category.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.document.category.store') }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="RAB" aria-label="RAB" aria-describedby="name"
                                value="{{ old('name') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea id="description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror"
                                placeholder="Kategori surat untuk pengajuan rancangan anggaran" aria-label="Kategori surat untuk pengajuan rancangan anggaran" aria-describedby="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="document.category.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-name" class="form-control dt-name" placeholder="hiling"
                                aria-label="hiling" aria-describedby="show-name" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea id="show-description" class="form-control dt-description"
                                placeholder="Kategori surat untuk pengajuan rancangan anggaran" aria-label="Kategori surat untuk pengajuan rancangan anggaran" aria-describedby="show-description"
                                disabled>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="document.category.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="hiling" aria-label="hiling" aria-describedby="name" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea id="edit-description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror"
                                placeholder="Kategori surat untuk pengajuan rancangan anggaran" aria-label="Kategori surat untuk pengajuan rancangan anggaran" aria-describedby="description">{{ old('description') }}</textarea>
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
        @canany(['article.create', 'article.edit'])
            <script src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['document.category.create', 'document.category.edit'])
            <script type="text/javascript" src="{{ asset('js/pages/document-category.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#description'));
                    autosize($('#edit-description'));
                });
            </script>
        @endcanany
        @canany(['document.category.create', 'document.category.edit', 'document.category.show', 'document.category.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#document-category-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.document.category.update', ':id') }}",
                            destroy: "{{ route('dashboard.document.category.destroy', ':id') }}"
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
                                    description: '#edit-description'
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
