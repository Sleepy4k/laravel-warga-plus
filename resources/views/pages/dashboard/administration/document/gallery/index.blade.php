<x-layouts.dashboard title="Document List">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <x-dashboard.breadcrumb />
        </div>

        <div
            class="row g-4 mb-5 @if ($documents->isEmpty()) justify-content-center align-items-center min-vh-30 row-cols-12 row-cols-md-12 @else row-cols-1 row-cols-md-2 @endif">
            @forelse ($documents as $item)
                <div class="col">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h5 class="card-title mb-1 text-uppercase fw-bold">{{ ucfirst($item->title) }}</h5>
                                    <small class="text-muted">{{ $item->description }}</small>
                                </div>
                                <span class="badge bg-primary">{{ $item->versions->count() }}
                                    {{ Str::plural('Version', $item->versions->count()) }}</span>
                            </div>
                            <div class="accordion mt-3" id="accordion-{{ str_replace('.', '-', $item->title) }}">
                                @if ($item->versions && $item->versions->count())
                                    @foreach ($item->versions as $version)
                                        <div class="accordion-item mb-2 rounded shadow-sm border">
                                            <h2 class="accordion-header" id="heading-{{ $version->id }}">
                                                <button
                                                    class="accordion-button collapsed py-2 px-3 d-flex align-items-center gap-2"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $version->id }}" aria-expanded="false"
                                                    aria-controls="collapse-{{ $version->id }}">
                                                    <span class="flex-grow-1 text-truncate fw-semibold">
                                                        @if (strlen($version->file_name) > 57)
                                                            {{ substr($version->file_name, 0, 50) . '...' . substr($version->file_name, -7) }}
                                                        @else
                                                            {{ $version->file_name }}
                                                        @endif
                                                    </span>
                                                    <span
                                                        class="badge bg-primary ms-2">v{{ $version->version_number }}</span>
                                                </button>
                                            </h2>
                                            <div id="collapse-{{ $version->id }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading-{{ $version->id }}"
                                                data-bs-parent="#accordion-{{ str_replace('.', '-', $item->title) }}">
                                                <div class="accordion-body p-4">
                                                    <div class="d-flex flex-column flex-md-row align-items-start gap-4">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1 fw-bold text-dark">
                                                                        {{ $version->file_name }}</h6>
                                                                    <div
                                                                        class="d-flex flex-wrap gap-3 small text-muted">
                                                                        <span class="d-flex align-items-center gap-1">
                                                                            <i class="bx bx-data"></i>
                                                                            {{ App\Facades\Format::formatFileSize($version->file_size) }}
                                                                        </span>
                                                                        <span class="d-flex align-items-center gap-1">
                                                                            <i class="bx bx-time-five"></i>
                                                                            {{ $version->uploaded_at->format('M d, Y') }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="col-md-4 d-flex flex-column align-items-md-end align-items-start">
                                                            <div class="text-center text-md-end">
                                                                <div
                                                                    class="d-flex align-items-center gap-2 mb-1 text-muted small">
                                                                    <i class="bx bx-user-circle"></i>
                                                                    <span>{{ $version->user->personal->full_name ?? 'Unknown User' }}</span>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center gap-2 mb-1 text-muted small">
                                                                    <i class="bx bx-file"></i>
                                                                    <span>{{ $version->file_type }}</span>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center gap-2 mb-1 text-muted small">
                                                                    <i class="bx bx-time"></i>
                                                                    {{ $version->uploaded_at->diffForHumans() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @can('document.gallery.show')
                                                        <div class="border-top pt-3 mt-4">
                                                            <div class="row g-3 text-center">
                                                                <div class="col-12">
                                                                    <a data-href="{{ route('dashboard.document.gallery.show', ['document' => $item->id, 'version' => $version->id]) }}"
                                                                        class="btn btn-primary btn-sm w-50"
                                                                        id="download-button-{{ $item->id }}-{{ $version->id }}"
                                                                        style="cursor: pointer;">
                                                                        <i class="bx bx-download me-1"></i>
                                                                        Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-muted small py-3 text-center">No versions available.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <div class="card h-100 border-0 shadow-sm text-center py-5 w-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <i class="bx bx-folder-open display-3 text-muted mb-3"></i>
                            <h6 class="text-muted mb-0">No document files found</h6>
                            <p class="text-muted small mt-2">Try uploading a new document to get started.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @pushOnce('page-scripts')
        @can('document.gallery.show')
            <script @cspNonce>
                $(document).ready(function() {
                    $('a[id^="download-button-"]').on('click', function() {
                        const href = $(this).data('href');
                        window.location.href = href;
                    });
                });
            </script>
        @endcan
    @endPushOnce
</x-layouts.dashboard>
