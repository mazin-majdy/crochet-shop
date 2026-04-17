<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_city',
        'customer_address',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'total_price',
        'payment_method',
        'payment_status',
        'status',
        'source',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'product_price' => 'float',
        'total_price'   => 'float',
        'quantity'      => 'integer',
    ];

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Boot — auto-generate order_number & snapshot product data
    |─────────────────────────────────────────────────────────────────────────
    */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Order $order): void {
            if (empty($order->order_number)) {
                $next = (static::withTrashed()->max('id') ?? 0) + 1;
                $order->order_number = 'LK-' . date('Y') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }

            if ($order->product_id && empty($order->product_name)) {
                $product = Product::find($order->product_id);
                if ($product) {
                    $order->product_name  = $product->name;
                    $order->product_price = $product->price;
                    $order->total_price   = $product->price * ($order->quantity ?? 1);
                }
            }
        });
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Relationships
    |─────────────────────────────────────────────────────────────────────────
    */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Query Scopes
    |─────────────────────────────────────────────────────────────────────────
    */
    public function scopePending($q)    { return $q->where('status', 'pending'); }
    public function scopeConfirmed($q)  { return $q->where('status', 'confirmed'); }
    public function scopePreparing($q)  { return $q->where('status', 'preparing'); }
    public function scopeShipped($q)    { return $q->where('status', 'shipped'); }
    public function scopeCompleted($q)  { return $q->where('status', 'completed'); }
    public function scopeCancelled($q)  { return $q->where('status', 'cancelled'); }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Accessors
    |─────────────────────────────────────────────────────────────────────────
    */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'preparing' => 'قيد التحضير',
            'shipped'   => 'تم الشحن',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default     => $this->status ?? '—',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'   => '#d35400',
            'confirmed' => '#1a8a4a',
            'preparing' => '#9a7d0a',
            'shipped'   => '#1a6b8a',
            'completed' => '#1e4d2b',
            'cancelled' => '#7b1113',
            default     => '#888',
        };
    }

    public function getStatusBgAttribute(): string
    {
        return match ($this->status) {
            'pending'   => '#fef3e8',
            'confirmed' => '#e8faf0',
            'preparing' => '#fdf8e8',
            'shipped'   => '#e8f4fa',
            'completed' => '#edf7ef',
            'cancelled' => '#fdf0f0',
            default     => '#f5f5f5',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'palpay'         => 'بال باي',
            'bank_palestine' => 'بنك فلسطين',
            'jawwalpay'  => 'جوال باي',
            'cash_on_pickup' => 'كاش عند الاستلام',
            default          => $this->payment_method ?? 'غير محدد',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid'  => 'لم يُدفع',
            'pending' => 'بانتظار التأكيد',
            'paid'    => 'تم الدفع',
            default   => $this->payment_status ?? '—',
        };
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'website'  => '🌐 الموقع',
            'whatsapp' => '💬 واتساب',
            default    => '🌐 الموقع',
        };
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Static helpers for select options
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function statusOptions(): array
    {
        return [
            'pending'   => 'قيد الانتظار',
            'confirmed' => 'مؤكد',
            'preparing' => 'قيد التحضير',
            'shipped'   => 'تم الشحن',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ];
    }

    public static function paymentMethods(): array
    {
        return [
            'palpay'         => 'بال باي',
            'bank_palestine' => 'بنك فلسطين',
            'jawwalpay'  => 'جوال باي',
            'cash_on_pickup' => 'كاش عند الاستلام',
        ];
    }

    public static function statusDotColors(): array
    {
        return [
            'pending'   => '#d35400',
            'confirmed' => '#1a8a4a',
            'preparing' => '#9a7d0a',
            'shipped'   => '#1a6b8a',
            'completed' => '#1e4d2b',
            'cancelled' => '#7b1113',
        ];
    }
}
