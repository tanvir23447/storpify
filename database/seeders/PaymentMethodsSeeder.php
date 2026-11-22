<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. bKash
        PaymentMethod::updateOrCreate(['code' => 'bkash'], [
            'name' => 'bKash',
            'name_bn' => 'বিকাশ',
            'account_type' => 'merchant',
            'account_numbers' => [
                ['number' => '01819001122', 'type' => 'Merchant (Make Payment)', 'note' => 'পেমেন্ট অপশন ব্যবহার করুন'],
                ['number' => '01712334455', 'type' => 'Personal (Send Money)', 'note' => 'সেন্ড মানি করুন'],
            ],
            'instructions' => '1. Dial *247# or open bKash App.\n2. Choose Make Payment (or Send Money).\n3. Enter the number shown above.\n4. Enter the exact order total amount.\n5. Enter reference number: Your Order ID.\n6. Enter your PIN to confirm.\n7. Copy Transaction ID (TrxID) and enter below.',
            'instructions_bn' => '১. আপনার বিকাশ অ্যাপ খুলুন অথবা *২৪৭# ডায়াল করুন।\n২. "Make Payment" অথবা "Send Money" নির্বাচন করুন।\n৩. উপরে দেখানো বিকাশ নম্বরটি লিখুন।\n৪. আপনার অর্ডারের মোট টাকার পরিমাণ লিখুন।\n৫. রেফারেন্সে আপনার অর্ডার নম্বর দিন।\n৬. পিন দিয়ে পেমেন্ট নিশ্চিত করুন।\n৭. প্রাপ্ত ট্রানজেকশন আইডি (TrxID) নিচের বক্সে দিন।',
            'qr_code' => null,
            'logo' => '/assets/images/bkash.png',
            'sort_order' => 1,
            'is_active' => true,
            'fee_percentage' => 0.00,
        ]);

        // 2. Nagad
        PaymentMethod::updateOrCreate(['code' => 'nagad'], [
            'name' => 'Nagad',
            'name_bn' => 'নগদ',
            'account_type' => 'merchant',
            'account_numbers' => [
                ['number' => '01619001122', 'type' => 'Merchant (Payment)', 'note' => 'পেমেন্ট করুন'],
                ['number' => '01912334455', 'type' => 'Personal (Send Money)', 'note' => 'সেন্ড মানি করুন'],
            ],
            'instructions' => '1. Open Nagad App or dial *167#.\n2. Select Payment (or Send Money).\n3. Enter the Nagad number shown above.\n4. Enter order total amount.\n5. Put Order ID in Reference.\n6. Enter PIN to complete payment.\n7. Enter Nagad Transaction ID below.',
            'instructions_bn' => '১. নগদ অ্যাপ খুলুন বা *১৬৭# ডায়াল করুন।\n২. "পেমেন্ট" অথবা "সেন্ড মানি" নির্বাচন করুন।\n৩. উপরের নগদ নম্বরটি দিন।\n৪. অর্ডারের মোট টাকা লিখুন।\n৫. রেফারেন্সে অর্ডার আইডি দিন।\n৬. পিন দিয়ে লেনদেন সফল করুন এবং TrxID জমা দিন।',
            'qr_code' => null,
            'logo' => '/assets/images/nagad.png',
            'sort_order' => 2,
            'is_active' => true,
            'fee_percentage' => 0.00,
        ]);

        // 3. Rocket
        PaymentMethod::updateOrCreate(['code' => 'rocket'], [
            'name' => 'Rocket',
            'name_bn' => 'রকেট',
            'account_type' => 'personal',
            'account_numbers' => [
                ['number' => '019123344558', 'type' => 'Personal (Send Money)', 'note' => '১২ ডিজিটের নম্বর'],
            ],
            'instructions' => '1. Open Rocket App or dial *322#.\n2. Select Send Money.\n3. Enter 12-digit Rocket account number.\n4. Enter total amount and PIN.\n5. Enter Rocket Transaction ID below.',
            'instructions_bn' => '১. রকেট অ্যাপ খুলুন অথবা *৩২২# ডায়াল করুন।\n২. সেন্ড মানি সিলেক্ট করে উপরের ১২ ডিজিটের নম্বরটি দিন।\n৩. টাকা সেন্ড করে TrxID নিচে জমা দিন।',
            'qr_code' => null,
            'logo' => '/assets/images/rocket.png',
            'sort_order' => 3,
            'is_active' => true,
            'fee_percentage' => 0.00,
        ]);

        // 4. SSLCOMMERZ / Card Gateway
        PaymentMethod::updateOrCreate(['code' => 'sslcommerz'], [
            'name' => 'Cards / Net Banking (SSLCOMMERZ)',
            'name_bn' => 'কার্ড ও অনলাইন ব্যাংকিং (ভিসা, মাস্টারকার্ড)',
            'account_type' => 'automated',
            'account_numbers' => [
                ['number' => 'STORE_ID', 'type' => 'Automated Gateway', 'note' => 'Secure Checkout'],
            ],
            'instructions' => 'Pay securely via Visa, MasterCard, Amex, Internet Banking or Mobile Wallets.',
            'instructions_bn' => 'ভিসা, মাস্টারকার্ড, ক্রেডিট/ডেবিট কার্ড ও মোবাইল ব্যাংকিং দিয়ে সরাসরি পেমেন্ট করুন।',
            'qr_code' => null,
            'logo' => '/assets/images/sslcommerz.png',
            'sort_order' => 4,
            'is_active' => true,
            'fee_percentage' => 0.00,
        ]);
    }
}
