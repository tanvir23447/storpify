<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Homepage Layouts
        Schema::create('homepage_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Default Layout');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Homepage Sections
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_id')->constrained('homepage_layouts')->cascadeOnDelete();
            $table->string('section_type'); // announcement_bar, header, hero_slider, trust_bar, featured_categories, flash_sale, best_sellers, promotional_banners, subscription_banner, why_choose_us, reviews, faq, newsletter, footer
            $table->string('title')->nullable();
            $table->string('title_bn')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('subtitle_bn')->nullable();
            $table->json('content')->nullable(); // structured dynamic content (text, buttons, links, banners)
            $table->json('settings')->nullable(); // styling, layout, bg_color, text_color, items_limit, etc.
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_desktop')->default(true);
            $table->boolean('show_on_tablet')->default(true);
            $table->boolean('show_on_mobile')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Homepage Revisions (Version Control for CMS)
        Schema::create('homepage_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('homepage_sections')->cascadeOnDelete();
            $table->json('content')->nullable();
            $table->json('settings')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        // 4. Hero Slider Management
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('subtitle_bn')->nullable();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('desktop_image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('bg_image')->nullable();
            $table->string('bg_color')->default('#17152B');
            $table->decimal('overlay_opacity', 3, 2)->default(0.00);
            $table->string('text_color')->default('#FFFFFF');
            $table->string('primary_button_text')->nullable();
            $table->string('primary_button_url')->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // 5. Dynamic Offer Popup System
        Schema::create('popup_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->text('description')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('offer_image')->nullable();
            $table->string('discount_text')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('bg_color')->default('#17152B');
            $table->string('text_color')->default('#FFFFFF');
            $table->integer('display_delay_sec')->default(3);
            $table->boolean('show_on_homepage')->default(true);
            $table->boolean('show_on_products')->default(true);
            $table->boolean('show_on_all')->default(false);
            $table->string('frequency')->default('once_per_session'); // once_per_session, once_per_day, always
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('priority')->default(1);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('click_count')->default(0);
            $table->timestamps();
        });

        // 6. Promotional Banners
        Schema::create('promotional_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('subtitle_bn')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('link_url')->nullable();
            $table->string('banner_type')->default('promo_card'); // promo_left, promo_right, full_width
            $table->string('badge_text')->nullable();
            $table->string('discount_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Trust Features (Insta-delivery, secure, support, etc.)
        Schema::create('trust_features', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('zap');
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('subtitle_bn')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Testimonials & Reviews
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_role')->nullable();
            $table->string('customer_role_bn')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('review_text');
            $table->text('review_text_bn')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 9. FAQs
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('question_bn')->nullable();
            $table->text('answer');
            $table->text('answer_bn')->nullable();
            $table->string('category')->default('general');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 10. Newsletter Subscribers
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamps();
        });

        // 11. SEO Metadata
        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();
            $table->string('page_type')->index(); // homepage, shop, category, product, subscription
            $table->string('page_identifier')->index(); // slug or identifier
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->json('structured_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metadata');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('trust_features');
        Schema::dropIfExists('promotional_banners');
        Schema::dropIfExists('popup_campaigns');
        Schema::dropIfExists('hero_slides');
        Schema::dropIfExists('homepage_revisions');
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('homepage_layouts');
    }
};
