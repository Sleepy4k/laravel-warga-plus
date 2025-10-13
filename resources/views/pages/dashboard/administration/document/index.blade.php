<x-layouts.dashboard title="Document Management">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @canany(['document.create', 'document.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('document.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Add Document
                </button>
            @endcan
        </div>

        <div class="row" id="document-stats">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Document</h5>
                        <p class="card-text">
                            <span id="total-document">{{ $totalDocument }}</span>
                            <span id="text-document">&nbsp{{ Str::plural('Document', $totalDocument) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Archived Document</h5>
                        <p class="card-text">
                            <span id="total-document-archived">{{ $totalDocumentArchived }}</span>
                            <span
                                id="text-document-archived">&nbsp{{ Str::plural('Document', $totalDocumentArchived) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Category</h5>
                        <p class="card-text">
                            <span id="total-document-categories">{{ $totalDocumentCategories }}</span>
                            <span
                                id="text-document-categories">&nbsp{{ Str::plural('Category', $totalDocumentCategories) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="document.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.document.store') }}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <label class="form-label" for="title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="title"
                                class="form-control dt-title @error('title') is-invalid @enderror" name="title"
                                placeholder="Surat Pengajuan Program Kerja" aria-label="Surat Pengajuan Program Kerja"
                                aria-describedby="title" value="{{ old('title') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea id="description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror" placeholder="Deskripsi dokumen"
                                aria-label="Deskripsi dokumen" aria-describedby="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="is_archived">Archive</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('is_archived') is-invalid @enderror"
                                id="is_archived" name="is_archived">
                                <option value="0" @selected(old('is_archived') == 0)>No</option>
                                <option value="1" @selected(old('is_archived') == 1)>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="file">Add File</label>
                        <div class="input-group input-group-merge">
                            <input type="file" id="file"
                                class="form-control dt-file @error('file') is-invalid @enderror" name="file"
                                aria-label="File" aria-describedby="file" />
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="document.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-title"
                                class="form-control dt-title @error('title') is-invalid @enderror" name="title"
                                placeholder="Surat Pengajuan Program Kerja" aria-label="Surat Pengajuan Program Kerja"
                                aria-describedby="title" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea id="edit-description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror" placeholder="Deskripsi dokumen"
                                aria-label="Deskripsi dokumen" aria-describedby="description"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('category_id') is-invalid @enderror"
                                id="edit-category_id" name="category_id">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-is_archived">Archive</label>
                        <div class="input-group input-group-merge">
                            <select class="form-select select2 @error('is_archived') is-invalid @enderror"
                                id="edit-is_archived" name="is_archived">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
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
        @canany(['document.create', 'document.edit'])
            <script src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce>
            </script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['document.create', 'document.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#description'));
                    autosize($('#edit-description'));
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
        @canany(['document.create', 'document.edit', 'document.show', 'document.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#document-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.document.update', ':id') }}",
                            destroy: "{{ route('dashboard.document.destroy', ':id') }}"
                        },
                        offcanvas: {
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    title: '#edit-title',
                                    description: '#edit-description',
                                    category_id: '#edit-category_id',
                                    is_archived: '#edit-is_archived',
                                },
                                fieldMapBehavior: {
                                    category_id: function(el, data, rowData) {
                                        el.val(data).trigger('change');
                                    },
                                    is_archived: function(el, data, rowData) {
                                        el.val(data.toLowerCase() === 'no' ? '0' : '1').trigger('change');
                                    },
                                }
                            }
                        },
                        onSuccess: function(response, formId) {
                            const getPluralText = (count, singular, plural) => {
                                return "&nbsp" + (count === 1 ? singular : plural);
                            };

                            const totalDocument = response.data.totalDocument || 0;
                            const totalDocumentArchived = response.data.totalDocumentArchived || 0;
                            const totalDocumentCategories = response.data.totalDocumentCategories || 0;
                            $('#document-stats #total-document').text(totalDocument);
                            $('#document-stats #text-document').html(getPluralText(totalDocument, 'Document',
                                'Documents'));
                            $('#document-stats #total-document-archived').text(totalDocumentArchived);
                            $('#document-stats #text-document-archived').html(getPluralText(totalDocumentArchived, 'Archived Document',
                                'Archived Documents'));
                            $('#document-stats #total-document-categories').text(totalDocumentCategories);
                            $('#document-stats #text-document-categories').html(getPluralText(totalDocumentCategories, 'Category',
                                'Categories'));

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
