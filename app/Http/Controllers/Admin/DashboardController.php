<?php
// ═══════════════════════════════════════════════════
// app/Http/Controllers/Admin/DashboardController.php
// ═══════════════════════════════════════════════════

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ContactMessage;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'embroideryCount' => Product::where('category', 'embroidery')->count(),
            'handicraftCount' => Product::where('category', 'handicraft')->count(),
            'woolCount'       => Product::where('category', 'wool')->count(),
            'totalProducts'   => Product::count(),
            'pendingOrders'   => Order::whereIn('status', ['pending', 'preparing'])->count(),
            'unreadMessages'  => ContactMessage::where('is_read', false)->count(),
            'whatsappCount'   => 0, // يمكن تتبعه لاحقاً
            'recentProducts'  => Product::latest()->take(8)->get(),
            'recentMessages'  => ContactMessage::latest()->take(4)->get(),
        ]);
    }
}
