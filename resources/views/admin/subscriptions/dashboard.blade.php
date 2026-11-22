@extends('layouts.subscription-admin', ['title' => 'STORPIFY - ওয়েব সাবস্ক্রিপশন কন্ট্রোল সেন্টার'])

@section('content')
<div class="space-y-6">
    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xl">👑</span>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">ওয়েব সাবস্ক্রিপশন ড্যাশবোর্ড</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1">STORPIFY এর ইন্ডিপেনডেন্ট মেম্বারশিপ, ডিজিটাল রিসোর্স লাইব্রেরি ও সাবস্ক্রিপশন মেট্রিক্স।</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('subscription.index') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-xs">
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-purple-600"></i>
                পাবলিক পোর্টাল দেখুন
            </a>
            <a href="{{ route('admin.subscriptions.plans') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                নতুন প্ল্যান তৈরি
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards Grid Matching Mockup Style -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- 1. Total Subscribers -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">মোট সাবস্ক্রাইবার</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1 font-mono">{{ number_format($totalSubscribers) }}</h3>
                <span class="text-[10px] text-purple-600 font-bold mt-0.5 block">সর্বমোট মেম্বারশিপ</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- 2. Active Members -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">সক্রিয় মেম্বার</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1 font-mono">{{ number_format($activeMembers) }}</h3>
                <span class="text-[10px] text-emerald-600 font-bold mt-0.5 block">বর্তমানে একটিভ</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- 3. Expired Members -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">মেয়াদোত্তীর্ণ</p>
                <h3 class="text-2xl font-black text-rose-500 mt-1 font-mono">{{ number_format($expiredMembers) }}</h3>
                <span class="text-[10px] text-rose-500 font-bold mt-0.5 block">রিনিউয়াল প্রয়োজন</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- 4. Total Subscription Files -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">লাইব্রেরি ফাইল</p>
                <h3 class="text-2xl font-black text-blue-600 mt-1 font-mono">{{ number_format($totalFiles) }}</h3>
                <span class="text-[10px] text-blue-600 font-bold mt-0.5 block">সুরক্ষিত ডিজিটাল এসেট</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="folder-archive" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- 5. Total Downloads -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">মোট ডাউনলোড</p>
                <h3 class="text-2xl font-black text-purple-600 mt-1 font-mono">{{ number_format($totalDownloads) }}</h3>
                <span class="text-[10px] text-purple-600 font-bold mt-0.5 block">সফল ডেলিভারি</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i data-lucide="download" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Subscription Plans Status Table Matching Mockup -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-black text-gray-900 text-base">সক্রিয় সাবস্ক্রিপশন প্ল্যান সমূহ</h3>
                <p class="text-xs text-gray-500 mt-0.5">গ্রাহকদের জন্য নির্ধারিত মেম্বারশিপ প্যাকেজের বিস্তারিত。</p>
            </div>
            <a href="{{ route('admin.subscriptions.plans') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700">
                সব প্ল্যান পরিচালনা করুন →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">প্ল্যানের নাম</th>
                        <th class="px-5 py-4">মেয়াদ</th>
                        <th class="px-5 py-4">মূল্য (৳)</th>
                        <th class="px-5 py-4">দৈনিক ডাউনলোড লিমিট</th>
                        <th class="px-5 py-4">ডিভাইস লিমিট</th>
                        <th class="px-5 py-4">ক্যাটাগরি অ্যাক্সেস</th>
                        <th class="px-5 py-4">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($plans as $plan)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4 font-black text-gray-900 flex items-center gap-2">
                                @if($plan->is_featured)
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">POPULAR</span>
                                @endif
                                <span>{{ $plan->name_bn ?: $plan->name }}</span>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-gray-700">
                                {{ $plan->duration_days }} দিন
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-purple-700 text-sm">
                                ৳ {{ number_format($plan->effective_price, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                {{ $plan->download_limit_daily > 0 ? $plan->download_limit_daily . ' টি / দিন' : 'আনলিমিটেড' }}
                            </td>
                            <td class="px-5 py-4 font-mono text-gray-600">
                                {{ $plan->device_limit }} টি ডিভাইস
                            </td>
                            <td class="px-5 py-4">
                                @if($plan->all_categories_access)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">সকল ক্যাটাগরি</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">নির্দিষ্ট ক্যাটাগরি</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($plan->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">সক্রিয়</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-400">কোন প্ল্যান পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Subscription Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-black text-gray-900 text-base">সাম্প্রতিক সাবস্ক্রিপশন ট্রানজেকশন</h3>
            <a href="{{ route('admin.subscriptions.orders') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700">
                সব ট্রানজেকশন দেখুন →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">অর্ডার নং</th>
                        <th class="px-5 py-4">গ্রাহক</th>
                        <th class="px-5 py-4">প্ল্যান</th>
                        <th class="px-5 py-4">পেমেন্ট মেথড ও TrxID</th>
                        <th class="px-5 py-4">টাকার পরিমাণ</th>
                        <th class="px-5 py-4">তারিখ</th>
                        <th class="px-5 py-4">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-purple-700">
                                #{{ $order->order_number }}
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-900">
                                {{ $order->user ? $order->user->name : 'N/A' }}
                                <span class="block text-[10px] text-gray-500 font-mono font-normal">{{ $order->user ? $order->user->email : '' }}</span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-800">
                                {{ $order->plan ? $order->plan->name : 'N/A' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="uppercase font-bold text-purple-700 text-[10px] bg-purple-50 px-2 py-0.5 rounded">{{ $order->method_code }}</span>
                                <span class="font-mono text-gray-600 text-[11px] block mt-0.5">{{ $order->transaction_id }}</span>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-gray-900">
                                ৳ {{ number_format($order->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-gray-400">কোন সাম্প্রতিক সাবস্ক্রিপশন অর্ডার নেই।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
