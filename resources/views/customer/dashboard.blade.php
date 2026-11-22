@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Banner -->
        <div class="rounded-3xl bg-gradient-to-r from-[#17152B] to-purple-950 p-6 sm:p-8 text-white shadow-xl mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs text-purple-300 font-bold uppercase tracking-wider">কাস্টমার পোর্টাল</span>
                <h1 class="text-2xl font-extrabold text-white mt-1">স্বাগতম, {{ $user->name }}!</h1>
                <p class="text-xs text-gray-300 mt-1">আপনার কেনা প্রোডাক্ট, ডাউনলোড ও সাবস্ক্রিপশন স্ট্যাটাস একনজরে দেখুন</p>
            </div>
            @if($subscription && $subscription->isActive())
                <div class="px-4 py-2 bg-yellow-400/20 border border-yellow-400/30 rounded-2xl text-yellow-300 text-xs font-bold flex items-center gap-2 shrink-0">
                    <span>👑 VIP মেম্বার (বাকি: {{ $subscription->remainingDays() }} দিন)</span>
                </div>
            @else
                <a href="{{ route('subscription.index') }}" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shrink-0">
                    👑 সাবস্ক্রিপশন নিন →
                </a>
            @endif
        </div>

        <!-- Dashboard Navigation Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 text-xs font-bold text-gray-600">
            <a href="{{ route('customer.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-purple-600 text-white shadow-xs shrink-0">ওভারভিউ</a>
            <a href="{{ route('customer.orders') }}" class="px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-purple-50 hover:text-purple-700 shrink-0">অর্ডার সমূহ</a>
            <a href="{{ route('customer.downloads') }}" class="px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-purple-50 hover:text-purple-700 shrink-0">মাই ডাউনলোডস</a>
            <a href="{{ route('customer.subscription') }}" class="px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-purple-50 hover:text-purple-700 shrink-0">ওয়েব সাবস্ক্রিপশন</a>
            <a href="{{ route('customer.wishlist') }}" class="px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-purple-50 hover:text-purple-700 shrink-0">উইশলিস্ট</a>
            <a href="{{ route('customer.tickets') }}" class="px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-purple-50 hover:text-purple-700 shrink-0">সাপোর্ট টিকিট</a>
            <a href="{{ route('customer.profile') }}" class="px-4 py-2.5 rounded-xl bg-white border border-gray-200 hover:bg-purple-50 hover:text-purple-700 shrink-0">প্রোফাইল সেটিংস</a>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold">মোট অর্ডার</span>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $ordersCount }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <i data-lucide="download-cloud" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold">ডাউনলোডকৃত ফাইল</span>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $downloadsCount }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center font-bold">
                    <i data-lucide="crown" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold">সাবস্ক্রিপশন স্ট্যাটাস</span>
                    <h3 class="text-base font-extrabold {{ $subscription && $subscription->isActive() ? 'text-emerald-600' : 'text-gray-400' }}">
                        {{ $subscription && $subscription->isActive() ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (None)' }}
                    </h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold">
                    <i data-lucide="heart" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold">উইশলিস্ট পণ্য</span>
                    <h3 class="text-2xl font-extrabold text-gray-900">{{ $user->wishlists()->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900">সাম্প্রতিক অর্ডারসমূহ</h3>
                <a href="{{ route('customer.orders') }}" class="text-xs text-purple-600 font-bold hover:underline">সবগুলো দেখুন →</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="p-8 text-center text-xs text-gray-500">আপনার এখনও কোনো অর্ডার নেই।</div>
            @else
                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($recentOrders as $order)
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold font-mono text-purple-700 text-sm">#{{ $order->order_number }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $order->payment_status === 'paid' ? 'Paid' : 'Pending' }}
                                    </span>
                                </div>
                                <span class="text-gray-400 text-[11px] block mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                            </div>

                            <div class="flex items-center gap-4 justify-between sm:justify-end">
                                <span class="font-extrabold text-gray-900 text-sm">{{ format_price($order->total_amount) }}</span>
                                <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-purple-100 text-purple-700 font-bold text-xs">
                                    বিস্তারিত
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
