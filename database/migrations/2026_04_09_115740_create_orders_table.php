<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            // ── رقم الطلب الفريد ────────────────────────────────────────
            // صيغة:  LK-2025-0001
            $table->string('order_number', 20)->unique()->comment('LK-YYYY-NNNN');

            // ── بيانات العميل ────────────────────────────────────────────
            $table->string('customer_name',    100);
            $table->string('customer_phone',    25);
            $table->string('customer_email',   150)->nullable();
            $table->string('customer_city',     80)->nullable();
            $table->text('customer_address')->nullable();

            // ── ربط المنتج ──────────────────────────────────────────────
            // نستخدم nullOnDelete حتى لا يُحذف الطلب عند حذف المنتج
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            // لقطة من بيانات المنتج وقت الطلب (لحماية السجلات التاريخية)
            $table->string('product_name');
            $table->decimal('product_price', 10, 2);
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('total_price',  10, 2);

            // ── الدفع ───────────────────────────────────────────────────
            $table->enum('payment_method', [
                'palpay',
                'bank_palestine',
                'jawwalpay',
                'cash_on_pickup',
            ])->nullable();

            $table->enum('payment_status', [
                'unpaid',   // لم يُدفع
                'pending',  // الدفع بانتظار تأكيد
                'paid',     // تم الدفع
            ])->default('unpaid');

            // ── حالة الطلب ──────────────────────────────────────────────
            $table->enum('status', [
                'pending',    // قيد الانتظار — الافتراضي
                'confirmed',  // تم التأكيد
                'preparing',  // قيد التحضير / الصنع
                'shipped',    // تم الشحن
                'completed',  // وصل للعميل ومكتمل
                'cancelled',  // ملغي
            ])->default('pending');

            // ── مصدر الطلب ──────────────────────────────────────────────
            $table->enum('source', ['website', 'whatsapp'])->default('website');

            // ── ملاحظات ─────────────────────────────────────────────────
            $table->text('notes')->nullable(); // ملاحظات العميل
            $table->text('admin_notes')->nullable(); // ملاحظات المدير (داخلية)

            $table->softDeletes(); // حذف ناعم — يبقى في قاعدة البيانات
            $table->timestamps();

            // ── فهارس ───────────────────────────────────────────────────
            $table->index('status');
            $table->index('payment_status');
            $table->index('source');
            $table->index('customer_phone');
            $table->index(['status', 'payment_status']); // composite للتقارير
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
