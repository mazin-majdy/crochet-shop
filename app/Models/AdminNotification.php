<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdminNotification extends Model
{
    protected $table = 'admin_notifications';

    protected $fillable = [
        'type', 'title', 'body', 'action_url', 'data', 'is_read', 'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Factory Methods — إنشاء إشعارات من أي مكان في التطبيق
    |─────────────────────────────────────────────────────────────────────────
    */

    /**
     * إشعار عند وصول تقييم جديد
     * يُستدعى من ReviewController بعد الحفظ
     */
    public static function fromReview(Review $review): self
    {
        $stars  = str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating);
        $type   = match (true) {
            $review->rating >= 4  => 'review_approved',
            $review->rating === 3 => 'review_pending',
            default               => 'review_negative',
        };

        $titles = [
            'review_approved'  => "تقييم إيجابي جديد {$stars}",
            'review_pending'   => "تقييم بانتظار مراجعتك {$stars}",
            'review_negative'  => "تنبيه: تقييم سلبي {$stars}",
        ];

        $bodies = [
            'review_approved'  => "{$review->reviewer_name}: \"" . Str::limit($review->body, 60) . "\"",
            'review_pending'   => "{$review->reviewer_name} أضاف رأيًا — بانتظار موافقتك",
            'review_negative'  => "{$review->reviewer_name}: \"" . Str::limit($review->body, 60) . "\"",
        ];

        return static::create([
            'type'       => $type,
            'title'      => $titles[$type],
            'body'       => $bodies[$type],
            'action_url' => route('admin.reviews.index',
                $type === 'review_pending' ? ['status' => 'pending'] : []
            ),
            'data'       => [
                'review_id'     => $review->id,
                'reviewer_name' => $review->reviewer_name,
                'rating'        => $review->rating,
                'product_id'    => $review->product_id,
            ],
            'is_read'    => false,
        ]);
    }

    /**
     * إشعار رسالة جديدة
     */
    // public static function fromMessage(ContactMessage $msg): self
    // {
    //     return static::create([
    //         'type'       => 'new_message',
    //         'title'      => "رسالة جديدة من {$msg->name}",
    //         'body'       => Str::limit($msg->message, 70),
    //         'action_url' => route('admin.messages.show', $msg->id),
    //         'data'       => ['message_id' => $msg->id, 'phone' => $msg->phone],
    //         'is_read'    => false,
    //     ]);
    // }

    /**
     * إشعار طلب جديد
     */
    // public static function fromOrder(Order $order): self
    // {
    //     return static::create([
    //         'type'       => 'new_order',
    //         'title'      => "طلب جديد #{$order->order_number}",
    //         'body'       => "{$order->customer_name} — {$order->product_name}",
    //         'action_url' => route('admin.orders.show', $order->id),
    //         'data'       => ['order_id' => $order->id, 'total' => $order->total_price],
    //         'is_read'    => false,
    //     ]);
    // }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Scopes
    |─────────────────────────────────────────────────────────────────────────
    */
    public function scopeUnread($q)  { return $q->where('is_read', false); }
    public function scopeRecent($q)  { return $q->latest()->take(15); }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Actions
    |─────────────────────────────────────────────────────────────────────────
    */
    public function markRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    public static function markAllRead(): void
    {
        static::unread()->update(['is_read' => true, 'read_at' => now()]);
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Accessors — للعرض في الواجهة
    |─────────────────────────────────────────────────────────────────────────
    */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'review_approved'  => 'bi-star-fill',
            'review_pending'   => 'bi-hourglass-split',
            'review_negative'  => 'bi-exclamation-triangle-fill',
            'new_message'      => 'bi-chat-dots-fill',
            'new_order'        => 'bi-bag-plus-fill',
            default            => 'bi-bell-fill',
        };
    }

    public function getIconBgAttribute(): string
    {
        return match ($this->type) {
            'review_approved'  => 'rgba(26,138,74,0.12)',
            'review_pending'   => 'rgba(212,175,55,0.15)',
            'review_negative'  => 'rgba(211,84,0,0.12)',
            'new_message'      => 'rgba(26,107,138,0.12)',
            'new_order'        => 'rgba(123,17,19,0.10)',
            default            => 'rgba(100,100,100,0.10)',
        };
    }

    public function getIconColorAttribute(): string
    {
        return match ($this->type) {
            'review_approved'  => '#1a8a4a',
            'review_pending'   => '#9a7d0a',
            'review_negative'  => '#d35400',
            'new_message'      => '#1a6b8a',
            'new_order'        => '#7b1113',
            default            => '#666',
        };
    }
}
