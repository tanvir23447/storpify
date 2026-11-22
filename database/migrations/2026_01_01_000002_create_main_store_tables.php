<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->string('product_type')->default('digital'); // wordpress_plugin, wordpress_theme, template, graphics, course, ebook, ai_tools, etc.
            $table->text('short_description')->nullable();
            $table->text('short_description_bn')->nullable();
            $table->longText('full_description')->nullable();
            $table->longText('full_description_bn')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('image_gallery')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('video_preview_url')->nullable();
            
            // Financials: numeric/decimal for financial accuracy
            $table->decimal('regular_price', 12, 2)->default(0.00);
            $table->decimal('sale_price', 12, 2)->nullable();
            
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('published'); // draft, pending_approval, published, unpublished, archived
            $table->string('version')->default('1.0.0');
            $table->text('update_info')->nullable();
            $table->text('download_instructions')->nullable();
            $table->integer('download_limit')->default(0); // 0 = unlimited
            $table->boolean('requires_license')->default(false);
            
            // Metrics & SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->unsignedBigInteger('sales_count')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(5.00);
            $table->unsignedInteger('review_count')->default(0);
            
            // Authorship & Workflow
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Product Category Pivot
        Schema::create('product_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->unique(['product_id', 'category_id']);
        });

        // 4. Product Variants
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., Personal License, Agency License, Commercial
            $table->string('name_bn')->nullable();
            $table->text('description')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('regular_price', 12, 2)->default(0.00);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('download_limit')->default(0);
            $table->boolean('requires_license')->default(false);
            $table->string('status')->default('active'); // active, inactive
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 5. Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 6. Product Files (Protected Digital Delivery)
        Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('storage_bucket')->default('storpify-product-downloads');
            $table->unsignedBigInteger('file_size')->default(0); // in bytes
            $table->string('file_type', 50)->nullable(); // zip, pdf, rar, etc.
            $table->string('version')->default('1.0.0');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Product Tags & Relations
        Schema::create('product_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('product_tag_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('product_tags')->cascadeOnDelete();
            $table->unique(['product_id', 'tag_id']);
        });

        // 8. Product Reviews
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('review_text')->nullable();
            $table->string('status')->default('approved'); // pending, approved, rejected
            $table->timestamps();
        });

        // 9. Product Download Logs
        Schema::create('product_download_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->foreignId('file_id')->constrained('product_files')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('downloaded_at')->useCurrent();
        });

        // 10. Product Licenses
        Schema::create('product_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('license_key')->unique();
            $table->integer('max_activations')->default(1);
            $table->integer('current_activations')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->string('status')->default('active'); // active, expired, revoked, suspended
            $table->timestamps();
        });

        // 11. License Activations (Domains/IPs)
        Schema::create('license_activations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained('product_licenses')->cascadeOnDelete();
            $table->string('domain')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('activated_at')->useCurrent();
            $table->timestamp('last_checked_at')->nullable();
        });

        // 12. Wishlists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('license_activations');
        Schema::dropIfExists('product_licenses');
        Schema::dropIfExists('product_download_logs');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_tag_relations');
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('product_files');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
