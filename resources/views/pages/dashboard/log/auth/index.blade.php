<x-layouts.dashboard title="Auth Logs">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('log.auth.store')
                <button class="btn btn-primary" type="button" id="btn-clear-logs" @disabled($logCount === 0)>
                    Clear Auth Logs
                </button>
            @endcan
        </div>

        <p class="mb-4">
            Every time a user logs in or logs out, an entry is created in the auth log.<br />
            This log is useful for tracking user activity and identifying potential security issues.<br />
            You can clear the auth logs that more than {{ config('activitylog.delete_records_older_than_days', 60) }}
            days by clicking the button above.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

        <x-dashboard.canvas.wrapper canvasId="show-record" canvasPermission="log.auth.show">
            <x-dashboard.canvas.header title="Show Record" />
            <x-dashboard.canvas.body>
                <div class="row g-2" id="show-record-body">
                    <div class="col-sm-6">
                        <label class="form-label" for="show-name">Log Type</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-name" class="form-control dt-name" placeholder="Auth"
                                aria-label="Auth" aria-describedby="show-name" disabled />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="show-event">Event</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-event" class="form-control dt-event" placeholder="Login"
                                aria-label="Login" aria-describedby="show-event" disabled />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="show-description">Description</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-description" class="form-control dt-description"
                                placeholder="User Login" aria-label="User Login" aria-describedby="show-description"
                                disabled />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="show-causer">Causer Name</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="show-causer" class="form-control dt-causer" placeholder="John Doe"
                                aria-label="John Doe" aria-describedby="show-causer" disabled />
                        </div>
                    </div>
                    <input type="hidden" id="show-properties" class="form-control dt-properties" />
                </div>
            </x-dashboard.canvas.body>
            <x-dashboard.canvas.footer type="show" />
        </x-dashboard.canvas.wrapper>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @can('log.auth.store')
            <script @cspNonce>
                $(document).on('click', '#btn-clear-logs', function() {
                    const dataTable = $('#auth-table').DataTable();
                    if (dataTable.data().count() === 0) return;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will clear all auth logs older than {{ config('activitylog.delete_records_older_than_days', 60) }} days.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, clear logs!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Clearing Auth Logs...',
                                text: 'Please wait while the auth logs are being cleared.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                url: '{{ route('dashboard.log.auth.store') }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    log_name: 'auth',
                                },
                                success: function(response) {
                                    dataTable.ajax.reload(null, false);

                                    Swal.fire(
                                        'Cleared!',
                                        'Auth logs have been cleared.',
                                        'success'
                                    ).then(() => {
                                        if (dataTable.data().count() === 0) {
                                            $('#btn-clear-auth-logs').attr('disabled', true);
                                        }
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to clear auth logs. Please try again later.',
                                        'error'
                                    );
                                },
                            });
                        }
                    });
                });
            </script>
        @endcan
        @can('log.auth.show')
            <script type="text/javascript" src="{{ asset('js/handler/crud-manager.min.js') }}" @cspNonce></script>
            <script @cspNonce>
                $(document).ready(function() {
                    const crudManager = new CrudManager({
                        debug: "{{ config('app.debug') }}",
                        tableId: '#auth-table',
                        csrfToken: '{{ csrf_token() }}',
                        routes: {
                            update: "#",
                            destroy: "#"
                        },
                        offcanvas: {
                            show: {
                                id: '#show-record',
                                fieldMap: {
                                    log_name: '#show-name',
                                    event: '#show-event',
                                    description: '#show-description',
                                    causer: '#show-causer',
                                    properties: '#show-properties',
                                    created_at: '#show-created-at',
                                    updated_at: '#show-last-updated'
                                },
                                fieldMapBehavior: {
                                    properties: function(el, data, rowData) {
                                        const properties = data ? JSON.parse(data) : {};
                                        const canvasBody = el.closest('.offcanvas-body, .canvas-body');
                                        if (canvasBody.length === 0) return;

                                        canvasBody.find('.dt-properties-rendered').remove();

                                        const showRecordBody = canvasBody.find('#show-record-body');
                                        if (showRecordBody.length === 0) return;

                                        const propertyKeys = Object.keys(properties);

                                        if (propertyKeys.length > 0) {
                                            const fullWidthType = ['login_at', 'logout_at', 'registered_at',
                                                'verified_at'
                                            ];

                                            propertyKeys.sort((a, b) => {
                                                const aFullWidth = fullWidthType.includes(a) ? 1 : 0;
                                                const bFullWidth = fullWidthType.includes(b) ? 1 : 0;
                                                return aFullWidth - bFullWidth;
                                            });

                                            propertyKeys.forEach(key => {
                                                if (key === 'user_agent') return;

                                                const group = $('<div>', {
                                                    class: `col-sm-` + (fullWidthType.includes(
                                                            key) ? '12' : '6') +
                                                        ' mb-2 dt-properties-rendered'
                                                });
                                                const label = $('<label>', {
                                                    class: 'form-label',
                                                    text: key.replace(/[-_]/g, ' ').replace(
                                                        /^./, str => str.toUpperCase())
                                                });
                                                const input = $('<input>', {
                                                    type: 'text',
                                                    class: 'form-control',
                                                    value: properties[key] || 'N/A',
                                                    disabled: true
                                                });
                                                group.append(label).append(
                                                    $('<div>', {
                                                        class: 'input-group input-group-merge'
                                                    }).append(input)
                                                );
                                                showRecordBody.append(group);
                                            });
                                        } else {
                                            showRecordBody.append($('<div>', {
                                                class: 'col-sm-12 text-muted dt-properties-rendered',
                                                text: 'No properties available'
                                            }));
                                        }
                                    },
                                }
                            }
                        }
                    });
                });
            </script>
        @endcan
    @endPushOnce
</x-layouts.dashboard>
