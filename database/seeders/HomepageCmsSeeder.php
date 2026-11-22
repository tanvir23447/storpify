<?php

namespace Database\Seeders;

use App\Models\HomepageLayout;
use App\Models\HomepageSection;
use App\Models\HeroSlide;
use App\Models\PromotionalBanner;
use App\Models\TrustFeature;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\PopupCampaign;
use Illuminate\Database\Seeder;

class HomepageCmsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Primary Layout
        $layout = HomepageLayout::firstOrCreate(['name' => 'Default Production Layout'], [
            'is_active' => true,
        ]);

        // 2. Hero Slides matching Mockup
        HeroSlide::updateOrCreate(['title' => 'আপনার প্রয়োজনীয় সব ডিজিটাল প্রোডাক্ট, এক জায়গায়!'], [
            'title_bn' => 'আপনার প্রয়োজনীয় সব ডিজিটাল প্রোডাক্ট, এক জায়গায়!',
            'subtitle' => 'Premium Digital Assets Marketplace',
            'subtitle_bn' => 'ওয়েবসাইট, থিম, প্লাগইন, ডিজাইন রিসোর্স, AI টুলস, কোর্স এবং আরও অনেক কিছু কিনুন সহজে ও নিরাপদে।',
            'description' => 'Browse over 2,500+ verified scripts, themes, and resources.',
            'description_bn' => 'হাজারো প্রিমিয়াম ফাইল ডাউনলোড করুন ইনস্ট্যান্ট অ্যাকসেসে।',
            'bg_color' => '#17152B',
            'overlay_opacity' => 0.00,
            'text_color' => '#FFFFFF',
            'primary_button_text' => 'সব প্রোডাক্ট দেখুন',
            'primary_button_url' => '/shop',
            'secondary_button_text' => 'আজকের অফার',
            'secondary_button_url' => '/offers',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 3. Trust Features matching Mockup
        $trustFeatures = [
            [
                'icon' => 'zap',
                'title' => 'Instant Delivery',
                'title_bn' => 'ইনস্ট্যান্ট ডেলিভারি',
                'subtitle' => 'Instant file access upon payment confirmation',
                'subtitle_bn' => 'পেমেন্ট নিশ্চিত হলে সাথে সাথে',
                'sort_order' => 1,
            ],
            [
                'icon' => 'shield-check',
                'title' => 'Secure Payment',
                'title_bn' => 'নিরাপদ লেনদেন',
                'subtitle' => '100% verified Bangladeshi mobile banking & cards',
                'subtitle_bn' => '১০০% সিকিউর পেমেন্ট',
                'sort_order' => 2,
            ],
            [
                'icon' => 'users',
                'title' => 'Trusted & Reliable',
                'title_bn' => 'বিশ্বস্ত ও নির্ভরযোগ্য',
                'subtitle' => '10,000+ happy clients & businesses',
                'subtitle_bn' => 'হাজারো সন্তুষ্ট কাস্টমার',
                'sort_order' => 3,
            ],
            [
                'icon' => 'headphones',
                'title' => 'Bangla Support',
                'title_bn' => 'বাংলাদেশী সাপোর্ট',
                'subtitle' => 'Fast assistance in native Bengali',
                'subtitle_bn' => 'বাংলায় দ্রুত সহায়তা',
                'sort_order' => 4,
            ],
        ];

        foreach ($trustFeatures as $tf) {
            TrustFeature::updateOrCreate(['title_bn' => $tf['title_bn']], $tf);
        }

        // 4. Promotional Banners matching Mockup
        PromotionalBanner::updateOrCreate(['title_bn' => 'Web Subscription'], [
            'title' => 'Web Subscription',
            'title_bn' => 'Web Subscription',
            'subtitle' => 'Get membership and access thousands of premium files in one place!',
            'subtitle_bn' => 'সদস্যপদ নিন এবং হাজারো প্রিমিয়াম ফাইল একসাথেই অ্যাকসেস করুন!',
            'link_url' => '/web-subscription',
            'banner_type' => 'promo_left',
            'badge_text' => '👑 Web Subscription',
            'discount_text' => 'Unlimited Downloads',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        PromotionalBanner::updateOrCreate(['title_bn' => 'বড় অফার'], [
            'title' => 'Mega Offer',
            'title_bn' => 'বড় অফার',
            'subtitle' => 'Up to 70% discount on selected digital products!',
            'subtitle_bn' => 'সর্বোচ্চ ৭০% ছাড়! নির্বাচিত প্রোডাক্ট',
            'link_url' => '/offers',
            'banner_type' => 'promo_right',
            'badge_text' => '🔥 বিশেষ ছাড়',
            'discount_text' => '৭০% ছাড়!',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 5. Testimonials
        Testimonial::updateOrCreate(['customer_name' => 'রোকনুজ্জামান সায়াদ'], [
            'customer_role' => 'Educator & Entrepreneur',
            'customer_role_bn' => 'শিক্ষক ও উদ্যোক্তা',
            'rating' => 5,
            'review_text' => 'Bought the school management system. Instant delivery and stellar support. 100% recommended!',
            'review_text_bn' => 'STORPIFY থেকে স্কুল ম্যানেজমেন্ট সিস্টেম নিয়েছি, ইনস্টলেশন এবং সাপোর্ট অসাধারণ। ১০০% রিকমেন্ডেড!',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Testimonial::updateOrCreate(['customer_name' => 'তানভীর আহমেদ'], [
            'customer_role' => 'Freelance Web Designer',
            'customer_role_bn' => 'ফ্রিল্যান্স ওয়েব ডিজাইনার',
            'rating' => 5,
            'review_text' => 'The Bangla Graphics Bundle saved me tons of time during Eid campaigns. Top-notch files.',
            'review_text_bn' => 'বাংলা গ্রাফিক্স বান্ডল আমার ক্লায়েন্টের ঈদের বিজ্ঞাপনের কাজের সময় বাঁচিয়ে দিয়েছে। কোয়ালিটি দুর্দান্ত।',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 6. FAQs
        $faqs = [
            [
                'question' => 'How do I receive digital products after purchasing?',
                'question_bn' => 'কেনার পর ডিজিটাল প্রোডাক্ট কীভাবে ডাউনলোড করব?',
                'answer' => 'As soon as your payment is confirmed, your download links become instantly available in My Downloads dashboard. You will also receive an email notification.',
                'answer_bn' => 'পেমেন্ট সম্পন্ন হওয়ার সাথে সাথেই আপনার কাস্টমার ড্যাশবোর্ডের "My Downloads" অপশনে ডাউনলোড লিঙ্ক অ্যাক্টিভ হয়ে যাবে।',
                'category' => 'download',
                'sort_order' => 1,
            ],
            [
                'question' => 'Which payment methods are supported in Bangladesh?',
                'question_bn' => 'বাংলাদেশ থেকে কোন কোন মাধ্যমে পেমেন্ট করা যাবে?',
                'answer' => 'We accept bKash, Nagad, Rocket, as well as Visa, MasterCard, and Internet Banking.',
                'answer_bn' => 'বিকাশ, নগদ, রকেটের পাশাপাশি যেকোনো ভিসা, মাস্টারকার্ড ও অনলাইন ব্যাংকিংয়ের মাধ্যমে পেমেন্ট করা যাবে।',
                'category' => 'payment',
                'sort_order' => 2,
            ],
            [
                'question' => 'What is the Web Subscription module?',
                'question_bn' => 'ওয়েব সাবস্ক্রিপশন কি?',
                'answer' => 'Web Subscription is a dedicated membership service granting access to our entire curated library of WordPress plugins, graphic packs, and source code with a single affordable plan.',
                'answer_bn' => 'ওয়েব সাবস্ক্রিপশন হলো একটি অল-ইন-ওয়ান মেম্বারশিপ প্ল্যান, যার মাধ্যমে আপনি আলাদা আলাদা না কিনে একটি প্ল্যানেই আমাদের হাজারো প্রিমিয়াম ফাইল সরাসরি ডাউনলোড করতে পারবেন।',
                'category' => 'subscription',
                'sort_order' => 3,
            ],
        ];

        foreach ($faqs as $f) {
            Faq::updateOrCreate(['question_bn' => $f['question_bn']], $f);
        }

        // 7. Offer Popup Campaign
        PopupCampaign::updateOrCreate(['title' => 'Welcome Offer 10% Off'], [
            'title_bn' => 'প্রথম অর্ডারে বিশেষ ১০% ছাড়!',
            'description' => 'Use coupon code STORPIFY10 at checkout to get 10% instant discount.',
            'description_bn' => 'চেকআউটে কুপন কোড STORPIFY10 ব্যবহার করুন এবং পান তাৎক্ষণিক ১০% ছাড়!',
            'discount_text' => '১০% ছাড়',
            'coupon_code' => 'STORPIFY10',
            'button_text' => 'অফারটি ব্যবহার করুন',
            'button_url' => '/shop',
            'bg_color' => '#17152B',
            'text_color' => '#FFFFFF',
            'display_delay_sec' => 3,
            'show_on_homepage' => true,
            'show_on_products' => true,
            'show_on_all' => false,
            'frequency' => 'once_per_session',
            'priority' => 1,
            'is_active' => true,
        ]);

        // 8. Homepage Dynamic CMS Sections Registry
        $sections = [
            [
                'section_type' => 'announcement_bar',
                'title' => 'Top Announcement Bar',
                'title_bn' => 'শীর্ষ ঘোষণা বার',
                'content' => [
                    'text' => '🔥 বড় অফার চলছে! নির্বাচিত ডিজিটাল প্রোডাক্টে সর্বোচ্চ ৭০% ছাড়! এখনই দেখুন',
                    'link_text' => 'এখনই দেখুন',
                    'link_url' => '/offers',
                ],
                'settings' => ['bg_color' => '#7651E8', 'text_color' => '#FFFFFF'],
                'sort_order' => 1,
            ],
            [
                'section_type' => 'hero_slider',
                'title' => 'Hero Slider',
                'title_bn' => 'হিরো স্লাইডার',
                'content' => [],
                'settings' => ['autoplay' => true, 'interval' => 5000],
                'sort_order' => 2,
            ],
            [
                'section_type' => 'trust_bar',
                'title' => 'Trust Indicators',
                'title_bn' => 'বিশ্বাসযোগ্যতা ও ফিচারসমূহ',
                'content' => [],
                'settings' => ['columns' => 4],
                'sort_order' => 3,
            ],
            [
                'section_type' => 'featured_categories',
                'title' => 'Popular Categories',
                'title_bn' => 'জনপ্রিয় ক্যাটাগরি',
                'subtitle_bn' => 'আপনার প্রয়োজন অনুযায়ী সেরা ডিজিটাল রিসোর্স বেছে নিন',
                'content' => [],
                'settings' => ['limit' => 7],
                'sort_order' => 4,
            ],
            [
                'section_type' => 'promotional_banners',
                'title' => 'Dual Promotional Cards',
                'title_bn' => 'প্রমোশনাল ব্যানার কার্ডস',
                'content' => [],
                'settings' => ['layout' => 'two_columns'],
                'sort_order' => 5,
            ],
            [
                'section_type' => 'featured_products',
                'title' => 'Featured Products',
                'title_bn' => 'ফিচার্ড প্রোডাক্ট',
                'subtitle_bn' => 'জনপ্রিয় এবং বাছাইকৃত প্রিমিয়াম প্রোডাক্টসমূহ',
                'content' => [],
                'settings' => ['limit' => 8],
                'sort_order' => 6,
            ],
            [
                'section_type' => 'why_choose_us',
                'title' => 'Why Choose STORPIFY',
                'title_bn' => 'কেন STORPIFY বেছে নেবেন?',
                'content' => [
                    ['title_bn' => 'প্রিমিয়াম কোয়ালিটি', 'desc_bn' => 'বাছাইকৃত ও মানসম্মত প্রোডাক্ট', 'icon' => 'award'],
                    ['title_bn' => 'ইনস্ট্যান্ট ডেলিভারি', 'desc_bn' => 'দ্রুত ও সহজ ডাউনলোড', 'icon' => 'zap'],
                    ['title_bn' => 'নিরাপদ পেমেন্ট', 'desc_bn' => 'bKash, Nagad, Rocket', 'icon' => 'shield'],
                    ['title_bn' => 'বিশেষজ্ঞ সাপোর্ট', 'desc_bn' => 'বাংলায় দ্রুত সহায়তা', 'icon' => 'headphones'],
                ],
                'settings' => ['bg_color' => '#17152B'],
                'sort_order' => 7,
            ],
            [
                'section_type' => 'reviews',
                'title' => 'Customer Reviews',
                'title_bn' => 'আমাদের গ্রাহকদের মতামত',
                'content' => [],
                'settings' => ['limit' => 6],
                'sort_order' => 8,
            ],
            [
                'section_type' => 'faq',
                'title' => 'Frequently Asked Questions',
                'title_bn' => 'সাধারণ প্রশ্নাবলী',
                'content' => [],
                'settings' => ['limit' => 6],
                'sort_order' => 9,
            ],
            [
                'section_type' => 'newsletter',
                'title' => 'Newsletter',
                'title_bn' => 'নতুন প্রোডাক্ট ও অফারের আপডেট',
                'subtitle_bn' => 'নিয়মিত সেরা অফার ও নতুন রিলিজের খবর পেতে সাবস্ক্রাইব করুন।',
                'content' => [],
                'settings' => ['bg_color' => '#7651E8'],
                'sort_order' => 10,
            ],
        ];

        foreach ($sections as $sec) {
            HomepageSection::updateOrCreate(
                ['layout_id' => $layout->id, 'section_type' => $sec['section_type']],
                array_merge($sec, [
                    'is_active' => true,
                    'show_on_desktop' => true,
                    'show_on_tablet' => true,
                    'show_on_mobile' => true,
                ])
            );
        }
    }
}
