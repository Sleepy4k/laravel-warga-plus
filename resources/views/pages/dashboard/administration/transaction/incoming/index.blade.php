<x-layouts.dashboard title="Incoming Letter">
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
        @canany(['letter_transaction.incoming.create', 'letter_transaction.incoming.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}" @cspNonce />
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('letter_transaction.incoming.create')
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-new-record"
                    aria-controls="add-new-record">Add Letter
                </button>
            @endcan
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="#" class="row g-3" id="search-form">
                    <div class="col-sm-10" id="search-input-group">
                        <div class="input-group input-group-merge">
                            <input type="text" id="search"
                                class="form-control @error('search') is-invalid @enderror" name="search"
                                placeholder="Search by reference number, agenda number, or sender"
                                aria-label="Search by value" aria-describedby="search" />
                        </div>
                    </div>
                    <div class="col-sm-2 d-flex align-items-end" id="search-button-group">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-search me-1"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @forelse ($data as $letter)
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between flex-column flex-sm-row">
                        <div class="card-title">
                            <h5 class="text-nowrap mb-0 fw-bold text-primary">{{ $letter->reference_number }}</h5>
                            <small class="text-primary">
                                {{ ucwords($letter->from) }}
                                <span class="text-secondary">|</span>
                                <span class="text-secondary">Agenda Number:</span>
                                {{ $letter->agenda_number }}
                                <span class="text-secondary">|</span>
                                {{ ucfirst($letter->classification?->name) }}
                            </small>
                        </div>
                        <div class="card-title d-flex flex-row">
                            <div class="d-inline-block mx-2 text-end text-primary">
                                <small class="d-block text-secondary">Letter Date</small>
                                {{ $letter->letter_date->format('D, j F Y') }}
                            </div>
                            @can('letter_transaction.disposition.index')
                                <div class="mx-3">
                                    <a href="{{ route('dashboard.letter.transaction.disposition.index', $letter) }}"
                                        class="btn btn-primary btn">Letter Disposition&nbsp;
                                        @if ($letter->dispositions->count() > 0)
                                            <span>({{ $letter->dispositions->count() }})</span>
                                        @endif
                                    </a>
                                </div>
                            @endcan
                            <div class="dropdown d-inline-block">
                                <button class="btn p-0" type="button"
                                    id="dropdown-{{ $letter->type }}-{{ $letter->id }}" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base icon-md mt-2 bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="dropdown-{{ $letter->type }}-{{ $letter->id }}">
                                    <button class="dropdown-item d-flex align-items-center gap-2 show-record"
                                        data-id="{{ $letter->id }}" data-target="#show-record">
                                        <i class="bx bx-info-circle text-primary"></i> Detail
                                    </button>
                                    <button class="dropdown-item d-flex align-items-center gap-2 edit-record"
                                        data-id="{{ $letter->id }}" data-target="#edit-record">
                                        <i class="bx bx-edit-alt text-warning"></i> Edit
                                    </button>
                                    <button
                                        class="dropdown-item d-flex align-items-center gap-2 text-danger delete-record"
                                        data-id="{{ $letter->id }}" data-target="#delete-record">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <hr>
                    <p>{{ $letter->description }}</p>
                    <div class="d-flex justify-content-between flex-column flex-sm-row">
                        <small class="text-secondary">{{ $letter->note }}</small>
                        @if (count($letter->attachments))
                            <div>
                                @foreach ($letter->attachments as $attachment)
                                    <a href="{{ $attachment->path }}" target="_blank">
                                        <i class="bx bxs-file display-6 cursor-pointer text-primary"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-3">
                        <i class="bx bx-file-blank display-1 text-muted"></i>
                    </div>
                    <h5 class="card-title text-muted mb-2">No Letters Found</h5>
                    <p class="card-text text-muted mb-4">
                        There are no incoming letters to display at the moment.
                    </p>
                    @can('letter_transaction.incoming.create')
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#add-new-record" aria-controls="add-new-record" id="add-new-record-btn-card">
                            <i class="bx bx-plus me-1"></i>Add Your First Letter
                        </button>
                    @endcan
                </div>
            </div>
        @endforelse

        {!! $data->links() !!}

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="letter_transaction.incoming.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.letter.transaction.incoming.store') }}" enctype="multipart/form-data">
                    <div class="col-sm-6">
                        <label class="form-label" for="reference_number">Reference Number</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="reference_number"
                                class="form-control @error('reference_number') is-invalid @enderror"
                                name="reference_number" placeholder="Reference Number" aria-label="Reference Number"
                                aria-describedby="reference_number" value="{{ old('reference_number') }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="agenda_number">Agenda Number</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="agenda_number"
                                class="form-control @error('agenda_number') is-invalid @enderror" name="agenda_number"
                                placeholder="Agenda Number" aria-label="Agenda Number"
                                aria-describedby="agenda_number" value="{{ old('agenda_number') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="from">From</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="from"
                                class="form-control @error('from') is-invalid @enderror" name="from"
                                placeholder="From" aria-label="From" aria-describedby="from"
                                value="{{ old('from') }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="letter_date">Letter Date</label>
                        <div>
                            <input type="text" id="letter_date"
                                class="form-control @error('letter_date') is-invalid @enderror" name="letter_date"
                                placeholder="Letter Date" aria-label="Letter Date" aria-describedby="letter_date"
                                value="{{ old('letter_date') }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="received_date">Received Date</label>
                        <div>
                            <input type="text" id="received_date"
                                class="form-control @error('received_date') is-invalid @enderror" name="received_date"
                                placeholder="Received Date" aria-label="Received Date"
                                aria-describedby="received_date" value="{{ old('received_date') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="classification_id">Classification Code</label>
                        <div class="input-group input-group-merge">
                            <select id="classification_id"
                                class="form-select select2 @error('classification_id') is-invalid @enderror"
                                name="classification_id">
                                @foreach ($classifications as $classification)
                                    <option value="{{ $classification->id }}" @selected(old('classification_id') == $classification->id)>
                                        {{ $classification->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="description">Description</label>
                        <div>
                            <textarea id="description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror" placeholder="Deskripsi surat"
                                aria-label="Deskripsi surat" aria-describedby="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="note">Note</label>
                        <div>
                            <textarea id="note" name="note" class="form-control dt-note @error('note') is-invalid @enderror"
                                placeholder="Catatan" aria-label="Catatan" aria-describedby="note">{{ old('note') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="file">Add Files</label>
                        <div class="input-group input-group-merge">
                            <input type="file" id="file"
                                class="form-control dt-file @error('file') is-invalid @enderror" name="file[]"
                                multiple aria-label="Files" aria-describedby="file"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
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

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="letter_transaction.incoming.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2" id="show-record-content">
                    <div class="col-sm-6">
                        <label class="form-label" for="show-reference_number">Reference Number</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-reference_number" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="show-agenda_number">Agenda Number</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-agenda_number" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-from">From</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-from" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="show-letter_date">Letter Date</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-letter_date" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="show-received_date">Received Date</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-received_date" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-classification_id">Classification</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-classification_id" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-description">Description</label>
                        <div class="input-group input-group-merge">
                            <textarea id="show-description" class="form-control" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-note">Note</label>
                        <div class="input-group input-group-merge">
                            <textarea id="show-note" class="form-control" readonly></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12" id="show-attachments-group" style="display: none;">
                        <label class="form-label">Attachments</label>
                        <div id="show-attachments"></div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="letter_transaction.incoming.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-reference_number">Reference Number</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-reference_number"
                                class="form-control @error('reference_number') is-invalid @enderror"
                                name="reference_number" placeholder="Reference Number" aria-label="Reference Number"
                                aria-describedby="reference_number" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-agenda_number">Agenda Number</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-agenda_number"
                                class="form-control @error('agenda_number') is-invalid @enderror"
                                name="agenda_number" placeholder="Agenda Number" aria-label="Agenda Number"
                                aria-describedby="agenda_number" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-from">From</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-from"
                                class="form-control @error('from') is-invalid @enderror" name="from"
                                placeholder="From" aria-label="From" aria-describedby="from" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-letter_date">Letter Date</label>
                        <div>
                            <input type="text" id="edit-letter_date"
                                class="form-control @error('letter_date') is-invalid @enderror" name="letter_date"
                                placeholder="Letter Date" aria-label="Letter Date" aria-describedby="letter_date" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-received_date">Received Date</label>
                        <div>
                            <input type="text" id="edit-received_date"
                                class="form-control @error('received_date') is-invalid @enderror"
                                name="received_date" placeholder="Received Date" aria-label="Received Date"
                                aria-describedby="received_date" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-classification_id">Classification Code</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-classification_id"
                                class="form-select select2 @error('classification_id') is-invalid @enderror"
                                name="classification_id">
                                @foreach ($classifications as $classification)
                                    <option value="{{ $classification->id }}">
                                        {{ $classification->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-description">Description</label>
                        <div>
                            <textarea id="edit-description" name="description"
                                class="form-control dt-description @error('description') is-invalid @enderror" placeholder="Deskripsi surat"
                                aria-label="Deskripsi surat" aria-describedby="description"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-note">Note</label>
                        <div>
                            <textarea id="edit-note" name="note" class="form-control dt-note @error('note') is-invalid @enderror"
                                placeholder="Catatan" aria-label="Catatan" aria-describedby="note"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-file">Add Files</label>
                        <div class="input-group input-group-merge">
                            <input type="file" id="edit-file"
                                class="form-control dt-file @error('file') is-invalid @enderror" name="file[]"
                                multiple aria-label="Files" aria-describedby="file"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
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
        @canany(['letter_transaction.incoming.create', 'letter_transaction.incoming.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}" @cspNonce></script>
            <script src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        <script @cspNonce>
            $(document).ready(function() {
                const baseUrl = "{{ route('dashboard.letter.transaction.incoming.index') }}";

                $('#search-form').on('submit', function(e) {
                    e.preventDefault();

                    const searchQuery = $('#search').val().trim();
                    if (!searchQuery) return;

                    const newUrl = searchQuery ? `${baseUrl}?search=${encodeURIComponent(searchQuery)}` :
                        baseUrl;
                    window.location.href = newUrl;
                });

                const currentUrl = new URL(window.location.href);
                const searchParams = new URLSearchParams(currentUrl.search);

                if (searchParams.has('search')) {
                    const searchValue = searchParams.get('search');
                    $('#search').val(searchValue);
                    $('#search-input-group').removeClass('col-sm-10').addClass('col-sm-8');
                    $('#search-button-group').removeClass('col-sm-2').addClass('col-sm-4');
                    $('#search-button-group').append(`
                        <button type="button" class="btn btn-secondary ms-2 w-100" id="reset-search">
                            <i class="bx bx-trash me-1"></i> Reset
                        </button>
                    `);

                    $('#reset-search').on('click', function() {
                        window.location.href = baseUrl;
                    });
                }
            });
        </script>
        @canany(['letter_transaction.incoming.create', 'letter_transaction.incoming.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    const currentUrl = new URL(window.location.href);
                    const searchParams = new URLSearchParams(currentUrl.search);
                    const createBtnCard = $('#add-new-record-btn-card');

                    if (searchParams.has('search')) {
                        createBtnCard.hide();
                    } else {
                        createBtnCard.show();
                    }
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#description'));
                    autosize($('#note'));
                    autosize($('#show-description'));
                    autosize($('#show-note'));
                    autosize($('#edit-description'));
                    autosize($('#edit-note'));
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    $('#letter_date, #edit-letter_date, #received_date, #edit-received_date').each(function() {
                        if ($(this).length) {
                            $(this).flatpickr({
                                enableTime: false,
                                dateFormat: 'Y-m-d',
                                allowInput: false,
                                defaultDate: 'today',
                                altInput: true,
                                altFormat: 'j F Y'
                            });
                        }
                    });
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

                    @can('letter_transaction.incoming.edit')
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
                                        url: '{{ route('dashboard.letter.transaction.attachment.destroy', ':id') }}'
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
        @canany(['letter_transaction.incoming.create', 'letter_transaction.incoming.edit',
            'letter_transaction.incoming.show', 'letter_transaction.incoming.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        pageData: @json($data->items()),
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.letter.transaction.incoming.update', ':id') }}",
                            destroy: "{{ route('dashboard.letter.transaction.incoming.destroy', ':id') }}"
                        },
                        findFunction: (id) => {
                            return crudManager.pageData.find(item => item.id === id);
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    reference_number: '#show-reference_number',
                                    agenda_number: '#show-agenda_number',
                                    from: '#show-from',
                                    letter_date: '#show-letter_date',
                                    received_date: '#show-received_date',
                                    classification_id: '#show-classification_id',
                                    description: '#show-description',
                                    note: '#show-note',
                                    file: '#show-attachments-group',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    letter_date: function(el, data, rowData) {
                                        el.val(new Date(data).toLocaleDateString('en-GB', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric'
                                        }));
                                    },
                                    received_date: function(el, data, rowData) {
                                        el.val(new Date(data).toLocaleDateString('en-GB', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric'
                                        }));
                                    },
                                    classification_id: function(el, data, rowData) {
                                        el.val(rowData.classification.name).trigger('change');
                                    },
                                    file: function(el, data, rowData) {
                                        if (rowData.attachments && rowData.attachments.length) {
                                            let attachmentsHtml = '';
                                            rowData.attachments.forEach(attachment => {
                                                attachmentsHtml += `
                                                    <a href="${attachment.path}" target="_blank" class="me-2">
                                                        <i class="bx bxs-file display-6 cursor-pointer text-primary"></i>
                                                    </a>
                                                `;
                                            });
                                            $('#show-attachments').html(attachmentsHtml);
                                            el.show();
                                        } else {
                                            $('#show-attachments').html('');
                                            el.hide();
                                        }
                                    },
                                }
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    reference_number: '#edit-reference_number',
                                    agenda_number: '#edit-agenda_number',
                                    from: '#edit-from',
                                    letter_date: '#edit-letter_date',
                                    received_date: '#edit-received_date',
                                    classification_id: '#edit-classification_id',
                                    description: '#edit-description',
                                    note: '#edit-note',
                                    attachments: '#edit-file',
                                },
                                fieldMapBehavior: {
                                    letter_date: function(el, data, rowData) {
                                        el.flatpickr({
                                            dateFormat: 'Y-m-d',
                                            defaultDate: new Date(data),
                                            altInput: true,
                                            altFormat: 'j F Y'
                                        });
                                    },
                                    received_date: function(el, data, rowData) {
                                        el.flatpickr({
                                            dateFormat: 'Y-m-d',
                                            defaultDate: new Date(data),
                                            altInput: true,
                                            altFormat: 'j F Y'
                                        });
                                    },
                                    classification_id: function(el, data, rowData) {
                                        el.val(data).trigger('change');
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
                                    }
                                }
                            }
                        },
                        onSuccess: function(response, formId) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: response.message || "Operation completed successfully.",
                                    showConfirmButton: true,
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
