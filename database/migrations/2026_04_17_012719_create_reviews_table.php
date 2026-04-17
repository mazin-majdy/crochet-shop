<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // ── من كتب التقييم ──────────────────────────────────────
            $table->string('reviewer_name', 80);
            $table->string('reviewer_phone', 25)->nullable();  // للتحقق الداخلي فقط (لا يُعرض)
            $table->string('reviewer_city', 60)->nullable();

            // ── التقييم ──────────────────────────────────────────────
            $table->unsignedTinyInteger('rating');              // 1-5
            $table->string('title', 120)->nullable();           // عنوان اختياري
            $table->text('body');                               // نص التقييم

            // ── ربط بمنتج (اختياري) ──────────────────────────────────
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            // ── نظام الموافقة الذكي ──────────────────────────────────
            /*
             * pending   → بانتظار مراجعة المدير (رأي 3 نجوم)
             * approved  → منشور على الموقع     (رأي 4-5 نجوم — auto)
             * rejected  → مرفوض               (رأي 1-2 نجوم — auto)
             */
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // هل تمت الموافقة تلقائياً؟
            $table->boolean('auto_approved')->default(false);

            // ملاحظة المدير عند الرفض أو الموافقة اليدوية
            $table->string('admin_note', 255)->nullable();

            // حماية من التكرار — IP + fingerprint
            $table->string('ip_address', 45)->nullable();
            $table->string('fingerprint', 64)->nullable();  // hash(name+phone+product)

            $table->timestamps();

            // ── فهارس ────────────────────────────────────────────────
            $table->index('status');
            $table->index('rating');
            $table->index(['product_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
