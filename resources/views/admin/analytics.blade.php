@extends('layouts.admin')
@section('title', 'التحليلات')
@section('page-title', 'تحليلات الموقع')

@push('styles')
    <style>
        /* ── Analytics Cards ────────────────────────────── */
        .an-card {
            background: #fff;
            border-radius: 20px;
            border: 1.5px solid #f0e8e0;
            padding: 24px;
            transition: all 0.25s;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        }

        .an-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
        }

        /* LIVE card */
        .an-card.live {
            background: linear-gradient(135deg, #422018, #7b1113);
            border: none;
        }

        .an-num {
            font-size: 2.8rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 4px;
        }

        .an-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #aaa;
        }

        /* ── Live pulse ─────────────────────────────────── */
        .live-dot {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(37, 211, 102, 0.15);
            color: #25D366;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        .live-dot-inner {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #25D366;
            animation: live-pulse 1.4s ease-in-out infinite;
        }

        @keyframes live-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.4;
                transform: scale(0.8);
            }
        }

        /* ── Chart container ────────────────────────────── */
        .chart-wrap {
            background: #fff;
            border-radius: 20px;
            border: 1.5px solid #f0e8e0;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        }

        .chart-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #422018;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Top pages table ────────────────────────────── */
        .page-row {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #faf4ef;
            gap: 12px;
        }

        .page-row:last-child {
            border-bottom: none;
        }

        .page-bar-wrap {
            flex: 1;
        }

        .page-bar-bg {
            height: 6px;
            background: #f0e8e0;
            border-radius: 6px;
            overflow: hidden;
        }

        .page-bar-fill {
            height: 100%;
            background: linear-gradient(to left, #d4af37, #7b1113);
            border-radius: 6px;
            transition: width 1s ease;
        }

        /* ── Device donut ────────────────────────────────── */
        .device-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #faf4ef;
        }

        .device-item:last-child {
            border-bottom: none;
        }

        .device-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ── Loading skeleton ───────────────────────────── */
        .skel {
            background: linear-gradient(90deg, #f0e8e0 25%, #fdf9f4 50%, #f0e8e0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
            border-radius: 8px;
        }

        @keyframes shimmer {
            to {
                background-position: -200% 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        {{-- ── Header ─────────────────────────────────────────────────────────── --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <div>
                <h4 class="fw-bold m-0 text-brown">📊 تحليلات الموقع</h4>
                <p class="text-muted small mb-0 mt-1">بيانات حقيقية من زوار لمسة خيط</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="live-dot">
                    <div class="live-dot-inner"></div>
                    تحديث تلقائي كل 30 ث
                </div>
            </div>
        </div>

        {{-- ── Row 1: Live Cards ──────────────────────────────────────────────── --}}
        <div class="row g-3 mb-4">

            {{-- LIVE — Active now --}}
            <div class="col-6 col-md-3">
                <div class="an-card live" style="position:relative;overflow:hidden">
                    {{-- Decorative pattern --}}
                    <div
                        style="position:absolute;inset:0;background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\'%3E%3Ccircle cx=\'20\' cy=\'20\' r=\'1.2\' fill=\'%23fff\' opacity=\'0.06\'/%3E%3C/svg%3E');background-size:40px;pointer-events:none">
                    </div>
                    <div style="position:relative;z-index:1">
                        <div class="live-dot" style="background:rgba(37,211,102,0.2)">
                            <div class="live-dot-inner"></div>
                            الآن
                        </div>
                        <div class="an-num" style="color:#fff" id="live-active-count">
                            <span class="skel" style="width:60px;height:40px;display:inline-block"></span>
                        </div>
                        <div class="an-label" style="color:rgba(255,255,255,0.6)">زائر نشط الآن</div>
                    </div>
                </div>
            </div>

            {{-- Today views --}}
            <div class="col-6 col-md-3">
                <div class="an-card">
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(123,17,19,0.08);color:#7b1113;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:16px">
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div class="an-num text-maroon" id="live-today-views">
                        <span class="skel" style="width:50px;height:36px;display:inline-block"></span>
                    </div>
                    <div class="an-label">مشاهدة اليوم</div>
                </div>
            </div>

            {{-- Today visitors --}}
            <div class="col-6 col-md-3">
                <div class="an-card">
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(26,138,74,0.08);color:#1a8a4a;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:16px">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="an-num" style="color:#1a8a4a" id="live-today-visitors">
                        <span class="skel" style="width:50px;height:36px;display:inline-block"></span>
                    </div>
                    <div class="an-label">زائر فريد اليوم</div>
                </div>
            </div>

            {{-- All time --}}
            <div class="col-6 col-md-3">
                <div class="an-card">
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:rgba(212,175,55,0.12);color:#9a7d0a;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:16px">
                        <i class="bi bi-globe2"></i>
                    </div>
                    <div class="an-num text-gold" id="total-views">
                        <span class="skel" style="width:60px;height:36px;display:inline-block"></span>
                    </div>
                    <div class="an-label">إجمالي الزيارات</div>
                </div>
            </div>

        </div>

        {{-- ── Row 2: Chart + Top Pages ──────────────────────────────────────── --}}
        <div class="row g-4 mb-4">

            {{-- Line chart — 7 days ─────────────────────────────────────────────── --}}
            <div class="col-lg-7">
                <div class="chart-wrap" style="height:320px">
                    <div class="chart-title">
                        <i class="bi bi-graph-up-arrow text-maroon"></i>
                        الزيارات — آخر 7 أيام
                    </div>
                    <canvas id="visits-chart" style="max-height:240px"></canvas>
                </div>
            </div>

            {{-- Top Pages ────────────────────────────────────────────────────────── --}}
            <div class="col-lg-5">
                <div class="chart-wrap" style="height:320px;overflow-y:auto">
                    <div class="chart-title">
                        <i class="bi bi-bar-chart-line text-maroon"></i>
                        أكثر الصفحات زيارةً
                    </div>
                    <div id="top-pages-list">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="page-row">
                                <div class="skel" style="width:80px;height:14px"></div>
                                <div class="page-bar-wrap">
                                    <div class="page-bar-bg">
                                        <div class="page-bar-fill" style="width:0"></div>
                                    </div>
                                </div>
                                <div class="skel" style="width:30px;height:14px"></div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Row 3: Devices + This Month ───────────────────────────────────── --}}
        <div class="row g-4 mb-4">

            {{-- Devices --}}
            <div class="col-md-5">
                <div class="chart-wrap">
                    <div class="chart-title">
                        <i class="bi bi-laptop text-maroon"></i>
                        توزيع الأجهزة (آخر 30 يوم)
                    </div>
                    <div style="display:flex;gap:20px;align-items:center">
                        <canvas id="device-chart"
                            style="width:130px!important;height:130px!important;flex-shrink:0"></canvas>
                        <div id="device-legend" style="flex:1;display:flex;flex-direction:column;gap:0">
                            <div class="skel" style="height:14px;margin-bottom:10px"></div>
                            <div class="skel" style="height:14px;margin-bottom:10px"></div>
                            <div class="skel" style="height:14px"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Monthly Stats --}}
            <div class="col-md-7">
                <div class="chart-wrap">
                    <div class="chart-title">
                        <i class="bi bi-calendar3 text-maroon"></i>
                        ملخص الشهر الحالي
                    </div>
                    <div class="row g-3" id="monthly-stats">
                        @foreach ([['إجمالي مشاهدات الشهر', 'bi-eye', '#7b1113', 'month-views'], ['زوار فريدون هذا الشهر', 'bi-people', '#1a8a4a', 'month-visitors'], ['إجمالي كل الأوقات', 'bi-infinity', '#9a7d0a', 'all-views']] as [$label, $icon, $color, $id])
                            <div class="col-6">
                                <div style="background:var(--cream);border-radius:14px;padding:16px">
                                    <i class="bi {{ $icon }}"
                                        style="color:{{ $color }};font-size:1.3rem;display:block;margin-bottom:8px"></i>
                                    <div style="font-size:1.5rem;font-weight:900;color:{{ $color }}"
                                        id="{{ $id }}">
                                        <span class="skel" style="width:50px;height:24px;display:inline-block"></span>
                                    </div>
                                    <div style="font-size:0.78rem;color:#aaa;font-weight:700;margin-top:3px">
                                        {{ $label }}</div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-6">
                            <div
                                style="background:linear-gradient(135deg,#422018,#7b1113);border-radius:14px;padding:16px">
                                <i class="bi bi-globe2"
                                    style="color:rgba(255,255,255,0.7);font-size:1.3rem;display:block;margin-bottom:8px"></i>
                                <div style="font-size:1.5rem;font-weight:900;color:#fff" id="live-active-count-2">—</div>
                                <div style="font-size:0.78rem;color:rgba(255,255,255,0.55);font-weight:700;margin-top:3px">
                                    نشط الآن</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        const LIVE_URL = '{{ route('admin.analytics.live') }}';
        const SUMMARY_URL = '{{ route('admin.analytics.summary') }}';

        let visitsChart = null;
        let deviceChart = null;

        // ── Helpers ────────────────────────────────────────────────────────
        const fmt = n => Number(n || 0).toLocaleString('ar-SA');

        function setEl(id, val) {
            const el = document.getElementById(id);
            if (el) el.textContent = fmt(val);
        }

        function clearSkel(id) {
            const el = document.getElementById(id);
            if (el) el.querySelector('.skel')?.remove();
        }

        // ── Fetch Live Data (كل 30 ثانية) ─────────────────────────────────
        async function fetchLive() {
            try {
                const res = await fetch(LIVE_URL, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();

                const active = data.active_now ?? 0;
                const views = data.today?.views ?? 0;
                const visitors = data.today?.visitors ?? 0;

                // Live cards
                clearSkel('live-active-count');
                clearSkel('live-today-views');
                clearSkel('live-today-visitors');
                setEl('live-active-count', active);
                setEl('live-today-views', views);
                setEl('live-today-visitors', visitors);
                setEl('live-active-count-2', active);

                // Sidebar badge update
                const sbBadge = document.getElementById('sb-active-badge');
                const sbCount = document.getElementById('sb-active-count');
                if (sbBadge && sbCount) {
                    sbCount.textContent = active;
                    sbBadge.style.display = active > 0 ? 'inline-flex' : 'none';
                }

            } catch (e) {}
        }

        // ── Fetch Summary Data (مرة عند التحميل) ──────────────────────────
        async function fetchSummary() {
            try {
                const res = await fetch(SUMMARY_URL, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();

                // ── Totals ─────────────────────────────────────────────────────
                clearSkel('total-views');
                clearSkel('month-views');
                clearSkel('month-visitors');
                clearSkel('all-views');

                setEl('total-views', data.totals?.total_views);
                setEl('month-views', data.totals?.this_month);
                setEl('month-visitors', data.totals?.total_visitors);
                setEl('all-views', data.totals?.total_views);

                // ── 7-Day Chart ────────────────────────────────────────────────
                const days = data.last7days ?? [];
                const labels = days.map(d => d.label);
                const views = days.map(d => d.views);
                const visitors = days.map(d => d.visitors);

                const ctx = document.getElementById('visits-chart')?.getContext('2d');
                if (ctx) {
                    if (visitsChart) visitsChart.destroy();
                    visitsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                    label: 'مشاهدات',
                                    data: views,
                                    borderColor: '#7b1113',
                                    backgroundColor: 'rgba(123,17,19,0.07)',
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: '#7b1113',
                                    pointRadius: 4,
                                },
                                {
                                    label: 'زوار',
                                    data: visitors,
                                    borderColor: '#d4af37',
                                    backgroundColor: 'rgba(212,175,55,0.07)',
                                    tension: 0.4,
                                    fill: false,
                                    borderDash: [5, 4],
                                    pointBackgroundColor: '#d4af37',
                                    pointRadius: 4,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    labels: {
                                        font: {
                                            family: 'Cairo',
                                            size: 12
                                        },
                                        color: '#422018',
                                    },
                                },
                                tooltip: {
                                    rtl: true,
                                    titleFont: {
                                        family: 'Cairo'
                                    },
                                    bodyFont: {
                                        family: 'Cairo'
                                    },
                                },
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        font: {
                                            family: 'Cairo',
                                            size: 11
                                        },
                                        color: '#aaa'
                                    },
                                    grid: {
                                        color: '#f0e8e0'
                                    },
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            family: 'Cairo',
                                            size: 11
                                        },
                                        color: '#aaa',
                                        callback: v => Number(v).toLocaleString('ar-SA')
                                    },
                                    grid: {
                                        color: '#f0e8e0'
                                    },
                                },
                            },
                        },
                    });
                }

                // ── Top Pages ──────────────────────────────────────────────────
                const pages = data.top_pages ?? [];
                const maxViews = Math.max(...pages.map(p => p.views), 1);
                const listEl = document.getElementById('top-pages-list');
                if (listEl) {
                    listEl.innerHTML = pages.length ? pages.map(p => `
        <div class="page-row">
          <div style="font-size:0.82rem;font-weight:700;color:#422018;min-width:100px">${p.label}</div>
          <div class="page-bar-wrap">
            <div class="page-bar-bg">
              <div class="page-bar-fill" style="width:${Math.round((p.views/maxViews)*100)}%"></div>
            </div>
          </div>
          <div style="font-size:0.82rem;font-weight:800;color:#7b1113;white-space:nowrap">${fmt(p.views)}</div>
        </div>
      `).join('') : '<div class="text-center text-muted py-3" style="font-size:0.87rem">لا بيانات بعد</div>';
                }

                // ── Device Donut Chart ─────────────────────────────────────────
                const devices = data.devices ?? [];
                const devColors = {
                    desktop: '#7b1113',
                    mobile: '#d4af37',
                    tablet: '#1a8a4a'
                };

                const dCtx = document.getElementById('device-chart')?.getContext('2d');
                if (dCtx && devices.length) {
                    if (deviceChart) deviceChart.destroy();
                    deviceChart = new Chart(dCtx, {
                        type: 'doughnut',
                        data: {
                            labels: devices.map(d => d.label),
                            datasets: [{
                                data: devices.map(d => d.count),
                                backgroundColor: devices.map(d => devColors[d.device] || '#aaa'),
                                borderWidth: 0,
                                hoverOffset: 6,
                            }],
                        },
                        options: {
                            responsive: false,
                            cutout: '68%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    titleFont: {
                                        family: 'Cairo'
                                    },
                                    bodyFont: {
                                        family: 'Cairo'
                                    },
                                }
                            },
                        },
                    });

                    // Legend
                    const legend = document.getElementById('device-legend');
                    if (legend) {
                        legend.innerHTML = devices.map(d => `
          <div class="device-item">
            <div class="device-dot" style="background:${devColors[d.device]||'#aaa'}"></div>
            <div style="flex:1;font-size:0.83rem;font-weight:700;color:#422018">${d.label}</div>
            <div style="font-size:0.82rem;color:#7b1113;font-weight:800">${d.percent}%</div>
          </div>
        `).join('');
                    }
                } else if (devices.length === 0) {
                    const legend = document.getElementById('device-legend');
                    if (legend) legend.innerHTML = '<div style="color:#aaa;font-size:0.83rem">لا بيانات بعد</div>';
                }

            } catch (e) {
                console.error('Analytics error:', e);
            }
        }

        // ── Init ────────────────────────────────────────────────────────────
        fetchLive();
        fetchSummary();
        setInterval(fetchLive, 30000);
    </script>
@endpush
