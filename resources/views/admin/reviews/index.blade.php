@extends('layouts.admin')
@section('title', 'التقييمات')
@section('page-title', 'إدارة التقييمات')

@push('styles')
    <style>
        .rv-card {
            background: #fff;
            border-radius: 18px;
            border: 1.5px solid #f0e8e0;
            padding: 22px;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }

        .rv-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.07);
            transform: translateY(-2px);
        }

        .rv-card.pending {
            border-right: 4px solid #d4af37;
        }

        .rv-card.approved {
            border-right: 4px solid #1a8a4a;
        }

        .rv-card.rejected {
            border-right: 4px solid #ccc;
            opacity: 0.75;
        }

        .rv-stars {
            color: #f1c40f;
            font-size: 1rem;
            letter-spacing: 2px;
        }

        .rv-stars .empty {
            color: #e0d0c0;
        }

        .action-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 16px;
            border-radius: 20px;
            border: none;
            font-family: 'Cairo', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .action-pill.approve {
            background: #e8faf0;
            color: #1a8a4a;
        }

        .action-pill.reject {
            background: #fdf0f0;
            color: #7b1113;
        }

        .action-pill.delete {
            background: #f5f5f5;
            color: #888;
        }

        .action-pill:hover {
            filter: brightness(0.92);
            transform: scale(1.03);
        }

        .stat-strip {
            background: #fff;
            border-radius: 16px;
            padding: 18px 20px;
            border: 1.5px solid #f0e8e0;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
            transition: all 0.25s;
        }

        .stat-strip:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
            transform: translateY(-2px);
        }

        .stat-strip.active-filter {
            border-color: #7b1113;
            background: rgba(123, 17, 19, 0.03);
        }

        .stat-num {
            font-size: 1.6rem;
            font-weight: 900;
            line-height: 1;
        }

        .stat-lbl {
            font-size: 0.75rem;
            color: #aaa;
            font-weight: 700;
            margin-top: 2px;
        }

        .alert-pending {
            background: linear-gradient(135deg, #fdf8e8, #fdf3d0);
            border: 1.5px solid rgba(212, 175, 55, 0.3);
            border-radius: 16px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <div>
                <h4 class="fw-bold m-0 text-brown">⭐ التقييمات</h4>
                <p class="text-muted small mb-0 mt-1">{{ number_format($stats['total']) }} تقييم إجمالاً</p>
            </div>
        </div>

        {{-- Pending alert --}}
        @if ($stats['pending'] > 0)
            <div class="alert-pending">
                <div style="font-size:1.5rem;flex-shrink:0">⏳</div>
                <div>
                    <div style="font-weight:800;color:#422018;margin-bottom:3px">
                        {{ $stats['pending'] }} تقييم بانتظار مراجعتك
                    </div>
                    <div style="font-size:0.83rem;color:#9a7d0a">
                        هذه التقييمات (3 نجوم) لم تُنشر بعد — راجعها وقرر: نشر أو رفض
                    </div>
                </div>
                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
                    style="margin-right:auto;background:#d4af37;color:#2d1e14;padding:9px 20px;border-radius:12px;font-weight:800;text-decoration:none;font-size:0.87rem;white-space:nowrap;flex-shrink:0">
                    مراجعتها الآن
                </a>
            </div>
        @endif

        {{-- Stats Strips --}}
        <div class="row g-3 mb-4">
            @php
                $strips = [
                    ['', 'الكل', $stats['total'], '#422018', 'bi-grid-fill'],
                    ['approved', 'منشورة', $stats['approved'], '#1a8a4a', 'bi-check-circle-fill'],
                    ['pending', 'بانتظار المراجعة', $stats['pending'], '#9a7d0a', 'bi-hourglass-split'],
                    ['rejected', 'مرفوضة', $stats['rejected'], '#888', 'bi-x-circle-fill'],
                ];
            @endphp
            @foreach ($strips as [$sv, $sl, $sc, $color, $icon])
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.reviews.index', $sv ? ['status' => $sv] : []) }}"
                        class="stat-strip {{ request('status', '') === $sv ? 'active-filter' : '' }}">
                        <div
                            style="width:42px;height:42px;border-radius:12px;background:{{ $color }}18;color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div>
                            <div class="stat-num" style="color:{{ $color }}">{{ $sc }}</div>
                            <div class="stat-lbl">{{ $sl }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
            {{-- Average rating --}}
            <div class="col-12 col-md d-none d-md-block">
                <div class="stat-strip">
                    <div style="font-size:1.8rem;flex-shrink:0">⭐</div>
                    <div>
                        <div class="stat-num" style="color:#9a7d0a">{{ $stats['avg'] ?: '—' }}</div>
                        <div class="stat-lbl">متوسط التقييم</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="main-table mb-4">
            <form action="{{ route('admin.reviews.index') }}" method="GET">
                <div class="d-flex gap-3 flex-wrap align-items-center mb-4">
                    <select name="status" class="form-select" style="width:180px" onchange="this.form.submit()">
                        <option value="">كل الحالات</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ بانتظار المراجعة
                        </option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ منشورة</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ مرفوضة</option>
                    </select>
                    <select name="rating" class="form-select" style="width:150px" onchange="this.form.submit()">
                        <option value="">كل النجوم</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ str_repeat('★', $i) . str_repeat('☆', 5 - $i) }} ({{ $i }})
                            </option>
                        @endfor
                    </select>
                    <div class="me-auto" style="position:relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="ابحث بالاسم أو النص..." class="form-control" style="width:240px;padding-left:38px">
                        <i class="bi bi-search"
                            style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#bbb"></i>
                    </div>
                    <button type="submit" class="btn-maroon"><i class="bi bi-funnel-fill"></i> فلترة</button>
                    @if (request()->hasAny(['status', 'rating', 'search']))
                        <a href="{{ route('admin.reviews.index') }}" class="btn-action py-2 px-3 text-decoration-none">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            {{-- Reviews list --}}
            <div class="row g-3">
                @forelse($reviews as $review)
                    <div class="col-12 col-md-6">
                        <div class="rv-card {{ $review->status }}">

                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Avatar --}}
                                    <div
                                        style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#7b1113,#422018);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1rem;flex-shrink:0">
                                        {{ mb_substr($review->reviewer_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:800;color:#2d1e14;font-size:0.92rem">
                                            {{ $review->reviewer_name }}</div>
                                        @if ($review->reviewer_city)
                                            <div style="font-size:0.75rem;color:#aaa">📍 {{ $review->reviewer_city }}</div>
                                        @endif
                                    </div>
                                </div>
                                {{-- Status pill --}}
                                <span
                                    style="background:{{ $review->status_bg }};color:{{ $review->status_color }};font-size:0.72rem;font-weight:700;padding:4px 12px;border-radius:20px;white-space:nowrap">
                                    {{ $review->status_label }}
                                </span>
                            </div>

                            {{-- Stars --}}
                            <div class="rv-stars mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        ★
                                    @else
                                        <span class="empty">☆</span>
                                    @endif
                                @endfor
                                <span style="font-size:0.78rem;color:#aaa;margin-right:6px">{{ $review->rating }}/5</span>
                                @if ($review->auto_approved)
                                    <span
                                        style="font-size:0.68rem;color:#aaa;background:#f5f5f5;padding:2px 8px;border-radius:20px">تلقائي</span>
                                @endif
                            </div>

                            {{-- Title --}}
                            @if ($review->title)
                                <div style="font-weight:800;color:#422018;font-size:0.92rem;margin-bottom:6px">
                                    "{{ $review->title }}"
                                </div>
                            @endif

                            {{-- Body --}}
                            <p style="font-size:0.87rem;color:#666;line-height:1.7;margin-bottom:14px;font-style:italic">
                                {{ Str::limit($review->body, 160) }}
                            </p>

                            {{-- Product link --}}
                            @if ($review->product)
                                <div
                                    style="font-size:0.77rem;color:#aaa;margin-bottom:12px;display:flex;align-items:center;gap:6px">
                                    <i class="bi bi-box" style="color:#d4af37"></i>
                                    <span>على منتج:</span>
                                    <a href="{{ route('admin.products.edit', $review->product) }}"
                                        style="color:#7b1113;font-weight:700;text-decoration:none">
                                        {{ $review->product->name }}
                                    </a>
                                </div>
                            @endif

                            {{-- Date --}}
                            <div style="font-size:0.75rem;color:#bbb;margin-bottom:14px">
                                {{ $review->created_at->diffForHumans() }} · {{ $review->created_at->format('d/m/Y') }}
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex gap-2 flex-wrap">

                                @if ($review->status !== 'approved')
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="action-pill approve">
                                            <i class="bi bi-check2-circle"></i> نشر
                                        </button>
                                    </form>
                                @endif

                                @if ($review->status !== 'rejected')
                                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="action-pill reject">
                                            <i class="bi bi-x-circle"></i> رفض
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                    onsubmit="return confirm('حذف هذا التقييم نهائياً؟')">
                                    @csrf @method('DELETE')
                                    <button class="action-pill delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div style="font-size:3.5rem;margin-bottom:14px;opacity:0.12">⭐</div>
                        <div style="font-weight:800;color:#422018;font-size:1rem;margin-bottom:6px">لا توجد تقييمات</div>
                        <div style="font-size:0.87rem;color:#aaa">
                            @if (request()->hasAny(['status', 'rating', 'search']))
                                <a href="{{ route('admin.reviews.index') }}" class="text-maroon fw-bold">عرض الكل</a>
                            @else
                                ستظهر التقييمات هنا فور وصولها
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $reviews->withQueryString()->links('vendor.pagination.custom') }}
            </div>

        </div>
    </div>
@endsection
