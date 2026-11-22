<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductFile;
use App\Models\ProductTag;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class MainStoreSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        // 1. Create Main Categories matching Mockup
        $categoriesData = [
            [
                'name' => 'WordPress',
                'name_bn' => 'WordPress (থিম ও প্লাগইন)',
                'slug' => 'wordpress',
                'description' => 'Premium WordPress Themes, Plugins, and Elementor Template Kits.',
                'description_bn' => 'প্রিমিয়াম ওয়ার্ডপ্রেস থিম, প্লাগইন এবং এলিমেন্টর টেমপ্লেট কিট।',
                'icon' => 'globe',
                'sort_order' => 1,
            ],
            [
                'name' => 'Graphics',
                'name_bn' => 'Graphics (ডিজাইন রিসোর্স)',
                'slug' => 'graphics',
                'description' => 'Vector graphics, PSD templates, social media kits, and fonts.',
                'description_bn' => 'ভেক্টর গ্রাফিক্স, পিএসডি টেমপ্লেট, সোশ্যাল মিডিয়া কিট ও ফন্ট।',
                'icon' => 'palette',
                'sort_order' => 2,
            ],
            [
                'name' => 'Website',
                'name_bn' => 'Website (টেমপ্লেট ও সোর্স কোড)',
                'slug' => 'website',
                'description' => 'Ready PHP, Laravel, HTML5, React, and Vue scripts.',
                'description_bn' => 'রেডি পিএইচপি, লারাভেল, এইচটিএমএল৫ ও রিঅ্যাক্ট সোর্স কোড।',
                'icon' => 'code',
                'sort_order' => 3,
            ],
            [
                'name' => 'AI Tools',
                'name_bn' => 'AI Tools (প্রম্পট ও টুলস)',
                'slug' => 'ai-tools',
                'description' => 'Engineered AI Prompts, GPT bots, and automation templates.',
                'description_bn' => 'প্রফেশনাল এআই প্রম্পট বান্ডল, জিপিটি বট ও অটোমেশন গাইড।',
                'icon' => 'cpu',
                'sort_order' => 4,
            ],
            [
                'name' => 'Courses',
                'name_bn' => 'Courses (ডিজিটাল কোর্স)',
                'slug' => 'courses',
                'description' => 'High quality digital skill training video courses.',
                'description_bn' => 'দক্ষতা অর্জনের প্রফেশনাল ডিজিটাল ভিডিও কোর্স।',
                'icon' => 'graduation-cap',
                'sort_order' => 5,
            ],
            [
                'name' => 'E-books',
                'name_bn' => 'E-books (ই-বুক ও গাইড)',
                'slug' => 'ebooks',
                'description' => 'Business, coding, marketing, and self-help guides.',
                'description_bn' => 'বিজনেস, প্রোগ্রামিং, ফ্রিল্যান্সিং ও ক্যারিয়ার ই-বুক।',
                'icon' => 'book-open',
                'sort_order' => 6,
            ],
            [
                'name' => 'Business',
                'name_bn' => 'Business (এক্সেল ও ডকুমেন্ট)',
                'slug' => 'business',
                'description' => 'Automated Excel sheets, business proposals, and legal contracts.',
                'description_bn' => 'অটোমেটেড এক্সেল শিট, ইনভয়েস ও বিজনেস ডকুমেন্টস।',
                'icon' => 'file-spreadsheet',
                'sort_order' => 7,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = ProductCategory::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // 2. Tags
        $tagNames = ['WordPress', 'Elementor', 'PHP Script', 'Graphic Design', 'Bangla Fonts', 'AI Prompts', 'ChatGPT', 'Midjourney', 'Laravel', 'Business'];
        $tags = [];
        foreach ($tagNames as $name) {
            $slug = \Illuminate\Support\Str::slug($name);
            $tags[$slug] = ProductTag::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // 3. Products directly matching the Approved Mockup!
        $productsData = [
            [
                'name' => 'SIS School Management System – Complete Script',
                'name_bn' => 'SIS স্কুল ম্যানেজমেন্ট সিস্টেম – কমপ্লিট স্ক্রিপ্ট',
                'slug' => 'sis-school-management-system-complete-script',
                'sku' => 'SIS-SCH-001',
                'product_type' => 'website_template',
                'short_description' => 'Full-featured school and college ERP management web application with student portal, attendance, fee collection and exams.',
                'short_description_bn' => 'স্কুল, কলেজ ও মাদ্রাসার জন্য পূর্ণাঙ্গ ম্যানেজমেন্ট সফটওয়্যার। স্টুডেন্ট পোর্টাল, হাজিরা ও ফি কালেকশন সহ।',
                'full_description' => 'The complete SIS School Management script is built on modern PHP & Laravel. Features include: Student Admission, Fee Invoice & bKash Collection, Live Class, Gradebook, Result SMS, and Role Management for Super Admin, Teachers, Parents, and Students.',
                'full_description_bn' => 'এই কমপ্লিট স্কুল ম্যানেজমেন্ট সফটওয়্যারের মাধ্যমে যেকোনো শিক্ষা প্রতিষ্ঠানের দৈনন্দিন সব কার্যক্রম এক ক্লিকেই পরিচালনা করুন। বিকাশ পেমেন্ট ইন্টিগ্রেশন ও অভিভাবক এসএমএস এলার্ট যুক্ত।',
                'regular_price' => 4999.00,
                'sale_price' => 2499.00,
                'is_featured' => true,
                'status' => 'published',
                'version' => '3.4.0',
                'category_slug' => 'website',
                'avg_rating' => 4.90,
                'review_count' => 84,
                'sales_count' => 192,
                'requires_license' => true,
                'variants' => [
                    ['name' => 'Single Domain License', 'name_bn' => 'এক ডোমেইন লাইসেন্স', 'regular_price' => 4999.00, 'sale_price' => 2499.00],
                    ['name' => 'Unlimited Agency License', 'name_bn' => 'আনলিমিটেড এজেন্সি লাইসেন্স', 'regular_price' => 9999.00, 'sale_price' => 4999.00],
                ],
                'files' => [
                    ['file_name' => 'sis-school-management-v3.4.0.zip', 'file_size' => 48234500, 'file_type' => 'zip'],
                ],
            ],
            [
                'name' => 'Premium Elementor Template Kit (50+ Designs)',
                'name_bn' => 'প্রিমিয়াম এলিমেন্টর টেমপ্লেট কিট (৫০+ ডিজাইন)',
                'slug' => 'premium-elementor-template-kit-50-designs',
                'sku' => 'ELM-KIT-050',
                'product_type' => 'wordpress_theme',
                'short_description' => '50+ Ready-to-import responsive WordPress Elementor Pro landing pages and business kits.',
                'short_description_bn' => '৫০টিরও বেশি রেডি ওয়ার্ডপ্রেস এলিমেন্টর ল্যান্ডিং পেজ ও বিজনেস ওয়েবসাইট টেমপ্লেট কিট।',
                'full_description' => 'Instantly import 50+ hand-crafted Elementor templates into your WordPress site. Fully optimized for speed, mobile viewports, and high conversion.',
                'full_description_bn' => 'যেকোনো ওয়ার্ডপ্রেস সাইটে এক ক্লিকে ৫০+ প্রিমিয়াম এলিমেন্টর কিট ইমপোর্ট করুন। ১০০% মোবাইল রেস্পন্সিভ ও ফাস্ট লোডিং।',
                'regular_price' => 1599.00,
                'sale_price' => 799.00,
                'is_featured' => true,
                'status' => 'published',
                'version' => '2.1.0',
                'category_slug' => 'wordpress',
                'avg_rating' => 4.90,
                'review_count' => 310,
                'sales_count' => 485,
                'requires_license' => false,
                'variants' => [
                    ['name' => 'Standard Kit', 'name_bn' => 'স্ট্যান্ডার্ড কিট', 'regular_price' => 1599.00, 'sale_price' => 799.00],
                    ['name' => 'Developer Bundle (+ Source PSDs)', 'name_bn' => 'ডেভেলপার বান্ডল (সোর্স ফাইল সহ)', 'regular_price' => 2499.00, 'sale_price' => 1299.00],
                ],
                'files' => [
                    ['file_name' => 'elementor-templates-pack-50.zip', 'file_size' => 125829120, 'file_type' => 'zip'],
                ],
            ],
            [
                'name' => 'বাংলা গ্রাফিক্স ডিজাইন রিসোর্স বান্ডল (10GB+)',
                'name_bn' => 'বাংলা গ্রাফিক্স ডিজাইন রিসোর্স বান্ডল (10GB+)',
                'slug' => 'bangla-graphics-design-resource-bundle-10gb',
                'sku' => 'BGR-10GB-001',
                'product_type' => 'graphics',
                'short_description' => 'Mega Bangla graphic assets: 500+ festive banners, social media posts, vector arts, logos, and premium Unicode fonts.',
                'short_description_bn' => '৫০০+ সোশ্যাল ব্যানার, উৎসবের পোস্টার, লোগো, ইসলামিক ভেক্টর ও ৫০+ প্রিমিয়াম বাংলা ইউনিকোড ফন্ট।',
                'full_description' => 'Save hundreds of hours with Bangladesh-themed graphics. Includes Photoshop PSD, Illustrator AI, PNG elements, and festive creative templates.',
                'full_description_bn' => 'বাংলাদেশের ডিজাইনারদের জন্য সেরা কালেকশন। পিএসডি, এআই, ভেক্টর ও হাই-রেজুলেশন ব্যাকগ্রাউন্ড ফাইল অন্তর্ভুক্ত।',
                'regular_price' => 999.00,
                'sale_price' => 499.00,
                'is_featured' => true,
                'status' => 'published',
                'version' => '5.0.0',
                'category_slug' => 'graphics',
                'avg_rating' => 4.90,
                'review_count' => 420,
                'sales_count' => 890,
                'requires_license' => false,
                'variants' => [],
                'files' => [
                    ['file_name' => 'bangla-graphics-mega-bundle.zip', 'file_size' => 350000000, 'file_type' => 'zip'],
                ],
            ],
            [
                'name' => 'AI Prompt Mega Bundle (ChatGPT, Gemini, Midjourney)',
                'name_bn' => 'AI প্রম্পট মেগা বান্ডল (ChatGPT, Gemini, Midjourney)',
                'slug' => 'ai-prompt-mega-bundle-chatgpt-gemini-midjourney',
                'sku' => 'AIP-MGA-001',
                'product_type' => 'ai_tools',
                'short_description' => '10,000+ curated AI prompts for marketing, copywriting, coding, SEO, and realistic photorealistic Midjourney art.',
                'short_description_bn' => '১০,০০০+ রেডি এআই প্রম্পট: ডিজিটাল মার্কেটিং, কন্টেন্ট রাইটিং, কোডিং এবং মিডজার্নি ফটোরিয়্যালিস্টিক আর্ট।',
                'full_description' => 'Supercharge your daily productivity with master prompts designed for ChatGPT-4, Google Gemini, and Midjourney v6.',
                'full_description_bn' => 'মার্কেটিং, ব্যবসা ও ক্রিয়েটিভ কাজের জন্য সাজানো মাস্টার এআই প্রম্পট লাইব্রেরি। এক ক্লিকে কপি করুন ও রেজাল্ট পান।',
                'regular_price' => 799.00,
                'sale_price' => 399.00,
                'is_featured' => true,
                'status' => 'published',
                'version' => '2026.1',
                'category_slug' => 'ai-tools',
                'avg_rating' => 4.80,
                'review_count' => 382,
                'sales_count' => 640,
                'requires_license' => false,
                'variants' => [],
                'files' => [
                    ['file_name' => 'ai-prompts-mega-bundle-2026.pdf', 'file_size' => 15728640, 'file_type' => 'pdf'],
                ],
            ],
            [
                'name' => 'Complete Laravel E-Commerce Script with bKash & Nagad',
                'name_bn' => 'কমপ্লিট লারাভেল ই-কমার্স স্ক্রিপ্ট (বিকাশ ও নগদ পেমেন্ট সহ)',
                'slug' => 'complete-laravel-ecommerce-script-bkash-nagad',
                'sku' => 'LAR-ECO-009',
                'product_type' => 'website_template',
                'short_description' => 'Production-ready Bangladeshi multi-currency online store with bKash, Nagad, Rocket, Invoice generation and SMS alerts.',
                'short_description_bn' => 'সম্পূর্ণ বাংলাদেশি ফিচারযুক্ত আধুনিক লারাভেল ই-কমার্স স্ক্রিপ্ট। বিকাশ, নগদ ও ইনভয়েস সিস্টেম সহ।',
                'full_description' => 'Everything you need to launch a modern digital or physical products store in Bangladesh. Built with Laravel 11, Tailwind CSS, Alpine.js, and automated manual verification workflows.',
                'full_description_bn' => 'ডিজিটাল ও ফিজিক্যাল প্রোডাক্ট বিক্রির জন্য কমপ্লিট ই-কমার্স প্ল্যাটফর্ম। সুপার অ্যাডমিন প্যানেল ও লাইভওয়্যার ফ্রন্টএন্ড।',
                'regular_price' => 3999.00,
                'sale_price' => 1999.00,
                'is_featured' => true,
                'status' => 'published',
                'version' => '2.5.0',
                'category_slug' => 'website',
                'avg_rating' => 4.95,
                'review_count' => 140,
                'sales_count' => 260,
                'requires_license' => true,
                'variants' => [
                    ['name' => 'Regular License', 'name_bn' => 'রেগুলার লাইসেন্স', 'regular_price' => 3999.00, 'sale_price' => 1999.00],
                    ['name' => 'Extended Developer License', 'name_bn' => 'এক্সটেন্ডেড ডেভেলপার লাইসেন্স', 'regular_price' => 7999.00, 'sale_price' => 3999.00],
                ],
                'files' => [
                    ['file_name' => 'laravel-ecommerce-source.zip', 'file_size' => 64000000, 'file_type' => 'zip'],
                ],
            ],
            [
                'name' => 'Automated Business Accounts & Invoice Excel Sheet',
                'name_bn' => 'অটোমেটেড বিজনেস হিসাব ও ইনভয়েস এক্সেল শিট',
                'slug' => 'automated-business-accounts-invoice-excel-sheet',
                'sku' => 'XLS-ACC-002',
                'product_type' => 'business',
                'short_description' => 'Fully automated daily cashbook, expense tracker, stock inventory and automated PDF invoice generator in Excel.',
                'short_description_bn' => 'দৈনিক ক্যাশ হিসাব, খরচ ট্র্যাকার, স্টক ইনভেন্টরি ও স্বয়ংক্রিয় পিডিএফ ইনভয়েস জেনারেটর।',
                'full_description' => 'No accounting software required. Open in Microsoft Excel or Google Sheets, record daily transactions, and generate one-click balance sheets.',
                'full_description_bn' => 'ছোট-বড় সব ব্যবসার জন্য অত্যন্ত সহজ ও কার্যকরী হিসাবের এক্সেল শিট। বাংলায় সম্পূর্ণ গাইড অন্তর্ভুক্ত।',
                'regular_price' => 599.00,
                'sale_price' => 299.00,
                'is_featured' => true,
                'status' => 'published',
                'version' => '4.0.0',
                'category_slug' => 'business',
                'avg_rating' => 4.85,
                'review_count' => 96,
                'sales_count' => 320,
                'requires_license' => false,
                'variants' => [],
                'files' => [
                    ['file_name' => 'business-accounts-master-template.xlsx', 'file_size' => 4194304, 'file_type' => 'xlsx'],
                ],
            ],
        ];

        foreach ($productsData as $item) {
            $catSlug = $item['category_slug'];
            unset($item['category_slug']);
            $variants = $item['variants'] ?? [];
            unset($item['variants']);
            $files = $item['files'] ?? [];
            unset($item['files']);

            $item['created_by'] = $admin ? $admin->id : null;
            $product = Product::updateOrCreate(['slug' => $item['slug']], $item);

            if (isset($categories[$catSlug])) {
                $product->categories()->sync([$categories[$catSlug]->id]);
            }

            // Variants
            foreach ($variants as $vIndex => $variantData) {
                $variantData['product_id'] = $product->id;
                $variantData['sort_order'] = $vIndex;
                $variantData['sku'] = $product->sku . '-V' . ($vIndex + 1);
                ProductVariant::updateOrCreate(
                    ['product_id' => $product->id, 'name' => $variantData['name']],
                    $variantData
                );
            }

            // Files
            foreach ($files as $fileData) {
                ProductFile::updateOrCreate(
                    ['product_id' => $product->id, 'file_name' => $fileData['file_name']],
                    [
                        'file_path' => 'products/' . $fileData['file_name'],
                        'storage_bucket' => 'storpify-product-downloads',
                        'file_size' => $fileData['file_size'],
                        'file_type' => $fileData['file_type'],
                        'version' => $product->version,
                        'is_active' => true,
                    ]
                );
            }

            // Add sample review
            ProductReview::firstOrCreate(
                ['product_id' => $product->id, 'user_id' => $admin ? $admin->id : 1],
                [
                    'rating' => 5,
                    'review_text' => 'অসাধারণ কোয়ালিটি! পেমেন্ট করার সাথে সাথেই ইনস্ট্যান্ট ডাউনলোড পেয়েছি। সাপোর্টও দারুণ।',
                    'status' => 'approved',
                ]
            );
        }
    }
}
