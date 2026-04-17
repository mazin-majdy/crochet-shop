{{-- @extends('layouts.website')

@section('title', setting('site.name') . '– أشغال يدوية وتطريز')

@section('content')

    <!-- ═══ HERO ═══ -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-pattern"></div>

        <div class="hero-content">
            <div class="hero-badge fade-up">
                <span>🪡</span> أشغال يدوية أصيلة
            </div>

            <h1 class="hero-title fade-up delay-1">
                كل <span class="highlight">لمسة</span><br>
                تحكي قصة<br>
                جمال حقيقي
            </h1>

            <p class="hero-sub fade-up delay-2">
                اكتشف عالم الأشغال اليدوية الفاخرة — تطريز أصيل، أعمال صوف دافئة، وكروشيه مصنوع بأناملنا بحبٍّ وإتقان.
            </p>

            <div class="hero-actions fade-up delay-3">
                <a href="{{ route('products') }}" class="btn-hero-primary">
                    تصفح المنتجات <i class="bi bi-arrow-left"></i>
                </a>
                <a href="https://wa.me/{{ config('app.whatsapp_number', setting('contact.whatsapp')) }}" target="_blank"
                    class="btn-hero-secondary">
                    <i class="bi bi-whatsapp" style="color:#25D366"></i>
                    تواصل واتساب
                </a>
            </div>

            <!-- Trust badges -->
            <div class="d-flex gap-4 mt-5 fade-up" style="flex-wrap:wrap">
                @foreach ([['🏅', 'جودة مضمونة'], ['🚚', 'توصيل آمن'], ['💳', 'دفع إلكتروني'], ['🎁', 'تغليف مميز']] as $badge)
                    <div class="d-flex align-items-center gap-2" style="font-size:0.85rem;color:#6b5040;font-weight:600">
                        <span style="font-size:1.3rem">{{ $badge[0] }}</span> {{ $badge[1] }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Floating decorative cards -->
        <div class="hero-visual d-none d-lg-block">
            <div class="float-card float-card-1" style="text-align:center;min-width:160px">
                <div style="font-size:3rem;margin-bottom:8px">🪡</div>
                <div style="font-size:0.85rem;font-weight:800;color:#422018">التطريز الفلسطيني</div>
                <div style="font-size:0.75rem;color:#888">إرث وأصالة</div>
            </div>
            <div class="float-card float-card-2" style="text-align:center;min-width:160px">
                <div style="font-size:3rem;margin-bottom:8px">🧶</div>
                <div style="font-size:0.85rem;font-weight:800;color:#422018">أعمال الصوف</div>
                <div style="font-size:0.75rem;color:#888">دفء وأناقة</div>
            </div>
            <div class="float-card float-card-3" style="text-align:center;min-width:160px">
                <div style="font-size:3rem;margin-bottom:8px">✂️</div>
                <div style="font-size:0.85rem;font-weight:800;color:#422018">أشغال يدوية</div>
                <div style="font-size:0.75rem;color:#888">إبداع بلا حدود</div>
            </div>
        </div>
    </section>

    <!-- ═══ CATEGORIES ═══ -->
    <section class="categories-section">
        <div class="section-title">
            <div class="eyebrow">أقسامنا</div>
            <h2>اختر ما يناسبك</h2>
            <p>ثلاثة أقسام متخصصة — كل واحد يحمل لمسة فريدة وأسلوبًا مميزًا</p>
        </div>

        <div class="cat-grid">
            <!-- التطريز -->
            <a href="{{ route('products', ['category' => 'embroidery']) }}" class="cat-card embroidery fade-up">
                <div class="cat-card-bg">🪡</div>
                <div class="cat-card-body">
                    <h3>التطريز</h3>
                    <p>ثياب، مناديل، لفحات، وإكسسوارات مطرزة بأنامل متقنة وخيوط فاخرة.</p>
                    <span class="cat-link">تصفح القسم <i class="bi bi-arrow-left"></i></span>
                </div>
            </a>

            <!-- الأشغال اليدوية -->
            <a href="{{ route('products', ['category' => 'handicraft']) }}" class="cat-card handicraft fade-up delay-1">
                <div class="cat-card-bg">✂️</div>
                <div class="cat-card-body">
                    <h3>الأشغال اليدوية</h3>
                    <p>كروشيه، خرازة، أعمال جلدية وفنية. كل قطعة عمل إبداعي فريد.</p>
                    <span class="cat-link">تصفح القسم <i class="bi bi-arrow-left"></i></span>
                </div>
            </a>

            <!-- أعمال الصوف -->
            <a href="{{ route('products', ['category' => 'wool']) }}" class="cat-card wool fade-up delay-2">
                <div class="cat-card-bg">🧶</div>
                <div class="cat-card-body">
                    <h3>أعمال الصوف</h3>
                    <p>شالات، كارديجانات، بطانيات، وملابس للأطفال. دفء مصنوع بحب.</p>
                    <span class="cat-link">تصفح القسم <i class="bi bi-arrow-left"></i></span>
                </div>
            </a>
        </div>
    </section>

    <!-- ═══ FEATURED PRODUCTS ═══ -->
    @if ($featuredProducts->count())
        <section class="products-section">
            <div class="section-title">
                <div class="eyebrow">المنتجات المميزة</div>
                <h2>أحدث ما صنعناه</h2>
                <p>كل منتج يحكي قصة جميلة — اختر ما يلمس قلبك</p>
            </div>

            <div class="products-grid">
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="product-card fade-up">
                        <div class="product-card-img">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    style="width:100%;height:100%;object-fit:cover;">
                            @else
                                {{ $product->category_emoji }}
                            @endif
                            @if ($product->is_featured)
                                <span class="product-badge">مميز ⭐</span>
                            @endif
                            @if ($product->created_at->isCurrentWeek())
                                <span class="product-badge new" style="top:auto;bottom:14px">جديد</span>
                            @endif
                        </div>
                        <div class="product-card-body">
                            <div class="product-cat">{{ $product->category_label }} · {{ $product->target_label }}</div>
                            <h3>{{ $product->name }}</h3>
                            <p class="desc">{{ Str::limit($product->description, 70) }}</p>
                            <div class="product-card-footer">
                                <div class="product-price">
                                    {{ number_format($product->price) }} <span>₪</span>
                                </div>
                                <span class="btn-order">
                                    <i class="bi bi-eye"></i> التفاصيل
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('products') }}" class="btn-hero-primary" style="display:inline-flex;padding:14px 36px">
                    عرض جميع المنتجات <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </section>
    @endif

    <!-- ═══ HOW TO ORDER ═══ -->
    <section id="how-to-order" style="padding:80px 5%;background:var(--cream-2)">
        <div class="section-title">
            <div class="eyebrow">طريقة الطلب</div>
            <h2>كيف تطلب منتجك؟</h2>
            <p>خطوات بسيطة وسهلة للحصول على منتجك المفضل</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach ([['1', 'bi-search', 'تصفح المنتجات', 'اختر المنتج الذي يعجبك من أقسامنا المتنوعة'], ['2', 'bi-whatsapp', 'تواصل معنا', 'راسلنا عبر واتساب أو نموذج التواصل وأخبرنا بطلبك'], ['3', 'bi-wallet2', 'الدفع الإلكتروني', 'يتم الدفع عبر المحافظ الإلكترونية بطريقة آمنة'], ['4', 'bi-box-seam', 'استلام طلبك', 'نُحضّر طلبك بعناية ويصلك بالتغليف المميز 🎁']] as $step)
                <div class="col-6 col-md-3 text-center fade-up">
                    <div
                        style="width:70px;height:70px;border-radius:20px;background:rgba(123,17,19,0.08);color:#7b1113;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto 16px">
                        <i class="bi {{ $step[1] }}"></i>
                    </div>
                    <div
                        style="display:inline-block;background:#7b1113;color:#fff;width:26px;height:26px;border-radius:50%;font-size:0.8rem;font-weight:800;line-height:26px;margin-bottom:10px">
                        {{ $step[0] }}</div>
                    <h5 style="font-weight:800;color:#422018;margin-bottom:8px">{{ $step[2] }}</h5>
                    <p style="font-size:0.87rem;color:#8a7060;line-height:1.7">{{ $step[3] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Payment methods -->
        <div class="text-center mt-5">
            <p style="font-size:0.85rem;color:#aaa;margin-bottom:14px;font-weight:600">طرق الدفع الإلكترونية المقبولة</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                @foreach ($paymentMethods as $method)
                    <span
                        style="background:#fff;border:1.5px solid #e8ddd4;padding:8px 18px;border-radius:20px;font-size:0.83rem;font-weight:700;color:#6b5040">{{ $method['name'] }}
                        {!! $method['icon'] !!}</span>
                @endforeach


            </div>
        </div>
    </section>

    <!-- ═══ ABOUT ═══ -->
    <section id="about" class="about-section">
        <div class="about-grid">
            <div class="about-visual">
                <div class="about-main-box">
                    🪡
                </div>
                <div class="about-float f1">
                    <div>
                        <div class="num">+200</div>
                        <div class="lbl">منتج مصنوع بحب</div>
                    </div>
                </div>
                <div class="about-float f2">
                    <span style="font-size:1.5rem">⭐</span>
                    <div>
                        <div class="num">5.0</div>
                        <div class="lbl">تقييم عملائنا</div>
                    </div>
                </div>
            </div>

            <div>
                <div class="eyebrow"
                    style="color:var(--maroon);font-size:0.8rem;font-weight:800;letter-spacing:2px;text-transform:uppercase;display:block;margin-bottom:10px">
                    من نحن</div>
                <h2 style="font-size:2rem;font-weight:900;color:#422018;margin-bottom:18px">
                    نصنع الجمال<br>بأناملنا وبقلوبنا
                </h2>
                <p style="color:#8a7060;line-height:1.85;font-size:1rem;margin-bottom:28px">
                    {{ setting('site.name') }} متجر متخصص في الأشغال اليدوية الأصيلة — من التطريز الفلسطيني التراثي إلى أحدث تصاميم الكروشيه
                    والصوف.
                    نؤمن بأن كل قطعة يدوية تحمل روحًا خاصة وقصة تُحكى.
                </p>
                <div class="row g-3 mb-4">
                    @foreach ([['🎨', 'تصاميم حصرية', 'كل منتج فريد لا يتكرر'], ['💝', 'صُنع بحب', 'أناملنا تبذل قلبها في كل غرزة'], ['✅', 'جودة عالية', 'نستخدم أفضل الخيوط والمواد']] as $feat)
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    style="width:42px;height:42px;border-radius:12px;background:rgba(123,17,19,0.08);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0">
                                    {{ $feat[0] }}</div>
                                <div>
                                    <div style="font-weight:800;color:#422018;font-size:0.92rem">{{ $feat[1] }}</div>
                                    <div style="font-size:0.82rem;color:#8a7060">{{ $feat[2] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('contact') }}" class="btn-hero-primary" style="display:inline-flex">
                    تواصل معنا <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══ CTA BANNER ═══ -->
    <section
        style="margin:0 5% 60px;background:linear-gradient(135deg,#422018,#7b1113);border-radius:28px;padding:52px 44px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap">
        <div>
            <h2 style="font-size:1.9rem;font-weight:900;color:#fff;margin-bottom:10px">هل لديك طلب خاص؟ 🎁</h2>
            <p style="color:rgba(255,255,255,0.7);margin:0;font-size:1rem">تواصلي معنا وسنصنع لك ما يناسب ذوقك تمامًا</p>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="https://wa.me/{{ config('app.whatsapp_number', setting('contact.whatsapp')) }}" target="_blank"
                style="background:#25D366;color:#fff;padding:14px 26px;border-radius:15px;font-weight:800;text-decoration:none;display:flex;align-items:center;gap:9px">
                <i class="bi bi-whatsapp"></i> واتساب
            </a>
            <a href="{{ route('contact') }}"
                style="background:rgba(255,255,255,0.1);color:#fff;padding:14px 26px;border-radius:15px;font-weight:800;text-decoration:none;border:1.5px solid rgba(255,255,255,0.2)">
                <i class="bi bi-envelope"></i> راسلنا
            </a>
        </div>
    </section>

@endsection --}}


@extends('layouts.website')
@section('title', setting('site.name', 'لمسة خيط'))

@section('content')

    <!-- ═══ HERO ═══ -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-pattern"></div>
        <canvas id="hero-canvas"
            style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:1"></canvas>

        <div class="hero-content" style="z-index:2">
            <div class="hero-badge">
                <span style="font-size:1rem">🪡</span> أشغال يدوية أصيلة من فلسطين
            </div>

            <h1 class="hero-title">
                <span class="line"><span>كل <span class="highlight">لمسة</span></span></span>
                <span class="line"><span>تحكي قصة</span></span>
                <span class="line"><span class="mb-3">جمال حقيقي</span></span>
            </h1>

            <p class="hero-sub">
                اكتشف عالم الأشغال اليدوية الفاخرة — تطريز أصيل، أعمال صوف دافئة، وكروشيه مصنوع
                بأناملنا بحبٍّ وإتقان يُورَث جيلاً بعد جيل.
            </p>

            <div class="hero-actions">
                <a href="{{ route('products') }}" class="btn-hero-primary magnetic">
                    تصفح المنتجات <i class="bi bi-arrow-left"></i>
                </a>
                <a href="https://wa.me/{{ setting('contact.whatsapp', '970591234567') }}" target="_blank"
                    class="btn-hero-secondary">
                    <i class="bi bi-whatsapp" style="color:#25D366;font-size:1.1rem"></i>
                    تواصل مباشر
                </a>
            </div>

            <div class="hero-trust reveal">
                @foreach ([['🏅', 'جودة مضمونة'], ['🚚', 'توصيل آمن'], ['💳', 'دفع إلكتروني'], ['🎁', 'تغليف مميز'], ['✨', 'تصميم حصري']] as [$icon, $label])
                    <div class="trust-item">
                        <div class="trust-icon">{{ $icon }}</div>
                        {{ $label }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Decorative float cards (desktop) -->
        <div style="position:relative;flex:1;min-height:580px;z-index:2" class="d-none d-lg-block">
            @foreach ([['🪡', 'التطريز الفلسطيني', 'إرث وأصالة', 'top:8%;right:50%', '0s'], ['🧶', 'أعمال الصوف', 'دفء وأناقة', 'bottom:20%;right:30%', '1.5s'], ['✂️', 'أشغال يدوية', 'إبداع بلا حدود', 'top:48%;left:5%', '0.8s']] as [$em, $title, $sub, $pos, $delay])
                <div
                    style="position:absolute;{{ $pos }};background:#fff;border-radius:20px;padding:20px 22px;box-shadow:0 16px 40px rgba(0,0,0,0.1);text-align:center;animation:float {{ rand(3, 5) }}s ease-in-out infinite;animation-delay:{{ $delay }};min-width:150px">
                    <div style="font-size:3rem;margin-bottom:8px">{{ $em }}</div>
                    <div style="font-size:0.85rem;font-weight:800;color:var(--brown)">{{ $title }}</div>
                    <div style="font-size:0.75rem;color:var(--muted)">{{ $sub }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-14px);
            }
        }
    </style>

    <!-- ═══ STATS BAND ═══ -->
    <div class="stats-band reveal">
        @foreach ([['200+', 'منتج يدوي', 'count' => 200, 'suffix' => '+'], ['500+', 'عميلة سعيدة', 'count' => 500, 'suffix' => '+'], ['5', 'سنوات خبرة', 'count' => 5, 'suffix' => ''], ['100%', 'صنع يدوي', 'count' => 100, 'suffix' => '%']] as $s)
            <div class="stat-item">
                <span class="stat-num" data-count="{{ $s['count'] }}" data-suffix="{{ $s['suffix'] }}">0</span>
                <span class="stat-label">{{ $s[1] }}</span>
            </div>
        @endforeach
    </div>

    <!-- ═══ CATEGORIES ═══ -->
    <section class="categories-section">
        <div class="section-title reveal">
            <div class="eyebrow">أقسامنا</div>
            <h2>اختر ما يناسبك</h2>
            <p>ثلاثة أقسام متخصصة — كل واحد يحمل لمسة فريدة وأسلوبًا مميزًا</p>
        </div>

        <div class="cat-grid">
            <a href="{{ route('products', ['category' => 'embroidery']) }}" class="cat-card embroidery reveal">
                <div class="cat-card-bg">🪡</div>
                <div class="cat-card-body">
                    <h3>التطريز</h3>
                    <p>ثياب، مناديل، لفحات، وإكسسوارات مطرزة بأنامل متقنة وخيوط فاخرة.</p>
                    <span class="cat-link">تصفح القسم <i class="bi bi-arrow-left"></i></span>
                </div>
            </a>
            <a href="{{ route('products', ['category' => 'handicraft']) }}" class="cat-card handicraft reveal delay-1">
                <div class="cat-card-bg">✂️</div>
                <div class="cat-card-body">
                    <h3>الأشغال اليدوية</h3>
                    <p>كروشيه، خرازة، أعمال جلدية وفنية. كل قطعة عمل إبداعي فريد.</p>
                    <span class="cat-link">تصفح القسم <i class="bi bi-arrow-left"></i></span>
                </div>
            </a>
            <a href="{{ route('products', ['category' => 'wool']) }}" class="cat-card wool reveal delay-2">
                <div class="cat-card-bg">🧶</div>
                <div class="cat-card-body">
                    <h3>أعمال الصوف</h3>
                    <p>شالات، كارديجانات، بطانيات، وملابس للأطفال. دفء مصنوع بحب.</p>
                    <span class="cat-link">تصفح القسم <i class="bi bi-arrow-left"></i></span>
                </div>
            </a>
        </div>
    </section>

    <!-- ═══ FEATURED PRODUCTS ═══ -->
    @if ($featuredProducts->count())
        <section class="products-section">
            <div class="section-title reveal">
                <div class="eyebrow">المنتجات المميزة</div>
                <h2>أحدث ما صنعناه</h2>
                <p>كل منتج يحكي قصة جميلة — اختر ما يلمس قلبك</p>
            </div>

            <div class="products-grid">
                @foreach ($featuredProducts as $i => $product)
                    <div class="product-card reveal delay-{{ min(($i % 4) + 1, 4) }}">
                        <div class="product-card-img">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <span class="emoji-icon">{{ $product->category_emoji }}</span>
                            @endif
                            @if ($product->is_featured)
                                <span class="product-badge featured">⭐ مميز</span>
                            @endif
                            @if ($product->created_at->isCurrentWeek())
                                <span class="product-badge new"
                                    style="{{ $product->is_featured ? 'top:auto;bottom:14px' : '' }}">جديد</span>
                            @endif
                            <button class="quick-view-btn"
                                onclick="event.preventDefault(); openQuickView({
        name:  {{ json_encode($product->name) }},
        cat:   {{ json_encode($product->category_label) }},
        price: '{{ number_format($product->price) }}',
        desc:  {{ json_encode(Str::limit($product->description, 120)) }},
        emoji: {{ json_encode($product->category_emoji) }},
        image: '{{ $product->image ? asset('storage/' . $product->image) : '' }}',
        url:   '{{ route('product.show', $product->slug ?? $product->id) }}',
        wa:    '{{ setting('contact.whatsapp', '970591234567') }}'
    })">
                                <i class="bi bi-eye-fill"></i> معاينة سريعة
                            </button>
                        </div>
                        <a href="{{ route('product.show', $product->slug ?? $product->id) }}" style="text-decoration:none">
                            <div class="product-card-body">
                                <div class="product-cat">{{ $product->category_label }} · {{ $product->target_label }}
                                </div>
                                <h3>{{ $product->name }}</h3>
                                <p class="desc">{{ Str::limit($product->description, 70) }}</p>
                                <div class="product-card-footer">
                                    <div class="product-price">{{ number_format($product->price) }} <span>₪</span></div>
                                    <span class="btn-order"><i class="bi bi-bag-plus"></i> طلب</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5 reveal">
                <a href="{{ route('products') }}" class="btn-hero-primary magnetic"
                    style="display:inline-flex;padding:14px 36px">
                    عرض جميع المنتجات <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </section>
    @endif

    <!-- ═══ HOW TO ORDER ═══ -->
    <section id="how-to-order" style="padding:80px 5%;background:var(--cream-2)">
        <div class="section-title reveal">
            <div class="eyebrow">طريقة الطلب</div>
            <h2>أربع خطوات بسيطة</h2>
            <p>من الاختيار حتى الاستلام — نحن معك في كل خطوة</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px">
            @foreach ([['bi-search', '#7b1113', 'تصفح المنتجات', 'اختر ما يعجبك من أقسامنا المتنوعة', '1'], ['bi-whatsapp', '#25D366', 'تواصل معنا', 'راسلنا عبر واتساب أو نموذج التواصل', '2'], ['bi-wallet2', '#d4af37', 'الدفع الإلكتروني', 'عبر المحافظ الإلكترونية بأمان', '3'], ['bi-box-seam', '#1e4d2b', 'استلام طلبك', 'نُحضّره بعناية ويصلك بالتغليف المميز', '4']] as $step)
                <div class="reveal" style="text-align:center">
                    <div style="position:relative;display:inline-block;margin-bottom:18px">
                        <div
                            style="width:72px;height:72px;border-radius:20px;background:{{ $step[1] }}18;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto;color:{{ $step[1] }}">
                            <i class="bi {{ $step[0] }}"></i>
                        </div>
                        <div
                            style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;border-radius:50%;background:{{ $step[1] }};color:#fff;font-size:0.72rem;font-weight:900;display:flex;align-items:center;justify-content:center">
                            {{ $step[4] }}</div>
                    </div>
                    <h5 style="font-weight:900;color:var(--brown);margin-bottom:8px">{{ $step[2] }}</h5>
                    <p style="font-size:0.87rem;color:var(--muted);line-height:1.7;margin:0">{{ $step[3] }}</p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5 reveal">
            <p style="font-size:0.85rem;color:#aaa;margin-bottom:14px;font-weight:600">طرق الدفع المقبولة</p>
            <div style="display:flex;justify-content:center;gap:10px;flex-wrap:wrap">
                @foreach ($paymentMethods as $method)
                    <span
                        style="background:#fff;border:1.5px solid #e8ddd4;padding:8px 18px;border-radius:20px;font-size:0.83rem;font-weight:700;color:#6b5040">{{ $method['name'] }}
                        {!! $method['icon'] !!}</span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══ TESTIMONIALS ═══ -->
    <section class="testimonials-section">
        <div class="section-title reveal">
            <div class="eyebrow">ماذا قالوا عنا</div>
            <h2>عملاؤنا يحبوننا ❤️</h2>
            <p>آراء حقيقية من عملاء لمسة خيط الأوفياء</p>
        </div>

        <div class="testimonials-grid">
            @if ($generalReviews->count() > 0)
                @foreach ($generalReviews as $i => $rv)
                    <div class="testimonial-card reveal delay-{{ ($i % 3) + 1 }}">
                        <div class="stars">{{ str_repeat('★', $rv->rating) }}<span
                                style="color:#e0d0c0">{{ str_repeat('☆', 5 - $rv->rating) }}</span></div>
                        <p class="t-text">"{{ Str::limit($rv->body, 140) }}"</p>
                        <div class="t-author">
                            <div class="t-avatar">{{ mb_substr($rv->reviewer_name, 0, 1) }}</div>
                            <div>
                                <div class="t-name">{{ $rv->reviewer_name }}</div>
                                <div class="t-loc">
                                    {{ $rv->reviewer_city ? '📍 ' . $rv->reviewer_city : '💝 عميلة لمسة خيط' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback static testimonials حتى تتراكم التقييمات الحقيقية --}}
                @foreach ([['سارة أحمد', 'رام الله', 'س', 'طلبت ثوب تطريز لعرسي وجاء أجمل من توقعاتي! الشغل دقيق جداً والألوان رائعة ❤️', 5], ['أم محمد', 'غزة', 'أ', 'شال الصوف جودته عالية جداً وحار كثير. بناتي انبسطوا فيه. بدي أطلب أكثر قريب!', 5], ['فاطمة الزهراء', 'الخليل', 'ف', 'حقيبة الكروشيه مميزة وفريدة. كل اللي شافوها سألوني من وين. شكراً على التغليف الجميل!', 5], ['خولة جاسم', 'نابلس', 'خ', 'تعاملت معهم أكثر من مرة والخدمة ممتازة. المنتجات دايماً مطابقة للصور.', 5], ['ريم العمري', 'جنين', 'ر', 'اشتريت إكليل كروشيه لابنتي وحبته كثير. الشغل محكم وما تهزز. الله يعطيكم العافية!', 5], ['نور الدين', 'طولكرم', 'ن', 'قميص التطريز الفلسطيني تحفة فنية حقيقية. ارتديته في الأعياد وكلهم أعجبوا فيه.', 5]] as $i => $t)
                    <div class="testimonial-card reveal delay-{{ ($i % 3) + 1 }}">
                        <div class="stars">{{ str_repeat('★', $t[4]) }}</div>
                        <p class="t-text">"{{ $t[3] }}"</p>
                        <div class="t-author">
                            <div class="t-avatar">{{ $t[2] }}</div>
                            <div>
                                <div class="t-name">{{ $t[0] }}</div>
                                <div class="t-loc">📍 {{ $t[1] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>

    <!-- ═══ ADD REVIEW CTA ═══ -->
    <div class="reveal" style="padding:0 5% 60px;text-align:center">
        <div
            style="background:linear-gradient(135deg,#fdf8f0,#faf0e4);border-radius:24px;padding:44px;border:1.5px solid rgba(212,175,55,0.2);max-width:640px;margin:0 auto">
            <div style="font-size:2.2rem;margin-bottom:14px">✍️</div>
            <h3 style="font-family:'Amiri',serif;font-size:1.5rem;font-weight:700;color:var(--brown);margin-bottom:10px">
                تعاملت معنا من قبل؟
            </h3>
            <p style="font-size:0.92rem;color:var(--muted);margin-bottom:24px;line-height:1.7">
                شاركي رأيك وتجربتك — كلمتك تساعد الآخرين في الاختيار وتحفّزنا على التميز ✨
            </p>
            <a href="{{ route('contact') }}#add-review"
                onclick="event.preventDefault(); document.querySelector('#review-section-home')?.scrollIntoView({behavior:'smooth'})"
                style="background:var(--maroon);color:#fff;padding:13px 30px;border-radius:14px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;gap:8px;font-size:0.95rem;transition:0.25s"
                onmouseover="this.style.background='var(--maroon-dk)';this.style.transform='translateY(-2px)'"
                onmouseout="this.style.background='var(--maroon)';this.style.transform=''">
                <i class="bi bi-star-fill" style="color:#f1c40f"></i> أضف تقييمك
            </a>
        </div>
    </div>

    <!-- General Review Form (hidden, shown on click) -->
    <div id="review-section-home" style="padding:0 5% 60px;display:none">
        <div
            style="max-width:640px;margin:0 auto;background:#fff;border-radius:24px;padding:36px;box-shadow:0 8px 28px rgba(0,0,0,0.07)">
            <h3 style="font-size:1.3rem;font-weight:900;color:var(--brown);margin-bottom:6px">💬 شاركي رأيك</h3>
            <p style="font-size:0.87rem;color:var(--muted);margin-bottom:24px">تقييمك العام عن خدمات لمسة خيط</p>
            @include('components.review-form')
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cta = document.querySelector('a[onclick*="review-section-home"]');
            if (cta) cta.addEventListener('click', () => {
                const sec = document.getElementById('review-section-home');
                if (sec) {
                    sec.style.display = 'block';
                    setTimeout(() => sec.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    }), 100);
                }
            });
        });
    </script>

    <!-- ═══ ABOUT ═══ -->
    <section id="about" class="about-section">
        <div class="about-grid">
            <div style="position:relative" class="reveal">
                <div class="about-main-box">🪡</div>
                <div class="about-float f1">
                    <div>
                        <div class="num">+200</div>
                        <div class="lbl">منتج مصنوع بحب</div>
                    </div>
                </div>
                <div class="about-float f2">
                    <span style="font-size:1.5rem">⭐</span>
                    <div>
                        <div class="num">5.0</div>
                        <div class="lbl">تقييم عملائنا</div>
                    </div>
                </div>
            </div>

            <div class="reveal delay-2">
                <span class="eyebrow">من نحن</span>
                <h2
                    style="font-family:'Amiri',serif;font-size:2.2rem;font-weight:700;color:var(--brown);margin-bottom:18px">
                    نصنع الجمال<br>بأناملنا وبقلوبنا
                </h2>
                <p style="color:var(--muted);line-height:1.9;font-size:1rem;margin-bottom:28px">
                    لمسة خيط متجر متخصص في الأشغال اليدوية الأصيلة — من التطريز الفلسطيني التراثي إلى أحدث تصاميم الكروشيه
                    والصوف.
                    نؤمن بأن كل قطعة يدوية تحمل روحًا خاصة وقصة تُحكى.
                </p>
                <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:28px">
                    @foreach ([['🎨', 'تصاميم حصرية', 'كل منتج فريد لا يتكرر'], ['💝', 'صُنع بحب', 'أناملنا تبذل قلبها في كل غرزة'], ['✅', 'جودة عالية', 'نستخدم أفضل الخيوط والمواد']] as $f)
                        <div style="display:flex;align-items:center;gap:12px">
                            <div
                                style="width:44px;height:44px;border-radius:12px;background:var(--maroon-lt);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0">
                                {{ $f[0] }}</div>
                            <div>
                                <div style="font-weight:800;color:var(--brown);font-size:0.92rem">{{ $f[1] }}
                                </div>
                                <div style="font-size:0.82rem;color:var(--muted)">{{ $f[2] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('contact') }}" class="btn-hero-primary magnetic" style="display:inline-flex">
                    تواصل معنا <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══ CTA BANNER ═══ -->
    <section class="reveal" style="margin:0 5% 60px">
        <div
            style="background:linear-gradient(135deg,#422018,#7b1113);border-radius:28px;padding:52px 44px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap;position:relative;overflow:hidden">
            <div
                style="position:absolute;inset:0;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40'%3E%3Ccircle cx='20' cy='20' r='1.5' fill='%23d4af37' opacity='0.15'/%3E%3C/svg%3E\");background-size:40px 40px">
            </div>
            <div style="position:relative;z-index:1">
                <h2 style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;color:#fff;margin-bottom:10px">هل لديك
                    طلب خاص؟ 🎁</h2>
                <p style="color:rgba(255,255,255,0.7);margin:0;font-size:1rem">تواصلي معنا وسنصنع لك ما يناسب ذوقك تمامًا
                </p>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;position:relative;z-index:1">
                <a href="https://wa.me/{{ setting('contact.whatsapp', '970591234567') }}" target="_blank"
                    style="background:#25D366;color:#fff;padding:14px 26px;border-radius:15px;font-weight:800;text-decoration:none;display:flex;align-items:center;gap:9px">
                    <i class="bi bi-whatsapp"></i> واتساب
                </a>
                <a href="{{ route('contact') }}"
                    style="background:rgba(255,255,255,0.12);color:#fff;padding:14px 26px;border-radius:15px;font-weight:800;text-decoration:none;border:1.5px solid rgba(255,255,255,0.2)">
                    <i class="bi bi-envelope"></i> راسلنا
                </a>
            </div>
        </div>
    </section>

@endsection
