<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Subscription Settings (dynamic base slug, defaults, etc.)
        Schema::create('subscription_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 2. Subscription Plans
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('duration_type')->default('days'); // days, months, years
            $table->integer('duration_days')->default(30);
            $table->decimal('price', 12, 2)->default(0.00);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('download_limit_daily')->default(0); // 0 = unlimited
            $table->integer('download_limit_total')->default(0); // 0 = unlimited
            $table->integer('device_limit')->default(1);
            $table->string('display_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('all_categories_access')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Subscription Categories (Separate from Main Store categories)
        Schema::create('subscription_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Subscription Files (Protected Digital Library)
        Schema::create('subscription_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('subscription_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('file_path');
            $table->string('storage_bucket')->default('storpify-subscription-library');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_type', 50)->nullable();
            $table->string('version')->default('1.0.0');
            $table->unsignedBigInteger('download_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Plan Category Access Pivot
        Schema::create('subscription_plan_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('subscription_categories')->cascadeOnDelete();
            $table->unique(['plan_id', 'category_id']);
        });

        // 6. Plan Specific File Access Pivot
        Schema::create('subscription_plan_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->foreignId('file_id')->constrained('subscription_files')->cascadeOnDelete();
            $table->unique(['plan_id', 'file_id']);
        });

        // 7. User Subscriptions (Membership Lifecycle)
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('status')->default('active'); // pending, active, expired, suspended, cancelled, renewed
            $table->integer('total_downloads')->default(0);
            $table->boolean('is_auto_renew')->default(false);
            $table->timestamps();
        });

        // 8. Subscription Orders
        Schema::create('subscription_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('BDT');
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('method_code')->default('bkash');
            $table->string('transaction_id')->nullable()->index();
            $table->string('sender_number')->nullable();
            $table->string('proof_file')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 9. Subscription Download Logs
        Schema::create('subscription_download_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained('user_subscriptions')->cascadeOnDelete();
            $table->foreignId('file_id')->constrained('subscription_files')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('downloaded_at')->useCurrent();
        });

        // 10. Subscription Renewals
        Schema::create('subscription_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_subscription_id')->constrained('user_subscriptions')->cascadeOnDelete();
            $table->foreignId('previous_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->foreignId('new_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->integer('extended_days');
            $table->decimal('amount_paid', 12, 2);
            $table->timestamp('renewed_at')->useCurrent();
        });

        // 11. Subscription Access Logs
        Schema::create('subscription_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('access_type'); // login, view_library, download_attempt, expired_attempt
            $table->string('resource_path')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_access_logs');
        Schema::dropIfExists('subscription_renewals');
        Schema::dropIfExists('subscription_download_logs');
        Schema::dropIfExists('subscription_orders');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_plan_files');
        Schema::dropIfExists('subscription_plan_categories');
        Schema::dropIfExists('subscription_files');
        Schema::dropIfExists('subscription_categories');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('subscription_settings');
    }
};
