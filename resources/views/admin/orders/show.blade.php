@extends('layouts.admin')
@section('title', $order->order_number)
@section('page-title', 'تفاصيل الطلب')

@push('styles')
<style>
.info-row {
  display: flex; align-items: flex-start; gap: 14px; padding: 10px 0;
  border-bottom: 1px solid #faf4ef;
}
.info-row:last-child { border-bottom: none; padding-bottom: 0; }
.info-icon {
  width: 40px; height: 40px; border-radius: 11px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: 1rem;
}
.info-label { font-size: 0.75rem; color: #aaa; font-weight: 700; margin-bottom: 2px; }
.info-value { font-size: 0.92rem; font-weight: 700; color: #2d1e14; }

/* Timeline steps */
.steps { display: flex; align-items: center; }
.step  { display: flex; flex-direction: column; align-items: center; flex: 1; }
.step-circle {
  width: 38px; height: 38px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.95rem; transition: 0.3s;
}
.step-label { font-size: 0.68rem; font-weight: 700; margin-top: 7px; text-align: center; white-space: nowrap; }
.step-line { flex: 1; height: 2px; margin: 0 4px; margin-bottom: 26px; transition: 0.3s; }
.step-done   .step-circle { color: #fff; }
.step-active .step-circle { color: #fff; box-shadow: 0 0 0 4px rgba(255,255,255,0.4), 0 0 0 6px currentColor; }
.step-future .step-circle { background: #f0e8e0 !important; color: #ccc; }
.step-future .step-label  { color: #ccc; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

  {{-- Breadcrumb + actions --}}
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 mt-2">
    <div class="d-flex align-items-center gap-3">
      <a href="{{ route('admin.orders.index') }}"
         style="width:36px;height:36px;border-radius:10px;background:#f0e8e0;color:#422018;display:flex;align-items:center;justify-content:center;text-decoration:none;transition:0.2s"
         onmouseover="this.style.background='#7b1113';this.style.color='#fff'"
         onmouseout="this.style.background='#f0e8e0';this.style.color='#422018'">
        <i class="bi bi-arrow-right"></i>
      </a>
      <div>
        <span style="font-size:1.3rem;font-weight:900;color:#7b1113;font-family:monospace">{{ $order->order_number }}</span>
        <span class="ms-3"
              style="background:{{ $order->status_bg }};color:{{ $order->status_color }};font-size:0.8rem;font-weight:800;padding:5px 14px;border-radius:30px;display:inline-flex;align-items:center;gap:5px">
          <span style="width:7px;height:7px;border-radius:50%;background:currentColor;display:inline-block"></span>
          {{ $order->status_label }}
        </span>
      </div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.orders.edit', $order) }}" class="btn-maroon">
        <i class="bi bi-pencil"></i> تعديل
      </a>
      <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
            onsubmit="return confirm('حذف الطلب {{ $order->order_number }}؟')">
        @csrf @method('DELETE')
        <button class="btn-action" style="background:#fdf0f0;color:#7b1113">
          <i class="bi bi-trash"></i>
        </button>
      </form>
    </div>
  </div>

  <div class="row g-4">

    {{-- ── Left Column ─────────────────────────────────────────────────── --}}
    <div class="col-lg-8 d-flex flex-column gap-4">

      {{-- Timeline --}}
      @if($order->status !== 'cancelled')
      <div class="main-table">
        <h6 style="font-weight:800;color:#422018;margin-bottom:22px">📍 مسار الطلب</h6>
        @php
          $steps   = ['pending','confirmed','preparing','shipped','completed'];
          $labels  = ['قيد الانتظار','مؤكد','قيد التحضير','تم الشحن','مكتمل'];
          $icons   = ['bi-hourglass-split','bi-check-circle','bi-tools','bi-truck','bi-bag-check'];
          $colors  = ['#d35400','#1a8a4a','#9a7d0a','#1a6b8a','#1e4d2b'];
          $current = array_search($order->status, $steps);
        @endphp
        <div class="steps">
          @foreach($steps as $i => $s)
            @php
              $done   = $current !== false && $i < $current;
              $active = $current !== false && $i === $current;
              $future = !$done && !$active;
              $cls    = $done ? 'step-done' : ($active ? 'step-active' : 'step-future');
            @endphp
            <div class="step {{ $cls }}">
              <div class="step-circle"
                   style="background:{{ !$future ? $colors[$i] : '#f0e8e0' }};color:{{ !$future ? '#fff' : '#ccc' }}">
                <i class="bi {{ $icons[$i] }}"></i>
              </div>
              <div class="step-label" style="color:{{ !$future ? $colors[$i] : '#ccc' }}">{{ $labels[$i] }}</div>
            </div>
            @if($i < count($steps) - 1)
            <div class="step-line"
                 style="background:{{ ($current !== false && $i < $current) ? '#d4af37' : '#f0e8e0' }}">
            </div>
            @endif
          @endforeach
        </div>
      </div>
      @else
      <div style="background:#fdf0f0;border-radius:14px;padding:14px 20px;color:#7b1113;font-weight:800;display:flex;align-items:center;gap:10px">
        <i class="bi bi-x-circle-fill fs-5"></i> هذا الطلب ملغي
      </div>
      @endif

      {{-- Product Card --}}
      <div class="main-table">
        <h6 style="font-weight:800;color:#422018;margin-bottom:18px">🛒 تفاصيل المنتج</h6>

        <div class="d-flex align-items-center gap-4 p-3 rounded-4" style="background:var(--cream)">
          <div style="width:72px;height:72px;border-radius:16px;background:#fff;display:flex;align-items:center;justify-content:center;font-size:2.5rem;border:1.5px solid #e8ddd4;flex-shrink:0;overflow:hidden">
            @if($order->product && $order->product->image)
              <img src="{{ asset('storage/'.$order->product->image) }}" style="width:100%;height:100%;object-fit:cover" alt="">
            @else
              {{ $order->product?->category_emoji ?? '🎁' }}
            @endif
          </div>
          <div class="flex-grow-1">
            <div style="font-size:1rem;font-weight:800;color:#2d1e14;margin-bottom:4px">{{ $order->product_name }}</div>
            @if($order->product)
              <div style="font-size:0.8rem;color:#888">
                {{ $order->product->category_label }}
                &nbsp;·&nbsp;
                <a href="{{ route('admin.products.edit', $order->product) }}"
                   style="color:#7b1113;font-weight:700;text-decoration:none">
                  عرض المنتج <i class="bi bi-box-arrow-up-right" style="font-size:0.75rem"></i>
                </a>
              </div>
            @endif
          </div>
          <div class="text-end flex-shrink-0">
            <div style="font-size:0.75rem;color:#aaa;margin-bottom:4px">سعر الوحدة</div>
            <div style="font-size:1.05rem;font-weight:900;color:#422018">{{ number_format($order->product_price) }} ₪</div>
          </div>
        </div>

        {{-- Price breakdown --}}
        <div style="margin-top:18px;padding-top:18px;border-top:1px dashed #f0e8e0">
          @foreach([
            ['الكمية المطلوبة', $order->quantity . ' قطعة'],
            ['سعر الوحدة', number_format($order->product_price) . ' ₪'],
          ] as [$k, $v])
          <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #faf6f2;font-size:0.9rem">
            <span style="color:#999">{{ $k }}</span>
            <span style="font-weight:700;color:#422018">{{ $v }}</span>
          </div>
          @endforeach
          <div class="d-flex justify-content-between pt-3 mt-1">
            <span style="font-weight:800;color:#422018;font-size:0.95rem">الإجمالي الكلي</span>
            <span style="font-size:1.4rem;font-weight:900;color:#7b1113">{{ number_format($order->total_price) }} ₪</span>
          </div>
        </div>
      </div>

      {{-- Notes --}}
      @if($order->notes || $order->admin_notes)
      <div class="main-table">
        <h6 style="font-weight:800;color:#422018;margin-bottom:18px">📝 الملاحظات</h6>
        @if($order->notes)
        <div class="mb-3">
          <div style="font-size:0.78rem;font-weight:700;color:#aaa;margin-bottom:7px;text-transform:uppercase;letter-spacing:0.5px">ملاحظات العميل</div>
          <div style="background:var(--cream);border-radius:13px;padding:14px 18px;font-size:0.9rem;color:#444;line-height:1.8;border-right:3px solid #d4af37">
            {!! nl2br(e($order->notes)) !!}
          </div>
        </div>
        @endif
        @if($order->admin_notes)
        <div>
          <div style="font-size:0.78rem;font-weight:700;color:#aaa;margin-bottom:7px;text-transform:uppercase;letter-spacing:0.5px">ملاحظات المدير (داخلية)</div>
          <div style="background:#fdf8f2;border-radius:13px;padding:14px 18px;font-size:0.9rem;color:#444;line-height:1.8;border-right:3px solid #7b1113">
            {!! nl2br(e($order->admin_notes)) !!}
          </div>
        </div>
        @endif
      </div>
      @endif

    </div>{{-- /col-lg-8 --}}

    {{-- ── Right Column ────────────────────────────────────────────────── --}}
    <div class="col-lg-4 d-flex flex-column gap-4">

      {{-- Customer Card --}}
      <div class="main-table">
        <h6 style="font-weight:800;color:#422018;margin-bottom:18px">👤 بيانات العميل</h6>

        @foreach([
          ['bi-person-fill',   'rgba(123,17,19,0.08)',    '#7b1113', 'الاسم',        $order->customer_name],
          ['bi-telephone-fill','rgba(37,211,102,0.08)',   '#25D366', 'الجوال',        $order->customer_phone],
          ['bi-envelope-fill', 'rgba(123,17,19,0.06)',    '#7b1113', 'البريد',        $order->customer_email],
          ['bi-geo-alt-fill',  'rgba(212,175,55,0.10)',   '#9a7d0a', 'المدينة',       $order->customer_city],
        ] as [$icon, $bg, $color, $label, $value])
          @if($value)
          <div class="info-row">
            <div class="info-icon" style="background:{{ $bg }};color:{{ $color }}">
              <i class="bi {{ $icon }}"></i>
            </div>
            <div>
              <div class="info-label">{{ $label }}</div>
              <div class="info-value">{{ $value }}</div>
            </div>
          </div>
          @endif
        @endforeach

        @if($order->customer_address)
        <div class="info-row">
          <div class="info-icon" style="background:rgba(212,175,55,0.1);color:#9a7d0a">
            <i class="bi bi-map-fill"></i>
          </div>
          <div>
            <div class="info-label">العنوان</div>
            <div class="info-value" style="font-size:0.85rem;line-height:1.5">{{ $order->customer_address }}</div>
          </div>
        </div>
        @endif

        {{-- Contact Buttons --}}
        <div class="d-flex flex-column gap-2 mt-4 pt-3" style="border-top:1px solid #f0e8e0">
          @if($order->customer_phone)
          <a href="https://wa.me/{{ preg_replace('/\D/', '', $order->customer_phone) }}?text={{ urlencode('السلام عليكم '.$order->customer_name.'، بخصوص طلبك '.$order->order_number.' من {{ setting('site.name') }} ✨') }}"
             target="_blank"
             style="background:#25D366;color:#fff;padding:11px 18px;border-radius:13px;font-weight:800;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:9px;font-size:0.9rem;transition:0.2s"
             onmouseover="this.style.background='#1db954';this.style.boxShadow='0 6px 18px rgba(37,211,102,0.35)'"
             onmouseout="this.style.background='#25D366';this.style.boxShadow=''">
            <i class="bi bi-whatsapp"></i> مراسلة عبر واتساب
          </a>
          @endif
          @if($order->customer_email)
          <a href="mailto:{{ $order->customer_email }}?subject=بخصوص طلبك {{ $order->order_number }} – {{ setting('site.name') }}"
             style="background:rgba(123,17,19,0.07);color:#7b1113;padding:11px 18px;border-radius:13px;font-weight:800;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:9px;font-size:0.9rem;border:1.5px solid transparent;transition:0.2s"
             onmouseover="this.style.borderColor='#7b1113'"
             onmouseout="this.style.borderColor='transparent'">
            <i class="bi bi-envelope"></i> مراسلة بالإيميل
          </a>
          @endif
        </div>
      </div>

      {{-- Payment Card --}}
      <div class="main-table">
        <h6 style="font-weight:800;color:#422018;margin-bottom:18px">💳 الدفع</h6>

        <div style="display:flex;flex-direction:column;gap:12px">
          <div class="d-flex justify-content-between align-items-center">
            <span style="font-size:0.83rem;color:#999">طريقة الدفع</span>
            <span style="font-weight:800;color:#422018;font-size:0.88rem">{{ $order->payment_method_label }}</span>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="padding-top:10px;border-top:1px dashed #f0e8e0">
            <span style="font-size:0.83rem;color:#999">حالة الدفع</span>
            @php
              $pm = ['unpaid'=>['#fef3e8','#d35400'],'pending'=>['#fdf8e8','#9a7d0a'],'paid'=>['#e8faf0','#1a8a4a']];
              [$pb,$pc] = $pm[$order->payment_status] ?? ['#f5f5f5','#888'];
            @endphp
            <span style="background:{{ $pb }};color:{{ $pc }};font-size:0.77rem;font-weight:800;padding:5px 13px;border-radius:20px">
              {{ $order->payment_status_label }}
            </span>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="padding-top:10px;border-top:1px dashed #f0e8e0">
            <span style="font-size:0.83rem;color:#999">مصدر الطلب</span>
            <span style="font-weight:700;color:#422018;font-size:0.88rem">{{ $order->source_label }}</span>
          </div>
        </div>

        {{-- Quick payment update --}}
        <form action="{{ route('admin.orders.update', $order) }}" method="POST"
              class="mt-4 pt-3" style="border-top:1px solid #f0e8e0">
          @csrf @method('PUT')
          {{-- pass all required fields silently --}}
          <input type="hidden" name="customer_name"    value="{{ $order->customer_name }}">
          <input type="hidden" name="customer_phone"   value="{{ $order->customer_phone }}">
          <input type="hidden" name="customer_email"   value="{{ $order->customer_email }}">
          <input type="hidden" name="customer_city"    value="{{ $order->customer_city }}">
          <input type="hidden" name="customer_address" value="{{ $order->customer_address }}">
          <input type="hidden" name="quantity"         value="{{ $order->quantity }}">
          <input type="hidden" name="status"           value="{{ $order->status }}">
          <input type="hidden" name="payment_method"   value="{{ $order->payment_method }}">
          <input type="hidden" name="admin_notes"      value="{{ $order->admin_notes }}">
          <input type="hidden" name="notes"            value="{{ $order->notes }}">
          <input type="hidden" name="source"           value="{{ $order->source }}">

          <label class="form-label" style="font-size:0.82rem">تحديث حالة الدفع السريع</label>
          <div class="d-flex gap-2">
            <select name="payment_status" class="form-select">
              <option value="unpaid"  {{ $order->payment_status==='unpaid'  ?'selected':'' }}>لم يُدفع</option>
              <option value="pending" {{ $order->payment_status==='pending' ?'selected':'' }}>بانتظار تأكيد</option>
              <option value="paid"    {{ $order->payment_status==='paid'    ?'selected':'' }}>تم الدفع ✅</option>
            </select>
            <button type="submit" class="btn-maroon" style="white-space:nowrap">حفظ</button>
          </div>
        </form>
      </div>

      {{-- Meta Card --}}
      <div class="main-table">
        <h6 style="font-weight:800;color:#422018;margin-bottom:14px">ℹ️ معلومات الطلب</h6>
        @foreach([
          ['تاريخ الإنشاء', $order->created_at->format('d/m/Y H:i')],
          ['آخر تحديث',     $order->updated_at->format('d/m/Y H:i')],
          ['منذ',           $order->created_at->diffForHumans()],
        ] as [$k, $v])
        <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #faf6f2;font-size:0.85rem">
          <span style="color:#aaa;font-weight:600">{{ $k }}</span>
          <span style="color:#422018;font-weight:700">{{ $v }}</span>
        </div>
        @endforeach
      </div>

    </div>{{-- /col-lg-4 --}}
  </div>{{-- /row --}}
</div>
@endsection
