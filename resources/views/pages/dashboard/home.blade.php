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
                                <h5 class="card-title text-primary mb-2">Welcome to WARGA PLUS! 🚀</h5>
                                <p class="mb-3 text-muted">
                                    You have been actively participating in community reports. Keep up!<br>
                                    Click the button below to create a new report and contribute further to our
                                    community.
                                </p>
                                <button data-route="{{ route('dashboard.report.index') }}"
                                    id="dashboard-write-report-button"
                                    class="btn btn-primary btn-sm rounded-pill px-4">Write a Report</button>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="card-body p-0">
                                <img src="{{ asset('img/illustrations/man-with-laptop-dark.png') }}" height="100"
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
                                <h6 class="mb-0 fw-semibold">My Reports</h6>
                                <span class="badge bg-warning text-black rounded-pill">Year {{ now()->format('Y') }}</span>
                            </div>
                        </div>

                        <div class="mb-2">
                            <h3 class="mb-0 fw-bold">
                                {{ $totalMyReports ?? 0 }}
                                {{ Str::plural('Report', $totalMyReports ?? 0) }}
                            </h3>
                            <small class="text-muted">Reports you've submitted this year</small>
                        </div>

                        <div id="profileReportChart"></div>
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
                                <h5 class="card-title mb-0">Total Report Created</h5>
                                <small class="card-subtitle">Yearly report overview about residents report being created
                                    and processed on our website</small>
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
            (function () {
                try {
                    const overviewData = @json($chartData) ?? [];
                    const myReportData = @json($myReports) ?? [];
                    const chartColors = @json($chartColorData) ?? ['#7367F0'];

                    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

                    const commonLabelStyle = {
                        offsetX: 0,
                        style: { colors: '#a1acb8', fontSize: '13px' }
                    };

                    const createAndRender = (el, config) => {
                        if (!el) return;
                        new ApexCharts(el, config).render();
                    };

                    const totalProductEl = document.getElementById('totalProductEl');
                    const totalProductConfig = {
                        chart: { height: 250, type: 'area', toolbar: true },
                        legend: {
                            show: true,
                            position: 'top',
                            horizontalAlign: 'right',
                            markers: { width: 10, height: 10, radius: 12 },
                            itemMargin: { horizontal: 10, vertical: 5 },
                            fontSize: '14px',
                            labels: { colors: '#fff' }
                        },
                        series: overviewData,
                        dataLabels: { enabled: false },
                        stroke: { width: 3, curve: 'smooth' },
                        colors: chartColors,
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
                            padding: { top: -15, bottom: -10, left: 0, right: 0 }
                        },
                        xaxis: { categories: months, labels: commonLabelStyle, axisBorder: { show: false }, axisTicks: { show: false }, lines: { show: false } },
                        yaxis: {
                            labels: {
                                offsetX: -15,
                                style: { fontSize: '13px', colors: '#a1acb8' },
                                formatter: val => parseInt(val, 10)
                            },
                            min: 0
                        }
                    };

                    createAndRender(totalProductEl, totalProductConfig);

                    const profileReportChartEl = document.getElementById('profileReportChart');
                    const profileReportChartConfig = {
                        chart: {
                            height: 110,
                            type: 'area',
                            toolbar: { show: false },
                            sparkline: { enabled: true }
                        },
                        grid: { show: false, padding: { right: 8 } },
                        colors: chartColors,
                        dataLabels: { enabled: false },
                        stroke: { width: 5, curve: 'smooth' },
                        series: myReportData,
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
                        xaxis: { categories: months, labels: commonLabelStyle, axisBorder: { show: false }, axisTicks: { show: false }, lines: { show: false } },
                        yaxis: { show: true, labels: { show: true, formatter: val => parseInt(val, 10) }, min: 0 }
                    };

                    createAndRender(profileReportChartEl, profileReportChartConfig);
                } catch (e) {
                    console.error(e);
                }
            })();
        </script>
    @endPushOnce
</x-layouts.dashboard>
