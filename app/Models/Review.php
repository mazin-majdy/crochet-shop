<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_name',
        'reviewer_phone',
        'reviewer_city',
        'rating',
        'title',
        'body',
        'product_id',
        'status',
        'auto_approved',
        'admin_note',
        'ip_address',
        'fingerprint',
    ];

    protected $casts = [
        'rating'        => 'integer',
        'auto_approved' => 'boolean',
    ];

    /*
    |─────────────────────────────────────────────────────────────────────────
    | SMART APPROVAL LOGIC
    | ─────────────────────────────────────────────────────────────────────────
    | 5 ★★★★★  → approved  تلقائياً
    | 4 ★★★★☆  → approved  تلقائياً
    | 3 ★★★☆☆  → pending   ينتظر مراجعة المدير
    | 2 ★★☆☆☆  → rejected  تلقائياً
    | 1 ★☆☆☆☆  → rejected  تلقائياً
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function resolveStatus(int $rating): array
    {
        return match (true) {
            $rating >= 4 => ['status' => 'approved', 'auto_approved' => true],
            $rating === 3 => ['status' => 'pending',  'auto_approved' => false],
            default       => ['status' => 'rejected', 'auto_approved' => true],
        };
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Duplicate guard — منع التكرار من نفس الشخص على نفس المنتج
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function makeFingerprint(string $name, ?string $phone, ?int $productId): string
    {
        return hash('sha256', mb_strtolower(trim($name)) . '|' . ($phone ?? '') . '|' . ($productId ?? '0'));
    }

    public static function alreadySubmitted(string $fingerprint): bool
    {
        return static::where('fingerprint', $fingerprint)
            ->where('created_at', '>=', now()->subDays(30))  // نافذة 30 يوم
            ->exists();
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
    | Scopes
    |─────────────────────────────────────────────────────────────────────────
    */
    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopePending($q)  { return $q->where('status', 'pending'); }
    public function scopeRejected($q) { return $q->where('status', 'rejected'); }

    public function scopeForProduct($q, int $productId)
    {
        return $q->where('product_id', $productId)->approved();
    }

    public function scopeGeneral($q)
    {
        return $q->whereNull('product_id')->approved();
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Accessors
    |─────────────────────────────────────────────────────────────────────────
    */
    public function getStarsHtmlAttribute(): string
    {
        $filled = str_repeat('★', $this->rating);
        $empty  = str_repeat('☆', 5 - $this->rating);
        return "<span style=\"color:#f1c40f\">{$filled}</span><span style=\"color:#e0d0c0\">{$empty}</span>";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'منشور',
            'pending'  => 'بانتظار المراجعة',
            'rejected' => 'مرفوض',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'approved' => '#1a8a4a',
            'pending'  => '#9a7d0a',
            'rejected' => '#7b1113',
            default    => '#888',
        };
    }

    public function getStatusBgAttribute(): string
    {
        return match ($this->status) {
            'approved' => '#e8faf0',
            'pending'  => '#fdf8e8',
            'rejected' => '#fdf0f0',
            default    => '#f5f5f5',
        };
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | Stats helper (for admin dashboard)
    |─────────────────────────────────────────────────────────────────────────
    */
    public static function stats(): array
    {
        return [
            'total'    => static::count(),
            'approved' => static::approved()->count(),
            'pending'  => static::pending()->count(),
            'rejected' => static::rejected()->count(),
            'avg'      => round(static::approved()->avg('rating') ?? 0, 1),
        ];
    }
}
