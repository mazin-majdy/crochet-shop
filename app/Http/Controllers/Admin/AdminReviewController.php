<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /*
    |─────────────────────────────────────────────────────────────────────────
    | INDEX — إدارة التقييمات مع فلاتر
    |─────────────────────────────────────────────────────────────────────────
    */
    public function index(Request $request)
    {
        $query = Review::with('product')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reviewer_name', 'like', "%{$s}%")
                    ->orWhere('body', 'like', "%{$s}%")
                    ->orWhere('title', 'like', "%{$s}%");
            });
        }

        $reviews = $query->paginate(20)->withQueryString();
        $stats   = Review::stats();

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | APPROVE — الموافقة على تقييم
    |─────────────────────────────────────────────────────────────────────────
    */
    public function approve(Review $review)
    {
        $review->update([
            'status'       => 'approved',
            'auto_approved' => false,
            'admin_note'   => 'تمت الموافقة من المدير',
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'status' => 'approved']);
        }

        return back()->with('success', "تم نشر تقييم {$review->reviewer_name} ✅");
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | REJECT — رفض تقييم
    |─────────────────────────────────────────────────────────────────────────
    */
    public function reject(Request $request, Review $review)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:255',
        ]);

        $review->update([
            'status'     => 'rejected',
            'admin_note' => $request->admin_note ?? 'تم الرفض من المدير',
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'status' => 'rejected']);
        }

        return back()->with('success', "تم رفض تقييم {$review->reviewer_name}");
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | DESTROY — حذف نهائي
    |─────────────────────────────────────────────────────────────────────────
    */
    public function destroy(Review $review)
    {
        $name = $review->reviewer_name;
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', "تم حذف تقييم {$name}");
    }
}
