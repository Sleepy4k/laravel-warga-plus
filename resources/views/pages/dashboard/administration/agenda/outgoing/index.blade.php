<x-layouts.dashboard title="Outgoing Agenda">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="#" class="row g-3" id="filtering-form">
                    <div class="col-sm-3">
                        <div class="input-group input-group-merge">
                            <select id="column" class="form-select select2" name="column">
                                @foreach ($columns as $column)
                                    <option value="{{ $column }}" @selected(old('column') == $column)>
                                        {{ ucwords(str_replace('_', ' ', $column)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <input type="text" id="since" class="form-control" name="since" placeholder="Since"
                                aria-label="Since" aria-describedby="since" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div>
                            <input type="text" id="until" class="form-control" name="until" placeholder="Until"
                                aria-label="Until" aria-describedby="until" />
                        </div>
                    </div>
                    <div class="col-sm-3 d-flex align-items-end" id="filtering-button-group">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-search me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/flatpickr/flatpickr.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        <script @cspNonce>
            $(document).ready(function() {
                const select2Elements = $('.select2');
                if (select2Elements.length) {
                    select2Elements.each(function() {
                        $(this).select2({
                            dropdownParent: $(this).closest('.card-body'),
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
                $('#since, #until').each(function() {
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
                const baseUrl = "{{ route('dashboard.letter.agenda.outgoing.index') }}";

                $('#filtering-form').on('submit', function(e) {
                    e.preventDefault();

                    const sinceQuery = $('#since').val().trim();
                    const untilQuery = $('#until').val().trim();
                    const columnQuery = $('#column').val().trim();
                    if (!sinceQuery && !untilQuery && !columnQuery) return;

                    window.location.href =
                        `${baseUrl}?since=${encodeURIComponent(sinceQuery)}&until=${encodeURIComponent(untilQuery)}&column=${encodeURIComponent(columnQuery)}`;
                });

                const currentUrl = new URL(window.location.href);
                const searchParams = new URLSearchParams(currentUrl.search);

                if (searchParams.has('since') || searchParams.has('until') || searchParams.has('column')) {
                    const columnValue = searchParams.get('column');
                    if (columnValue) $('#column').val(columnValue).trigger('change');

                    const sinceValue = searchParams.get('since');
                    if (sinceValue) {
                        $('#since').flatpickr({
                            dateFormat: 'Y-m-d',
                            defaultDate: new Date(sinceValue),
                            altInput: true,
                            altFormat: 'j F Y'
                        });
                    }

                    const untilValue = searchParams.get('until');
                    if (untilValue) {
                        $('#until').flatpickr({
                            dateFormat: 'Y-m-d',
                            defaultDate: new Date(untilValue),
                            altInput: true,
                            altFormat: 'j F Y'
                        });
                    }

                    $('#filtering-button-group').append(`
                        <button type="button" class="btn btn-secondary ms-2 w-100" id="reset-search">
                            <i class="bx bx-trash me-1"></i>Reset
                        </button>
                    `);

                    $('#reset-search').on('click', function() {
                        window.location.href = baseUrl;
                    });
                }
            });
        </script>
    @endPushOnce
</x-layouts.dashboard>
