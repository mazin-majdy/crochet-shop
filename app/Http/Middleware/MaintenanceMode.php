<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * نتحقق إذا كان وضع الصيانة مفعّلاً.
     *
     * القواعد:
     *  - إذا الصيانة مفعّلة والمستخدم مدير (مسجّل دخول) → نسمح له بالمرور عادياً
     *  - إذا الصيانة مفعّلة وأي زائر عادي             → نعرض صفحة الصيانة (503)
     *  - إذا الصيانة معطّلة                            → نكمل الطلب عادياً
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تحقق من قاعدة البيانات (مع الـ Cache التلقائي في Setting::get)
        $isUnderMaintenance = Setting::get('site.maintenance', false);

        if ($isUnderMaintenance) {
            // نسمح للمديرين بتصفح الموقع حتى أثناء الصيانة
            if (Auth::check()) {
                return $next($request);
            }

            // أي زائر عادي → صفحة الصيانة
            return response()
                ->view('maintenance', [], 503)
                ->header('Retry-After', '3600');
        }

        return $next($request);
    }
}
