<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('target')) {
            $query->where('target', $request->target);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $total    = Product::active()->count();

        return view('frontend.products', compact('products', 'total'));
    }

    public function show($identifier)
    {
        $product = Product::active()->where('slug', $identifier)->first();

        if (!$product && is_numeric($identifier)) {
            $product = Product::active()->findOrFail($identifier);
        }

        abort_if(!$product, 404);

        $related = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()->take(4)->get();

        // جلب التقييمات المنشورة لهذا المنتج
        $reviews = Review::forProduct($product->id)
            ->latest()->paginate(5);

        $reviewStats = [
            'count' => Review::forProduct($product->id)->count(),
            'avg'   => round(Review::forProduct($product->id)->avg('rating') ?? 0, 1),
        ];

        return view('frontend.product-detail', compact('product', 'related', 'reviews', 'reviewStats'));
    }
}
