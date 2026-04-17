@extends('layouts.admin')
@section('title', 'الطلبات')
@section('page-title', 'إدارة الطلبات')

@push('styles')
    <style>
        /* ── Status Pill ────────────────────────────── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 30px;
            white-space: nowrap;
            border: none;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
            transition: 0.2s;
        }

        .status-pill::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        /* ── Stat Strip ─────────────────────────────── */
        .stat-strip {
            background: #fff;
            border-radius: 16px;
            padding: 16px 20px;
            border: 1.5px solid #f0e8e0;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.25s;
            text-decoration: none;
            color: inherit;
        }

        .stat-strip:hover {
            box-shadow: 0 6px 22px rgba(0, 0, 0, 0.08);
            transform: translateY(-3px);
        }

        .stat-strip.active-filter {
            border-color: #7b1113;
            background: rgba(123, 17, 19, 0.03);
        }

        .stat-strip .num {
            font-size: 1.7rem;
            font-weight: 900;
            line-height: 1;
        }

        .stat-strip .lbl {
            font-size: 0.75rem;
            color: #999;
            font-weight: 700;
            margin-top: 3px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        /* ── Status Modal ───────────────────────────── */
        .s-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9000;
            align-items: center;
            justify-content: center;
        }

        .s-overlay.open {
            display: flex;
        }

        .s-modal {
            background: #fff;
            border-radius: 22px;
            padding: 30px;
            width: 360px;
            max-width: 92vw;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.18);
            animation: fadeInUp 0.28s ease;
        }

        .s-opt {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 13px;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.15s;
            border: 1.5px solid transparent;
            margin-bottom: 4px;
        }

        .s-opt:hover {
            background: #faf6f2;
        }

        .s-opt.selected {
            border-color: #7b1113;
            background: rgba(123, 17, 19, 0.04);
        }

        .s-dot {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <div>
                <h4 class="fw-bold m-0 text-brown">🛍️ الطلبات</h4>
                <p class="text-muted small mb-0 mt-1">{{ number_format($stats['all']) }} طلب إجمالاً</p>
            </div>
            <a href="{{ route('admin.orders.create') }}" class="btn-maroon">
                <i class="bi bi-plus-lg"></i> طلب يدوي
            </a>
        </div>

        {{-- Stat Strips --}}
        @php
            $strips = [
                ['', 'الكل', $stats['all'], '#422018', 'bi-grid-fill'],
                ['pending', 'انتظار', $stats['pending'], '#d35400', 'bi-hourglass-split'],
                ['confirmed', 'مؤكدة', $stats['confirmed'], '#1a8a4a', 'bi-check-circle-fill'],
                ['completed', 'مكتملة', $stats['completed'], '#1e4d2b', 'bi-bag-check-fill'],
                ['cancelled', 'ملغاة', $stats['cancelled'], '#7b1113', 'bi-x-circle-fill'],
            ];
        @endphp
        <div class="row g-3 mb-4">
            @foreach ($strips as [$sv, $sl, $sc, $color, $icon])
                <div class="col-6 col-md">
                    <a href="{{ route('admin.orders.index', $sv ? ['status' => $sv] : []) }}"
                        class="stat-strip {{ request('status', '') === $sv ? 'active-filter' : '' }}">
                        <div class="stat-icon" style="background:{{ $color }}18; color:{{ $color }}">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div>
                            <div class="num" style="color:{{ $color }}">{{ $sc }}</div>
                            <div class="lbl">{{ $sl }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Table Card --}}
        <div class="main-table">

            {{-- Filters --}}
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <div class="d-flex gap-3 flex-wrap align-items-center mb-4">

                    <select name="status" class="form-select" style="width:155px" onchange="this.form.submit()">
                        <option value="">كل الحالات</option>
                        @foreach (\App\Models\Order::statusOptions() as $v => $l)
                            <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>
                                {{ $l }}</option>
                        @endforeach
                    </select>

                    <select name="payment_status" class="form-select" style="width:175px" onchange="this.form.submit()">
                        <option value="">كل حالات الدفع</option>
                        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>لم يُدفع</option>
                        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>بانتظار تأكيد
                        </option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>تم الدفع ✅
                        </option>
                    </select>

                    <div class="me-auto" style="position:relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="اسم العميل أو رقم الطلب..." class="form-control"
                            style="width:260px;padding-left:40px">
                        <i class="bi bi-search"
                            style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#bbb;font-size:0.9rem"></i>
                    </div>

                    <button type="submit" class="btn-maroon px-4">
                        <i class="bi bi-funnel-fill"></i> بحث
                    </button>

                    @if (request()->hasAny(['status', 'payment_status', 'search']))
                        <a href="{{ route('admin.orders.index') }}" class="btn-action py-2 px-3 text-decoration-none"
                            title="مسح الفلاتر">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th style="min-width:120px">رقم الطلب</th>
                            <th>العميل</th>
                            <th>المنتج</th>
                            <th>الإجمالي</th>
                            <th>الدفع</th>
                            <th>الحالة</th>
                            <th>المصدر</th>
                            <th>التاريخ</th>
                            <th style="min-width:120px">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>

                                {{-- Order Number --}}
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        style="font-weight:900;color:#7b1113;font-size:0.88rem;text-decoration:none;font-family:monospace">
                                        {{ $order->order_number }}
                                    </a>
                                </td>

                                {{-- Customer --}}
                                <td>
                                    <div style="font-weight:700;color:#2d1e14;font-size:0.9rem">{{ $order->customer_name }}
                                    </div>
                                    <div style="font-size:0.77rem;color:#aaa;margin-top:2px">{{ $order->customer_phone }}
                                    </div>
                                </td>

                                {{-- Product --}}
                                <td>
                                    <div style="font-size:0.87rem;font-weight:700;color:#422018;max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                                        title="{{ $order->product_name }}">
                                        {{ $order->product_name }}
                                    </div>
                                    <div style="font-size:0.74rem;color:#bbb">{{ number_format($order->product_price) }} ₪
                                        × {{ $order->quantity }}</div>
                                </td>

                                {{-- Total --}}
                                <td>
                                    <span
                                        style="font-size:0.97rem;font-weight:900;color:#7b1113">{{ number_format($order->total_price) }}
                                        ₪</span>
                                </td>

                                {{-- Payment Status --}}
                                <td>
                                    @php
                                        $pMap = [
                                            'unpaid' => ['#fef3e8', '#d35400'],
                                            'pending' => ['#fdf8e8', '#9a7d0a'],
                                            'paid' => ['#e8faf0', '#1a8a4a'],
                                        ];
                                        [$pbg, $ptx] = $pMap[$order->payment_status] ?? ['#f5f5f5', '#888'];
                                    @endphp
                                    <span
                                        style="background:{{ $pbg }};color:{{ $ptx }};font-size:0.74rem;font-weight:800;padding:4px 11px;border-radius:20px;white-space:nowrap">
                                        {{ $order->payment_status_label }}
                                    </span>
                                </td>

                                {{-- Status (clickable) --}}
                                <td>
                                    <button class="status-pill"
                                        style="background:{{ $order->status_bg }};color:{{ $order->status_color }}"
                                        onclick="openModal({{ $order->id }}, '{{ $order->status }}')"
                                        title="اضغط لتغيير الحالة">
                                        {{ $order->status_label }}
                                        <i class="bi bi-chevron-down" style="font-size:0.55rem;opacity:0.7"></i>
                                    </button>
                                </td>

                                {{-- Source --}}
                                <td style="font-size:0.8rem;color:#999">{{ $order->source_label }}</td>

                                {{-- Date --}}
                                <td style="white-space:nowrap">
                                    <div style="font-size:0.82rem;color:#666">{{ $order->created_at->format('d/m/Y') }}
                                    </div>
                                    <div style="font-size:0.72rem;color:#bbb">{{ $order->created_at->format('H:i') }}</div>
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div class="d-flex gap-2">
                                        @if ($order->customer_phone)
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer_phone) }}?text={{ urlencode('السلام عليكم ' . $order->customer_name . '، بخصوص طلبك ' . $order->order_number . ' من ' . setting('site.name') . ' ✨') }}"
                                                target="_blank"
                                                style="width:33px;height:33px;border-radius:9px;background:#e8f9ef;color:#25D366;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;flex-shrink:0"
                                                title="رسالة واتساب">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="btn-action py-1 px-3 text-decoration-none" style="font-size:0.8rem">
                                            عرض
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div style="font-size:3.5rem;margin-bottom:14px;opacity:0.12">🛍️</div>
                                    <div style="font-weight:800;color:#422018;font-size:1rem;margin-bottom:6px">لا توجد
                                        طلبات</div>
                                    <div style="font-size:0.87rem;color:#aaa">
                                        @if (request()->hasAny(['status', 'payment_status', 'search']))
                                            لا نتائج — <a href="{{ route('admin.orders.index') }}"
                                                class="text-maroon fw-bold">عرض الكل</a>
                                        @else
                                            ستظهر الطلبات هنا فور ورودها
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            {{ $orders->withQueryString()->links() }}

        </div>{{-- /main-table --}}
    </div>

    {{-- ── Quick Status Modal ─────────────────────────────────────────────── --}}
    <div class="s-overlay" id="sOverlay" onclick="closeModal(event)">
        <div class="s-modal" onclick="event.stopPropagation()">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 style="font-weight:900;color:#422018;margin:0;font-size:1rem">تغيير حالة الطلب</h6>
                <button onclick="closeModal()"
                    style="background:none;border:none;color:#bbb;font-size:1.1rem;cursor:pointer;padding:4px">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            @php $dotColors = \App\Models\Order::statusDotColors(); @endphp
            <div>
                @foreach (\App\Models\Order::statusOptions() as $v => $l)
                    <div class="s-opt" data-value="{{ $v }}" onclick="pickStatus('{{ $v }}')">
                        <div class="s-dot" style="background:{{ $dotColors[$v] ?? '#888' }}"></div>
                        <span style="font-weight:700;font-size:0.9rem;color:#333">{{ $l }}</span>
                    </div>
                @endforeach
            </div>

            <div class="d-flex gap-3 mt-4 pt-3" style="border-top:1px solid #f0e8e0">
                <button onclick="saveStatus()" class="btn-maroon flex-grow-1">
                    <i class="bi bi-check2"></i> حفظ الحالة
                </button>
                <button onclick="closeModal()" class="btn-action px-4">إلغاء</button>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let _orderId = null;
        let _status = null;

        function openModal(id, current) {
            _orderId = id;
            _status = current;
            document.querySelectorAll('.s-opt').forEach(o => {
                o.classList.toggle('selected', o.dataset.value === current);
            });
            document.getElementById('sOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(e) {
            if (!e || e.target === document.getElementById('sOverlay')) {
                document.getElementById('sOverlay').classList.remove('open');
                document.body.style.overflow = '';
            }
        }

        function pickStatus(val) {
            _status = val;
            document.querySelectorAll('.s-opt').forEach(o => o.classList.toggle('selected', o.dataset.value === val));
        }

        async function saveStatus() {
            if (!_orderId || !_status) return;

            try {
                const res = await fetch(`/admin/orders/${_orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        status: _status
                    }),
                });

                const json = await res.json();

                if (json.success) {
                    // تحديث الزر بدون reload
                    document.querySelectorAll(`.status-pill[onclick*="openModal(${_orderId},"]`).forEach(btn => {
                        btn.style.background = json.bg;
                        btn.style.color = json.color;
                        btn.innerHTML =
                            `${json.label} <i class="bi bi-chevron-down" style="font-size:0.55rem;opacity:0.7"></i>`;
                        btn.setAttribute('onclick', `openModal(${_orderId}, '${json.status}')`);
                    });
                    toast('✅ تم تحديث حالة الطلب');
                    closeModal();
                }
            } catch (err) {
                // fallback: reload
                window.location.reload();
            }
        }

        function toast(msg) {
            const el = document.createElement('div');
            el.textContent = msg;
            el.style.cssText = [
                'position:fixed', 'bottom:28px', 'left:50%',
                'transform:translateX(-50%) translateY(14px)',
                'background:#2d1e14', 'color:#fff',
                'padding:12px 26px', 'border-radius:30px',
                "font-family:'Cairo',sans-serif", 'font-weight:700',
                'font-size:0.9rem', 'z-index:9999',
                'opacity:0', 'transition:all 0.3s ease',
                'box-shadow:0 8px 28px rgba(0,0,0,0.2)',
                'pointer-events:none',
            ].join(';');
            document.body.appendChild(el);
            requestAnimationFrame(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateX(-50%) translateY(0)';
            });
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(-50%) translateY(10px)';
                setTimeout(() => el.remove(), 350);
            }, 2800);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
@endpush
