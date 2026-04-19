@extends('layouts.admin')

@section('title', 'الرئيسية')
@section('page-title', 'الرئيسية')

@section('content')
    <div class="container-fluid px-4">

        {{-- ── Maintenance Banner ────────────────────────────────────────────── --}}
        @php $maintenanceOn = \App\Models\Setting::get('site.maintenance', false); @endphp

        @if ($maintenanceOn)
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 mt-2"
                style="background:linear-gradient(135deg,#422018,#7b1113);border-radius:16px;padding:18px 24px">
                <div class="d-flex align-items-center gap-3">
                    <div
                        style="width:42px;height:42px;border-radius:12px;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">
                        🔧
                    </div>
                    <div>
                        <div style="font-weight:800;color:#fff;font-size:0.95rem">وضع الصيانة مُفعَّل</div>
                        <div style="font-size:0.82rem;color:rgba(255,255,255,0.6)">الزوار يرون صفحة الصيانة — أنت ترى الموقع
                            عادياً كمدير</div>
                    </div>
                </div>
                <a href="{{ route('admin.settings', ['tab' => 'site']) }}"
                    style="background:rgba(255,255,255,0.15);color:#fff;padding:10px 20px;border-radius:12px;font-weight:800;text-decoration:none;font-size:0.88rem;border:1.5px solid rgba(255,255,255,0.25);white-space:nowrap;transition:0.2s"
                    onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    <i class="bi bi-gear-fill me-1"></i> إيقاف الصيانة
                </a>
            </div>
        @endif

        {{-- ── Welcome ──────────────────────────────────────────────────────────── --}}
        <div class="d-flex justify-content-between align-items-center mb-5 mt-2 fade-in-up">
            <div>
                <h2 class="fw-bold m-0" style="color:#422018">
                    مرحباً، {{ auth()->user()->name ?? 'مدير لمسة خيط' }} ✨
                </h2>
                <p class="text-muted mb-0 mt-1">نظرة سريعة على ما أنجزته أناملك في "لمسة خيط".</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-maroon">
                <i class="bi bi-plus-lg"></i> إضافة منتج
            </a>
        </div>

        {{-- ── Stats Cards ──────────────────────────────────────────────────────── --}}
        <div class="row g-4 mb-5">
            <div class="col-6 col-md-3 fade-in-up delay-1">
                <div class="card-stat h-100">
                    <div class="icon-shape bg-embroidery"><i class="bi bi-stars"></i></div>
                    <h5 class="fw-bold mb-1">قسم التطريز</h5>
                    <p class="text-muted small mb-3">ثياب، مناديل، وإكسسوارات</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold m-0 text-maroon">{{ $embroideryCount }}</h3>
                        <a href="{{ route('admin.products.index', ['category' => 'embroidery']) }}"
                            class="btn-action">إدارة</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 fade-in-up delay-2">
                <div class="card-stat h-100">
                    <div class="icon-shape bg-handicrafts"><i class="bi bi-scissors"></i></div>
                    <h5 class="fw-bold mb-1">أشغال يدوية</h5>
                    <p class="text-muted small mb-3">كروشيه، خرازة، وتحف فنية</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold m-0" style="color:#1e4d2b">{{ $handicraftCount }}</h3>
                        <a href="{{ route('admin.products.index', ['category' => 'handicraft']) }}" class="btn-action"
                            style="background:#e9fdf0;color:#1e4d2b">إدارة</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 fade-in-up delay-3">
                <div class="card-stat h-100">
                    <div class="icon-shape bg-wool"><i class="bi bi-wind"></i></div>
                    <h5 class="fw-bold mb-1">أعمال الصوف</h5>
                    <p class="text-muted small mb-3">شالات، كروشيه، ملابس شتوية</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold m-0" style="color:#d35400">{{ $woolCount }}</h3>
                        <a href="{{ route('admin.products.index', ['category' => 'wool']) }}" class="btn-action">إدارة</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 fade-in-up delay-4">
                <div class="card-stat h-100">
                    <div class="icon-shape bg-gold"><i class="bi bi-bag-check-fill"></i></div>
                    <h5 class="fw-bold mb-1">الطلبات</h5>
                    <p class="text-muted small mb-3">طلبات بانتظار المعالجة</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold m-0 text-gold">{{ $pendingOrders }}</h3>
                        <a href="{{ route('admin.orders.index') }}" class="btn-action"
                            style="background:#fdf8e8;color:#9a7d0a">إدارة</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Mini Stats ───────────────────────────────────────────────────────── --}}
        <div class="row g-3 mb-5 fade-in-up">
            <div class="col-6 col-md-3">
                <div class="stat-mini">
                    <div class="notify-icon bg-success bg-opacity-10 text-success"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <div class="num">{{ $totalProducts }}</div>
                        <div class="lbl">إجمالي المنتجات</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.analytics') }}" style="text-decoration:none">
                    <div class="stat-mini" style="cursor:pointer;transition:0.2s"
                        onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,0.08)'"
                        onmouseout="this.style.boxShadow=''">
                        <div style="position:relative">
                            <div class="notify-icon" style="background:rgba(26,138,74,0.1);color:#25D366"><i
                                    class="bi bi-person-check-fill"></i></div>
                            <span id="dash-active-dot"
                                style="position:absolute;top:-2px;right:-2px;width:9px;height:9px;border-radius:50%;background:#25D366;border:2px solid #fff;animation:dot-pulse 1.5s infinite;display:none"></span>
                        </div>
                        <div>
                            <div class="num" id="dash-active-count" style="color:#1a8a4a">—</div>
                            <div class="lbl">نشط الآن</div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-6 col-md-3">
                <div class="stat-mini">
                    <div class="notify-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-chat-dots"></i></div>
                    <div>
                        <div class="num">{{ $unreadMessages }}</div>
                        <div class="lbl">رسائل غير مقروءة</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                {{-- حالة الصيانة كـ stat-mini --}}
                <div class="stat-mini"
                    style="{{ $maintenanceOn ? 'background:rgba(123,17,19,0.06);border:1.5px solid rgba(123,17,19,0.15)' : '' }}">
                    <div class="notify-icon"
                        style="background:{{ $maintenanceOn ? 'rgba(123,17,19,0.1)' : 'rgba(26,138,74,0.1)' }};color:{{ $maintenanceOn ? '#7b1113' : '#1a8a4a' }}">
                        <i class="bi {{ $maintenanceOn ? 'bi-cone-striped' : 'bi-globe2' }}"></i>
                    </div>
                    <div>
                        <div class="num" style="font-size:0.95rem;color:{{ $maintenanceOn ? '#7b1113' : '#1a8a4a' }}">
                            {{ $maintenanceOn ? 'صيانة' : 'نشط' }}
                        </div>
                        <div class="lbl">حالة الموقع</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Recent Products ──────────────────────────────────────────────────── --}}
        <div class="main-table fade-in-up">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">🛍️ أحدث المنتجات</h5>
                <a href="{{ route('admin.products.create') }}" class="btn-maroon">
                    <i class="bi bi-plus-lg"></i> إضافة منتج
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>القسم</th>
                            <th>الفئة</th>
                            <th>الحالة</th>
                            <th>التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProducts as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="product-thumb d-flex align-items-center justify-content-center"
                                            style="font-size:1.6rem;background:var(--cream)">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="product-thumb" alt="{{ $product->name }}">
                                            @else
                                                {{ $product->category_emoji }}
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 small">{{ $product->name }}</h6>
                                            <span class="text-muted" style="font-size:0.72rem">
                                                #{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge-cat badge-{{ $product->category }}">{{ $product->category_label }}</span>
                                </td>
                                <td class="small text-muted">{{ $product->target_label }}</td>
                                <td>
                                    <span
                                        class="{{ $product->is_active ? 'badge-status-active' : 'badge-status-inactive' }}">
                                        {{ $product->is_active ? '● نشط' : '● مخفي' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="btn-action py-1 px-3 text-decoration-none">تعديل</a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            onsubmit="return confirm('حذف هذا المنتج؟')">
                                            @csrf @method('DELETE')
                                            <button class="btn-action py-1 px-3" style="background:#fdf0f0;color:#7b1113">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div style="font-size:3rem;margin-bottom:12px;opacity:0.2">📦</div>
                                    لا توجد منتجات —
                                    <a href="{{ route('admin.products.create') }}" class="text-maroon fw-bold">أضف أول
                                        منتج</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Recent Messages ──────────────────────────────────────────────────── --}}
        @if ($recentMessages->count())
            <div class="mt-5 fade-in-up">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0">💬 آخر الرسائل</h5>
                    <a href="{{ route('admin.messages.index') }}"
                        class="small text-maroon fw-700 text-decoration-none">عرض الكل</a>
                </div>
                <div class="row g-3">
                    @foreach ($recentMessages as $msg)
                        <div class="col-md-6">
                            <div class="msg-card {{ !$msg->is_read ? 'unread' : '' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $msg->name }}</h6>
                                        <small class="text-muted">{{ $msg->phone ?? $msg->email }}</small>
                                    </div>
                                    <span class="time-ago" style="font-size:0.72rem;color:#bbb">
                                        {{ $msg->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="small text-muted mb-2">{{ Str::limit($msg->message, 90) }}</p>
                                <a href="{{ route('admin.messages.show', $msg->id) }}"
                                    class="btn-action py-1 px-3 text-decoration-none">قراءة الرسالة</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection


@push('scripts')
    <script>
        // تحديث "نشط الآن" في الـ Dashboard كل 30 ثانية
        const LIVE_URL_DASH = '{{ route('admin.analytics.live') }}';

        async function refreshDashActive() {
            try {
                const res = await fetch(LIVE_URL_DASH, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                const count = data.active_now ?? 0;

                const el = document.getElementById('dash-active-count');
                const dot = document.getElementById('dash-active-dot');
                if (el) el.textContent = count || '0';
                if (dot) dot.style.display = count > 0 ? 'block' : 'none';
            } catch (e) {}
        }

        refreshDashActive();
        setInterval(refreshDashActive, 30000);
    </script>
@endpush
