<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'url',
        'page',
        'session_id',
        'ip_hash',
        'device',
        'country',
        'referrer',
        'utm_source',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /*
    |─────────────────────────────────────────────────────────────────────────
    | ACTIVE USERS — الزوار النشطون الآن (آخر 5 دقائق)
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function activeNow(): int
    {
        // نستخدم Cache لتخفيف الضغط على قاعدة البيانات
        return Cache::remember('analytics:active_now', 60, function () {
            return static::where('visited_at', '>=', now()->subMinutes(5))
                ->distinct('session_id')
                ->count('session_id');
        });
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | TODAY — إحصاءات اليوم
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function today(): array
    {
        return Cache::remember('analytics:today', 120, function () {
            $today = static::whereDate('visited_at', today());

            return [
                'views'    => $today->count(),
                'visitors' => $today->distinct('session_id')->count('session_id'),
            ];
        });
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | LAST 7 DAYS — مخطط الأيام السبع الأخيرة
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function last7Days(): array
    {
        return Cache::remember('analytics:last_7_days', 300, function () {
            $days = [];
            for ($i = 6; $i >= 0; $i--) {
                $date  = now()->subDays($i)->toDateString();
                $label = now()->subDays($i)->locale('ar')->isoFormat('ddd D/M');

                $row = static::whereDate('visited_at', $date)
                    ->selectRaw('COUNT(*) as views, COUNT(DISTINCT session_id) as visitors')
                    ->first();

                $days[] = [
                    'date'     => $date,
                    'label'    => $label,
                    'views'    => (int) ($row->views ?? 0),
                    'visitors' => (int) ($row->visitors ?? 0),
                ];
            }
            return $days;
        });
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | TOP PAGES — أكثر الصفحات زيارةً (آخر 30 يوم)
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function topPages(int $limit = 5): array
    {
        return Cache::remember('analytics:top_pages', 300, function () use ($limit) {
            return static::where('visited_at', '>=', now()->subDays(30))
                ->whereNotNull('page')
                ->groupBy('page')
                ->orderByRaw('COUNT(*) DESC')
                ->limit($limit)
                ->selectRaw('page, COUNT(*) as views, COUNT(DISTINCT session_id) as visitors')
                ->get()
                ->map(fn($r) => [
                    'page'     => $r->page,
                    'label'    => self::pageLabel($r->page),
                    'views'    => $r->views,
                    'visitors' => $r->visitors,
                ])
                ->toArray();
        });
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | DEVICES — توزيع الأجهزة
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function deviceBreakdown(): array
    {
        return Cache::remember('analytics:devices', 300, function () {
            $total = static::where('visited_at', '>=', now()->subDays(30))->count();
            if ($total === 0) return [];

            return static::where('visited_at', '>=', now()->subDays(30))
                ->groupBy('device')
                ->orderByRaw('COUNT(*) DESC')
                ->selectRaw('device, COUNT(*) as count')
                ->get()
                ->map(fn($r) => [
                    'device'  => $r->device,
                    'label'   => match ($r->device) {
                        'mobile'  => 'موبايل 📱',
                        'tablet'  => 'تابلت 📲',
                        default   => 'كمبيوتر 💻',
                    },
                    'count'   => $r->count,
                    'percent' => round(($r->count / $total) * 100),
                ])
                ->toArray();
        });
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | TOTALS — إجمالي كل الأوقات
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function allTimeTotals(): array
    {
        return Cache::remember('analytics:totals', 600, function () {
            return [
                'total_views'    => static::count(),
                'total_visitors' => static::distinct('session_id')->count('session_id'),
                'this_month'     => static::whereMonth('visited_at', now()->month)->count(),
            ];
        });
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Flush analytics cache (بعد كل تسجيل زيارة)
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function flushCache(): void
    {
        foreach (['active_now', 'today', 'last_7_days', 'top_pages', 'devices', 'totals'] as $key) {
            Cache::forget("analytics:{$key}");
        }
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Helper — اسم الصفحة
    |─────────────────────────────────────────────────────────────────────────
    */
    private static function pageLabel(string $page): string
    {
        return match ($page) {
            'home'    => '🏠 الرئيسية',
            'products' => '🛍️ المنتجات',
            'product' => '📦 تفاصيل منتج',
            'contact' => '💬 تواصل معنا',
            default   => $page,
        };
    }
}
