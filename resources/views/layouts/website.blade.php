<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#8B7355"> 
    <meta name="description"
        content="{{ setting('site.description', 'لمسة خيط – متجر الأشغال اليدوية والتطريز وأعمال الصوف') }}">
    <title>@yield('title', setting('site.name', 'لمسة خيط')) – أشغال يدوية وتطريز</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Amiri:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/website.css') }}">
    @stack('styles')
</head>

<body>

    <!-- Scroll progress bar -->
    <div id="scroll-progress"></div>

    <!-- Custom cursor -->
    <div id="cursor-dot"></div>
    <div id="cursor-ring"></div>

    <!-- Toast container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- ═══ NAVBAR ═══ -->
    <header class="site-navbar" id="navbar">
        <a href="{{ route('home') }}" class="brand">
            <img src="{{ asset('images/logo.svg') }}" alt="{{ setting('site.name', 'لمسة خيط') }}">
            <span class="brand-name">{{ setting('site.name', 'لمسة خيط') }}</span>
        </a>

        <nav class="d-none d-md-flex align-items-center gap-1">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a>
            <a href="{{ route('products') }}"
                class="{{ request()->routeIs('products*') ? 'active' : '' }}">المنتجات</a>
            <a href="{{ route('home') }}#about">من نحن</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">تواصل
                معنا</a>
        </nav>

        <div class="d-flex align-items-center gap-3">
            <a href="https://wa.me/{{ setting('contact.whatsapp', '970591234567') }}" target="_blank"
                class="cta-btn d-none d-md-flex magnetic">
                <i class="bi bi-whatsapp"></i>
                واتساب
            </a>
            <button class="hamburger" id="hamburger" aria-label="قائمة">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobile-menu">
        <a href="{{ route('home') }}">الرئيسية</a>
        <a href="{{ route('products') }}">المنتجات</a>
        <a href="{{ route('home') }}#about">من نحن</a>
        <a href="{{ route('contact') }}">تواصل معنا</a>
        <a href="https://wa.me/{{ setting('contact.whatsapp', '970591234567') }}" target="_blank"
            style="color:#25D366">
            <i class="bi bi-whatsapp"></i> واتساب
        </a>
    </div>

    <!-- ═══ CONTENT ═══ -->
    <main>
        @yield('content')
    </main>

    <!-- ═══ FOOTER ═══ -->
    <footer class="site-footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="{{ asset('images/logo.svg') }}" alt="{{ setting('site.name', 'لمسة خيط') }}">
                <h3>{{ setting('site.name', 'لمسة خيط') }}</h3>
                <p>{{ setting('site.description', 'متجر متخصص في الأشغال اليدوية الفاخرة، التطريز الأصيل، وأعمال الصوف المتنوعة.') }}
                </p>
            </div>
            <div class="footer-col">
                <h4>الأقسام</h4>
                <a href="{{ route('products', ['category' => 'embroidery']) }}">🪡 التطريز</a>
                <a href="{{ route('products', ['category' => 'handicraft']) }}">✂️ الأشغال اليدوية</a>
                <a href="{{ route('products', ['category' => 'wool']) }}">🧶 أعمال الصوف</a>
                <a href="{{ route('products', ['target' => 'kids']) }}">👶 للأطفال</a>
            </div>
            <div class="footer-col">
                <h4>روابط</h4>
                <a href="{{ route('home') }}">الرئيسية</a>
                <a href="{{ route('home') }}#about">من نحن</a>
                <a href="{{ route('contact') }}">تواصل معنا</a>
                <a href="{{ route('home') }}#how-to-order">كيف تطلب؟</a>
            </div>
            <div class="footer-col">
                <h4>تواصل</h4>
                @if (setting('contact.whatsapp'))
                    <a href="https://wa.me/{{ setting('contact.whatsapp') }}" target="_blank">
                        <i class="bi bi-whatsapp" style="color:#25D366"></i> واتساب
                    </a>
                @endif
                @if (setting('contact.email'))
                    <a href="mailto:{{ setting('contact.email') }}">
                        <i class="bi bi-envelope"></i> {{ setting('contact.email') }}
                    </a>
                @endif
                @if (setting('contact.hours'))
                    <a href="#" style="cursor:default"><i class="bi bi-clock"></i>
                        {{ setting('contact.hours') }}</a>
                @endif
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} {{ setting('site.name', 'لمسة خيط') }} · جميع الحقوق محفوظة · نصنع الجمال يدوياً
                ✨
            </p>
            <div class="social-links">
                @if (setting('social.instagram'))
                    <a href="{{ setting('social.instagram') }}" target="_blank"><i class="bi bi-instagram"></i></a>
                @endif
                @if (setting('social.facebook'))
                    <a href="{{ setting('social.facebook') }}" target="_blank"><i class="bi bi-facebook"></i></a>
                @endif
                @if (setting('social.tiktok'))
                    <a href="{{ setting('social.tiktok') }}" target="_blank"><i class="bi bi-tiktok"></i></a>
                @endif
                <a href="https://wa.me/{{ setting('contact.whatsapp', '970591234567') }}" target="_blank">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </div>
        </div>
    </footer>

    <!-- ═══ WHATSAPP FLOAT ═══ -->
    <div class="wa-float">
        <div class="wa-tooltip">تواصل معنا على واتساب ✨</div>
        <a href="https://wa.me/{{ setting('contact.whatsapp', '970591234567') }}" target="_blank" class="wa-btn"
            title="واتساب">
            <i class="bi bi-whatsapp"></i>
        </a>
    </div>

    <!-- Back to Top -->
    <button id="back-top" aria-label="العودة للأعلى">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Quick View Modal -->
    <div class="qv-overlay" id="qv-overlay" role="dialog" aria-modal="true">
        <div class="qv-modal" style="position:relative">
            <button class="qv-close" onclick="closeQuickView()" aria-label="إغلاق">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="qv-img" id="qv-img"></div>
            <div class="qv-body">
                <div class="cat" id="qv-cat"></div>
                <h2 id="qv-name"></h2>
                <div class="price" id="qv-price"></div>
                <p class="desc" id="qv-desc"></p>
                <div class="qv-actions">
                    <a href="#" id="qv-wa" class="btn-wa-full"><i class="bi bi-whatsapp"></i> اطلب عبر
                        واتساب</a>
                    <a href="#" id="qv-detail" class="btn-detail">عرض التفاصيل الكاملة</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/website.js') }}"></script>
    @stack('scripts')
</body>

</html>
