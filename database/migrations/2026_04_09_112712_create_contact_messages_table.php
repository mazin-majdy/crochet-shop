<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('phone', 25)->nullable();
            $table->string('email', 150)->nullable();

            $table->enum('subject', ['order', 'custom', 'inquiry', 'other'])
                  ->default('inquiry');

            $table->text('message');

            // ربط اختياري بمنتج محدد
            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->nullOnDelete();

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index('is_read');
            $table->index('subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
