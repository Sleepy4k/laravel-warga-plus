<x-layouts.landing title="Informasi RT">
    <x-landing.hero>
        <div class="hero-text-box text-center position-relative">
            <h1 class="text-primary hero-title display-6 fw-extrabold">Informasi RT</h1>
            <h2 class="hero-sub-title h6">Semua informasi kegiatan dan pengumuman RT terkumpul di sini — kerja bakti, ronda malam, pengumuman penting, dan lain-lain.</h2>
        </div>
    </x-landing.hero>

    <section id="informationList" class="section-py">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <form id="filterForm" class="row g-2 align-items-center">
                        <div class="col-12 col-md">
                            <input
                                type="search"
                                name="q"
                                class="form-control"
                                placeholder="Cari informasi..."
                                value="{{ request('q') }}"
                                aria-label="Cari informasi"
                            >
                        </div>

                        <div class="col-6 col-md-auto">
                            <select name="type" class="form-select" aria-label="Filter tipe">
                                <option value="">Semua Tipe</option>
                                <option value="Kegiatan" {{ request('type') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="Pengumuman" {{ request('type') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                <option value="Peraturan" {{ request('type') == 'Peraturan' ? 'selected' : '' }}>Peraturan</option>
                                <option value="Keuangan" {{ request('type') == 'Keuangan' ? 'selected' : '' }}>Keuangan</option>
                                <option value="Keamanan" {{ request('type') == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-auto d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 w-md-auto">Filter</button>
                            <button type="button" class="btn btn-outline-secondary w-100 w-md-auto" id="resetFilters">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            @php
                // sample items for UI preview. Replace with real data in controller.
                $items = range(1, 8);
            @endphp

            <div class="row g-4">
                @forelse($items as $index)
                    @php
                        $types = ['Kegiatan', 'Pengumuman', 'Peraturan', 'Keuangan', 'Keamanan'];
                        $titles = [
                            'Kerja Bakti Bersama RT 03',
                            'Rapat Koordinasi Warga',
                            'Peraturan Baru: Jam Tenang',
                            'Laporan Keuangan Bulanan',
                            'Peningkatan Keamanan Lingkungan',
                        ];
                        $type = $types[$index % 5];
                        $title = $titles[$index % 5];
                        $date = now()->addDays($index)->format('d M Y');
                    @endphp

                    <div class="col-12">
                        <div class="card shadow-sm border-0 bg-body">
                            <div class="card-body d-flex align-items-start gap-3">
                                <div class="avatar avatar-sm d-flex align-items-center justify-content-center bg-label-{{ ['primary','success','danger','warning','info'][$index % 5] }} rounded me-3 mt-1">
                                    <i class="bx {{ ['bx-calendar','bx-phone','bx-book','bx-money','bx-shield'][$index % 5] }} fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $title }}</h5>
                                    <p class="mb-1 text-muted">
                                        @if($type == 'Kegiatan')
                                            Kerja bakti pembersihan selokan dan pemangkasan ranting yang menghalangi jalan.
                                        @elseif($type == 'Pengumuman')
                                            Rapat penting akan dilaksanakan di Balai RT; mohon hadir tepat waktu.
                                        @elseif($type == 'Peraturan')
                                            Ditetapkan jam tenang mulai pukul 22.00 sampai 05.00 setiap hari.
                                        @elseif($type == 'Keuangan')
                                            Pengumpulan iuran keamanan bulan ini telah dibuka. Silakan konfirmasi kepada pengurus.
                                        @else
                                            Ronda malam akan dimulai pada pukul 20.00; warga diharapkan mendaftar petugas ronda.
                                        @endif
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-label-{{ ['primary','success','danger','warning','info'][$index % 5] }} me-2">{{ $type }}</span>
                                        <small class="text-muted">{{ $date }}</small>
                                        <div class="ms-auto">
                                            <a href="#" class="btn btn-sm btn-primary">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4">
                        <div class="text-center p-4 bg-light-primary rounded-3">
                            <h5 class="mb-0">Tidak ada informasi tersedia</h5>
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
            document.addEventListener('DOMContentLoaded', () => {
                const BASE_URL = @json(route('landing.information'));
                const form = document.getElementById('filterForm');
                const resetBtn = document.getElementById('resetFilters');

                if (!form) return;

                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const params = new URLSearchParams();
                    let q = form.q?.value?.trim();
                    let type = form.type?.value;

                    if (type) {
                        type = type.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                        params.set('type', type);
                    }

                    if (q) {
                        q = q.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                        params.set('q', q);
                    }

                    const url = params.toString() ? `${BASE_URL}?${params.toString()}` : BASE_URL;
                    window.location.href = url;
                });

                if (resetBtn) {
                    resetBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        window.location.href = BASE_URL;
                    });
                }
            });
        </script>
    @endPushOnce
</x-layouts.landing>
