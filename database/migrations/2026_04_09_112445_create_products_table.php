<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);

            // التصنيف: embroidery | handicraft | wool
            $table->enum('category', ['embroidery', 'handicraft', 'wool']);

            // الفئة المستهدفة: kids | girls | women | men | general
            $table->enum('target', ['kids', 'girls', 'women', 'men', 'general'])
                ->default('general');

            $table->string('tags')->nullable();          // فصل بفاصلة
            $table->string('image')->nullable();         // الصورة الرئيسية
            $table->json('images')->nullable();          // صور إضافية

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            // Indexes
            $table->index('category');
            $table->index('target');
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
