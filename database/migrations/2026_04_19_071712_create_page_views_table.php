<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();

            // الصفحة المزارة
            $table->string('url', 500);
            $table->string('page', 100)->nullable();   // home | products | product | contact

            // بيانات الزائر (لا نحفظ معلومات شخصية)
            $table->string('session_id', 64);          // معرف الجلسة (مشفر)
            $table->string('ip_hash', 64)->nullable(); // hash(ip) لحماية الخصوصية
            $table->string('device', 20)->default('desktop'); // desktop | mobile | tablet
            $table->string('country', 80)->nullable();

            // المصدر
            $table->string('referrer', 500)->nullable();
            $table->string('utm_source', 100)->nullable();

            // التوقيت
            $table->timestamp('visited_at');

            // فهارس للأداء
            $table->index('visited_at');
            $table->index('session_id');
            $table->index('page');
            $table->index(['visited_at', 'page']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
