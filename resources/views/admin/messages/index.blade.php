@extends('layouts.admin')
@section('title', 'الرسائل')
@section('page-title', 'الرسائل الواردة')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <div>
                <h4 class="fw-bold m-0 text-brown">💬 الرسائل</h4>
                <p class="text-muted small mb-0">{{ $messages->total() }} رسالة •
                    {{ $messages->where('is_read', false)->count() }} غير مقروءة</p>
            </div>
        </div>

        <div class="row g-3">
            @forelse($messages as $msg)
                <div class="col-md-6">
                    <div class="msg-card {{ !$msg->is_read ? 'unread' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div
                                    style="width:40px;height:40px;border-radius:11px;background:rgba(123,17,19,0.1);display:flex;align-items:center;justify-content:center;color:#7b1113;font-size:1.1rem">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size:0.92rem">{{ $msg->name }}</h6>
                                    <small class="text-muted">{{ $msg->phone ?? $msg->email }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if (!$msg->is_read)
                                    <span
                                        style="background:#7b1113;color:#fff;font-size:0.65rem;padding:2px 8px;border-radius:20px;font-weight:700">جديد</span>
                                @endif
                                <small class="text-muted"
                                    style="font-size:0.72rem">{{ $msg->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        @if ($msg->subject)
                            <div style="font-size:0.78rem;font-weight:700;color:#7b1113;margin-bottom:6px">
                                {{ ['order' => '🛍️ طلب منتج', 'custom' => '🎨 تصميم خاص', 'inquiry' => '❓ استفسار', 'other' => '💬 أخرى'][$msg->subject] ?? $msg->subject }}
                            </div>
                        @endif

                        <p style="font-size:0.87rem;color:#666;line-height:1.6;margin-bottom:14px">
                            {{ Str::limit($msg->message, 100) }}
                        </p>

                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ route('admin.messages.show', $msg->id) }}"
                                class="btn-action py-1 px-3 text-decoration-none">
                                <i class="bi bi-eye"></i> قراءة الرسالة
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $msg->phone ?? '') }}" target="_blank"
                                style="background:#e8f9ef;color:#25D366;border:none;padding:5px 14px;border-radius:10px;font-weight:700;font-size:0.82rem;text-decoration:none;display:inline-flex;align-items:center;gap:5px">
                                <i class="bi bi-whatsapp"></i> رد واتساب
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <div style="font-size:3rem;margin-bottom:12px;opacity:0.2">💬</div>
                    لا توجد رسائل حتى الآن
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $messages->links() }}</div>
    </div>
@endsection
