<?php

return [
    'brand' => [
        'name' => 'STORPIFY',
        'tagline' => 'Premium Digital Products Marketplace',
        'tagline_bn' => 'প্রিমিয়াম ডিজিটাল প্রোডাক্টস মার্কেটপ্লেস',
        'colors' => [
            'navy' => '#17152B',
            'royal_purple' => '#7651E8',
            'electric_purple' => '#8B5CF6',
            'lavender' => '#A78BFA',
            'soft_bg' => '#F5F3FF',
            'white' => '#FFFFFF',
        ],
        'logo_url' => '/assets/images/storpify-logo.png',
        'logo_dark' => '/assets/images/storpify-logo.png',
        'logo_light' => '/assets/images/storpify-logo.png',
        'favicon' => '/favicon.ico',
    ],

    'currency' => [
        'default' => 'BDT',
        'symbol' => '৳',
        'secondary' => 'USD',
        'secondary_symbol' => '$',
        'usd_to_bdt_rate' => 120.00,
    ],

    'subscription' => [
        'base_slug' => env('SUBSCRIPTION_BASE_SLUG', 'web-subscription'),
        'route_prefix' => env('SUBSCRIPTION_BASE_SLUG', 'web-subscription'),
    ],

    'supabase' => [
        'url' => env('SUPABASE_URL'),
        'anon_key' => env('SUPABASE_ANON_KEY'),
        'publishable_key' => env('SUPABASE_PUBLISHABLE_KEY'),
        'secret_key' => env('SUPABASE_SECRET_KEY'),
        'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY'),
        'buckets' => [
            'public' => env('SUPABASE_STORAGE_BUCKET_PUBLIC', 'storpify-public-assets'),
            'products' => env('SUPABASE_STORAGE_BUCKET_PRODUCTS', 'storpify-product-downloads'),
            'subscriptions' => env('SUPABASE_STORAGE_BUCKET_SUBSCRIPTIONS', 'storpify-subscription-library'),
            'payment_proofs' => env('SUPABASE_STORAGE_BUCKET_PAYMENT_PROOFS', 'storpify-payment-proofs'),
        ],
        'signed_url_ttl_seconds' => env('SUPABASE_SIGNED_URL_TTL', 3600),
    ],

    'roles' => [
        'super_admin' => 'super_admin',
        'general_admin' => 'general_admin',
        'moderator' => 'moderator',
        'customer' => 'customer',
    ],

    'permissions' => [
        'product.view' => 'View Products',
        'product.create' => 'Create Products',
        'product.update' => 'Update Products',
        'product.price.update' => 'Update Product Prices',
        'product.variation.manage' => 'Manage Variations',
        'product.image.upload' => 'Upload Images',
        'product.file.upload' => 'Upload Files',
        'product.publish' => 'Publish Products',
        'product.delete' => 'Delete Products',

        'category.view' => 'View Categories',
        'category.create' => 'Create Categories',
        'category.update' => 'Update Categories',
        'category.delete' => 'Delete Categories',

        'order.view' => 'View Orders',
        'order.update' => 'Update Orders',
        'order.refund' => 'Process Refunds',
        'payment.view' => 'View Payments',
        'payment.verify' => 'Verify Payments',

        'customer.view' => 'View Customers',
        'customer.update' => 'Update Customers',
        'customer.suspend' => 'Suspend Customers',

        'subscription.view' => 'View Web Subscription',
        'subscription.plan.manage' => 'Manage Subscription Plans',
        'subscription.category.manage' => 'Manage Subscription Categories',
        'subscription.file.manage' => 'Manage Subscription Files',
        'subscription.member.manage' => 'Manage Subscription Members',

        'homepage.view' => 'View Homepage CMS',
        'homepage.edit' => 'Edit Homepage Sections',
        'homepage.publish' => 'Publish Homepage',

        'hero.view' => 'View Hero Slider',
        'hero.manage' => 'Manage Hero Slides',

        'popup.view' => 'View Offer Popups',
        'popup.manage' => 'Manage Offer Popups',

        'staff.view' => 'View Staff Accounts',
        'staff.create' => 'Create Staff Accounts',
        'staff.update' => 'Update Staff Accounts & Permissions',

        'payment.settings.manage' => 'Manage Payment Methods & Numbers',
        'website.settings.manage' => 'Manage Website Settings',
        'system.settings.manage' => 'Manage System Settings',
    ],
];
