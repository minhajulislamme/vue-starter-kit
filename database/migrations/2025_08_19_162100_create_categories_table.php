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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Category banner/cover image
            $table->string('icon')->nullable(); // Category icon image
            $table->string('icon_class')->nullable(); // CSS icon class (like FontAwesome)
            $table->string('color_code')->nullable(); // Category theme color
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('level')->default(0); // Category depth level
            $table->string('path')->nullable(); // Category path for breadcrumbs
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('show_in_menu')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('product_count')->default(0); // Cache product count
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->index(['is_active', 'sort_order']);
            $table->index(['parent_id', 'level']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['show_in_menu', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
