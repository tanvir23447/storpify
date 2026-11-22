@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">আমার ওয়েব সাবস্ক্রিপশন</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">আপনার ভিআইপি মেম্বারশিপ স্ট্যাটাস, মেয়াদ ও ডাউনলোড হিসাব</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-purple-600 hover:underline">
                ← ড্যাশবোর্ডে ফিরুন
            </a>
        </div>

        @if($subscription && $subscription->isActive())
            <!-- Active Membership Card -->
            <div class="bg-gradient-to-r from-purple-900 via-[#17152B] to-indigo-950 rounded-3xl p-8 text-white shadow-xl mb-8 border border-purple-500/20">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-400/20 border border-yellow-400/30 text-yellow-300 text-xs font-bold">
                            <span>👑</span>
                            <span>সক্রিয় সদস্যপদ (Active VIP Member)</span>
                        </div>
                        <h2 class="text-2xl font-extrabold text-white">
                            {{ $subscription->plan ? $subscription->plan->name : 'VIP Plan' }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-6 text-xs text-purple-200">
                            <div>শুরু: <span class="font-bold text-white">{{ $subscription->starts_at->format('d M Y') }}</span></div>
                            <div>মেয়াদ শেষ: <span class="font-bold text-white">{{ $subscription->ends_at->format('d M Y') }}</span></div>
                            <div>অবশিষ্ট মেয়াদ: <span class="font-extrabold text-yellow-300 text-sm">{{ $subscription->remainingDays() }} দিন</span></div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                        <a href="{{ route('subscription.index') }}" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-transform hover:scale-105 flex items-center justify-center gap-2">
                            <i data-lucide="folder-search" class="w-4 h-4"></i>
                            <span>ফাইল লাইব্রেরি ব্রাউজ করুন</span>
                        </a>
                        <a href="{{ route('subscription.plans') }}" class="w-full sm:w-auto px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-bold text-xs rounded-xl border border-white/10 transition-colors text-center">
                            রিনিউ / প্ল্যান পরিবর্তন
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Expired or Inactive Notice -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xs mb-8 text-center space-y-4 max-w-lg mx-auto">
                <div class="w-16 h-16 rounded-full bg-purple-50 text-purple-600 mx-auto flex items-center justify-center">
                    <i data-lucide="crown" class="w-8 h-8 text-yellow-500"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">বর্তমানে কোনো সক্রিয় সাবস্ক্রিপশন নেই</h3>
                <p class="text-xs text-gray-500">হাজারো প্রিমিয়াম ফাইল সরাসরি ডাউনলোড করতে আমাদের সাশ্রয়ী ওয়েব সাবস্ক্রিপশন প্ল্যানে যুক্ত হোন।</p>
                <a href="{{ route('subscription.plans') }}" class="inline-block px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-transform hover:scale-105">
                    সাবস্ক্রিপশন প্ল্যান দেখুন →
                </a>
            </div>
        @endif

        <!-- Past Subscription History -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">মেম্বারশিপ হিস্ট্রি</h3>
            </div>

            @if($history->isEmpty())
                <div class="p-8 text-center text-xs text-gray-400">কোনো পূর্ববর্তী সাবস্ক্রিপশন রেকর্ড নেই।</div>
            @else
                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($history as $sub)
                        <div class="p-5 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $sub->plan ? $sub->plan->name : 'Subscription' }}</h4>
                                <span class="text-gray-400 text-[11px]">{{ $sub->starts_at->format('d M Y') }} - {{ $sub->ends_at->format('d M Y') }}</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $sub->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $sub->isActive() ? 'Active' : 'Expired' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
