<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'reviewer_name'  => 'required|string|max:80',
            'reviewer_phone' => 'nullable|string|max:25',
            'reviewer_city'  => 'nullable|string|max:60',
            'rating'         => 'required|integer|min:1|max:5',
            'title'          => 'nullable|string|max:120',
            'body'           => 'required|string|min:10|max:800',
            'product_id'     => 'nullable|exists:products,id',
        ], [
            'reviewer_name.required' => 'اسمك مطلوب لإضافة التقييم',
            'rating.required'        => 'يرجى اختيار عدد النجوم',
            'body.required'          => 'يرجى كتابة رأيك',
            'body.min'               => 'الرأي قصير جداً — الحد الأدنى 10 أحرف',
        ]);

        // ── فحص التكرار ───────────────────────────────────────────────
        $fingerprint = Review::makeFingerprint(
            $data['reviewer_name'],
            $data['reviewer_phone'] ?? null,
            $data['product_id'] ?? null
        );

        if (Review::alreadySubmitted($fingerprint)) {
            return back()
                ->withInput()
                ->withErrors(['body' => 'لقد أضفت رأيك مسبقاً — شكراً لك! 💝']);
        }

        // ── تحديد الحالة تلقائياً حسب النجوم ──────────────────────────
        ['status' => $status, 'auto_approved' => $autoApproved] = Review::resolveStatus((int) $data['rating']);

        $review = Review::create(array_merge($data, [
            'status'        => $status,
            'auto_approved' => $autoApproved,
            'fingerprint'   => $fingerprint,
            'ip_address'    => $request->ip(),
        ]));

        // ── إشعار للمدير فور وصول أي تقييم ───────────────────────────
        AdminNotification::fromReview($review);

        // ── رسالة مناسبة لكل حالة ────────────────────────────────────
        $message = match ($status) {
            'approved' => 'شكراً لك! 🌟 تم نشر رأيك على الموقع.',
            'pending'  => 'شكراً لك! سيظهر رأيك بعد مراجعة سريعة من الإدارة ✨',
            'rejected' => 'شكراً لتواصلك معنا — نأخذ ملاحظاتك بعين الاعتبار.',
            default    => 'شكراً لك!',
        };

        return back()->with('review_success', $message);
    }
}
