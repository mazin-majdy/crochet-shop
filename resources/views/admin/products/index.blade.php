@extends('layouts.admin')

@section('title', 'إدارة المنتجات')
@section('page-title', 'إدارة المنتجات')

@section('content')
    <div class="container-fluid px-4">

        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <div>
                <h4 class="fw-bold m-0 text-brown">🛍️ المنتجات</h4>
                <p class="text-muted small mb-0">إجمالي: {{ $products->total() }} منتج</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-maroon">
                <i class="bi bi-plus-lg"></i> إضافة منتج
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            @foreach (['' => 'الكل', 'embroidery' => '🪡 التطريز', 'handicraft' => '✂️ يدوية', 'wool' => '🧶 صوف'] as $val => $label)
                <a href="{{ route('admin.products.index', $val ? ['category' => $val] : []) }}"
                    class="filter-tab {{ request('category', '') === $val ? 'active' : '' }}"
                    style="text-decoration:none;font-size:0.85rem">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="main-table">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>القسم</th>
                            <th>الفئة المستهدفة</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            style="width:52px;height:52px;border-radius:12px;background:var(--cream);display:flex;align-items:center;justify-content:center;font-size:1.7rem;overflow:hidden;flex-shrink:0;border:1.5px solid #e8ddd4">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    style="width:100%;height:100%;object-fit:cover" alt="">
                                            @else
                                                {{ $product->category_emoji }}
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0" style="font-size:0.9rem">{{ $product->name }}</h6>
                                            <span class="text-muted"
                                                style="font-size:0.72rem">#{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span
                                        class="badge-cat badge-{{ $product->category }}">{{ $product->category_label }}</span>
                                </td>
                                <td class="small text-muted">{{ $product->target_label }}</td>
                                <td class="fw-bold text-maroon">{{ number_format($product->price) }} ₪</td>
                                <td>
                                    <span
                                        class="{{ $product->is_active ? 'badge-status-active' : 'badge-status-inactive' }}">
                                        {{ $product->is_active ? '● نشط' : '● مخفي' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="btn-action py-1 px-3 text-decoration-none">
                                            <i class="bi bi-pencil"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            onsubmit="return confirm('حذف هذا المنتج نهائياً؟')">
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div style="font-size:3rem;margin-bottom:12px;opacity:0.2">📦</div>
                                    لا توجد منتجات — <a href="{{ route('admin.products.create') }}"
                                        class="text-maroon fw-bold">أضف أول منتج</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $products->links() }}</div>
        </div>
    </div>
@endsection
