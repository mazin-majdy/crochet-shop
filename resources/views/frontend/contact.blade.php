@extends('layouts.website')

@section('title', 'تواصل معنا')

@section('content')

    <div style="background:linear-gradient(135deg,#fdf5ef,#f5e8dc);padding:52px 5% 44px;border-bottom:1px solid #eee">
        <div class="eyebrow"
            style="color:var(--maroon);font-size:0.78rem;font-weight:800;letter-spacing:2px;text-transform:uppercase;display:block;margin-bottom:10px">
            التواصل</div>
        <h1 style="font-size:clamp(1.8rem,4vw,2.6rem);font-weight:900;color:#422018;margin-bottom:10px">تواصل معنا</h1>
        <p style="color:#8a7060;margin:0;font-size:1rem">نحن هنا لخدمتك — اكتبي لنا وسنردّ بسرعة ✨</p>
    </div>

    <section class="contact-section">
        <div class="contact-grid">

            <!-- Form -->
            <div class="contact-form-card">
                <h3 style="font-size:1.35rem;font-weight:900;color:#422018;margin-bottom:22px">🖊️ أرسل رسالتك</h3>

                @if (session('success'))
                    <div
                        style="background:#edf7ef;border-radius:14px;padding:14px 18px;margin-bottom:20px;color:#1a8a4a;font-weight:700;font-size:0.9rem">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        style="background:#fdf0f0;border-radius:14px;padding:14px 18px;margin-bottom:20px;color:#7b1113;font-size:0.88rem">
                        <ul style="margin:0;padding-right:16px">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    @if (request('product'))
                        <input type="hidden" name="product_id" value="{{ request('product') }}">
                    @endif

                    <div class="mb-4">
                        <label class="form-label">الاسم الكريم *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="ما اسمك؟" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">رقم الجوال *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control"
                                placeholder="05xxxxxxxx" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="اختياري">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">موضوع الرسالة</label>
                        <select name="subject" class="form-select">
                            <option value="order">طلب منتج</option>
                            <option value="custom">طلب تصميم خاص</option>
                            <option value="inquiry">استفسار</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">رسالتك *</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="اكتبي رسالتك هنا... أخبرينا بما تحتاجين ✨"
                            required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn-auth" style="background:var(--maroon)">
                        <i class="bi bi-send-fill me-2"></i> إرسال الرسالة
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info-card">
                <h3 style="font-size:1.35rem;font-weight:900;color:#422018;margin-bottom:28px">📬 معلومات التواصل</h3>

                <div class="contact-info-item">
                    <div class="contact-icon green">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div>
                        <h6 style="font-weight:800;color:#422018;margin-bottom:4px">واتساب (الأسرع)</h6>
                        <p style="color:#8a7060;font-size:0.9rem;margin:0">تواصل مباشر وسريع مع الإدارة</p>
                        <a href="https://wa.me/{{ config('app.whatsapp_number', setting('contact.whatsapp')) }}"
                            target="_blank"
                            style="display:inline-flex;align-items:center;gap:7px;margin-top:10px;background:#25D366;color:#fff;padding:9px 18px;border-radius:12px;font-weight:800;font-size:0.88rem;text-decoration:none">
                            <i class="bi bi-whatsapp"></i>
                            فتح واتساب
                        </a>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-icon maroon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <h6 style="font-weight:800;color:#422018;margin-bottom:4px">البريد الإلكتروني</h6>
                        <p style="color:#8a7060;font-size:0.9rem;margin:0">{{ setting('contact.email') }}</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-icon gold">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div>
                        <h6 style="font-weight:800;color:#422018;margin-bottom:4px">أوقات العمل</h6>
                        <p style="color:#8a7060;font-size:0.9rem;margin:0;line-height:1.7">
                            {{ setting('contact.hours') }}
                        </p>
                    </div>
                </div>

                <!-- Payment methods -->
                <div style="background:var(--cream-3);border-radius:18px;padding:22px;margin-top:8px">
                    <h6 style="font-weight:800;color:#422018;margin-bottom:14px">💳 طرق الدفع</h6>
                    @foreach ($paymentMethods as $method)
                        <div
                            style="display:flex;align-items:center;gap:10px;margin-bottom:10px;font-size:0.88rem;color:#6b5040;font-weight:600">
                            <i class="bi bi-check-circle-fill" style="color:#7b1113"></i>{!! $method['icon'] !!} {{ $method['name'] }}
                        </div>
                    @endforeach
                    <p style="font-size:0.8rem;color:#aaa;margin:12px 0 0;line-height:1.6">
                        * يتم التنسيق على طريقة الدفع عند التواصل المباشر
                    </p>
                </div>
            </div>

        </div>
    </section>

@endsection
