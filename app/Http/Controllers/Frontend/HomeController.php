<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->where(function ($q) {
                $q->where('is_featured', true)
                    ->orWhereRaw('1=1'); // show all if none featured
            })
            ->latest()
            ->take(6)
            ->get();

        $paymentMethods = config('paymentMethods');
        $testimonials = [
            [
                'سارة أحمد',
                'رام الله',
                'س',
                'طلبت ثوب تطريز لعرسي وجاء أجمل من توقعاتي! الشغل دقيق جداً والألوان رائعة. شكراً لمسة خيط ❤️',
                5,
            ],
            [
                'أم محمد',
                'غزة',
                'أ',
                'شال الصوف جودته عالية جداً وحار كثير. بناتي انبسطوا فيه. بدي أطلب أكثر قريب!',
                5,
            ],
            [
                'فاطمة الزهراء',
                'الخليل',
                'ف',
                'حقيبة الكروشيه مميزة وفريدة. كل اللي شافوها سألوني من وين. شكراً على التغليف الجميل أيضاً!',
                5,
            ],
            [
                'خولة جاسم',
                'نابلس',
                'خ',
                'تعاملت معهم أكثر من مرة والخدمة ممتازة. سرعة في التوصيل والمنتجات دايماً مطابقة للصور.',
                5,
            ],
            [
                'ريم العمري',
                'جنين',
                'ر',
                'اشتريت إكليل كروشيه لابنتي وحبته كثير. الشغل محكم وما تهزز. الله يعطيكم العافية!',
                5,
            ],
            [
                'نور الدين',
                'طولكرم',
                'ن',
                'قميص التطريز الفلسطيني تحفة فنية حقيقية. ارتديته في الأعياد وكلهم أعجبوا فيه.',
                5,
            ],
        ];
        $generalReviews = Review::where('status', 'approved')->latest()->take(5)->get();

        return view('frontend.home', compact('featuredProducts', 'paymentMethods', 'testimonials', 'generalReviews'));
    }
}
