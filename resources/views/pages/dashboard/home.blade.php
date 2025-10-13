<x-layouts.dashboard title="Analytics">
    @pushOnce('plugin-styles')
        <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}" @cspNonce />
        <link rel="stylesheet" href="{{ asset('vendor/css/pages/card-analytics.css') }}" @cspNonce />
    @endPushOnce

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="col-lg-8 order-0">
                <div class="card h-100 shadow-sm border-0">
                    <div class="row g-0 align-items-center">
                        <div class="col-sm-8">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-2">Welcome to Your CMS Dashboard! 🚀</h5>
                                <p class="mb-3 text-muted">
                                    Efficiently manage your business content and monitor landing page data storage.<br>
                                    Stay updated with the latest analytics and optimize your website's performance.
                                </p>
                                <a href="{{ route('dashboard.product.index') }}"
                                    class="btn btn-primary btn-sm rounded-pill px-4">Manage Your Product</a>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="card-body p-0">
                                <img src="{{ asset('img/illustrations/man-with-laptop-dark.png') }}" height="120"
                                    class="img-fluid" alt="View Badge User"
                                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" loading="lazy">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 order-1">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-semibold">Article Published</h6>
                                <span class="badge bg-warning text-black rounded-pill">Year {{ now()->format('Y') }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h3 class="mb-0 fw-bold">{{ $totalArticles }} Article</h3>
                        </div>
                        <div id="profileReportChart" style="min-height: 80px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-12">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Total Product Created</h5>
                                <small class="card-subtitle">Yearly report overview about your product being created and published on our website</small>
                            </div>
                            <div class="card-body" style="position: relative;">
                                <div id="totalProductEl" style="min-height: 50%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('plugin-scripts')
        <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}" @cspNonce></script>
    @endPushOnce

    @pushOnce('page-scripts')
        <script @cspNonce>
            var productData = {!! $productChartData !!};
            var articleData = {!! $articleChartData !!};

            const totalProductEl = document.querySelector('#totalProductEl'),
                totalProductConfig = {
                    chart: {
                        height: 250,
                        type: 'area',
                        toolbar: false,
                        dropShadow: {
                            enabled: true,
                            top: 14,
                            left: 2,
                            blur: 3,
                            color: '#696cff',
                            opacity: 0.15
                        }
                    },
                    series: [{
                        data: productData
                    }],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 3,
                        curve: 'straight'
                    },
                    colors: ['#696cff'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            shadeIntensity: 0.8,
                            opacityFrom: 0.7,
                            opacityTo: 0.25,
                            stops: [0, 95, 100]
                        }
                    },
                    grid: {
                        show: true,
                        borderColor: '#eceef1',
                        padding: {
                            top: -15,
                            bottom: -10,
                            left: 0,
                            right: 0
                        }
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        labels: {
                            offsetX: 0,
                            style: {
                                colors: '#a1acb8',
                                fontSize: '13px'
                            }
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            offsetX: -15,
                            style: {
                                fontSize: '13px',
                                colors: '#a1acb8'
                            }
                        },
                        min: 0,
                        tickAmount: 5
                    }
                };
            if (typeof totalProductEl !== undefined && totalProductEl !== null) {
                const totalProduct = new ApexCharts(totalProductEl, totalProductConfig);
                totalProduct.render();
            }

            const profileReportChartEl = document.querySelector('#profileReportChart'),
                profileReportChartConfig = {
                    chart: {
                        height: 80,
                        type: 'line',
                        toolbar: {
                            show: false
                        },
                        dropShadow: {
                            enabled: true,
                            top: 10,
                            left: 5,
                            blur: 3,
                            color: '#ffab00',
                            opacity: 0.15
                        },
                        sparkline: {
                            enabled: true
                        }
                    },
                    grid: {
                        show: false,
                        padding: {
                            right: 8
                        }
                    },
                    colors: ['#ffab00'],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 5,
                        curve: 'smooth'
                    },
                    series: [{
                        data: articleData
                    }],
                    xaxis: {
                        show: false,
                        lines: {
                            show: false
                        },
                        labels: {
                            show: false
                        },
                        axisBorder: {
                            show: false
                        }
                    },
                    yaxis: {
                        show: false
                    }
                };
            if (typeof profileReportChartEl !== undefined && profileReportChartEl !== null) {
                const profileReportChart = new ApexCharts(profileReportChartEl, profileReportChartConfig);
                profileReportChart.render();
            }
        </script>
    @endPushOnce
</x-layouts.dashboard>
