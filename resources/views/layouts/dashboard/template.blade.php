<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIUISKU</title>
    <meta name="description" content="sistem informasi SIUISKU">
    <meta name="keywords" content="admin, dashboard, bootstrap">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logouis.png') }}" rel="icon">
    <link href="{{ asset('assets/img/logouis.png') }}" rel="siuisku">

    <!-- Google Fonts - Manrope + Sora + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/phosphor-icons/phosphor-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/lucide-icons/lucide.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/choices.js/choices.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/flatpickr/flatpickr.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container {
            width: 100% !important;
        }
        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: var(--bs-border-radius, 0.375rem);
            display: flex;
            align-items: center;
            background-color: var(--bs-body-bg, #fff);
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: normal;
            padding-left: 0.75rem;
            padding-right: 2rem;
            color: var(--bs-body-color, #212529);
            font-size: 0.875rem;
        }
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 6px;
        }
        .select2-dropdown {
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: var(--bs-border-radius, 0.375rem);
            font-size: 0.875rem;
            z-index: 1065;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: 0.25rem;
            padding: 0.3rem 0.6rem;
            outline: none;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: var(--accent-color, #0d6efd);
        }
    </style>

</head>

<body>
    @include('layouts.dashboard.header')

    <div class="mobile-search">
        <form class="search-form" action="search-results.html" method="GET">
            <input type="search" name="q" placeholder="Search..." autocomplete="off">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="mobile-header-menu">
        <div class="mobile-header-menu-content">
            <button class="mobile-menu-item theme-toggle" title="Toggle Theme">
                <i class="ph-light ph-moon-stars theme-icon-dark"></i>
                <i class="ph-light ph-sun theme-icon-light"></i>
                <span class="mobile-menu-label">Theme</span>
            </button>

            <a href="notifications.html" class="mobile-menu-item">
                <i class="bi bi-bell"></i>
                <span class="badge">4</span>
                <span class="mobile-menu-label">Alerts</span>
            </a>

            <a href="apps-chat.html" class="mobile-menu-item">
                <i class="bi bi-chat-left-text"></i>
                <span class="mobile-menu-label">Messages</span>
            </a>

            <a href="apps-chat.html" class="mobile-menu-item">
                <i class="bi bi-chat-dots"></i>
                <span class="mobile-menu-label">Chat</span>
            </a>

            <a href="{{ route('profile.index') }}" class="mobile-menu-item">
                <i class="bi bi-person"></i>
                <span class="mobile-menu-label">Profile</span>
            </a>

            <a href="settings.html" class="mobile-menu-item">
                <i class="bi bi-sliders"></i>
                <span class="mobile-menu-label">Settings</span>
            </a>

            <a href="{{ route('logout') }}" class="mobile-menu-item mobile-menu-item-danger">
                <i class="bi bi-box-arrow-right"></i>
                <span class="mobile-menu-label">Sign Out</span>
            </a>
        </div>
    </div>

    @include('layouts.dashboard.sidebar')

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay"></div>

    @sweetAlert
    @yield('content')

    <!-- Back to Top -->
    <a href="#" class="back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/choices.js/choices.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS Files -->
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- App Sidebar Toggle (for app pages with sidebars) -->
    <script src="{{ asset('assets/js/apps-sidebar-toggle.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    <script>
        window.initSelect2 = function (context) {
            if (typeof $ === 'undefined' || !$.fn || !$.fn.select2) return;
            var $root = context ? $(context) : $(document);
            $root.find('select.select2').each(function () {
                var $el = $(this);
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.select2('destroy');
                }
                var $modal = $el.closest('.modal');
                var options = {
                    width: '100%',
                    placeholder: $el.data('placeholder') || 'Pilih opsi...',
                    allowClear: Boolean($el.data('allow-clear'))
                };
                if ($modal.length) {
                    options.dropdownParent = $modal;
                }
                $el.select2(options);
            });
        };

        document.addEventListener('DOMContentLoaded', function () {
            window.initSelect2(document);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--accent-color')
        .trim();
            const successColor = getComputedStyle(document.documentElement).getPropertyValue('--success-color')
                .trim();
            const warningColor = getComputedStyle(document.documentElement).getPropertyValue('--warning-color')
                .trim();
            const infoColor = getComputedStyle(document.documentElement).getPropertyValue('--info-color').trim();
            const borderColor = getComputedStyle(document.documentElement).getPropertyValue('--border-color')
        .trim();
            const mutedColor = getComputedStyle(document.documentElement).getPropertyValue('--muted-color').trim();
            const surfaceColor = getComputedStyle(document.documentElement).getPropertyValue('--surface-color')
                .trim();
            // Revenue data sets per period
            const revenueData = {
                monthly: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                    revenue: [4200, 5800, 4900, 6200, 5100, 7400, 6800, 8100, 7200, 9500, 8900, 10200],
                    expenses: [2800, 3200, 2900, 3400, 3100, 3800, 3500, 4200, 3900, 4800, 4200, 5100],
                    totals: {
                        revenue: '$48,295',
                        expenses: '$28,450',
                        profit: '$19,845'
                    }
                },
                weekly: {
                    categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    revenue: [8450, 9820, 7630, 10350],
                    expenses: [5200, 6100, 4800, 6450],
                    totals: {
                        revenue: '$36,250',
                        expenses: '$22,550',
                        profit: '$13,700'
                    }
                },
                daily: {
                    categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    revenue: [1850, 2100, 1620, 2340, 2780, 1200, 960],
                    expenses: [1100, 1350, 980, 1420, 1680, 750, 580],
                    totals: {
                        revenue: '$12,850',
                        expenses: '$7,860',
                        profit: '$4,990'
                    }
                }
            };

            function formatCurrency(value) {
                return '$' + value.toLocaleString();
            }

            function updateSummary(period) {
                var totals = revenueData[period].totals;
                document.getElementById('summaryRevenue').textContent = totals.revenue;
                document.getElementById('summaryExpenses').textContent = totals.expenses;
                document.getElementById('summaryProfit').textContent = totals.profit;
            }
            // Revenue Overview Chart
            var revenueOptions = {
                series: [{
                    name: 'Revenue',
                    data: revenueData.monthly.revenue
                }, {
                    name: 'Expenses',
                    data: revenueData.monthly.expenses
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'inherit',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    stacked: false,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500
                    }
                },
                colors: [accentColor, successColor],
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                        borderRadius: 4,
                        borderRadiusApplication: 'end'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: revenueData.monthly.categories,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: mutedColor,
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: mutedColor,
                            fontSize: '12px'
                        },
                        formatter: function(value) {
                            return '$' + (value / 1000).toFixed(0) + 'k';
                        }
                    }
                },
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                legend: {
                    show: false
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            };
            var revenueEl = document.querySelector('#revenueChart');
            var revenueChart = null;
            if (revenueEl) {
                revenueChart = new ApexCharts(revenueEl, revenueOptions);
                revenueChart.render();
            }
            // Period tab switching
            document.querySelectorAll('.dash-chart-tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    if (!revenueChart) return;
                    document.querySelectorAll('.dash-chart-tab').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    tab.classList.add('active');
                    var period = tab.getAttribute('data-period');
                    var data = revenueData[period];
                    revenueChart.updateOptions({
                        xaxis: {
                            categories: data.categories
                        },
                    });
                    revenueChart.updateSeries([{
                            name: 'Revenue',
                            data: data.revenue
                        },
                        {
                            name: 'Expenses',
                            data: data.expenses
                        }
                    ]);
                    updateSummary(period);
                });
            });
            // Traffic Sources Donut Chart
            var trafficOptions = {
                series: [42, 28, 18, 12],
                chart: {
                    type: 'donut',
                    height: 260,
                    fontFamily: 'inherit'
                },
                colors: [accentColor, successColor, warningColor, infoColor],
                labels: ['Direct', 'Organic', 'Referral', 'Social'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                name: {
                                    fontSize: '13px',
                                    color: mutedColor
                                },
                                value: {
                                    fontSize: '22px',
                                    fontWeight: 700,
                                    formatter: function(val) {
                                        return val + '%';
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total Visits',
                                    fontSize: '13px',
                                    color: mutedColor,
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce(function(a, b) {
                                            return a + b;
                                        }, 0) + '%';
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                stroke: {
                    width: 3,
                    colors: [surfaceColor]
                }
            };
            var trafficEl = document.querySelector('#trafficChart');
            var trafficChart = null;
            if (trafficEl) {
                trafficChart = new ApexCharts(trafficEl, trafficOptions);
                trafficChart.render();
            }
            // Update charts on theme change
            document.addEventListener('themeChanged', function() {
                var newBorderColor = getComputedStyle(document.documentElement).getPropertyValue(
                    '--border-color').trim();
                var newMutedColor = getComputedStyle(document.documentElement).getPropertyValue(
                    '--muted-color').trim();
                var newSurfaceColor = getComputedStyle(document.documentElement).getPropertyValue(
                    '--surface-color').trim();
                var newAccentColor = getComputedStyle(document.documentElement).getPropertyValue(
                    '--accent-color').trim();
                var newSuccessColor = getComputedStyle(document.documentElement).getPropertyValue(
                    '--success-color').trim();
                var newWarningColor = getComputedStyle(document.documentElement).getPropertyValue(
                    '--warning-color').trim();
                var newInfoColor = getComputedStyle(document.documentElement).getPropertyValue(
                    '--info-color').trim();
                if (revenueChart) {
                    revenueChart.updateOptions({
                        colors: [newAccentColor, newSuccessColor],
                        grid: {
                            borderColor: newBorderColor
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    colors: newMutedColor
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: newMutedColor
                                }
                            }
                        }
                    });
                }
                if (trafficChart) {
                    trafficChart.updateOptions({
                        colors: [newAccentColor, newSuccessColor, newWarningColor, newInfoColor],
                        stroke: {
                            colors: [newSurfaceColor]
                        }
                    });
                }
            });
        });
    </script>

    @stack('script')
    @stack('scripts')
    @stack('style')
</body>

</html>
