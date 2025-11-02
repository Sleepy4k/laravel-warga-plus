<x-layouts.dashboard title="User Account">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}"
            @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}"
            @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/css/pages/page-profile.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard.breadcrumb />

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        <img src="{{ asset('img/backgrounds/banner.png') }}" alt="Banner image" class="rounded-top"
                            loading="lazy">
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            <img src="{{ $personal->userAvatar() }}" alt="user image"
                                class="d-block ms-0 ms-sm-4 rounded user-profile-img"
                                style="height: 120px; width: 120px; object-fit: cover;" loading="lazy">
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div
                                class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>
                                        {{ $personal->full_name }}
                                    </h4>
                                    <ul
                                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        <li class="list-inline-item fw-semibold">
                                            <i class="bx bx-calendar-alt"></i> Joined
                                            {{ $user->created_at->format('d M Y') }}
                                        </li>
                                        <li class="list-inline-item fw-semibold"><i class="bx bx-map"></i>
                                            {{ $personal->address }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">About</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-user"></i><span class="fw-semibold mx-2">Identity Number:</span>
                                <span>
                                    {{ ucfirst($user->identity_number) }}
                                </span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-check"></i><span class="fw-semibold mx-2">Status:</span>
                                <span
                                    class="badge bg-label-{{ $user->is_active ? 'primary' : 'secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-star"></i><span class="fw-semibold mx-2">Role:</span>
                                <span>{{ ucfirst($role) }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-flag"></i><span class="fw-semibold mx-2">Country:</span>
                                <span>Indonesia</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-detail"></i><span class="fw-semibold mx-2">Language:</span>
                                <span>English</span>
                            </li>
                        </ul>
                        <small class="text-muted text-uppercase">Contacts</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-phone"></i><span class="fw-semibold mx-2">Contact:</span>
                                <span>
                                    @php
                                        $parsedNumber = preg_replace('/\D/', '', $user->phone);
                                        $parsedNumber = '62' . ltrim($parsedNumber, '0');
                                    @endphp
                                    <a href="https://wa.me/{{ $parsedNumber }}"
                                        target="_blank">wa.me/{{ $parsedNumber }}</a>
                                </span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-chat"></i><span class="fw-semibold mx-2">Job:</span>
                                <span>{{ $personal->job }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7 col-md-7">
                <div class="card mb-4">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-projects border-top table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Title</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $index => $report)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $report->title }}</td>
                                        <td>{{ $report->location }}</td>
                                        <td>{{ $report->category->name ?? 'Uncategorized' }}</td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'dibuat' => 'badge bg-warning',
                                                    'diproses' => 'badge bg-info',
                                                    'ditolak' => 'badge bg-danger',
                                                    'selesai' => 'badge bg-success',
                                                ];
                                                $statusClass = $statusClasses[$report->status] ?? 'badge bg-secondary';
                                            @endphp
                                            <span class="{{ $statusClass }}">
                                                {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('plugin-scripts')
        <script src="{{ asset('vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        <script @cspNonce>
            $(document).ready(function() {
                $('.datatables-projects').DataTable({
                    responsive: true,
                    columnDefs: [{
                            orderable: false,
                            targets: -1
                        },
                        {
                            searchable: false,
                            targets: -1
                        }
                    ],
                    order: [
                        [0, 'asc']
                    ],
                    dom: '<"card-header pb-0 pt-sm-0"<"head-label text-center"><"d-flex justify-content-center justify-content-md-end"f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    displayLength: 10,
                    lengthMenu: [10, 25, 50, 75, 100],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of "' + data['project_name'] + '" Project';
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !== '' ?
                                        '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                        col.columnIndex + '">' +
                                        '<td>' +
                                        col.title + ':' +
                                        '</td> ' +
                                        '<td>' +
                                        col.data +
                                        '</td>' +
                                        '</tr>' :
                                        '';
                                }).join('');

                                return data ? $('<table class="table"/><tbody />').append(data) : false;
                            }
                        }
                    }
                });

                $('div.head-label').html('<h5 class="card-title mb-0">Reports</h5>');
            });
        </script>
    @endPushOnce
</x-layouts.dashboard>
