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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Subcategory banner/cover image
            $table->string('icon')->nullable(); // Subcategory icon image
            $table->string('icon_class')->nullable(); // CSS icon class
            $table->string('color_code')->nullable(); // Subcategory theme color
            $table->integer('level')->default(1); // Subcategory level (1 for direct children, 2 for sub-subcategory, etc.)
            $table->string('path')->nullable(); // Full path from root category
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_in_menu')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('product_count')->default(0); // Cache product count
            $table->timestamps();

            $table->unique(['category_id', 'slug']);
            $table->index(['category_id', 'is_active', 'sort_order']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['show_in_menu', 'is_active']);
            $table->index(['level', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
