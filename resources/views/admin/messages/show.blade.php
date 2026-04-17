{{-- resources/views/admin/messages/show.blade.php --}}
@extends('layouts.admin')
@section('title', 'الرسالة')
@section('page-title', 'تفاصيل الرسالة')

@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="main-table mt-2">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div
                                style="width:52px;height:52px;border-radius:14px;background:rgba(123,17,19,0.1);display:flex;align-items:center;justify-content:center;color:#7b1113;font-size:1.4rem">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold m-0 text-brown">{{ $message->name }}</h5>
                                <small class="text-muted">{{ $message->created_at->format('l، d F Y — H:i') }}</small>
                            </div>
                        </div>
                        <span
                            style="background:{{ ['order' => '#fdf0f0', 'custom' => '#fdf5e8', 'inquiry' => '#edf7ef', 'other' => '#f0f0f0'][$message->subject] ?? '#f0f0f0' }};color:{{ ['order' => '#7b1113', 'custom' => '#9a7d0a', 'inquiry' => '#1a8a4a', 'other' => '#666'][$message->subject] ?? '#666' }};font-size:0.8rem;font-weight:700;padding:5px 14px;border-radius:20px">
                            {{ $message->subject_label }}
                        </span>
                    </div>

                    <!-- Contact Info -->
                    <div class="row g-3 mb-4 p-3 rounded-4" style="background:var(--cream)">
                        @if ($message->phone)
                            <div class="col-auto">
                                <div class="d-flex align-items-center gap-2 small fw-bold text-brown">
                                    <i class="bi bi-telephone-fill text-maroon"></i>
                                    {{ $message->phone }}
                                </div>
                            </div>
                        @endif
                        @if ($message->email)
                            <div class="col-auto">
                                <div class="d-flex align-items-center gap-2 small fw-bold text-brown">
                                    <i class="bi bi-envelope-fill text-maroon"></i>
                                    {{ $message->email }}
                                </div>
                            </div>
                        @endif
                        @if ($message->product)
                            <div class="col-auto">
                                <div class="d-flex align-items-center gap-2 small fw-bold text-brown">
                                    <i class="bi bi-box text-maroon"></i>
                                    المنتج: {{ $message->product->name }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Message -->
                    <div class="mb-5">
                        <h6 class="fw-bold text-brown mb-3">نص الرسالة:</h6>
                        <div
                            style="background:#fafaf8;border-radius:16px;padding:22px 24px;font-size:0.95rem;line-height:1.85;color:#444;border-right:3px solid #7b1113">
                            {!! nl2br(e($message->message)) !!}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-3 flex-wrap">
                        @if ($message->phone)
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $message->phone) }}?text={{ urlencode('السلام عليكم ' . $message->name . '، شكراً لتواصلك مع ' . setting('site.name') . '  ✨') }}"
                                target="_blank"
                                style="background:#25D366;color:#fff;padding:11px 22px;border-radius:13px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;gap:8px;font-size:0.9rem">
                                <i class="bi bi-whatsapp"></i> رد عبر واتساب
                            </a>
                        @endif
                        @if ($message->email)
                            <a href="mailto:{{ $message->email }}?subject=رد على رسالتك – {{ setting('site.name') }}"
                                style="background:rgba(123,17,19,0.1);color:#7b1113;padding:11px 22px;border-radius:13px;font-weight:800;text-decoration:none;display:inline-flex;align-items:center;gap:8px;font-size:0.9rem">
                                <i class="bi bi-envelope"></i> رد بالإيميل
                            </a>
                        @endif
                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST"
                            onsubmit="return confirm('حذف هذه الرسالة؟')">
                            @csrf @method('DELETE')
                            <button
                                style="background:#fdf0f0;color:#7b1113;padding:11px 22px;border-radius:13px;font-weight:800;border:none;cursor:pointer;font-size:0.9rem">
                                <i class="bi bi-trash"></i> حذف
                            </button>
                        </form>
                        <a href="{{ route('admin.messages.index') }}"
                            style="color:#888;padding:11px 16px;font-size:0.88rem;text-decoration:none;align-self:center">
                            ← العودة
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
