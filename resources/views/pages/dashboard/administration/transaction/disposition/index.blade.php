<x-layouts.dashboard title="Disposition Incoming Letter">
    @pushOnce('plugin-styles')
        @canany(['letter_transaction.disposition.create', 'letter_transaction.disposition.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}" @cspNonce />
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('dashboard.letter.transaction.incoming.index') }}" class="btn btn-primary me-3">Back to
                    List</a>
                @can('letter_transaction.disposition.create')
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#add-new-record" aria-controls="add-new-record">Add Disposition
                    </button>
                @endcan
            </div>
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

        @forelse ($data as $disposition)
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between flex-column flex-sm-row">
                        <div class="card-title">
                            <h5 class="text-nowrap mb-0 fw-bold text-primary">{{ $disposition->status?->status }}</h5>
                            <small class="text-primary">{{ ucfirst($disposition->to) }}</small>
                        </div>
                        <div class="card-title d-flex flex-row">
                            <div class="d-inline-block mx-2 text-end text-primary">
                                <small class="d-block text-secondary">Letter Date</small>
                                {{ $disposition->due_date->format('D, j F Y') }}
                            </div>
                            <div class="dropdown d-inline-block">
                                <button class="btn p-0" type="button" id="dropdown-{{ $disposition->id }}"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base icon-md mt-2 bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="dropdown-{{ $disposition->id }}">
                                    <button class="dropdown-item d-flex align-items-center gap-2 edit-record"
                                        data-id="{{ $disposition->id }}" data-target="#edit-record">
                                        <i class="bx bx-edit-alt text-warning"></i> Edit
                                    </button>
                                    <button
                                        class="dropdown-item d-flex align-items-center gap-2 text-danger delete-record"
                                        data-id="{{ $disposition->id }}" data-target="#delete-record">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <hr>
                    <p>{{ $disposition->content }}</p>
                    <small class="text-secondary">{{ $disposition->note }}</small>
                </div>
            </div>
        @empty
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-3">
                        <i class="bx bx-file-blank display-1 text-muted"></i>
                    </div>
                    <h5 class="card-title text-muted mb-2">No Dispositions Created</h5>
                    <p class="card-text text-muted mb-4">
                        There are no dispositions created for this letter.
                    </p>
                    @can('letter_transaction.disposition.create')
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#add-new-record" aria-controls="add-new-record" id="add-new-record-btn-card">
                            <i class="bx bx-plus me-1"></i>Add Your First Disposition
                        </button>
                    @endcan
                </div>
            </div>
        @endforelse

        {!! $data->appends(['search' => $search])->links() !!}

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="letter_transaction.disposition.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.letter.transaction.disposition.store', $letterUid) }}">
                    <div class="col-sm-12">
                        <label class="form-label" for="to">Receiver</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="to" class="form-control @error('to') is-invalid @enderror"
                                name="to" placeholder="Receiver" aria-label="Receiver" aria-describedby="to"
                                value="{{ old('to') }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="due_date">Due Date</label>
                        <div>
                            <input type="text" id="due_date"
                                class="form-control @error('due_date') is-invalid @enderror" name="due_date"
                                placeholder="Due Date" aria-label="Due Date" aria-describedby="due_date"
                                value="{{ old('due_date') }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="letter_status_id">Status</label>
                        <div class="input-group input-group-merge">
                            <select id="letter_status_id"
                                class="form-select select2 @error('letter_status_id') is-invalid @enderror"
                                name="letter_status_id">
                                @foreach ($letterStatuses as $letterStatus)
                                    <option value="{{ $letterStatus->id }}" @selected(old('letter_status_id') == $letterStatus->id)>
                                        {{ $letterStatus->status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="content">Content</label>
                        <div>
                            <textarea id="content" name="content" class="form-control dt-content @error('content') is-invalid @enderror"
                                placeholder="Content" aria-label="Content" aria-describedby="content">{{ old('content') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="note">Note</label>
                        <div>
                            <textarea id="note" name="note" class="form-control dt-note @error('note') is-invalid @enderror"
                                placeholder="Note" aria-label="Note" aria-describedby="note">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="letter_transaction.disposition.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-to">Receiver</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-to"
                                class="form-control @error('to') is-invalid @enderror" name="to"
                                placeholder="To" aria-label="To" aria-describedby="to" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-due_date">Due Date</label>
                        <div>
                            <input type="text" id="edit-due_date"
                                class="form-control @error('due_date') is-invalid @enderror" name="due_date"
                                placeholder="Due Date" aria-label="Due Date" aria-describedby="due_date" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="edit-letter_status_id">Status</label>
                        <div class="input-group input-group-merge">
                            <select id="edit-letter_status_id"
                                class="form-select select2 @error('letter_status_id') is-invalid @enderror"
                                name="letter_status_id">
                                @foreach ($letterStatuses as $letterStatus)
                                    <option value="{{ $letterStatus->id }}">
                                        {{ $letterStatus->status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-content">Content</label>
                        <div>
                            <textarea id="edit-content" name="content" class="form-control dt-content @error('content') is-invalid @enderror"
                                placeholder="Content" aria-label="Content" aria-describedby="content"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-note">Note</label>
                        <div>
                            <textarea id="edit-note" name="note" class="form-control dt-note @error('note') is-invalid @enderror"
                                placeholder="Note" aria-label="Note" aria-describedby="note"></textarea>
                        </div>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="edit" />
        </x-dashboard.canvas.wrapper>

        <form class="d-inline" id="form-delete-record" method="DELETE" action="#"></form>
    </div>

    @pushOnce('plugin-scripts')
        @canany(['letter_transaction.disposition.create', 'letter_transaction.disposition.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}" @cspNonce></script>
            <script src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        <script @cspNonce>
            $(document).ready(function() {
                const baseUrl = "{{ route('dashboard.letter.transaction.disposition.index', $letterUid) }}";

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
        @canany(['letter_transaction.disposition.create', 'letter_transaction.disposition.edit'])
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
                    autosize($('#content'));
                    autosize($('#note'));
                    autosize($('#edit-content'));
                    autosize($('#edit-note'));
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    $('#due_date, #edit-due_date').each(function() {
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
        @endcanany
        @canany(['letter_transaction.disposition.create', 'letter_transaction.disposition.edit',
            'letter_transaction.disposition.show', 'letter_transaction.disposition.delete'])
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        pageData: @json($data->items()),
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.letter.transaction.disposition.update', ['letter' => $letterUid, 'disposition' => ':id']) }}",
                            destroy: "{{ route('dashboard.letter.transaction.disposition.destroy', ['letter' => $letterUid, 'disposition' => ':id']) }}"
                        },
                        findFunction: (id) => {
                            return crudManager.pageData.find(item => item.id === id);
                        },
                        offcanvas: {
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    to: '#edit-to',
                                    due_date: '#edit-due_date',
                                    status: '#edit-status',
                                    content: '#edit-content',
                                    note: '#edit-note',
                                },
                                fieldMapBehavior: {
                                    due_date: function(el, data, rowData) {
                                        el.flatpickr({
                                            dateFormat: 'Y-m-d',
                                            defaultDate: new Date(data),
                                            altInput: true,
                                            altFormat: 'j F Y'
                                        });
                                    },
                                    status: function(el, data, rowData) {
                                        el.val(data).trigger('change');
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
