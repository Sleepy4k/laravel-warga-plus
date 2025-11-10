<x-layouts.landing title="Laporan Warga">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" @cspNonce />
    @endPushOnce

    <x-landing.hero>
        <div class="hero-text-box text-center position-relative">
            <h1 class="text-primary hero-title display-6 fw-extrabold">Laporan Warga</h1>
            <h2 class="hero-sub-title h6 mb-6">Lihat semua laporan yang dibuat warga — kehilangan, sampah, kerusakan,
                keamanan, dan lainnya.
                Kita bersama-sama membangun lingkungan yang lebih baik dengan transparansi dan kolaborasi.
            </h2>
        </div>
    </x-landing.hero>

    <section id="reports" class="section-py">
        <div class="container">
            <div class="row mb-4 align-items-center">
                <div class="col-md-12">
                    <form id="filterForm" class="row g-2 align-items-center">
                        <div class="col-12 col-md">
                            <input type="search" name="q" class="form-control"
                                placeholder="Cari laporan (judul, deskripsi)..." value="{{ request('q') }}"
                                aria-label="Cari laporan">
                        </div>

                        <div class="col-6 col-md-auto">
                            <select name="type" class="form-select" aria-label="Filter tipe">
                                <option value="">Semua Tipe</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ request('type') == (string) $type->id ? 'selected' : '' }}>
                                        {{ ucwords($type->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-md-auto">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}"
                                        {{ request('status') == (string) $status->value ? 'selected' : '' }}>
                                        {{ ucwords($status->value) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-auto d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 w-md-auto">Cari</button>
                            <button type="button" class="btn btn-outline-secondary w-100 w-md-auto"
                                id="resetFilters">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4" id="reportsContainer">
                @forelse($reports as $item)
                    <div class="col-lg-4 col-sm-6">
                        <div class="card h-100 shadow-sm border-0 bg-body">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="{{ asset('img/avatars/1.png') }}" alt="Avatar"
                                            class="rounded-circle" loading="lazy">
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $item->user->personal->full_name ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $item->category->name ?? 'N/A' }} •
                                            {{ date('d M Y', strtotime($item->created_at)) }}</small>
                                    </div>
                                    <div class="ms-auto">
                                        <span
                                            class="badge bg-label-{{ $reportIcons[$item->status]['bg'] }} d-flex align-items-center gap-1">
                                            <i class="{{ $reportIcons[$item->status]['icon'] }}"></i>
                                            <span>{{ $reportIcons[$item->status]['text'] }}</span>
                                        </span>
                                    </div>
                                </div>

                                <h5 class="mb-2">{{ $item->title }}</h5>
                                <p class="mb-3 text-muted">
                                    {{ Str::limit($item->content, 100, '...') }}
                                </p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-label-info">{{ $item->category->name ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('landing.report.show', $item->id) }}"
                                            class="btn btn-sm btn-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4">
                        <div class="text-center p-4 bg-light-primary rounded-3">
                            <h5 class="mb-0">Belum ada laporan</h5>
                        </div>
                    </div>
                @endforelse
            </div>

            {{ $reports->links() }}
        </div>
    </section>

    @pushOnce('plugin-scripts')
        <script type="text/javascript" src="{{ asset('vendor/js/front-page/jquery.min.js') }}" @cspNonce></script>
        <script type="text/javascript" src="{{ asset('vendor/libs/select2/select2.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        <script @cspNonce>
            $(document).ready(function() {
                const select2Elements = $('.select2');
                if (select2Elements.length) {
                    select2Elements.each(function() {
                        $(this).select2({
                            dropdownParent: $(this).parent(),
                            placeholder: 'Select an option',
                            allowClear: false,
                            width: '100%',
                        });
                    });
                }
            });
        </script>
        @if (count($reports) > 0)
            <script @cspNonce>
                document.addEventListener('DOMContentLoaded', function() {
                    gsap.from("#reportsContainer .card", {
                        opacity: 0,
                        y: 50,
                        duration: 1,
                        ease: "power3.out",
                        stagger: 0.2,
                        scrollTrigger: {
                            trigger: "#reportsContainer",
                            start: "top 80%",
                            toggleActions: "play none none reverse"
                        }
                    });
                });
            </script>
        @endif
    @endPushOnce
</x-layouts.landing>
