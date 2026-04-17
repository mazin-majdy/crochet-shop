<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /*
    |─────────────────────────────────────────────────────────────────────────
    | INDEX — قائمة الطلبات مع فلاتر + إحصاءات
    |─────────────────────────────────────────────────────────────────────────
    */
    public function index(Request $request)
    {
        $query = Order::with('product')->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number',   'like', "%{$s}%")
                  ->orWhere('customer_name',  'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%");
            });
        }

        $orders = $query->paginate(7)->withQueryString();

        $stats = [
            'all'       => Order::count(),
            'pending'   => Order::pending()->count(),
            'confirmed' => Order::confirmed()->count(),
            'completed' => Order::completed()->count(),
            'cancelled' => Order::cancelled()->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | SHOW — تفاصيل طلب واحد
    |─────────────────────────────────────────────────────────────────────────
    */
    public function show(Order $order)
    {
        $order->load('product');
        return view('admin.orders.show', compact('order'));
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | CREATE — نموذج إنشاء طلب يدوي من المدير
    |─────────────────────────────────────────────────────────────────────────
    */
    public function create()
    {
        $products = Product::active()->orderBy('category')->orderBy('name')->get();
        return view('admin.orders.form', compact('products'));
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | STORE — حفظ طلب جديد
    |─────────────────────────────────────────────────────────────────────────
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'    => 'required|string|max:100',
            'customer_phone'   => 'required|string|max:25',
            'customer_email'   => 'nullable|email|max:150',
            'customer_city'    => 'nullable|string|max:80',
            'customer_address' => 'nullable|string|max:500',
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1|max:99',
            'payment_method'   => 'nullable|in:palpay,bank_palestine,jawwalpay,cash_on_pickup',
            'source'           => 'nullable|in:website,whatsapp',
            'notes'            => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'اسم العميل مطلوب',
            'customer_phone.required'=> 'رقم الجوال مطلوب',
            'product_id.required'    => 'يرجى اختيار منتج',
        ]);

        $product = Product::findOrFail($data['product_id']);

        Order::create(array_merge($data, [
            'product_name'   => $product->name,
            'product_price'  => $product->price,
            'total_price'    => $product->price * $data['quantity'],
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'source'         => $data['source'] ?? 'website',
        ]));

        return redirect()->route('admin.orders.index')
            ->with('success', 'تم إنشاء الطلب بنجاح ✅');
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | EDIT — نموذج تعديل طلب
    |─────────────────────────────────────────────────────────────────────────
    */
    public function edit(Order $order)
    {
        $products = Product::active()->orderBy('category')->orderBy('name')->get();
        return view('admin.orders.form', compact('order', 'products'));
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | UPDATE — حفظ التعديلات
    |─────────────────────────────────────────────────────────────────────────
    */
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'customer_name'    => 'required|string|max:100',
            'customer_phone'   => 'required|string|max:25',
            'customer_email'   => 'nullable|email|max:150',
            'customer_city'    => 'nullable|string|max:80',
            'customer_address' => 'nullable|string|max:500',
            'quantity'         => 'required|integer|min:1|max:99',
            'payment_method'   => 'nullable|in:palpay,bank_palestine,jawwalpay,cash_on_pickup',
            'payment_status'   => 'required|in:unpaid,pending,paid',
            'status'           => 'required|in:pending,confirmed,preparing,shipped,completed,cancelled',
            'source'           => 'nullable|in:website,whatsapp',
            'admin_notes'      => 'nullable|string|max:1000',
            'notes'            => 'nullable|string|max:1000',
        ]);

        // إعادة حساب الإجمالي إن تغيّرت الكمية
        $data['total_price'] = $order->product_price * $data['quantity'];

        $order->update($data);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'تم تحديث الطلب بنجاح ✅');
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | UPDATE STATUS — تحديث الحالة فقط (AJAX + fallback form)
    |─────────────────────────────────────────────────────────────────────────
    */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,shipped,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);
        $order->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'label'   => $order->status_label,
                'color'   => $order->status_color,
                'bg'      => $order->status_bg,
                'status'  => $order->status,
            ]);
        }

        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | DESTROY — حذف ناعم
    |─────────────────────────────────────────────────────────────────────────
    */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'تم حذف الطلب #' . $order->order_number);
    }
}
