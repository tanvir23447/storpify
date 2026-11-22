<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Product;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class WebsiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. General Settings
        WebsiteSetting::set('site_name', 'STORPIFY', 'general', true);
        WebsiteSetting::set('site_tagline', 'Premium Digital Products Marketplace', 'general', true);
        WebsiteSetting::set('site_tagline_bn', 'প্রিমিয়াম ডিজিটাল প্রোডাক্টস মার্কেটপ্লেস', 'general', true);
        WebsiteSetting::set('site_email', 'support@storpify.com', 'contact', true);
        WebsiteSetting::set('site_phone', '+880 1700-000000', 'contact', true);
        WebsiteSetting::set('currency_code', 'BDT', 'general', true);
        WebsiteSetting::set('currency_symbol', '৳', 'general', true);
        WebsiteSetting::set('social_links', [
            'facebook' => 'https://facebook.com/storpify',
            'youtube' => 'https://youtube.com/@storpify',
            'telegram' => 'https://t.me/storpify',
            'whatsapp' => 'https://wa.me/8801700000000',
        ], 'social', true);

        // 2. Seed Realistic Orders matching Mockup Recent Orders Table
        $customer = User::where('role', 'customer')->first();
        $bkash = PaymentMethod::where('code', 'bkash')->first();
        $nagad = PaymentMethod::where('code', 'nagad')->first();
        $rocket = PaymentMethod::where('code', 'rocket')->first();

        $sampleOrders = [
            [
                'order_number' => 'SP10245',
                'customer_name' => 'রোকনুজ্জামান সায়াদ',
                'customer_email' => 'sayad@example.com',
                'customer_phone' => '01711223344',
                'subtotal' => 799.00,
                'total_amount' => 799.00,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'payment_method' => $bkash,
                'product_slug' => 'premium-elementor-template-kit-50-designs',
                'trx_id' => 'BK9X24519A',
                'sender' => '01711223344',
            ],
            [
                'order_number' => 'SP10244',
                'customer_name' => 'তানভীর আহমেদ',
                'customer_email' => 'tanvir@example.com',
                'customer_phone' => '01811223344',
                'subtotal' => 2499.00,
                'total_amount' => 2499.00,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'payment_method' => $nagad,
                'product_slug' => 'sis-school-management-system-complete-script',
                'trx_id' => 'NG882103B',
                'sender' => '01811223344',
            ],
            [
                'order_number' => 'SP10243',
                'customer_name' => 'আরিফুল ইসলাম',
                'customer_email' => 'ariful@example.com',
                'customer_phone' => '01911223344',
                'subtotal' => 499.00,
                'total_amount' => 499.00,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'payment_method' => $rocket,
                'product_slug' => 'bangla-graphics-design-resource-bundle-10gb',
                'trx_id' => 'RC773129C',
                'sender' => '019112233441',
            ],
            [
                'order_number' => 'SP10242',
                'customer_name' => 'ফাহিম মোর্শেদ',
                'customer_email' => 'fahim@example.com',
                'customer_phone' => '01611223344',
                'subtotal' => 399.00,
                'total_amount' => 399.00,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'payment_method' => $bkash,
                'product_slug' => 'ai-prompt-mega-bundle-chatgpt-gemini-midjourney',
                'trx_id' => 'BK9X11082K',
                'sender' => '01611223344',
            ],
        ];

        foreach ($sampleOrders as $so) {
            $product = Product::where('slug', $so['product_slug'])->first();
            $method = $so['payment_method'];

            $order = Order::updateOrCreate(['order_number' => $so['order_number']], [
                'user_id' => $customer ? $customer->id : null,
                'customer_name' => $so['customer_name'],
                'customer_email' => $so['customer_email'],
                'customer_phone' => $so['customer_phone'],
                'subtotal' => $so['subtotal'],
                'discount_amount' => 0.00,
                'tax_amount' => 0.00,
                'total_amount' => $so['total_amount'],
                'currency' => 'BDT',
                'payment_status' => $so['payment_status'],
                'order_status' => $so['order_status'],
            ]);

            if ($product) {
                OrderItem::firstOrCreate(['order_id' => $order->id, 'product_id' => $product->id], [
                    'product_name' => $product->name,
                    'price' => $so['subtotal'],
                    'quantity' => 1,
                    'subtotal' => $so['subtotal'],
                ]);
            }

            Payment::firstOrCreate(['order_id' => $order->id], [
                'user_id' => $customer ? $customer->id : null,
                'payment_method_id' => $method ? $method->id : null,
                'method_code' => $method ? $method->code : 'manual',
                'transaction_id' => $so['trx_id'],
                'sender_number' => $so['sender'],
                'receiver_number' => $method ? $method->primary_number : '01819001122',
                'amount' => $so['total_amount'],
                'currency' => 'BDT',
                'status' => $so['payment_status'] === 'paid' ? 'verified' : 'pending',
                'verified_at' => $so['payment_status'] === 'paid' ? now() : null,
            ]);

            Invoice::firstOrCreate(['order_id' => $order->id], [
                'invoice_number' => 'INV-' . $order->order_number,
                'user_id' => $customer ? $customer->id : null,
                'total_amount' => $order->total_amount,
                'paid_amount' => $so['payment_status'] === 'paid' ? $order->total_amount : 0.00,
                'status' => $so['payment_status'] === 'paid' ? 'paid' : 'unpaid',
                'invoice_date' => today(),
            ]);
        }
    }
}
