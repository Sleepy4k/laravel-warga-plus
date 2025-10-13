<x-layouts.dashboard title="Jobs">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            @can('misc.job.store')
                <button class="btn btn-primary" type="button" id="btn-clear-jobs" @disabled($jobCount === 0)>
                    Clear Jobs
                </button>
            @endcan
        </div>

        <p class="mb-4">
            This page lists all jobs in the system, including pending and failed jobs.<br />
            <strong>Note:</strong> Clearing jobs will remove all failed jobs from the system.
        </p>

        <div class="card mb-4">
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
        @can('misc.job.store')
            <script @cspNonce>
                $(document).on('click', '#btn-clear-jobs', function() {
                    const dataTable = $('#job-table').DataTable();
                    if (dataTable.data().count() === 0) return;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will clear all failed jobs.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, clear jobs!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Clearing Jobs...',
                                text: 'Please wait while the jobs are being cleared.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '{{ route('dashboard.misc.job.store') }}',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success'
                                    });
                                    window.location.reload();
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: xhr.responseJSON.message || 'An error occurred while clearing jobs.',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            </script>
        @endcan
    @endPushOnce
</x-layouts.dashboard>
