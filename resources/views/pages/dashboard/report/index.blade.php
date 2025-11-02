<x-layouts.dashboard title="Report">
    @pushOnce('plugin-styles')
        <style @cspNonce>
            .selected-file {
                width: 275px
            }

            .selected-file-body {
                width: 20px;
                height: 20px;
            }
        </style>
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @canany(['report.create', 'report.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('report.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Add Report
                </button>
            @endcan
        </div>

        <div class="row" id="report-stats">
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Report</h5>
                        <p class="card-text">
                            <span id="total-report">{{ $totalReports }}</span>
                            <span id="text-report">&nbsp{{ Str::plural('Report', $totalReports) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Report Processed</h5>
                        <p class="card-text">
                            <span id="reports-processed">{{ $totalReportsProcessed }}</span>
                            <span id="text-reports-processed">&nbsp{{ Str::plural('Report', $totalReportsProcessed) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Report Declined</h5>
                        <p class="card-text">
                            <span id="reports-declined">{{ $totalReportsDeclined }}</span>
                            <span id="text-reports-declined">&nbsp{{ Str::plural('Report', $totalReportsDeclined) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Report Solved</h5>
                        <p class="card-text">
                            <span id="reports-solved">{{ $totalReportsSolved }}</span>
                            <span id="text-reports-solved">&nbsp{{ Str::plural('Report', $totalReportsSolved) }}</span>
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

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="report.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.report.store') }}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <label class="form-label" for="title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="title"
                                class="form-control dt-title @error('title') is-invalid @enderror" name="title"
                                placeholder="Sampah Berserakan di Jalan Raya" aria-label="Sampah Berserakan di Jalan Raya"
                                aria-describedby="title" value="{{ old('title') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="location">Location</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="location"
                                class="form-control dt-location @error('location') is-invalid @enderror" name="location"
                                placeholder="Jl. Merdeka No.123, Jakarta" aria-label="Jl. Merdeka No.123, Jakarta"
                                aria-describedby="location" value="{{ old('location') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="content">Content</label>
                        <div class="input-group input-group-merge">
                            <textarea id="content" name="content"
                                class="form-control dt-content @error('content') is-invalid @enderror"
                                placeholder="Saya ingin melaporkan adanya sampah berserakan di jalan raya yang mengganggu kenyamanan warga sekitar."
                                aria-label="Saya ingin melaporkan adanya sampah berserakan di jalan raya yang mengganggu kenyamanan warga sekitar."
                                aria-describedby="content" rows="3">{{ old('content') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <select id="category_id" name="category_id"
                                class="form-select select2 dt-category @error('category_id') is-invalid @enderror">
                                <option value="" disabled selected>Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ strtolower($category->name) == "lainnya" ? 'other' : $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ ucfirst(strtolower($category->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12" id="new-category-group">
                        <label class="form-label" for="new-category">New Category</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="new-category"
                                class="form-control dt-new-category @error('new-category') is-invalid @enderror" name="new-category"
                                placeholder="Lingkungan" aria-label="Lingkungan"
                                aria-describedby="new-category" value="{{ old('new-category', "") }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="file">Add Files</label>
                        <div class="input-group input-group-merge">
                            <input type="file" id="file"
                                class="form-control dt-file @error('file') is-invalid @enderror" name="file[]"
                                multiple aria-label="Files" aria-describedby="file"
                                accept=".jpg,.jpeg,.png" />
                        </div>
                        <div id="file-preview" class="mt-2" style="display: none;">
                            <small class="text-muted mb-2 d-block">Selected Files:</small>
                            <div id="file-list" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="report.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-title">Title</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-title"
                                class="form-control dt-title @error('title') is-invalid @enderror" name="title"
                                placeholder="Sampah Berserakan di Jalan Raya" aria-label="Sampah Berserakan di Jalan Raya"
                                aria-describedby="title" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-location">Location</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-location"
                                class="form-control dt-location @error('location') is-invalid @enderror" name="location"
                                placeholder="Jl. Merdeka No.123, Jakarta" aria-label="Jl. Merdeka No.123, Jakarta"
                                aria-describedby="location" value="{{ old('location') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-content">Content</label>
                        <div class="input-group input-group-merge">
                            <textarea id="edit-content" name="content"
                                class="form-control dt-content @error('content') is-invalid @enderror" placeholder="Deskripsi dokumen"
                                aria-label="Deskripsi dokumen" aria-describedby="content"></textarea>
                        </div>
                    </div>
                    @if (!$isUser)
                        <div class="col-sm-6">
                            <label class="form-label" for="edit-status">Status</label>
                            <div class="input-group input-group-merge">
                                <select id="edit-status" name="status"
                                    class="form-select select2 dt-status @error('status') is-invalid @enderror">
                                    <option value="" disabled selected>Select status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                            {{ ucfirst(strtolower(str_replace('_', ' ', $status->name))) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-{{ $isUser ? '12' : '6' }}">
                        <label class="form-label" for="edit-category_id">Category</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-category_id" name="category_id"
                                class="form-select select2 dt-category @error('category_id') is-invalid @enderror">
                                <option value="" disabled selected>Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ strtolower($category->name) == "lainnya" ? 'other' : $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ ucfirst(strtolower($category->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12" id="edit-new-category-group">
                        <label class="form-label" for="edit-new-category">New Category</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-new-category"
                                class="form-control dt-new-category @error('new-category') is-invalid @enderror" name="new-category"
                                placeholder="Lingkungan" aria-label="Lingkungan"
                                aria-describedby="new-category" value="{{ old('new-category', "") }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-file">Add Files</label>
                        <div class="input-group input-group-merge">
                            <input type="file" id="edit-file"
                                class="form-control dt-file @error('file') is-invalid @enderror" name="file[]"
                                multiple aria-label="Files" aria-describedby="file"
                                accept=".jpg,.jpeg,.png" />
                        </div>
                        <div id="edit-file-preview" class="mt-2" style="display: none;">
                            <small class="text-muted mb-2 d-block">Selected Files:</small>
                            <div id="edit-file-list" class="d-flex flex-wrap gap-2"></div>
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
        @canany(['report.create', 'report.edit'])
            <script src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce>
            </script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @canany(['report.create', 'report.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#content'));
                    autosize($('#edit-content'));
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
            <script @cspNonce>
                $(document).ready(function() {
                    function toggleFormFields(prefix) {
                        const $category = $(`#${prefix}category_id`);
                        const $newCategory = $(`#${prefix}new-category`);

                        function updateVisibility() {
                            const isOther = $category.val() === 'other';

                            $newCategory.closest('.col-sm-12').toggle(isOther);
                        }

                        updateVisibility();

                        $category.off('change.toggleFields').on('change.toggleFields', function() {
                            if ($(this).val() === 'other') {
                                $newCategory.val('');
                            }
                            updateVisibility();
                        });
                    }

                    toggleFormFields('');
                    toggleFormFields('edit-');
                });
            </script>
            <script @cspNonce>
                let addFormHandler, editFormHandler;

                $(document).ready(function() {
                    function createFileHandler(inputSelector, previewSelector, listSelector, removeSelector) {
                        const fileInput = $(inputSelector);
                        const filePreview = $(previewSelector);
                        const fileList = $(listSelector);
                        let selectedFiles = [];

                        function updatePreview() {
                            fileList.empty();

                            if (selectedFiles.length === 0) {
                                filePreview.hide();
                                return;
                            }

                            filePreview.show();

                            selectedFiles.forEach((file, index) => {
                                const isContainMetaData = file.name.includes('@');
                                const [originalFileName, rowId] = isContainMetaData ? file.name.split('@') : [file
                                    .name, Math.random().toString(36).substr(2, 9)
                                ];
                                const fileName = originalFileName.length > 20 ? originalFileName.substring(0, 20) +
                                    '...' : originalFileName;
                                const fileSize = (file.size / 1024).toFixed(1) + ' KB';
                                const isServerFile = file.type === 'application/octet-stream-server';

                                const fileItem = $(`
                                    <div class="badge bg-light text-white border d-flex align-items-center gap-2 p-2 selected-file">
                                        <i class="bx bx-file text-primary"></i>
                                        <span class="small">${fileName} (${fileSize})</span>
                                        <button type="button" class="btn btn-sm btn-outline-danger p-0 ms-auto selected-file-body ${isServerFile ? 'uploaded-server-file' : removeSelector}" data-index="${index}" data-id="${rowId}">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                `);

                                fileList.append(fileItem);
                            });
                        }

                        function updateInput() {
                            const dt = new DataTransfer();
                            selectedFiles.forEach(file => {
                                if (file.type !== 'application/octet-stream-server') {
                                    dt.items.add(file);
                                }
                            });
                            fileInput[0].files = dt.files;
                        }

                        fileInput.on('change', function(e) {
                            const files = Array.from(e.target.files);
                            selectedFiles = [...selectedFiles, ...files];
                            updatePreview();
                            updateInput();
                        });

                        return {
                            removeFile: function(index) {
                                selectedFiles.splice(index, 1);
                                updatePreview();
                                updateInput();
                            },
                            addFile: function(file) {
                                selectedFiles.push(file);
                                updatePreview();
                            },
                            clearPrevious: function() {
                                selectedFiles = [];
                                updatePreview();
                            }
                        };
                    }

                    addFormHandler = createFileHandler('#file', '#file-preview', '#file-list', 'remove-file');
                    editFormHandler = createFileHandler('#edit-file', '#edit-file-preview', '#edit-file-list',
                        'remove-edit-file');

                    $(document).on('click', '.remove-file', function() {
                        const index = $(this).data('index');
                        addFormHandler.removeFile(index);
                    });

                    $(document).on('click', '.remove-edit-file', function() {
                        const index = $(this).data('index');
                        editFormHandler.removeFile(index);
                    });

                    @can('report.edit')
                        $(document).on('click', '.uploaded-server-file', function() {
                            const index = $(this).data('index');
                            const button = $(this);

                            Swal.fire({
                                title: 'Are you sure?',
                                text: "This file already uploaded on server, this action will remove the file from the server.",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: "Yes, delete it!",
                                cancelButtonText: "Cancel",
                                customClass: {
                                    confirmButton: "btn btn-label-danger",
                                    cancelButton: "btn btn-primary",
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        title: "Deleting File...",
                                        text: "Please wait while the file is being deleted.",
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        },
                                    });

                                    button.prop('disabled', true);
                                    button.find('i').removeClass('bx-trash').addClass(
                                        'bx-loader-alt bx-spin');
                                    const rowId = button.data('id');

                                    $.ajax({
                                        url: "{{ route('dashboard.report.attachment.destroy', ':id') }}"
                                            .replace(':id', rowId),
                                        type: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            Swal.fire({
                                                title: 'Deleted!',
                                                text: 'File has been deleted successfully.',
                                                icon: 'success',
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                            editFormHandler.removeFile(index);
                                        },
                                        error: function(xhr) {
                                            button.prop('disabled', false);
                                            button.find('i').removeClass(
                                                'bx-loader-alt bx-spin').addClass(
                                                'bx-trash');

                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Failed to delete file. Please try again.',
                                                icon: 'error'
                                            });
                                        }
                                    });
                                }
                            });
                        });
                    @endcan
                });
            </script>
        @endcanany
        @canany(['report.create', 'report.edit', 'report.show', 'report.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const validStatuses = @json(array_map(fn($status) => $status->value, $statuses));

                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#report-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.report.update', ':id') }}",
                            destroy: "{{ route('dashboard.report.destroy', ':id') }}"
                        },
                        offcanvas: {
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    title: '#edit-title',
                                    location: '#edit-location',
                                    content: '#edit-content',
                                    status: '#edit-status',
                                    category_id: '#edit-category_id',
                                    'new-category': '#edit-new-category',
                                    attachments: '#edit-file',
                                },
                                fieldMapBehavior: {
                                    status: function(el, data, rowData) {
                                        const status = validStatuses.find(
                                            status => status === data.toLowerCase()
                                        );

                                        el.val(status ? status : null).trigger('change');
                                    },
                                    category_id: function(el, data, rowData) {
                                        const isOther = !isNaN(data) ? false : data === 'other';
                                        el.val(isOther ? 'other' : data).trigger('change');
                                    },
                                    attachments: function(el, data, rowData) {
                                        if (data && data.length) {
                                            editFormHandler.clearPrevious();
                                            data.forEach(element => {
                                                editFormHandler.addFile(new File([new ArrayBuffer(
                                                        parseInt(element.file_size || 0))],
                                                    element.file_name + '@' + element.id, {
                                                        type: 'application/octet-stream-server',
                                                        lastModified: new Date().getTime(),
                                                    }));
                                            });
                                        }
                                    },
                                }
                            }
                        },
                        onSuccess: function(response, formId) {
                            const getPluralText = (count, singular, plural) => {
                                return "&nbsp" + (count === 1 ? singular : plural);
                            };

                            if (formId === '#form-add-new-record') {
                                addFormHandler.clearPrevious();
                                $('#form-add-new-record')[0].reset();
                            }

                            const totalReports = response.data.totalReports || 0;
                            const reportsProcessed = response.data.totalReportsProcessed || 0;
                            const reportsDeclined = response.data.totalReportsDeclined || 0;
                            const reportsSolved = response.data.totalReportsSolved || 0;
                            $('#total-report').text(totalReports);
                            $('#text-report').html(getPluralText(totalReports, 'Report', 'Reports'));
                            $('#reports-processed').text(reportsProcessed);
                            $('#text-reports-processed').html(getPluralText(reportsProcessed, 'Report', 'Reports'));
                            $('#reports-declined').text(reportsDeclined);
                            $('#text-reports-declined').html(getPluralText(reportsDeclined, 'Report', 'Reports'));
                            $('#reports-solved').text(reportsSolved);
                            $('#text-reports-solved').html(getPluralText(reportsSolved, 'Report', 'Reports'));

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
