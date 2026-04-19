<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    /**
     * يُسجَّل تلقائياً على كل صفحة عامة
     * مستثنى: الـ Admin، الـ API، الـ Assets، الـ Ajax
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // نسجّل فقط طلبات GET الناجحة (200) وليس AJAX
        if (
            $request->isMethod('GET')
            && $response->getStatusCode() === 200
            && !$request->expectsJson()
            && !$request->ajax()
        ) {
            $this->record($request);
        }

        return $response;
    }

    private function record(Request $request): void
    {
        try {
            PageView::create([
                'url'        => $request->path(),
                'page'       => $this->detectPage($request),
                'session_id' => hash('sha256', $request->session()->getId()),
                'ip_hash'    => hash('sha256', $request->ip() . config('app.key')),
                'device'     => $this->detectDevice($request->userAgent() ?? ''),
                'referrer'   => $this->cleanReferrer($request->header('referer')),
                'utm_source' => $request->query('utm_source'),
                'visited_at' => now(),
            ]);

            // مسح كاش active_now فقط (الأهم للحظي)
            \Cache::forget('analytics:active_now');
            \Cache::forget('analytics:today');

        } catch (\Throwable) {
            // صمت — لا تكسر الموقع بسبب تحليلات
        }
    }

    private function detectPage(Request $request): string
    {
        $path = trim($request->path(), '/');

        return match(true) {
            $path === ''                     => 'home',
            $path === 'products'             => 'products',
            str_starts_with($path,'products/')=> 'product',
            $path === 'contact'              => 'contact',
            default                          => 'other',
        };
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);

        if (preg_match('/tablet|ipad|kindle|playbook|silk|(android(?!.*mobile))/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile/i', $ua)) {
            return 'mobile';
        }
        return 'desktop';
    }

    private function cleanReferrer(?string $ref): ?string
    {
        if (!$ref) return null;
        // نزيل الـ query string للخصوصية ونأخذ الـ domain فقط
        $host = parse_url($ref, PHP_URL_HOST);
        return $host ?: null;
    }
}
