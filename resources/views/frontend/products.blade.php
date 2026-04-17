@extends('layouts.website')

@section('title', 'المنتجات')

@section('content')

<!-- Page Header -->
<div style="background:linear-gradient(135deg,#fdf5ef,#f5e8dc);padding:52px 5% 44px;border-bottom:1px solid #eee">
  <div class="eyebrow" style="color:var(--maroon);font-size:0.78rem;font-weight:800;letter-spacing:2px;text-transform:uppercase;display:block;margin-bottom:10px">متجرنا</div>
  <h1 style="font-size:clamp(1.8rem,4vw,2.6rem);font-weight:900;color:#422018;margin-bottom:10px">كل منتجاتنا</h1>
  <p style="color:#8a7060;margin:0;font-size:1rem">اكتشف مجموعتنا الكاملة من الأشغال اليدوية الفاخرة</p>
</div>

<div style="padding:48px 5%">
  <!-- Filters -->
  <div class="filter-tabs mb-5">
    <a href="{{ route('products') }}" class="filter-tab {{ !request('category') && !request('target') ? 'active' : '' }}" style="text-decoration:none">
      الكل ({{ $total }})
    </a>
    <a href="{{ route('products', ['category' => 'embroidery']) }}" class="filter-tab {{ request('category') === 'embroidery' ? 'active' : '' }}" style="text-decoration:none">
      🪡 التطريز
    </a>
    <a href="{{ route('products', ['category' => 'handicraft']) }}" class="filter-tab {{ request('category') === 'handicraft' ? 'active' : '' }}" style="text-decoration:none">
      ✂️ أشغال يدوية
    </a>
    <a href="{{ route('products', ['category' => 'wool']) }}" class="filter-tab {{ request('category') === 'wool' ? 'active' : '' }}" style="text-decoration:none">
      🧶 أعمال الصوف
    </a>
    <a href="{{ route('products', ['target' => 'kids']) }}" class="filter-tab {{ request('target') === 'kids' ? 'active' : '' }}" style="text-decoration:none">
      👶 للأطفال
    </a>
    <a href="{{ route('products', ['target' => 'girls']) }}" class="filter-tab {{ request('target') === 'girls' ? 'active' : '' }}" style="text-decoration:none">
      👗 للبنات
    </a>

    <!-- Search -->
    <form action="{{ route('products') }}" method="GET" class="me-0 ms-auto">
      @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
      <div style="position:relative">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="ابحث عن منتج..."
               style="padding:9px 16px 9px 40px;border-radius:30px;border:1.5px solid #e8ddd4;font-family:'Cairo',sans-serif;font-size:0.87rem;width:220px;outline:none">
        <button type="submit" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#aaa;cursor:pointer">
          <i class="bi bi-search"></i>
        </button>
      </div>
    </form>
  </div>

  <!-- Products Grid -->
  @if($products->count())
    <div class="products-grid">
      @foreach($products as $product)
      <a href="{{ route('product.show', $product->slug ?? $product->id) }}" class="product-card fade-up">
        <div class="product-card-img">
          @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
          @else
            {{ $product->category_emoji }}
          @endif
          @if($product->is_featured)
            <span class="product-badge">⭐ مميز</span>
          @endif
        </div>
        <div class="product-card-body">
          <div class="product-cat">{{ $product->category_label }} · {{ $product->target_label }}</div>
          <h3>{{ $product->name }}</h3>
          <p class="desc">{{ Str::limit($product->description, 65) }}</p>
          <div class="product-card-footer">
            <div class="product-price">{{ number_format($product->price) }} <span>₪</span></div>
            <span class="btn-order"><i class="bi bi-eye"></i> عرض</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5 gap-2">
      {{ $products->appends(request()->query())->links() }}
    </div>

  @else
    <div class="text-center py-5">
      <div style="font-size:5rem;margin-bottom:20px;opacity:0.2">🧵</div>
      <h4 style="color:#422018;font-weight:800">لا توجد منتجات حالياً</h4>
      <p style="color:#8a7060">جرّب تغيير الفلتر أو ابحث بكلمة مختلفة</p>
      <a href="{{ route('products') }}" class="btn-hero-primary" style="display:inline-flex;margin-top:16px">عرض الكل</a>
    </div>
  @endif
</div>

@endsection
