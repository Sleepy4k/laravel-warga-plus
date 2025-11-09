<x-layouts.landing title="Laporan Warga">
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
                                placeholder="Cari laporan (lokasi, judul, deskripsi)..." value="{{ request('q') }}"
                                aria-label="Cari laporan">
                        </div>

                        <div class="col-6 col-md-auto">
                            <select name="type" class="form-select" aria-label="Filter tipe">
                                <option value="">Semua Tipe</option>
                                <option value="Kehilangan" {{ request('type') == 'Kehilangan' ? 'selected' : '' }}>
                                    Kehilangan</option>
                                <option value="Sampah" {{ request('type') == 'Sampah' ? 'selected' : '' }}>Sampah</option>
                                <option value="Infrastruktur" {{ request('type') == 'Infrastruktur' ? 'selected' : '' }}>
                                    Infrastruktur</option>
                                <option value="Keamanan" {{ request('type') == 'Keamanan' ? 'selected' : '' }}>Keamanan
                                </option>
                            </select>
                        </div>

                        <div class="col-6 col-md-auto">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Selesai</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Dalam Proses
                                </option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Menunggu</option>
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

            @php
                // contoh data sementara — ganti dengan data nyata dari controller
                $items = range(1, 9);
                $typeMap = ['Kehilangan', 'Sampah', 'Infrastruktur', 'Keamanan', 'Lainnya'];
            @endphp

            <div class="row g-4" id="reportsContainer">
                @forelse($items as $item)
                    @php
                        $reportType = $typeMap[$item % count($typeMap)];
                        $title = match ($reportType) {
                            'Kehilangan' => 'Motor hilang di parkiran',
                            'Sampah' => 'Sampah berserakan di gang A',
                            'Infrastruktur' => 'Papan jalan rusak di perempatan',
                            'Keamanan' => 'Lampu jalan mati di blok B',
                            default => 'Laporan umum warga',
                        };
                        $statusMap = [
                            0 => ['text' => 'Selesai', 'bg' => 'success', 'icon' => 'bx bx-check-circle'],
                            1 => ['text' => 'Dalam Proses', 'bg' => 'warning', 'icon' => 'bx bx-time-five'],
                            2 => ['text' => 'Menunggu', 'bg' => 'secondary', 'icon' => 'bx bx-hourglass'],
                        ];
                        $status = $statusMap[$item % 3];
                    @endphp

                    <div class="col-lg-4 col-sm-6">
                        <div class="card h-100 shadow-sm border-0 bg-body">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="{{ asset('img/avatars/1.png') }}" alt="Avatar"
                                            class="rounded-circle" loading="lazy">
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Nama Pelapor {{ $item }}</h6>
                                        <small class="text-muted">{{ $reportType }} •
                                            {{ date('d M Y', strtotime('-' . $item . ' days')) }}</small>
                                    </div>
                                    <div class="ms-auto">
                                        <span
                                            class="badge bg-label-{{ $status['bg'] }} d-flex align-items-center gap-1">
                                            <i class="{{ $status['icon'] }}"></i>
                                            <span>{{ $status['text'] }}</span>
                                        </span>
                                    </div>
                                </div>

                                <h5 class="mb-2">{{ $title }}</h5>
                                <p class="mb-3 text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    Deskripsi singkat masalah dilaporkan untuk memberi konteks kepada petugas.</p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-label-info">{{ $reportType }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('landing.report.show', $item) }}" class="btn btn-sm btn-primary">Detail</a>
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

            <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="text-muted text-center text-md-start w-100 w-md-auto">
                    <small class="d-block d-md-inline">Showing 1 to 10 of 30 entries</small>
                </div>

                <div class="w-100 w-md-auto d-flex justify-content-center justify-content-md-end">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-rounded mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Previous">&lt;</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">&gt;</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    @pushOnce('page-scripts')
        <script @cspNonce>
            document.addEventListener('DOMContentLoaded', function () {
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
    @endPushOnce
</x-layouts.landing>
