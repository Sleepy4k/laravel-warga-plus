<x-layouts.dashboard title="Sitemap">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        @canany(['misc.sitemap.create', 'misc.sitemap.edit'])
            <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}" @cspNonce />
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcanany
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            <div class="d-flex justify-content-between align-items-center">
                @can('misc.sitemap.create')
                    <button class="btn btn-primary me-3" type="button" id="btn-generate-sitemap">
                        Generate Sitemap
                    </button>
                @endcan
                @can('misc.sitemap.store')
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#add-new-record">
                        Add Data
                    </button>
                @endcan
            </div>
        </div>

        <p class="mb-4">
            This page allows you to create a new sitemap for your website.<br />
            The sitemap will include all relevant URLs for search engines to index.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="add-new-record" canvasPermission="article.create">
            <x-dashboard.canvas.header title="New Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="POST"
                    action="{{ route('dashboard.misc.sitemap.store') }}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <label class="form-label" for="url">URL</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="url"
                                class="form-control dt-url @error('url') is-invalid @enderror" name="url"
                                placeholder="https://example.com" aria-label="https://example.com"
                                aria-describedby="url" value="{{ old('url') }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="flatpickr-datetime">Last Modified</label>
                        <div>
                            <input type="text" id="flatpickr-datetime"
                                class="form-control @error('last_modified') is-invalid @enderror"
                                placeholder="YYYY-MM-DD HH:MM" aria-label="YYYY-MM-DD HH:MM" aria-label="Last Modified"
                                aria-describedby="last_modified" name="last_modified" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="change_frequency">Change Frequency</label>
                        <select id="change_frequency"
                            class="form-select select2 @error('change_frequency') is-invalid @enderror"
                            name="change_frequency">
                            @foreach ($changeFrequencies as $frequency)
                                <option value="{{ $frequency }}"
                                    {{ old('change_frequency') == $frequency ? 'selected' : '' }}>
                                    {{ ucfirst($frequency->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="priority">Priority</label>
                        <input type="number" id="priority"
                            class="form-control dt-priority @error('priority') is-invalid @enderror" name="priority"
                            placeholder="0.5" aria-label="0.5" aria-describedby="priority"
                            value="{{ old('priority', 0.5) }}" step="0.01" min="0" max="1" />
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" class="form-select select2 @error('status') is-invalid @enderror"
                            name="status">
                            <option value="active" @selected(old('status') == 'active')>Active</option>
                            <option value="inactive" @selected(old('status') == 'inactive')>Inactive
                            </option>
                        </select>
                    </div>
                </form>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="create" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="article.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2">
                    <div class="col-sm-12">
                        <label class="form-label" for="show-url">URL</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-url" class="form-control dt-url"
                                placeholder="https://example.com/membuat-inkubasi-bisnis-berbasis-teknologi"
                                aria-label="https://example.com/membuat-inkubasi-bisnis-berbasis-teknologi"
                                aria-describedby="show-url" readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-last-modified">Last Modified</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-last-modified" class="form-control dt-last_modified"
                                placeholder="Last Modified" aria-label="Last Modified"
                                aria-describedby="show-last-modified" readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-change-frequency">Change Frequency</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-change-frequency" class="form-control"
                                placeholder="Change Frequency" aria-label="Change Frequency"
                                aria-describedby="show-change-frequency" readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-priority">Priority</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-priority" class="form-control dt-priority"
                                placeholder="0.5" aria-label="0.5" aria-describedby="show-priority" readonly />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-status">Status</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-status" class="form-control dt-status"
                                placeholder="Active" aria-label="Active" aria-describedby="show-status" readonly />
                        </div>
                    </div>
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>

        <x-dashboard.canvas.wrapper canvasId="edit-record" canvasPermission="article.edit">
            <x-dashboard.canvas.header title="Edit Record" />
            <x-dashboard.canvas.body>
                <form class="add-new-record pt-0 row g-2" id="form-edit-record" method="PUT" action="#"
                    enctype="multipart/form-data">
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-url">URL</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="edit-url"
                                class="form-control dt-url @error('url') is-invalid @enderror"
                                placeholder="https://example.com" aria-label="https://example.com"
                                aria-describedby="edit-url" name="url" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="flatpickr-datetime-edit">Last Modified</label>
                        <div>
                            <input type="text" id="flatpickr-datetime-edit"
                                class="form-control dt-last_modified @error('last_modified') is-invalid @enderror"
                                placeholder="Last Modified" aria-label="Last Modified"
                                aria-describedby="flatpickr-datetime-edit" name="last_modified" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-change_frequency">Change Frequency</label>
                        <select id="edit-change_frequency"
                            class="form-select select2 @error('change_frequency') is-invalid @enderror"
                            name="change_frequency">
                            @foreach ($changeFrequencies as $frequency)
                                <option value="{{ $frequency }}">{{ ucfirst($frequency->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-priority">Priority</label>
                        <input type="number" id="edit-priority"
                            class="form-control dt-priority @error('priority') is-invalid @enderror" placeholder="0.5"
                            aria-label="0.5" aria-describedby="edit-priority" name="priority" step="0.01"
                            min="0" max="1" />
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="edit-status">Status</label>
                        <select id="edit-status" class="form-select select2 @error('status') is-invalid @enderror"
                            name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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
        @canany(['misc.sitemap.create', 'misc.sitemap.edit'])
            <script type="text/javascript" src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}" @cspNonce>
            </script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce>
            </script>
        @endcanany
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @can('misc.sitemap.create')
            <script @cspNonce>
                $(document).on('click', '#btn-generate-sitemap', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will generate a new sitemap for your website.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, generate!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Generating Sitemap...',
                                text: 'Please wait while the sitemap is being generated.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                url: '{{ route('dashboard.misc.sitemap.create') }}',
                                type: 'GET',
                                success: function(response) {
                                    Swal.fire(
                                        'Success!',
                                        'Sitemap has been generated successfully.',
                                        'success'
                                    );
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        xhr.responseJSON?.message || 'Failed to generate sitemap.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });
            </script>
        @endcan
        @canany(['misc.sitemap.create', 'misc.sitemap.edit'])
            <script @cspNonce>
                $(document).ready(function() {
                    $('#flatpickr-datetime, #flatpickr-datetime-edit').each(function() {
                        if ($(this).length) {
                            $(this).flatpickr({
                                enableTime: true,
                                dateFormat: 'Y-m-d H:i',
                                time_24hr: true,
                                allowInput: false,
                                defaultDate: 'today',
                                minuteIncrement: 1,
                                altInput: true,
                                altFormat: 'j F, Y H:i'
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
        @canany(['misc.sitemap.create', 'misc.sitemap.store', 'misc.sitemap.show', 'misc.sitemap.edit',
            'misc.sitemap.delete'])
            <script src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#sitemap-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "{{ route('dashboard.misc.sitemap.update', ':id') }}",
                            destroy: "{{ route('dashboard.misc.sitemap.destroy', ':id') }}"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    url: '#show-url',
                                    last_modified: '#show-last-modified',
                                    change_frequency: '#show-change-frequency',
                                    priority: '#show-priority',
                                    status: '#show-status',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    status: function(el, data, rowData) {
                                        const value = rowData.status_plain;
                                        el.val(value.charAt(0).toUpperCase() + value.slice(1));
                                    },
                                }
                            },
                            edit: {
                                id: '#edit-record',
                                fieldMap: {
                                    url: '#edit-url',
                                    last_modified: '#flatpickr-datetime-edit',
                                    change_frequency: '#edit-change_frequency',
                                    priority: '#edit-priority',
                                    status: '#edit-status'
                                },
                                fieldMapBehavior: {
                                    last_modified: function(el, data, rowData) {
                                        el.flatpickr({
                                            enableTime: true,
                                            dateFormat: 'Y-m-d H:i',
                                            time_24hr: true,
                                            allowInput: false,
                                            defaultDate: new Date(rowData.last_modified),
                                            minuteIncrement: 1,
                                            altInput: true,
                                            altFormat: 'j F, Y H:i'
                                        });
                                    },
                                    change_frequency: function(el, data, rowData) {
                                        el.val(rowData.change_frequency_plain).trigger('change');
                                    },
                                    status: function(el, data, rowData) {
                                        el.val(rowData.status_plain).trigger('change');
                                    },
                                }
                            }
                        }
                    });
                });
            </script>
        @endcanany
    @endPushOnce
</x-layouts.dashboard>
