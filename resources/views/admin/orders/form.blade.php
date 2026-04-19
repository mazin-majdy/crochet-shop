@extends('layouts.admin')
@section('title', isset($order) ? 'تعديل ' . $order->order_number : 'طلب يدوي جديد')
@section('page-title', isset($order) ? 'تعديل الطلب' : 'إنشاء طلب يدوي')

@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-xl-9">

                {{-- Errors --}}
                @if ($errors->any())
                    <div
                        style="background:#fdf0f0;border-radius:14px;padding:14px 20px;margin-bottom:20px;margin-top:8px;color:#7b1113;font-size:0.88rem;border-right:3px solid #7b1113">
                        <div style="font-weight:800;margin-bottom:6px"><i
                                class="bi bi-exclamation-triangle-fill me-2"></i>يرجى تصحيح الأخطاء التالية:</div>
                        <ul style="margin:0;padding-right:18px">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="main-table mt-2">
                    <h5 class="fw-bold mb-1 text-brown">
                        {{ isset($order) ? '✏️ تعديل: ' . $order->order_number : '🛍️ طلب يدوي جديد' }}
                    </h5>
                    <p style="font-size:0.85rem;color:#aaa;margin-bottom:28px">
                        {{ isset($order) ? 'عدّل البيانات المطلوبة ثم احفظ' : 'أدخل بيانات العميل والمنتج لإنشاء طلب يدوي' }}
                    </p>

                    <form action="{{ isset($order) ? route('admin.orders.update', $order) : route('admin.orders.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($order))
                            @method('PUT')
                        @endif

                        {{-- ── Customer Data ────────────────────────────────────────── --}}
                        <div class="mb-4 p-4 rounded-4" style="background:var(--cream);border:1px solid #f0e8e0">
                            <h6 style="font-weight:800;color:#422018;margin-bottom:18px">
                                <i class="bi bi-person-fill text-maroon me-2"></i> بيانات العميل
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">الاسم الكريم *</label>
                                    <input type="text" name="customer_name" class="form-control"
                                        value="{{ old('customer_name', $order->customer_name ?? '') }}"
                                        placeholder="اسم العميل" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">رقم الجوال *</label>
                                    <input type="tel" name="customer_phone" class="form-control"
                                        value="{{ old('customer_phone', $order->customer_phone ?? '') }}"
                                        placeholder="05xxxxxxxx" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="customer_email" class="form-control"
                                        value="{{ old('customer_email', $order->customer_email ?? '') }}"
                                        placeholder="اختياري">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المدينة / المنطقة</label>
                                    <input type="text" name="customer_city" class="form-control"
                                        value="{{ old('customer_city', $order->customer_city ?? '') }}"
                                        placeholder="رام الله، غزة، الخليل...">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">العنوان التفصيلي</label>
                                    <textarea name="customer_address" class="form-control" rows="2" placeholder="الحي، الشارع، رقم البناية...">{{ old('customer_address', $order->customer_address ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ── Product ─────────────────────────────────────────────── --}}
                        <div class="mb-4 p-4 rounded-4" style="background:#fdfaf6;border:1px solid #f0e8e0">
                            <h6 style="font-weight:800;color:#422018;margin-bottom:18px">
                                <i class="bi bi-bag-fill text-maroon me-2"></i> المنتج المطلوب
                            </h6>
                            <div class="row g-3">

                                @if (!isset($order))
                                    <div class="col-md-8">
                                        <label class="form-label">اختر المنتج *</label>
                                        <select name="product_id" class="form-select" required id="productSelect"
                                            onchange="onProductChange(this)">
                                            <option value="">── اختر من القائمة ──</option>
                                            @foreach ($products->groupBy('category') as $cat => $catProducts)
                                                <optgroup
                                                    label="{{ ['embroidery' => '🪡 التطريز', 'handicraft' => '✂️ أشغال يدوية', 'wool' => '🧶 أعمال الصوف'][$cat] ?? $cat }}">
                                                    @foreach ($catProducts as $p)
                                                        <option value="{{ $p->id }}"
                                                            data-price="{{ $p->price }}"
                                                            {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                                            {{ $p->name }} — {{ number_format($p->price) }} ₪
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="col-md-8">
                                        <label class="form-label">المنتج</label>
                                        <div
                                            style="background:#fff;border:1.5px solid #e8ddd4;border-radius:12px;padding:11px 15px;font-weight:700;color:#422018">
                                            {{ $order->product_name }}
                                            <span style="color:#aaa;font-weight:500"> —
                                                {{ number_format($order->product_price) }} ₪</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <label class="form-label">الكمية *</label>
                                    <input type="number" name="quantity" id="qtyInput" class="form-control"
                                        value="{{ old('quantity', $order->quantity ?? 1) }}" min="1" max="99"
                                        required oninput="calcTotal()">
                                </div>

                                {{-- Total preview (create only) --}}
                                @if (!isset($order))
                                    <div class="col-12">
                                        <div id="totalBox"
                                            style="display:none;background:rgba(123,17,19,0.05);border-radius:13px;padding:14px 20px;border:1.5px solid rgba(123,17,19,0.1);display:flex;align-items:center;justify-content:space-between">
                                            <span style="font-size:0.88rem;color:#7b1113;font-weight:700">الإجمالي
                                                التقديري</span>
                                            <span id="totalAmt" style="font-size:1.4rem;font-weight:900;color:#7b1113">0
                                                ₪</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="form-label">مصدر الطلب</label>
                                    <select name="source" class="form-select">
                                        <option value="website"
                                            {{ old('source', $order->source ?? 'website') === 'website' ? 'selected' : '' }}>
                                            🌐 الموقع الإلكتروني</option>
                                        <option value="whatsapp"
                                            {{ old('source', $order->source ?? '') === 'whatsapp' ? 'selected' : '' }}>💬
                                            واتساب</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">طريقة الدفع</label>
                                    <select name="payment_method" class="form-select">
                                        <option value="">── اختر ──</option>
                                        @foreach (\App\Models\Order::paymentMethods() as $v => $l)
                                            <option value="{{ $v }}"
                                                {{ old('payment_method', $order->payment_method ?? '') === $v ? 'selected' : '' }}>
                                                {{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- ── Status (edit only) ──────────────────────────────────── --}}
                        @if (isset($order))
                            <div class="mb-4 p-4 rounded-4" style="background:#fdfaf6;border:1px solid #f0e8e0">
                                <h6 style="font-weight:800;color:#422018;margin-bottom:18px">
                                    <i class="bi bi-flag-fill text-maroon me-2"></i> الحالة والدفع
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">حالة الطلب</label>
                                        <select name="status" class="form-select">
                                            @foreach (\App\Models\Order::statusOptions() as $v => $l)
                                                <option value="{{ $v }}"
                                                    {{ old('status', $order->status) === $v ? 'selected' : '' }}>
                                                    {{ $l }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">حالة الدفع</label>
                                        <select name="payment_status" class="form-select">
                                            <option value="unpaid"
                                                {{ old('payment_status', $order->payment_status) === 'unpaid' ? 'selected' : '' }}>
                                                لم يُدفع</option>
                                            <option value="pending"
                                                {{ old('payment_status', $order->payment_status) === 'pending' ? 'selected' : '' }}>
                                                بانتظار تأكيد</option>
                                            <option value="paid"
                                                {{ old('payment_status', $order->payment_status) === 'paid' ? 'selected' : '' }}>
                                                تم الدفع ✅</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- ── Notes ──────────────────────────────────────────────── --}}
                        <div class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">ملاحظات العميل</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="طلبات أو تفاصيل خاصة من العميل...">{{ old('notes', $order->notes ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        ملاحظات المدير
                                        <small style="color:#bbb;font-weight:500">(داخلية — لا يراها العميل)</small>
                                    </label>
                                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="ملاحظاتك الخاصة على هذا الطلب...">{{ old('admin_notes', $order->admin_notes ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-3 justify-content-end pt-4" style="border-top:1px solid #f0e8e0">
                            <a href="{{ isset($order) ? route('admin.orders.show', $order) : route('admin.orders.index') }}"
                                class="btn-action py-2 px-4 text-decoration-none">
                                إلغاء
                            </a>
                            <button type="submit" class="btn-maroon px-5">
                                <i class="bi bi-check-lg"></i>
                                {{ isset($order) ? 'حفظ التعديلات' : 'إنشاء الطلب' }}
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let _unitPrice = 0;

        function onProductChange(sel) {
            const opt = sel.options[sel.selectedIndex];
            _unitPrice = parseFloat(opt.dataset.price || 0);
            calcTotal();
        }

        function calcTotal() {
            const qty = parseInt(document.getElementById('qtyInput')?.value || 1, 10);
            const total = _unitPrice * qty;
            const box = document.getElementById('totalBox');
            const amt = document.getElementById('totalAmt');
            if (!box || !amt) return;
            if (_unitPrice > 0 && qty > 0) {
                amt.textContent = total.toLocaleString('ar-SA') + ' ₪';
                box.style.display = 'flex';
            } else {
                box.style.display = 'none';
            }
        }

        // Init on edit page if product already selected
        document.addEventListener('DOMContentLoaded', () => {
            const sel = document.getElementById('productSelect');
            if (sel) onProductChange(sel);
        });
    </script>
@endpush
