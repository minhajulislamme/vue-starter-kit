<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku')->unique();
            $table->enum('type', ['simple', 'variable'])->default('simple');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->decimal('price', 10, 2)->nullable(); // For simple products
            $table->decimal('compare_price', 10, 2)->nullable(); // Regular price for sale items
            $table->decimal('cost_price', 10, 2)->nullable(); // Cost for profit calculation
            $table->integer('stock_quantity')->default(0);
            $table->boolean('manage_stock')->default(true);
            $table->integer('low_stock_threshold')->default(5);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'backorder'])->default('in_stock');
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('dimensions')->nullable(); // JSON string for length, width, height
            $table->boolean('is_virtual')->default(false);
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');
            $table->json('gallery_images')->nullable(); // Array of image URLs
            $table->string('featured_image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'is_featured']);
            $table->index(['type', 'status']);
            $table->index(['stock_status', 'manage_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
