<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        return view('admin.products.index', [
            'products' => $query->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.products.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'category'    => 'required|in:embroidery,handicraft,wool',
            'target'      => 'nullable|in:kids,girls,women,men,general',
            'tags'        => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
            'is_active'   => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $data['slug']        = Str::slug($data['name'].'-'.Str::random(5));
        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح ✨');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'category'    => 'required|in:embroidery,handicraft,wool',
            'target'      => 'nullable|in:kids,girls,women,men,general',
            'tags'        => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
            'is_active'   => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح ✅');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج');
    }
}
