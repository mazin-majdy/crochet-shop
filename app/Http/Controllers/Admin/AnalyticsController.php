<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    /**
     * GET /admin/analytics/live
     * يُستدعى كل 30 ثانية من الـ Dashboard
     */
    public function live(): JsonResponse
    {
        return response()->json([
            'active_now' => PageView::activeNow(),
            'today'      => PageView::today(),
        ]);
    }

    /**
     * GET /admin/analytics/summary
     * بيانات المخططات (أبطأ — يُستدعى مرة عند تحميل الصفحة)
     */
    public function summary(): JsonResponse
    {
        return response()->json([
            'totals'     => PageView::allTimeTotals(),
            'last7days'  => PageView::last7Days(),
            'top_pages'  => PageView::topPages(5),
            'devices'    => PageView::deviceBreakdown(),
        ]);
    }
}
