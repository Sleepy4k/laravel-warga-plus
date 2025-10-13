<x-layouts.dashboard title="Document Details">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @can('document.edit')
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcan
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('dashboard.document.index') }}" class="btn btn-primary me-3">Back to Document List</a>
                @can('document.version.store')
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#add-new-record">
                        Add Document
                    </button>
                @endcan
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1 mb-4">
                <div class="card">
                    <div class="card-body table-responsive">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0 mb-4">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bx bx-file fs-3 text-primary me-2"></i>
                                <span class="text-muted text-uppercase fw-semibold">Document Details</span>
                            </div>
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Title</span>
                                    <span class="text-end">{{ $document->title }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Category</span>
                                    <span class="badge bg-primary text-white">{{ $document->category->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Archived</span>
                                    <span>
                                        @if ($document->is_archived)
                                            <span class="badge bg-danger">Yes</span>
                                        @else
                                            <span class="badge bg-primary">No</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Last Modified</span>
                                    <span class="text-muted">{{ $document->last_modified_at->format('d M Y, H:i') }}</span>
                                </li>
                            </ul>
                            <div class="mb-2">
                                <span class="text-muted text-uppercase fw-semibold">Description</span>
                            </div>
                            <div class="alert alert-secondary" role="alert">
                                {{ $document->description ?: 'No description provided.' }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-center pt-3">
                            @can('document.edit')
                                <button class="btn btn-primary me-3" data-bs-target="#editDocument"
                                    data-bs-toggle="modal">Edit</button>
                            @endcan
                            @can('document.delete')
                                <button class="btn btn-label-danger suspend-document"
                                    id="delete-document-btn">Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @can('document.edit')
            <div class="modal fade" id="editDocument" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3>Edit Document Data</h3>
                                <p>
                                    You can edit the document data here. Make sure to fill in all
                                </p>
                            </div>
                            <form id="editDocumentForm" class="row g-3">
                                <div class="col-sm-12">
                                    <label class="form-label" for="edit-title">Title</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="edit-title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Document Title" aria-label="Document Title"
                                            value="{{ $document->title }}" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-category">Category</label>
                                    <div class="input-group input-group-merge">
                                        <select id="edit-category" name="category_id"
                                            class="form-select select2 @error('category_id') is-invalid @enderror">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @selected($document->category_id == $category->id)>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-is_archived">Archived</label>
                                    <div class="input-group input-group-merge">
                                        <select id="edit-is_archived" name="is_archived"
                                            class="form-select select2 @error('is_archived') is-invalid @enderror">
                                            <option value="1" @selected($document->is_archived == 1)>Yes</option>
                                            <option value="0" @selected($document->is_archived == 0)>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label" for="edit-description">Description</label>
                                    <div class="input-group input-group-merge">
                                        <textarea id="edit-description" name="description"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Document description" rows="3">{{ $document->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
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

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="document.version.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.document.version.store', $document->id) }}">
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

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="document.version.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-file_name">File Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-file_name" class="form-control dt-file" placeholder="File"
                                aria-label="File" aria-describedby="show-file_name" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-version_number">Version Number</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-version_number" class="form-control dt-file" placeholder="Version Number"
                                aria-label="Version Number" aria-describedby="show-version_number" disabled />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="show-file_size">File Size</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-file_size" class="form-control dt-file" placeholder="File Size"
                                aria-label="File Size" aria-describedby="show-file_size" disabled />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="show-file_type">File Type</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-file_type" class="form-control dt-file" placeholder="File Type"
                                aria-label="File Type" aria-describedby="show-file_type" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-uploaded_by">Uploaded By</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-uploaded_by" class="form-control dt-file" placeholder="Uploaded By"
                                aria-label="Uploaded By" aria-describedby="show-uploaded_by" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-uploaded_at">Uploaded At</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-uploaded_at" class="form-control dt-file" placeholder="Uploaded At"
                                aria-label="Uploaded At" aria-describedby="show-uploaded_at" disabled />
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}" @cspNonce></script>
        @can('document.edit')
            <script type="text/javascript" src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcan
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}

        @can('document.edit')
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#edit-description'));
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    const select2Elements = $('.select2');
                    if (select2Elements.length) {
                        select2Elements.each(function() {
                            $(this).select2({
                                dropdownParent: $(this).closest('.modal'),
                                placeholder: 'Select an option',
                                allowClear: false,
                                width: '100%',
                            });
                        });
                    }
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    $('#editDocumentForm').on('submit', async function(e) {
                        e.preventDefault();
                        const form = $(this);
                        const url = "{{ route('dashboard.document.update', $document->id) }}";
                        const formData = new FormData(form[0]);

                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('_method', 'PUT');

                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                Accept: "application/json",
                            },
                        });

                        const responseData = await response.json();

                        if (response.ok) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Document data updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred while updating document data.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    });
                });
            </script>
        @endcan
        @can('document.delete')
            <script @cspNonce>
                $(document).ready(function() {
                    $('#delete-document-btn').on('click', function() {
                        const url = "{{ route('dashboard.document.destroy', $document->id) }}";

                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'This action cannot be undone.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, cancel!',
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-primary'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Deleting Record...",
                                    text: "Please wait while the record is being deleted.",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                    Swal.showLoading();
                                    },
                                });
                                $.ajax({
                                    url: url,
                                    type: 'DELETE',
                                    data: {
                                        '_token': '{{ csrf_token() }}',
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Document deleted successfully.',
                                            icon: 'success',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'btn btn-primary'
                                            }
                                        }).then(() => {
                                            window.location.href =
                                                "{{ route('dashboard.document.index') }}";
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'An error occurred while deleting document.',
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'btn btn-danger'
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    });
                });
            </script>
        @endcan
        @canany(['document.version.create', 'document.version.show', 'document.version.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#document-version-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            destroy: "{{ route('dashboard.document.version.destroy', ['document' => $document->id, 'version' => ':id']) }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    file_name: '#show-file_name',
                                    file_size: '#show-file_size',
                                    file_type: '#show-file_type',
                                    uploaded_by: '#show-uploaded_by',
                                    uploaded_at: '#show-uploaded_at',
                                    version_number: '#show-version_number',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    version_number: function(el, data, rowData) {
                                        el.val(`Version ${data}`);
                                    },
                                }
                            },
                        },
                    });
                });
            </script>
        @endcanany
    @endPushOnce
</x-layouts.dashboard>
