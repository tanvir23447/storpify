@extends('layouts.app')

@section('content')
<div class="py-12 bg-[#F8F9FD]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden text-center p-8 sm:p-12 space-y-6">
            
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center shadow-inner">
                <i data-lucide="check-circle" class="w-10 h-10"></i>
            </div>

            <div>
                <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-mono font-bold rounded-full">
                    Order #{{ $order->order_number }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">
                    অর্ডার সফলভাবে জমা হয়েছে!
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    ধন্যবাদ, <span class="font-bold text-gray-800">{{ $order->customer_name }}</span>! আপনার অর্ডারের তথ্য সিস্টেমে সংরক্ষিত হয়েছে।
                </p>
            </div>

            <!-- Status Card -->
            <div class="p-6 rounded-2xl bg-gradient-to-r from-purple-50 via-indigo-50 to-purple-50 border border-purple-100 text-left space-y-3">
                <div class="flex items-center justify-between border-b border-purple-200/60 pb-3">
                    <span class="text-xs font-semibold text-gray-600">পেমেন্ট স্ট্যাটাস:</span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $order->payment_status === 'paid' ? 'পেমেন্ট অনুমোদিত (Paid)' : 'ভেরিফিকেশন অপেক্ষমান (Pending Verification)' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs text-gray-700 pt-1">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">পেমেন্ট মাধ্যম</span>
                        <span class="font-bold">{{ strtoupper($order->payments->first() ? $order->payments->first()->method_code : 'Manual') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">ট্রানজেকশন আইডি</span>
                        <span class="font-mono font-bold">{{ $order->payments->first() ? $order->payments->first()->transaction_id : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">প্রেরক নম্বর</span>
                        <span class="font-mono font-bold">{{ $order->payments->first() ? $order->payments->first()->sender_number : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">মোট পরিশোধিত টাকা</span>
                        <span class="font-extrabold text-purple-700 text-sm">{{ format_price($order->total_amount) }}</span>
                    </div>
                </div>
            </div>

            <!-- Download Notification Notice -->
            <div class="text-xs text-gray-600 bg-gray-50 p-4 rounded-xl text-left flex items-start gap-2.5">
                <i data-lucide="info" class="w-4 h-4 text-purple-600 shrink-0 mt-0.5"></i>
                <div class="space-y-1">
                    <p class="font-semibold text-gray-800">পরবর্তী ধাপসমূহ:</p>
                    <p>১. আমাদের টিম আপনার ট্রানজেকশন আইডিটি ম্যানুয়ালি বিকাশ/নগদ স্টেটমেন্টের সাথে যাচাই করবে (সাধারণত ৫-১৫ মিনিট)।</p>
                    <p>২. ভেরিফিকেশন সম্পন্ন হলে আপনার <span class="font-bold text-purple-700">কাস্টমার ড্যাশবোর্ড -> My Downloads</span> সেকশনে সরাসরি ফাইল ডাউনলোড লিংক আনলক হয়ে যাবে।</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ route('customer.downloads') }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-transform hover:scale-105 flex items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>মাই ডাউনলোডস ড্যাশবোর্ড</span>
                </a>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs rounded-xl transition-colors">
                    হোমপেজে ফিরে যান
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
