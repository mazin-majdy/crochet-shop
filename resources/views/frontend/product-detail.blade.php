@extends('layouts.website')

@section('title', $product->name)

@section('content')

    <div class="product-detail-section">

        <!-- Breadcrumb -->
        <nav style="margin-bottom:32px;font-size:0.85rem;color:#8a7060">
            <a href="{{ route('home') }}" style="color:#8a7060;text-decoration:none">الرئيسية</a>
            <span class="mx-2">›</span>
            <a href="{{ route('products') }}" style="color:#8a7060;text-decoration:none">المنتجات</a>
            <span class="mx-2">›</span>
            <a href="{{ route('products', ['category' => $product->category]) }}"
                style="color:#8a7060;text-decoration:none">{{ $product->category_label }}</a>
            <span class="mx-2">›</span>
            <span style="color:#422018;font-weight:700">{{ $product->name }}</span>
        </nav>

        <div class="product-detail-grid">

            <!-- Images -->
            <div>
                <div class="product-main-img" id="mainImg">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            style="width:100%;height:100%;object-fit:cover;border-radius:24px">
                    @else
                        {{ $product->category_emoji }}
                    @endif
                </div>
                @if ($product->images && count($product->images) > 1)
                    <div class="product-thumbs">
                        @foreach ($product->images as $i => $img)
                            <div class="product-thumb-img {{ $i === 0 ? 'active' : '' }}"
                                onclick="changeImg('{{ asset('storage/' . $img) }}', this)">
                                <img src="{{ asset('storage/' . $img) }}"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:12px" alt="">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="product-thumbs">
                        @foreach (['🌟', '💫', '✨'] as $em)
                            <div class="product-thumb-img">{{ $em }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="product-info">
                <!-- Category badge -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span
                        style="background:rgba(123,17,19,0.08);color:#7b1113;font-size:0.78rem;font-weight:700;padding:5px 14px;border-radius:20px">
                        {{ $product->category_emoji }} {{ $product->category_label }}
                    </span>
                    @if ($product->target)
                        <span
                            style="background:var(--cream-3);color:#6b5040;font-size:0.78rem;font-weight:700;padding:5px 14px;border-radius:20px">
                            {{ $product->target_label }}
                        </span>
                    @endif
                    @if ($product->is_featured)
                        <span
                            style="background:rgba(212,175,55,0.12);color:#9a7d0a;font-size:0.78rem;font-weight:700;padding:5px 14px;border-radius:20px">
                            ⭐ مميز
                        </span>
                    @endif
                </div>

                <h1>{{ $product->name }}</h1>

                <div class="product-price-lg">{{ number_format($product->price) }} <span
                        style="font-size:1.1rem;font-weight:500;color:#8a7060">₪</span></div>

                <p class="product-desc">{{ $product->description }}</p>

                @if ($product->tags)
                    <div class="product-tags">
                        @foreach (explode(',', $product->tags) as $tag)
                            <span class="product-tag">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="https://wa.me/{{ config('app.whatsapp_number', setting('contact.whatsapp')) }}?text={{ urlencode('مرحباً، أريد طلب: ' . $product->name . ' - السعر: ' . number_format($product->price) . ' ₪') }}"
                        target="_blank" class="btn-whatsapp">
                        <i class="bi bi-whatsapp"></i>
                        اطلب عبر واتساب
                    </a>
                    <a href="{{ route('contact') }}?product={{ $product->id }}" class="btn-contact">
                        <i class="bi bi-envelope"></i>
                        تواصل لطلب المنتج
                    </a>
                </div>

                <!-- Payment notice -->
                <div class="payment-notice">
                    <i class="bi bi-shield-check-fill"></i>
                    <p>
                        <strong>طريقة الدفع الآمنة:</strong> يتم الدفع عبر المحافظ الإلكترونية (بايبال، بنك فلسطين، تحويل
                        واتساب) بعد التواصل المباشر مع الإدارة. لا ندعم الدفع المباشر عبر الموقع.
                    </p>
                </div>

                <!-- Additional info -->
                <div class="row g-3 mt-3">
                    @foreach ([['bi-truck', 'توصيل آمن', 'نصل إليك في أي مكان'], ['bi-arrow-repeat', 'تعديل مجاني', 'نعدّل حسب طلبك'], ['bi-award', 'جودة مضمونة', 'أو نُعيد صنعها لك']] as $info)
                        <div class="col-4">
                            <div style="text-align:center;background:var(--cream);border-radius:14px;padding:14px 8px">
                                <i class="bi {{ $info[0] }}"
                                    style="color:#7b1113;font-size:1.3rem;display:block;margin-bottom:6px"></i>
                                <div style="font-size:0.78rem;font-weight:800;color:#422018">{{ $info[1] }}</div>
                                <div style="font-size:0.7rem;color:#8a7060">{{ $info[2] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($related->count())
            <div class="mt-5 pt-3">
                <h3 style="font-size:1.5rem;font-weight:900;color:#422018;margin-bottom:28px">قد يعجبك أيضاً 💫</h3>
                <div class="products-grid" style="grid-template-columns:repeat(auto-fill,minmax(240px,1fr))">
                    @foreach ($related as $rel)
                        <a href="{{ route('product.show', $rel->slug ?? $rel->id) }}" class="product-card">
                            <div class="product-card-img">
                                @if ($rel->image)
                                    <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}"
                                        style="width:100%;height:100%;object-fit:cover">
                                @else
                                    {{ $rel->category_emoji }}
                                @endif
                            </div>
                            <div class="product-card-body">
                                <div class="product-cat">{{ $rel->category_label }}</div>
                                <h3>{{ $rel->name }}</h3>
                                <div class="product-card-footer">
                                    <div class="product-price">{{ number_format($rel->price) }} <span>₪</span></div>
                                    <span class="btn-order"><i class="bi bi-eye"></i> عرض</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
    {{-- ══════════════════════════════════════════════════════════════════
     REVIEWS SECTION — يُضاف بعد منتجات ذات الصلة
══════════════════════════════════════════════════════════════════ --}}

    <section style="padding:60px 5%;background:var(--cream-3)" id="reviews">
        <div style="max-width:900px;margin:0 auto">

            {{-- Section Header --}}
            <div
                style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:36px">
                <div>
                    <span class="eyebrow">آراء العملاء</span>
                    <h2
                        style="font-family:'Amiri',serif;font-size:1.9rem;font-weight:700;color:var(--brown);margin:4px 0 0">
                        ماذا قالوا عن هذا المنتج؟
                    </h2>
                </div>
                @if ($reviewStats['count'] > 0)
                    <div
                        style="text-align:center;background:#fff;border-radius:18px;padding:16px 24px;border:1px solid var(--cream-3)">
                        <div style="font-size:2.4rem;font-weight:900;color:var(--maroon);line-height:1">
                            {{ $reviewStats['avg'] }}</div>
                        <div style="color:#f1c40f;font-size:1.1rem;margin:4px 0">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($reviewStats['avg']))
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                        <div style="font-size:0.78rem;color:var(--muted);font-weight:700">{{ $reviewStats['count'] }} تقييم
                        </div>
                    </div>
                @endif
            </div>

            {{-- Existing Reviews --}}
            @if ($reviews->count())
                <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:40px">
                    @foreach ($reviews as $review)
                        <div class="reveal"
                            style="background:#fff;border-radius:20px;padding:24px;border:1px solid rgba(0,0,0,0.05);box-shadow:0 4px 16px rgba(0,0,0,0.04)">
                            <div
                                style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:14px">
                                <div style="display:flex;align-items:center;gap:12px">
                                    <div
                                        style="width:46px;height:46px;border-radius:13px;background:linear-gradient(135deg,var(--maroon),var(--brown));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.1rem;flex-shrink:0">
                                        {{ mb_substr($review->reviewer_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:800;color:var(--brown);font-size:0.95rem">
                                            {{ $review->reviewer_name }}</div>
                                        @if ($review->reviewer_city)
                                            <div style="font-size:0.76rem;color:var(--muted)">📍
                                                {{ $review->reviewer_city }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div style="text-align:left">
                                    <div style="color:#f1c40f;font-size:1rem;letter-spacing:2px">
                                        {{ str_repeat('★', $review->rating) }}<span
                                            style="color:#e0d0c0">{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                    </div>
                                    <div style="font-size:0.73rem;color:#bbb;margin-top:2px">
                                        {{ $review->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @if ($review->title)
                                <div style="font-weight:800;color:var(--brown);margin-bottom:8px">"{{ $review->title }}"
                                </div>
                            @endif
                            <p style="font-size:0.9rem;color:var(--muted);line-height:1.8;margin:0;font-style:italic">
                                "{{ $review->body }}"
                            </p>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($reviews->hasPages())
                    <div style="margin-bottom:40px">
                        {{ $reviews->fragment('reviews')->links('vendor.pagination.custom') }}
                    </div>
                @endif
            @else
                <div style="text-align:center;padding:32px 0;margin-bottom:36px">
                    <div style="font-size:3rem;margin-bottom:12px;opacity:0.15">⭐</div>
                    <div style="font-weight:800;color:var(--brown);margin-bottom:6px">لا توجد تقييمات بعد</div>
                    <div style="font-size:0.87rem;color:var(--muted)">كن أول من يُشارك رأيه 🌟</div>
                </div>
            @endif

            {{-- Add Review Form --}}
            <div style="background:#fff;border-radius:24px;padding:36px;border:1px solid rgba(212,175,55,0.15);box-shadow:0 8px 28px rgba(0,0,0,0.05)"
                id="add-review">
                <h3 style="font-size:1.3rem;font-weight:900;color:var(--brown);margin-bottom:6px">
                    💬 أضف تقييمك
                </h3>
                <p style="font-size:0.87rem;color:var(--muted);margin-bottom:24px">
                    هل تعاملت مع لمسة خيط؟ شاركينا رأيك وساعدي الآخرين في الاختيار ✨
                </p>
                @include('components.review-form', ['productId' => $product->id])
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        function changeImg(src, el) {
            document.getElementById('mainImg').querySelector('img, span').style.display = 'none';
            // For simplicity update src
            document.querySelectorAll('.product-thumb-img').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
        }
    </script>
@endpush
