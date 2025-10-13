<x-layouts.dashboard title="Database Backups">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('misc.backup.store')
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary me-3" type="button" id="btn-clear-backups" @disabled($backupCount === 0)>
                        Clear Database Backups
                    </button>
                    <button class="btn btn-primary" type="button" id="btn-backup-database">
                        Backup Database
                    </button>
                </div>
            @endcan
        </div>

        <p class="mb-4">
            This page lists all database backups available in the system.<br />
            You can download the backups and delete them if necessary.<br />
            To clear backups older than 30 days, click the button above.
        </p>

        <div class="card">
            <div class="card-body table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce>
        </script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}"
            @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module', 'nonce' => app('csp-nonce')]) }}
        @can('misc.backup.store')
            <script @cspNonce>
                $(document).on('click', '#btn-clear-backups', function() {
                    const dataTable = $('#backup-table').DataTable();
                    if (dataTable.data().count() === 0) return;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will clear all database backups older than 30 days.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, clear backups!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Clearing Backups...',
                                text: 'Please wait while the backups are being cleared.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                url: '{{ route('dashboard.misc.backup.store') }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    type: 'cleanup',
                                },
                                success: function(response) {
                                    dataTable.ajax.reload(null, false);

                                    Swal.fire(
                                        'Cleared!',
                                        response.message || 'Database backups older than 30 days have been cleared.',
                                        'success'
                                    ).then(() => {
                                        if (dataTable.data().count() === 0) {
                                            $('#btn-clear-backups').attr('disabled', true);
                                        }
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to clear database backups. Please try again later.',
                                        'error'
                                    );
                                },
                            });
                        }
                    });
                });

                $(document).on('click', '#btn-backup-database', function() {
                    const dataTable = $('#backup-table').DataTable();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will create a new database backup.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, backup database!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Generating Backup...',
                                text: 'Please wait while the backup is being generated.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                url: '{{ route('dashboard.misc.backup.store') }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    type: 'backup',
                                },
                                success: function(response) {
                                    dataTable.ajax.reload(null, false);

                                    Swal.fire(
                                        'Success!',
                                        response.message || 'Database backup created successfully.',
                                        'success'
                                    );
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to create database backup. Please try again later.',
                                        'error'
                                    );
                                },
                            });
                        }
                    });
                });
            </script>
        @endcan
        @can('misc.backup.show')
            <script @cspNonce>
                $(document).on('click', '.show-record', function() {
                    const fileName = $(this).data('id');
                    window.open('{{ route('dashboard.misc.backup.show', ':id') }}'.replace(':id', fileName), '_blank');
                });
            </script>
        @endcan
        @can('misc.backup.delete')
            <script @cspNonce>
                $(document).on('click', '.delete-record', function() {
                    const fileName = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will delete the backup file.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Deleting Backup...',
                                text: 'Please wait while the backup is being deleted.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                url: '{{ route('dashboard.misc.backup.destroy', ':id') }}'.replace(':id', fileName),
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    _method: 'DELETE',
                                },
                                success: function(response) {
                                    $('#backup-table').DataTable().ajax.reload(null, false);
                                    Swal.fire(
                                        'Deleted!',
                                        response.message || 'Backup file deleted successfully.',
                                        'success'
                                    );
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete backup file. Please try again later.',
                                        'error'
                                    );
                                },
                            });
                        }
                    });
                });
            </script>
        @endcan
    @endPushOnce
</x-layouts.dashboard>
