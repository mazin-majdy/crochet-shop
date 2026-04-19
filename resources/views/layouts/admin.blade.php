<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#8B7355"> <!-- لون الشريط في الموبايل -->
    <title>@yield('title', 'لوحة التحكم') – لمسة خيط</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>

<body>

    <div class="overlay" id="sidebarOverlay"></div>

    <div class="d-flex" style="min-height:100vh">

        <!-- ═══ SIDEBAR ═══ -->
        <nav class="sidebar shadow" id="sidebar">
            <div class="sidebar-brand">
                <div class="logo-wrap">
                    <img src="{{ asset('images/logo.svg') }}" alt="لمسة خيط">
                </div>
                <h4>لمسة خيط</h4>
                <p>نصنع الجمال يدوياً ✨</p>
            </div>

            <div class="sidebar-nav">
                <div class="nav-label">عام</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> الرئيسية
                </a>

                <a href="{{ route('admin.analytics') }}"
                    class="{{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i> التحليلات
                    <span id="sb-active-badge"
                        style="margin-right:auto;background:rgba(37,211,102,0.18);color:#1a8a4a;font-size:0.68rem;font-weight:800;padding:2px 8px;border-radius:20px;display:none">
                        <span class="sb-pulse"
                            style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#25D366;margin-left:4px;animation:dot-pulse 1.5s infinite"></span>
                        <span id="sb-active-count">0</span> نشط
                    </span>
                </a>

                <div class="nav-label">المنتجات</div>
                <a href="{{ route('admin.products.index') }}"
                    class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-stars"></i> التطريز
                </a>
                <a href="{{ route('admin.products.index', ['cat' => 'handicraft']) }}"
                    class="{{ request()->is('admin/products*') && request('cat') === 'handicraft' ? 'active' : '' }}">
                    <i class="bi bi-scissors"></i> أشغال يدوية
                </a>
                <a href="{{ route('admin.products.index', ['cat' => 'wool']) }}"
                    class="{{ request()->is('admin/products*') && request('cat') === 'wool' ? 'active' : '' }}">
                    <i class="bi bi-basket2"></i> أعمال الصوف
                </a>
                <a href="{{ route('admin.products.create') }}"
                    class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle-fill"></i> إضافة منتج
                </a>

                <div class="nav-label">إدارة</div>
                <a href="{{ route('admin.reviews.index') }}"
                    class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-star-fill"></i> التقييمات
                    @php $pendingRv = \App\Models\Review::pending()->count(); @endphp
                    @if ($pendingRv > 0)
                        <span class="ms-auto badge"
                            style="background:#d4af37;color:#2d1e14;font-size:0.7rem;border-radius:20px;padding:2px 8px">{{ $pendingRv }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> الطلبات
                    @php $pendingOrders = \App\Models\Order::pending()->count(); @endphp
                    @if ($pendingOrders > 0)
                        <span class="ms-auto badge"
                            style="background:#d35400;font-size:0.7rem;border-radius:20px;padding:2px 8px;">{{ $pendingOrders }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.messages.index') }}"
                    class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots-fill"></i> الرسائل
                    @if (($pending ?? 0) > 0)
                        <span class="ms-auto badge"
                            style="background:var(--maroon);font-size:0.7rem;border-radius:20px;padding:2px 8px;">{{ $pending }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.settings') }}"
                    class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> الإعدادات
                </a>

                <hr>
                <a href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-left"></i> الموقع العام
                </a>
                <a href="{{ route('admin.logout') }}" class="text-warning"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            <div class="sidebar-footer">
                <div class="avatar"><i class="bi bi-person-fill"></i></div>
                <div class="user-info">
                    <div class="name">{{ auth()->user()->name ?? 'المدير' }}</div>
                    <div class="role">مدير المنصة</div>
                </div>
            </div>
        </nav>

        <!-- ═══ MAIN ═══ -->
        <div class="flex-grow-1 main-content d-flex flex-column" style="min-width:0">

            <!-- Top Navbar -->
            <header class="top-navbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="nav-icon-btn d-md-none" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h5 class="m-0 fw-bold d-none d-md-block text-brown">@yield('page-title', 'لوحة التحكم')</h5>
                </div>

                <div class="d-flex align-items-center gap-2">

                    <!-- Messages Dropdown -->
                    <div class="dropdown">
                        <button class="nav-icon-btn" id="msgBtn" type="button">
                            <i class="bi bi-chat-dots"></i>
                            @php $unreadMsg = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                            @if ($unreadMsg > 0)
                                <span class="badge-notify">{{ $unreadMsg }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu" id="msgDropdownMenu" aria-labelledby="msgBtn">
                            <div class="dropdown-header">
                                <span>الرسائل الواردة</span>
                                <a href="{{ route('admin.messages.index') }}"
                                    class="small text-decoration-none text-maroon">عرض الكل</a>
                            </div>
                            @foreach (\App\Models\ContactMessage::latest()->take(3)->get() as $msg)
                                <a class="dropdown-item" href="{{ route('admin.messages.show', $msg->id) }}">
                                    <div class="notify-icon bg-light me-1"
                                        style="background:var(--cream) !important;">
                                        <i class="bi bi-person-circle text-brown"></i>
                                    </div>
                                    <div class="item-content">
                                        <h6>{{ $msg->name }}</h6>
                                        <p>{{ Str::limit($msg->message, 55) }}</p>
                                        <span class="time-ago">{{ $msg->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notifications Dropdown (Real-time) -->
                    <div class="dropdown" id="notif-dropdown-wrap">
                        <button class="nav-icon-btn" id="notifBtn" type="button" aria-label="الإشعارات">
                            <i class="bi bi-bell" id="notif-bell-icon"></i>
                            <span class="badge-notify" id="notif-count-badge" style="display:none">0</span>
                        </button>

                        <div class="dropdown-menu" id="notifDropdownMenu" style="min-width:340px">
                            <div class="dropdown-header">
                                <span>الإشعارات</span>
                                <button id="mark-all-read-btn"
                                    style="background:none;border:none;color:#7b1113;font-size:0.78rem;font-weight:700;cursor:pointer;padding:0;font-family:'Cairo',sans-serif"
                                    onclick="notifMarkAllRead()">
                                    تعليم الكل كمقروء
                                </button>
                            </div>
                            <div id="notif-list">
                                <div class="dropdown-item text-center text-muted py-3" id="notif-empty">
                                    <i class="bi bi-bell-slash opacity-25 d-block fs-2 mb-2"></i>
                                    لا توجد إشعارات جديدة
                                </div>
                            </div>
                            <div class="text-center border-top pt-2 pb-1" style="margin-top:4px">
                                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
                                    style="font-size:0.8rem;color:#7b1113;font-weight:700;text-decoration:none">
                                    عرض التقييمات المعلّقة
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="border-start ps-3 me-2 d-none d-sm-block">
                        <span class="small fw-bold text-brown">{{ auth()->user()->name ?? 'المدير' }}</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-grow-1 py-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-4 rounded-4 border-0 shadow-sm"
                        role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-4 rounded-4 border-0 shadow-sm"
                        role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </main>

        </div><!-- /main-content -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

    <script>
        // ═══════════════════════════════════════════════════════════════════════
        // لمسة خيط — Real-time Notification System (Unread Only Mode)
        // ═══════════════════════════════════════════════════════════════════════

        const NOTIF_FEED_URL = '{{ route('admin.notifications.feed') }}';
        const NOTIF_READ_URL = '/admin/notifications/{id}/read';
        const NOTIF_ALL_READ_URL = '{{ route('admin.notifications.mark-all-read') }}';
        const CSRF = '{{ csrf_token() }}';
        const POLL_INTERVAL = 30000; // 30 seconds

        let lastUnreadCount = -1;
        let notifPollTimer = null;

        // ── UI elements ────────────────────────────────────────────────────────
        const badge = document.getElementById('notif-count-badge');
        const bell = document.getElementById('notif-bell-icon');
        const list = document.getElementById('notif-list');
        const empty = document.getElementById('notif-empty');

        // ── Fetch & Render ──────────────────────────────────────────────────────
        async function fetchNotifications() {
            try {
                const res = await fetch(NOTIF_FEED_URL, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                });
                if (!res.ok) return;
                const data = await res.json();

                renderBadge(data.unread_count);
                renderList(data.notifications);

                // تشغيل الأنيميشن عند وصول إشعار جديد
                if (lastUnreadCount !== -1 && data.unread_count > lastUnreadCount) {
                    playBellAnimation();
                }
                lastUnreadCount = data.unread_count;

            } catch (e) {
                // صمت — لا تُظهر خطأ للمدير
            }
        }

        function renderBadge(count) {
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }

        function renderList(notifications) {
            if (!list || !empty) return;

            // ✅ فلتر: اعرض فقط الإشعارات غير المقروءة
            const unreadOnly = notifications.filter(n => !n.is_read);

            if (unreadOnly.length === 0) {
                list.innerHTML = '';
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';

            // ✅ جميع العناصر هنا غير مقروءة، لذا نثبت الكلاسات والـ dot
            list.innerHTML = unreadOnly.map(n => `
    <a class="dropdown-item d-flex align-items-start notif-item notif-unread"
       href="${n.action_url || '#'}"
       data-id="${n.id}"
       onclick="notifMarkRead(event, ${n.id}, '${n.action_url}')">
      <div class="notify-icon me-1" style="background:${n.icon_bg};color:${n.icon_color};flex-shrink:0">
        <i class="bi ${n.icon}"></i>
      </div>
      <div class="item-content flex-grow-1">
        <h6 style="font-size:0.85rem">${n.title}</h6>
        ${n.body ? `<p style="font-size:0.75rem">${n.body}</p>` : ''}
        <span class="time-ago">${n.time_ago}</span>
      </div>
      <span class="notif-dot"></span>
    </a>
  `).join('');
        }

        // ── Mark single as read ─────────────────────────────────────────────────
        async function notifMarkRead(e, id, url) {
            e.preventDefault();

            // ✅ إزالة العنصر فوراً من القائمة (لأننا نعرض غير المقروءة فقط)
            const item = document.querySelector(`[data-id="${id}"]`);
            if (item) {
                item.style.transition = 'opacity 0.2s ease';
                item.style.opacity = '0';
                setTimeout(() => {
                    item.remove();
                    // إذا أصبحت القائمة فارغة، اعرض رسالة "لا يوجد إشعارات"
                    if (list && list.children.length === 0 && empty) {
                        empty.style.display = 'block';
                    }
                }, 200);
            }

            // API call لتحديث الحالة في الخلفية
            await fetch(NOTIF_READ_URL.replace('{id}', id), {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
            }).then(r => r.json()).then(data => {
                renderBadge(data.unread_count);
                lastUnreadCount = data.unread_count;
            }).catch(() => {});

            // التوجيه للرابط المطلوب
            if (url && url !== '#') window.location.href = url;
        }

        // ── Mark all as read ─────────────────────────────────────────────────────
        async function notifMarkAllRead() {
            await fetch(NOTIF_ALL_READ_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
            });

            renderBadge(0);
            lastUnreadCount = 0;

            // ✅ إفراغ القائمة وعرض رسالة الفراغ
            if (list) list.innerHTML = '';
            if (empty) empty.style.display = 'block';
        }

        // ── Bell animation ──────────────────────────────────────────────────────
        function playBellAnimation() {
            if (!bell) return;
            bell.style.animation = 'none';
            void bell.offsetWidth; // reflow
            bell.style.animation = 'bell-ring 0.6s ease 3';
        }

        // ── Start polling ───────────────────────────────────────────────────────
        fetchNotifications(); // أول طلب فوري
        notifPollTimer = setInterval(fetchNotifications, POLL_INTERVAL);

        // إيقاف الـ polling عند إخفاء التبويب
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                clearInterval(notifPollTimer);
            } else {
                fetchNotifications();
                notifPollTimer = setInterval(fetchNotifications, POLL_INTERVAL);
            }
        });
    </script>
    <style>
        /* ── Notification dot ───────────────────────────────────────── */
        .notif-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #7b1113;
            flex-shrink: 0;
            margin-top: 4px;
            margin-right: auto;
            box-shadow: 0 0 0 2px rgba(123, 17, 19, 0.2);
            animation: dot-pulse 2s ease-in-out infinite;
        }

        @keyframes dot-pulse {

            0%,
            100% {
                box-shadow: 0 0 0 2px rgba(123, 17, 19, 0.2);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(123, 17, 19, 0.0);
            }
        }

        /* ── Unread vs Read item ────────────────────────────────────── */
        .notif-unread {
            background: rgba(123, 17, 19, 0.025) !important;
            border-right: 3px solid #7b1113 !important;
        }

        .notif-read {
            opacity: 0.75;
        }

        /* ── Bell ring animation ────────────────────────────────────── */
        @keyframes bell-ring {

            0%,
            100% {
                transform: rotate(0deg);
            }

            20% {
                transform: rotate(-15deg);
            }

            40% {
                transform: rotate(15deg);
            }

            60% {
                transform: rotate(-10deg);
            }

            80% {
                transform: rotate(10deg);
            }
        }
    </style>

    @stack('scripts')
</body>

</html>
