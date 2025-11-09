<x-layouts.landing title="Detail Laporan">
    <x-landing.hero>
        <div class="hero-text-box text-center position-relative">
            <h1 class="text-primary hero-title display-6 fw-extrabold">Detail Laporan</h1>
            <h2 class="hero-sub-title h6 mb-6">
                Pantau perkembangan laporan Anda secara real-time dan transparan hingga tuntas.
            </h2>
        </div>
    </x-landing.hero>

    <section id="detailReport" class="section-py">
        <div class="container">
            @php
                // sample placeholder data — replace with real $report from controller
                $report = $report ?? (object) [
                    'id' => 1,
                    'title' => 'Papan jalan rusak di perempatan',
                    'type' => 'Infrastruktur',
                    'status' => 1,
                    'reporter' => 'Nama Pelapor',
                    'reported_at' => now()->subDays(3),
                    'location' => 'Perempatan Blok C',
                    'description' => "Papan penunjuk jalan di perempatan rusak dan patah, berisiko bagi pengendara. Mohon segera diperbaiki.",
                    'images' => [
                        'img/products/1.png',
                        'img/products/2.png',
                    ],
                    'timeline' => [
                        ['status' => 'Diterima', 'note' => 'Laporan diterima oleh sistem', 'at' => now()->subDays(3)],
                        ['status' => 'Dikonfirmasi oleh Pengurus RT', 'note' => 'Pengurus RT memverifikasi lokasi', 'at' => now()->subDays(2)],
                        ['status' => 'Dalam Proses', 'note' => 'Dinas terkait dijadwalkan melakukan perbaikan', 'at' => now()->subDay()],
                    ],
                ];

                $statusMap = [
                    0 => ['text' => 'Selesai', 'bg' => 'success', 'icon' => 'bx bx-check-circle'],
                    1 => ['text' => 'Dalam Proses', 'bg' => 'warning', 'icon' => 'bx bx-time-five'],
                    2 => ['text' => 'Menunggu', 'bg' => 'secondary', 'icon' => 'bx bx-hourglass'],
                ];
                $status = $statusMap[$report->status ?? 2];
            @endphp

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 bg-body">
                        <div class="card-body">
                            <div id="reportGallery" class="carousel slide mb-4" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($report->images as $i => $img)
                                        <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                            <div class="d-block w-100 rounded overflow-hidden" style="height:360px;position:relative;">
                                                <img src="{{ asset($img) }}" alt="Laporan image {{ $i + 1 }}" loading="lazy"
                                                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#reportGallery" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#reportGallery" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>

                            <div class="d-flex gap-2 flex-wrap justify-content-center mb-3">
                                @foreach($report->images as $i => $img)
                                    <a href="{{ asset($img) }}" target="_blank" class="border rounded p-1 d-inline-block">
                                        <img src="{{ asset($img) }}" alt="Attachment {{ $i + 1 }}" style="width:80px;height:60px;object-fit:cover;" loading="lazy">
                                    </a>
                                @endforeach
                            </div>

                            <h3 class="mb-2" style="background:linear-gradient(90deg,#0d6efd,#0dcaf0);-webkit-background-clip:text;color:transparent;">
                                {{ $report->title }}
                            </h3>

                            <div class="d-flex align-items-center mb-3 gap-3">
                                <small class="text-muted"><i class="bx bx-calendar"></i> {{ $report->reported_at->format('d M Y, H:i') }}</small>
                                <small class="text-muted">·</small>
                                <small class="text-muted">{{ $report->reported_at->diffForHumans() }}</small>

                                <div class="ms-auto d-flex">
                                    <button id="shareBtn" type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-share me-1"></i> Bagikan
                                    </button>
                                </div>
                            </div>

                            <h6>Deskripsi Lengkap</h6>
                            <p id="reportDesc" class="text-muted">
                                {{ $report->description }}
                            </p>
                        </div>
                    </div>

                    <div class="card mt-4 shadow-sm border-0 bg-body">
                        <div class="card-body">
                            <h5 class="mb-3">Timeline Progress</h5>
                            <ul class="timeline list-unstyled mb-0">
                                @foreach($report->timeline as $step)
                                    <li class="d-flex mb-4">
                                        <div class="me-3">
                                            <span class="avatar avatar-xs bg-label-primary rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <i class="bx bx-check fs-5"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <div>
                                                <div class="mb-1">
                                                    <strong class="d-block">{{ $step['status'] }}</strong>
                                                </div>

                                                <div class="mb-2">
                                                    <p class="mb-1 text-muted">{{ $step['note'] }}</p>
                                                </div>

                                                <div>
                                                    <small class="text-muted">{{ (\Carbon\Carbon::parse($step['at']))->format('l, d M Y H:i') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 bg-body">
                        <div class="card-body">
                            <h6 class="mb-3">Ringkasan</h6>

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($report->reporter) . '&background=0D6EFD&color=fff&size=128' }}"
                                        alt="Foto {{ $report->reporter }}"
                                        class="rounded-circle"
                                        style="width:64px;height:64px;object-fit:cover;">
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start">
                                        <div>
                                            <div class="fw-bold">{{ $report->reporter }}</div>
                                            <small class="text-muted">Pelapor</small>
                                        </div>
                                        <div class="ms-auto text-end">
                                            <span class="badge bg-label-{{ $status['bg'] }}">{{ $status['text'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Jenis</small>
                                <div>{{ $report->type }}</div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Lokasi</small>
                                <div>{{ $report->location }}</div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Dilaporkan</small>
                                <div>{{ $report->reported_at->format('d M Y, H:i') }}</div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <a href="tel:813xxxxxxxx" class="btn btn-primary btn-sm">
                                    <i class="bx bx-phone me-1"></i> Hubungi Pelapor
                                </a>

                                <a href="{{ route('landing.report') ?? url()->previous() }}" class="btn btn-outline-primary btn-sm">Kembali ke Daftar</a>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4 shadow-sm border-0 bg-body">
                        <div class="card-body text-center">
                            <h6 class="mb-1">Butuh Bantuan?</h6>
                            <p class="text-muted mb-3">Hubungi pengurus RT untuk koordinasi cepat.</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Kontak Pengurus</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @pushOnce('page-scripts')
        <script @cspNonce>
            document.addEventListener('DOMContentLoaded', function () {
                gsap.from('#detailReport .timeline li', {
                    opacity: 0,
                    x: -30,
                    duration: 0.6,
                    stagger: 0.12,
                });
            });
        </script>
        <script @cspNonce>
            (function(){
                var share = document.getElementById('shareBtn');

                share.addEventListener('click', function(){
                    var data = {
                        title: {!! json_encode($report->title) !!},
                        text: {!! json_encode($report->description) !!},
                        url: window.location.href
                    };
                    if (navigator.share) {
                        navigator.share(data).catch(function(){});
                    } else if (navigator.clipboard) {
                        navigator.clipboard.writeText(window.location.href).then(function(){
                            var toast = document.createElement('div');
                            toast.textContent = 'Link disalin ke clipboard';
                            toast.style.position = 'fixed';
                            toast.style.right = '20px';
                            toast.style.bottom = '20px';
                            toast.style.padding = '8px 12px';
                            toast.style.background = 'rgba(0,0,0,0.75)';
                            toast.style.color = '#fff';
                            toast.style.borderRadius = '6px';
                            document.body.appendChild(toast);
                            setTimeout(function(){ toast.remove(); }, 1800);
                        });
                    } else {
                        window.open('mailto:?subject=' + encodeURIComponent(data.title) + '&body=' + encodeURIComponent(data.url + '\n\n' + data.text));
                    }
                });
            })();
        </script>
    @endPushOnce
</x-layouts.landing>
