<x-layouts.dashboard title="Article Category">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('article.category.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Create Category
                </button>
            @endcan
        </div>

        <p class="mb-4">
            <strong>Categories</strong> help you organize your articles for easier management and reporting.<br />
            <strong>Note:</strong> Categories are shared across all users. Please use clear and descriptive names.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="article.category.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.article.category.store') }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="name">Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="name"
                                class="form-control dt-name @error('name') is-invalid @enderror" name="name"
                                placeholder="hiling" aria-label="hiling" aria-describedby="name"
                                value="{{ old('name') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="label">Label</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="label" name="label"
                                class="form-control dt-label @error('label') is-invalid @enderror"
                                placeholder="HIPMI Keliling" aria-label="HIPMI Keliling" aria-describedby="label"
                                value="{{ old('label') }}" />
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="article.category.show">
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
                        <label class="form-label" for="show-label">Label</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-label" class="form-control dt-description"
                                placeholder="HIPMI Keliling" aria-label="HIPMI Keliling" aria-describedby="show-label"
                                disabled />
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="article.category.edit">
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
                        <label class="form-label" for="edit-label">Label</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-label" name="label"
                                class="form-control dt-label @error('label') is-invalid @enderror"
                                placeholder="HIPMI Keliling" aria-label="HIPMI Keliling" aria-describedby="label" />
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
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['article.category.create', 'article.category.edit'])
            <script type="text/javascript" src="{{ asset('js/pages/article-category.min.js') }}" @cspNonce></script>
        @endcanany
        @canany(['article.category.create', 'article.category.edit', 'article.category.show', 'article.category.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script type="text/javascript" @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#article-category-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.article.category.update', ':id') }}",
                            destroy: "{{ route('dashboard.article.category.destroy', ':id') }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    name: '#show-name',
                                    label: '#show-label',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {}
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    name: '#edit-name',
                                    label: '#edit-label'
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
