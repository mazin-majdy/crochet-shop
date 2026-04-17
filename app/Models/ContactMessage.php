<?php
// app/Models/ContactMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'subject',
        'message', 'product_id', 'is_read',
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function getSubjectLabelAttribute(): string
    {
        return match($this->subject) {
            'order'   => 'طلب منتج',
            'custom'  => 'تصميم خاص',
            'inquiry' => 'استفسار',
            default   => 'أخرى',
        };
    }
}
