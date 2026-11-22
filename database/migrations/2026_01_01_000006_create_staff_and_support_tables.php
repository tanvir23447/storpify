<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Staff Profiles
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->json('permissions_summary')->nullable();
            $table->timestamps();
        });

        // 2. Staff Assignments
        Schema::create('staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
            $table->string('module'); // main_store, web_subscription, customer_support, finance
            $table->unsignedBigInteger('entity_id')->nullable(); // category_id or ticket_id
            $table->string('role_type')->default('moderator');
            $table->timestamps();
        });

        // 3. Approval Workflow (Moderator changes require Admin approval)
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // Product, Category, PriceChange, File
            $table->unsignedBigInteger('model_id')->nullable();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->string('action_type'); // create, update, delete, price_change
            $table->json('original_data')->nullable();
            $table->json('proposed_data')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        // 4. Support Tickets
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->string('category')->default('general'); // order_issue, download_help, subscription, technical, payment
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('open'); // open, awaiting_reply, resolved, closed
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 5. Ticket Messages
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->boolean('is_admin_reply')->default(false);
            $table->timestamps();
        });

        // 6. Website Settings (Dynamic key-value storage)
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general'); // general, payment, contact, seo, branding
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        // 7. Notification Templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('event_key')->unique(); // order_created, payment_verified, download_ready, subscription_activated, etc.
            $table->string('name');
            $table->string('subject');
            $table->string('subject_bn')->nullable();
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Notification Logs
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('event_key')->index();
            $table->string('recipient');
            $table->string('channel')->default('email'); // email, sms, in_app
            $table->string('status')->default('sent'); // sent, failed, queued
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });

        // 9. System Logs
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level')->default('info'); // info, warning, error, critical
            $table->string('channel')->default('app');
            $table->text('message');
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('website_settings');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('staff_assignments');
        Schema::dropIfExists('staff_profiles');
    }
};
