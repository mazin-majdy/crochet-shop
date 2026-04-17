<?php
// ═══════════════════════════════════════════════════
// app/Models/Product.php
// ═══════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price',
        'category', 'target', 'tags',
        'image', 'images',
        'is_active', 'is_featured',
    ];

    protected $casts = [
        'price'       => 'float',
        'images'      => 'array',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    // ─── Boot ─────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name . '-' . Str::random(4));
            }
        });
    }

    // ─── Scopes ───────────────────────────────────
    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }
    public function scopeCategory($q, $cat) { return $q->where('category', $cat); }

    // ─── Accessors ────────────────────────────────
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'embroidery' => 'التطريز',
            'handicraft' => 'أشغال يدوية',
            'wool'       => 'أعمال الصوف',
            default      => $this->category,
        };
    }

    public function getCategoryEmojiAttribute(): string
    {
        return match($this->category) {
            'embroidery' => '🪡',
            'handicraft' => '✂️',
            'wool'       => '🧶',
            default      => '🎁',
        };
    }

    public function getTargetLabelAttribute(): string
    {
        return match($this->target) {
            'kids'    => 'أطفال',
            'girls'   => 'بنات',
            'women'   => 'نساء',
            'men'     => 'رجال',
            'general' => 'عام',
            default   => 'عام',
        };
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
