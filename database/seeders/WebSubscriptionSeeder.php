<?php

namespace Database\Seeders;

use App\Models\SubscriptionSetting;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionCategory;
use App\Models\SubscriptionFile;
use App\Models\UserSubscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class WebSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        SubscriptionSetting::set('base_slug', 'web-subscription');
        SubscriptionSetting::set('allow_guest_browse', 'true');
        SubscriptionSetting::set('grace_period_days', '0');

        // 2. Subscription Plans matching Approved Mockup
        $plansData = [
            [
                'name' => 'Monthly (30 Days)',
                'name_bn' => 'Monthly (৩০ দিন)',
                'description' => 'Unlimited downloads from all categories for 30 days.',
                'description_bn' => '৩০ দিনের জন্য সব ক্যাটাগরির সকল ফাইলে আনলিমিটেড অ্যাকসেস।',
                'duration_type' => 'days',
                'duration_days' => 30,
                'price' => 299.00,
                'sale_price' => null,
                'download_limit_daily' => 20,
                'download_limit_total' => 0,
                'device_limit' => 2,
                'is_featured' => false,
                'is_active' => true,
                'all_categories_access' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Quarterly (90 Days)',
                'name_bn' => 'Quarterly (৯০ দিন)',
                'description' => 'Popular quarter pass. Full access to library, updates, and priority downloads for 90 days.',
                'description_bn' => '৯০ দিনের সম্পূর্ণ লাইব্রেরি অ্যাকসেস, নতুন আপডেট ও প্রায়োরিটি ডাউনলোড।',
                'duration_type' => 'days',
                'duration_days' => 90,
                'price' => 699.00,
                'sale_price' => null,
                'download_limit_daily' => 50,
                'download_limit_total' => 0,
                'device_limit' => 3,
                'is_featured' => true,
                'is_active' => true,
                'all_categories_access' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Yearly (365 Days)',
                'name_bn' => 'Yearly (৩৬৫ দিন)',
                'description' => 'Complete yearly VIP access with maximum download capacity and new daily additions.',
                'description_bn' => '৩৬৫ দিনের ভিআইপি সদস্যপদ। প্রতিদিনের নতুন ফাইল ও আনলিমিটেড অ্যাকসেস।',
                'duration_type' => 'days',
                'duration_days' => 365,
                'price' => 1999.00,
                'sale_price' => null,
                'download_limit_daily' => 100,
                'download_limit_total' => 0,
                'device_limit' => 5,
                'is_featured' => false,
                'is_active' => true,
                'all_categories_access' => true,
                'sort_order' => 3,
            ],
        ];

        $plans = [];
        foreach ($plansData as $pData) {
            $plans[$pData['duration_days']] = SubscriptionPlan::updateOrCreate(
                ['duration_days' => $pData['duration_days']],
                $pData
            );
        }

        // 3. Subscription Categories (Separate from Store)
        $subCategories = [
            [
                'name' => 'Premium WordPress Plugins Library',
                'name_bn' => 'প্রিমিয়াম ওয়ার্ডপ্রেস প্লাগইন ও থিম লাইব্রেরি',
                'slug' => 'wordpress-plugins-themes',
                'description' => 'GPL licensed developer tools, security plugins, caching suites, and builders.',
                'description_bn' => 'ডেভেলপারদের জন্য জনপ্রিয় সব ওয়ার্ডপ্রেস প্লাগইন ও থিম কালেকশন।',
                'icon' => 'plug',
                'sort_order' => 1,
            ],
            [
                'name' => 'Raw Graphics Assets & Vector Kits',
                'name_bn' => 'গ্রাফিক্স রিসোর্স, পিএসডি ও ভেক্টর কিট',
                'slug' => 'graphics-assets-psd',
                'description' => 'High-resolution editable banners, mockup scenes, print designs, and 3D icons.',
                'description_bn' => 'সোশ্যাল মিডিয়া টেমপ্লেট, প্রোডাক্ট মকআপ ও থ্রি-ডি আইকন ফাইল।',
                'icon' => 'image',
                'sort_order' => 2,
            ],
            [
                'name' => 'Complete Web Source Codes & Scripts',
                'name_bn' => 'ওয়েব সোর্স কোড ও পিএইচপি স্ক্রিপ্ট',
                'slug' => 'web-source-codes',
                'description' => 'Ready backend architectures, admin panels, API starters, and utilities.',
                'description_bn' => 'রেডি অ্যাডমিন প্যানেল, এপিআই ব্যাকএন্ড ও ইউটিলিটি সোর্স কোড।',
                'icon' => 'terminal',
                'sort_order' => 3,
            ],
            [
                'name' => 'AI Automation Prompts & Workflows',
                'name_bn' => 'এআই অটোমেশন প্রম্পট ও মেক/জ্যাপিয়ার ব্লুপ্রিন্ট',
                'slug' => 'ai-automation-prompts',
                'description' => 'High conversion marketing sequences, automated bot prompts, and API scripts.',
                'description_bn' => 'মার্কেটিং ফানেল, বট অটোমেশন ও এআই ইন্টিগ্রেশন ফাইল।',
                'icon' => 'sparkles',
                'sort_order' => 4,
            ],
        ];

        $createdCats = [];
        foreach ($subCategories as $c) {
            $createdCats[$c['slug']] = SubscriptionCategory::updateOrCreate(['slug' => $c['slug']], $c);
        }

        // 4. Subscription Files (Protected Digital Library)
        $subFiles = [
            [
                'category_slug' => 'wordpress-plugins-themes',
                'title' => 'Yoast SEO Premium + All Addons Pack',
                'title_bn' => 'Yoast SEO প্রিমিয়াম + সকল অ্যাড-অন প্যাক',
                'slug' => 'yoast-seo-premium-addon-pack',
                'description' => 'Latest clean version of Yoast SEO Premium with News, Video, and WooCommerce SEO.',
                'description_bn' => 'সবগুলো অফিশিয়াল অ্যাড-অন সহ ইয়োস্ট এসইও প্রিমিয়াম প্লাগইন।',
                'file_name' => 'yoast-seo-premium-v22.zip',
                'file_size' => 12500000,
                'file_type' => 'zip',
                'version' => '22.4.0',
            ],
            [
                'category_slug' => 'wordpress-plugins-themes',
                'title' => 'WP Rocket Speed Optimization Engine',
                'title_bn' => 'WP রকেট স্পিড অপটিমাইজেশন প্লাগইন',
                'slug' => 'wp-rocket-cache-optimizer',
                'description' => 'Top caching and speed performance booster for WordPress.',
                'description_bn' => 'ওয়ার্ডপ্রেস ওয়েবসাইটের স্পিড ও কোর ওয়েব ভাইটাল বাড়ানোর প্লাগইন।',
                'file_name' => 'wp-rocket-v3.16.zip',
                'file_size' => 8400000,
                'file_type' => 'zip',
                'version' => '3.16.1',
            ],
            [
                'category_slug' => 'graphics-assets-psd',
                'title' => 'Bangla Typography & Poster Design Assets (500+ Elements)',
                'title_bn' => 'বাংলা টাইপোগ্রাফি ও পোস্টার ডিজাইন এসেটস',
                'slug' => 'bangla-typography-poster-assets',
                'description' => 'High-resolution PNG cutouts, calligraphy words, and festive graphics.',
                'description_bn' => 'ডিজাইনারদের জন্য ৫০০+ রেডি বাংলা ক্যালিগ্রাফি ও ব্যাকগ্রাউন্ড উপাদান।',
                'file_name' => 'bangla-typography-pack.zip',
                'file_size' => 180000000,
                'file_type' => 'zip',
                'version' => '2.0.0',
            ],
            [
                'category_slug' => 'web-source-codes',
                'title' => 'Vue 3 + Tailwind CSS Admin Dashboard Boilerplate',
                'title_bn' => 'Vue ৩ + Tailwind CSS অ্যাডমিন ড্যাশবোর্ড বয়লারপ্লেট',
                'slug' => 'vue3-tailwind-admin-boilerplate',
                'description' => 'Fully responsive dark/light mode admin template with Chart.js and auth.',
                'description_bn' => 'ডার্ক/লাইট মোড সহ পূর্ণাঙ্গ রিঅ্যাক্টিভ অ্যাডমিন ড্যাশবোর্ড সোর্স কোড।',
                'file_name' => 'vue3-tailwind-dashboard.zip',
                'file_size' => 24000000,
                'file_type' => 'zip',
                'version' => '1.5.0',
            ],
            [
                'category_slug' => 'ai-automation-prompts',
                'title' => 'E-Commerce Marketing Automation Workflows',
                'title_bn' => 'ই-কমার্স মার্কেটিং অটোমেশন ওয়ার্কফ্লো গাইড',
                'slug' => 'ecommerce-marketing-automation-workflows',
                'description' => 'Make.com & Zapier blueprints for abandoned cart recovery, SMS notifications, and review generation.',
                'description_bn' => 'স্বয়ংক্রিয় এসএমএস, কার্ট রিকভারি ও কাস্টমার ফলো-আপ অটোমেশন ব্লুপ্রিন্ট।',
                'file_name' => 'ecommerce-automation-blueprints.pdf',
                'file_size' => 9500000,
                'file_type' => 'pdf',
                'version' => '1.0.0',
            ],
        ];

        foreach ($subFiles as $f) {
            $catSlug = $f['category_slug'];
            unset($f['category_slug']);
            $fileName = $f['file_name'];
            unset($f['file_name']);

            $cat = $createdCats[$catSlug] ?? null;
            if ($cat) {
                $f['category_id'] = $cat->id;
                $f['file_path'] = 'subscriptions/' . $fileName;
                $f['storage_bucket'] = 'storpify-subscription-library';
                $f['is_active'] = true;
                $f['download_count'] = rand(45, 230);
                SubscriptionFile::updateOrCreate(['slug' => $f['slug']], $f);
            }
        }

        // 5. Active sample membership for test customer
        $customer = User::where('email', 'customer@storpify.com')->first();
        if ($customer && isset($plans[30])) {
            UserSubscription::updateOrCreate(
                ['user_id' => $customer->id],
                [
                    'plan_id' => $plans[30]->id,
                    'starts_at' => now()->subDays(5),
                    'ends_at' => now()->addDays(25),
                    'status' => 'active',
                    'total_downloads' => 3,
                    'is_auto_renew' => false,
                ]
            );
        }
    }
}
