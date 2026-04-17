<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') – {{ setting('site.name') }}</title>
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
                    <img src="{{ asset('images/logo.svg') }}" alt="{{ setting('site.name') }}">
                </div>
                <h4>{{ setting('site.name') }}</h4>
                <p>{{ setting('site.tagline') }}</p>
            </div>

            <div class="sidebar-nav">
                <div class="nav-label">عام</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> الرئيسية
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
                <a href="{{ route('admin.orders.index') }}"
                    class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> الطلبات
                    @php $pending = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                </a>
                <a href="{{ route('admin.reviews.index') }}"
                    class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-star"></i> التقييمات
                    @php $reviewPending = \App\Models\Review::where('status', 'pending')->count(); @endphp
                     @if ($reviewPending ?? 0 > 0)
                        <span class="ms-auto badge"
                            style="background:var(--maroon);font-size:0.7rem;border-radius:20px;padding:2px 8px;">{{ $pending }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.messages.index') }}"
                    class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots-fill"></i> الرسائل
                    @if ($pending ?? 0 > 0)
                        <span class="ms-auto badge"
                            style="background:var(--maroon);font-size:0.7rem;border-radius:20px;padding:2px 8px;">{{ $pending }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.settings') }}"
                    class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> إعدادات الموقع
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

                    <!-- Notifications Dropdown -->
                    <div class="dropdown">
                        <button class="nav-icon-btn" id="notifBtn" type="button">
                            <i class="bi bi-bell"></i>
                            @php $unreadOrders = \App\Models\Order::where('status', 'pending')->count(); @endphp
                            @if ($unreadOrders > 0)
                                <span class="badge-notify">{{ $unreadOrders }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu" id="notifDropdownMenu">
                            <div class="dropdown-header"><span>الإشعارات</span></div>
                            @if ($unreadOrders > 0)
                                <a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                    <div class="notify-icon bg-success bg-opacity-10 text-success me-1">
                                        <i class="bi bi-bag-plus"></i>
                                    </div>
                                    <div class="item-content">
                                        <h6>{{ $unreadOrders }} طلب جديد</h6>
                                        <p>طلبات بانتظار مراجعتك</p>
                                        <span class="time-ago">الآن</span>
                                    </div>
                                </a>
                            @else
                                <div class="dropdown-item text-center text-muted py-3">لا توجد إشعارات جديدة</div>
                            @endif
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
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
