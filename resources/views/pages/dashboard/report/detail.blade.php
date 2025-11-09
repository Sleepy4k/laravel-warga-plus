<x-layouts.dashboard title="Report Detail">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/lightbox/css/lightbox.min.css') }}" @cspNonce />
        <style @cspNonce>
            .timeline {
                position: relative;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .timeline-item {
                position: relative;
                margin-bottom: 20px;
                padding-left: 25px;
            }
            .timeline-icon {
                position: absolute;
                left: 5px;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: #0d6efd;
            }
            .timeline-content {
                border-radius: 5px;
            }
        </style>
        @can('report.edit')
            <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
        @endcan
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('dashboard.report.index') }}" class="btn btn-primary me-2">Back to Report List</a>
                @can('report.store')
                    @if ($isCanAddProgress)
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                            data-bs-target="#add-progress">
                            Add Progress
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded my-4" src="{{ $personal->userAvatar() }}" height="110"
                                    width="110" alt="User avatar" loading="lazy" />
                                <div class="user-info text-center">
                                    <h4 class="mb-2">{{ $personal->full_name }}</h4>
                                    <span class="badge bg-label-secondary">{{ $role }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">About</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-user"></i><span class="fw-bold mx-2">Identity Number:</span>
                                <span>{{ $user->identity_number }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-check"></i><span class="fw-bold mx-2">Status:</span>
                                <span
                                    class="badge bg-label-{{ $user->is_active ? 'primary' : 'secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-star"></i><span class="fw-bold mx-2">Role:</span>
                                <span>{{ ucfirst($role) }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-flag"></i><span class="fw-bold mx-2">Country:</span>
                                <span>Indonesia</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-detail"></i><span class="fw-bold mx-2">Language:</span>
                                <span>English</span>
                            </li>
                        </ul>
                        <small class="text-muted text-uppercase">Contacts</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-phone"></i><span class="fw-bold mx-2">Phone:</span>
                                @php
                                    $parsedNumber = preg_replace('/\D/', '', $user->phone);
                                    $parsedNumber = '62' . ltrim($parsedNumber, '0');
                                @endphp
                                <a href="https://wa.me/{{ $parsedNumber }}"
                                    target="_blank">wa.me/{{ $parsedNumber }}</a>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-chat"></i><span class="fw-bold mx-2">Job:</span>
                                <span>{{ $personal->job }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <ul class="nav nav-pills flex-column flex-md-row mb-2" role="tablist" id="detail-report-tabs">
                    <li class="nav-item">
                        <button class="nav-link" role="tab" data-bs-toggle="tab" id="report-tab"
                            data-bs-target="#report-settings" aria-controls="report-settings" aria-selected="true">
                            <i class="bx bx-comment-add me-1"></i>
                            Report
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" role="tab" data-bs-toggle="tab" id="progress-tab"
                            data-bs-target="#progress-settings" aria-controls="progress-settings" aria-selected="false">
                            <i class="bx bx-check-circle me-1"></i>
                            Progress
                        </button>
                    </li>
                </ul>

                <div class="tab-content mb-4" style="padding: 0; padding-top: 1rem;">
                    <div class="tab-pane fade" id="report-settings" role="tabpanel" aria-labelledby="report-tab">
                        <div class="card mb-4">
                            <h5 class="card-header">{{ $report->title }}</h5>
                            <div class="card-body table-responsive">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="mb-2">Description</h6>
                                        <div class="mb-3" style="white-space:pre-wrap;">{{ $report->content }}</div>

                                        <dl class="row mb-3">
                                            <dt class="col-sm-4">Reported by</dt>
                                            <dd class="col-sm-8">{{ $personal->full_name ?? '-' }}</dd>

                                            <dt class="col-sm-4">Created</dt>
                                            <dd class="col-sm-8">
                                                {{ optional($report->created_at)->format('d M Y H:i') ?? '-' }}
                                                @if(optional($report->created_at))
                                                    <small class="text-muted">(&nbsp;{{ $report->created_at->diffForHumans() }}&nbsp;)</small>
                                                @endif
                                            </dd>

                                            <dt class="col-sm-4">Category</dt>
                                            <dd class="col-sm-8">
                                                <span class="badge bg-label-primary">{{ $report->category->name ?? '-' }}</span>
                                            </dd>

                                            <dt class="col-sm-4">Status</dt>
                                            <dd class="col-sm-8">
                                                @php
                                                    $status = $report->status ?? 'unknown';
                                                    $map = [
                                                        'dibuat' => 'warning',
                                                        'diproses' => 'info',
                                                        'selesai' => 'success',
                                                        'ditolak' => 'danger'
                                                    ];
                                                    $badge = $map[$status] ?? 'dark';
                                                @endphp
                                                <span class="badge bg-label-{{ $badge }}">
                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                </span>
                                            </dd>

                                            <dt class="col-sm-4">Location</dt>
                                            <dd class="col-sm-8">
                                                @if(!empty($report->location))
                                                    {{ $report->location }}
                                                    <br />
                                                    <a href="https://www.google.com/maps/search/{{ urlencode($report->location) }}" target="_blank" rel="noopener noreferrer" class="small">View on map</a>
                                                @else
                                                    -
                                                @endif
                                            </dd>

                                            <dt class="col-sm-4">Last updated</dt>
                                            <dd class="col-sm-8">
                                                {{ optional($report->updated_at)->format('d M Y H:i') ?? '-' }}
                                                @if(optional($report->updated_at))
                                                    <small class="text-muted">(&nbsp;{{ $report->updated_at->diffForHumans() }}&nbsp;)</small>
                                                @endif
                                            </dd>
                                        </dl>
                                    </div>

                                    <div class="col-md-4">
                                        <h6 class="mb-2">Attachments
                                            <small class="text-muted">({{ is_countable($report->attachments) ? count($report->attachments) : 0 }})</small>
                                        </h6>

                                        @if (!empty($report->attachments) && count($report->attachments) > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($report->attachments as $attachment)
                                                    @php
                                                        $path = $attachment->path ?? $attachment;
                                                        $filePath = parse_url($path, PHP_URL_PATH) ?: $path;
                                                        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                                        $imageExts = ['jpg','jpeg','png','gif','webp','bmp','svg'];
                                                        $isImage = in_array($ext, $imageExts);
                                                    @endphp

                                                    @if ($isImage)
                                                        <a href="{{ $path }}" data-lightbox="report-attachments" class="d-block" title="{{ basename($filePath) }}">
                                                            <img src="{{ $path }}" alt="{{ basename($filePath) }}"
                                                                class="product-image border"
                                                                loading="lazy"
                                                                style="width:100px;height:100px;object-fit:cover;display:block;"
                                                            />
                                                        </a>
                                                    @else
                                                        <div class="card mb-2" style="width:100%;">
                                                            <div class="card-body p-2 d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="bx bx-file me-2" style="font-size:1.4rem"></i>
                                                                    <div>
                                                                        <div class="small text-truncate" style="max-width:160px;">{{ basename($filePath) }}</div>
                                                                        <small class="text-muted">{{ strtoupper($ext) }} file</small>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="{{ $path }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">Open</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">No attachments available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="progress-settings" role="tabpanel" aria-labelledby="progress-tab">
                        <div class="card mb-4">
                            <h5 class="card-header">Report Progress</h5>
                            <div class="card-body">
                                <div class="timeline">
                                    @if($progresses && count($progresses) > 0)
                                        @foreach($progresses as $progress)
                                            <div class="timeline-item mb-4">
                                                <div class="timeline-icon">
                                                    <i class="bx bx-check-circle text-primary"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">{{ $progress->title }}</h6>
                                                    <p class="mb-2" style="white-space:pre-wrap;">{{ $progress->description }}</p>
                                                    <small class="text-muted">
                                                        {{ optional($progress->created_at)->format('d M Y H:i') ?? '-' }}
                                                        @if(optional($progress->created_at))
                                                            (&nbsp;{{ $progress->created_at->diffForHumans() }}&nbsp;)
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No progress updates available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($isCanAddProgress)
            <div class="modal fade" id="add-progress" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <form id="add-progress-form" class="row g-3">
                                <input type="hidden" name="report_id" value="{{ $report->id }}" />
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-title">Title</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="edit-title"
                                            class="form-control dt-title @error('title') is-invalid @enderror"
                                            name="title" placeholder="Report Title" aria-label="Report Title"
                                            aria-describedby="title" value="{{ old('title') }}" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="edit-description">Description</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="edit-description"
                                            class="form-control dt-description @error('description') is-invalid @enderror"
                                            name="description" placeholder="Report Description" aria-label="Report Description"
                                            aria-describedby="description" value="{{ old('description') }}" />
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
        @endif
    </div>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/lightbox/js/lightbox.min.js') }}" @cspNonce></script>
        @can('report.edit')
            <script type="text/javascript" src="{{ asset('vendor/libs/autosize/autosize.js') }}" @cspNonce></script>
            <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
        @endcan
    @endPushOnce

    @pushOnce('page-scripts')
        <script src="{{ asset('js/pages/profile-security.min.js') }}" @cspNonce></script>
        <script @cspNonce>
            lightbox.option({
                'resizeDuration': 500,
                'wrapAround': true
            })
        </script>
        <script type="text/javascript" @cspNonce>
            $(document).ready(function() {
                $('#detail-report-tabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    const tabId = $(e.target).attr('id');
                    let tab = '';
                    switch (tabId) {
                        case 'report-tab':
                            tab = 'report';
                            break;
                        case 'progress-tab':
                            tab = 'progress';
                            break;
                    }
                    if (tab) {
                        const url = new URL(window.location);
                        url.searchParams.set('tab', tab);
                        window.history.replaceState({}, '', url);
                        $('.tab-pane.active form').each(function() {
                            let $input = $(this).find('input[name="tab"]');
                            if ($input.length === 0) {
                                $input = $('<input type="hidden" name="tab" />').appendTo($(this));
                            }
                            $input.val(tab);
                        });
                    }
                });

                const params = new URLSearchParams(window.location.search);
                let tab = params.get('tab');
                if (!tab) {
                    tab = 'report';
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url);
                }
                if (tab) {
                    $(`#${tab}-tab`).tab('show');
                    setTimeout(function() {
                        $(`#${tab}-settings form`).each(function() {
                            let $input = $(this).find('input[name="tab"]');
                            if ($input.length === 0) {
                                $input = $('<input type="hidden" name="tab" />').appendTo($(this));
                            }
                            $input.val(tab);
                        });
                    }, 100);
                }
            });
        </script>
        @can('report.edit')
            <script @cspNonce>
                $(document).ready(function() {
                    autosize($('#edit-description'));
                });
            </script>
            <script @cspNonce>
                $(document).ready(function() {
                    $('#add-progress-form').on('submit', async function(e) {
                        e.preventDefault();
                        const form = $(this);
                        const url = "{{ route('dashboard.report.progress.store') }}";
                        const formData = new FormData(form[0]);

                        formData.append('_token', '{{ csrf_token() }}');

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
                                text: 'Progress has been added successfully.',
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
                                text: 'An error occurred while updating user information.',
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
    @endPushOnce
</x-layouts.dashboard>
