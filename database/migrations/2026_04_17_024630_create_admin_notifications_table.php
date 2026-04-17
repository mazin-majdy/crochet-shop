<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();

            // النوع يحدد الأيقونة واللون في الواجهة
            $table->enum('type', [
                'review_pending',    // تقييم بانتظار المراجعة (3 نجوم)
                'review_approved',   // تقييم نُشر تلقائياً (4-5 نجوم)
                'review_negative',   // تقييم سلبي تلقائياً (1-2 نجوم)
                // 'new_message',       // رسالة تواصل جديدة
                // 'new_order',         // طلب جديد
            ]);

            $table->string('title', 120);          // عنوان الإشعار
            $table->string('body', 255)->nullable(); // نص تفصيلي
            $table->string('action_url')->nullable(); // رابط "اذهب إلى"

            // بيانات إضافية (JSON مرن)
            $table->json('data')->nullable();

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['is_read', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
