@extends('layouts.subscription-admin', ['title' => 'STORPIFY - সাবস্ক্রিপশন প্ল্যান সমূহ'])

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false, allCats: true }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">সাবস্ক্রিপশন প্ল্যান ম্যানেজমেন্ট</h1>
            <p class="text-xs text-gray-500 mt-1">মেম্বারশিপ প্যাকেজ, মেয়াদ, ডাউনলোড লিমিট ও ক্যাটাগরি অ্যাক্সেস নির্ধারণ করুন।</p>
        </div>
        <button @click="createModalOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            নতুন প্ল্যান তৈরি করুন
        </button>
    </div>

    <!-- Plans Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($plans as $plan)
            <div class="bg-white rounded-2xl border {{ $plan->is_featured ? 'border-purple-500 ring-2 ring-purple-500/20 shadow-md' : 'border-gray-200/80 shadow-xs' }} p-6 flex flex-col justify-between relative overflow-hidden">
                @if($plan->is_featured)
                    <div class="absolute -right-12 top-6 bg-purple-600 text-white text-[10px] font-black uppercase py-1 px-12 rotate-45 shadow-xs">
                        FEATURED
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-bold text-purple-600 uppercase tracking-wider block mb-1">
                            {{ $plan->duration_days }} দিনের মেম্বারশিপ
                        </span>
                        <h3 class="text-xl font-black text-gray-900">{{ $plan->name_bn ?: $plan->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $plan->description_bn ?: ($plan->description ?: 'ডিজিটাল রিসোর্স আনলিমিটেড অ্যাক্সেস') }}</p>
                    </div>

                    <div class="py-3 border-y border-gray-100 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-gray-900 font-mono">৳ {{ number_format($plan->effective_price, 0) }}</span>
                        @if($plan->sale_price && $plan->regular_price > $plan->sale_price)
                            <span class="text-sm text-gray-400 line-through font-mono">৳ {{ number_format($plan->regular_price, 0) }}</span>
                        @endif
                    </div>

                    <ul class="space-y-2.5 text-xs text-gray-600">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                            <span>দৈনিক ডাউনলোড: <strong>{{ $plan->download_limit_daily > 0 ? $plan->download_limit_daily . ' টি' : 'আনলিমিটেড' }}</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                            <span>ডিভাইস লিমিট: <strong>{{ $plan->device_limit }} টি ডিভাইস</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                            <span>ক্যাটাগরি অ্যাক্সেস: <strong>{{ $plan->all_categories_access ? 'সকল ক্যাটাগরি' : 'নির্দিষ্ট ক্যাটাগরি' }}</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                            <span>বর্তমান গ্রাহক: <strong>{{ $plan->subscriptions_count }} জন</strong></span>
                        </li>
                    </ul>
                </div>

                <div class="pt-6 mt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[11px] font-mono text-gray-400">সিরিয়াল: #{{ $plan->sort_order }}</span>
                    @if($plan->is_active)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">সক্রিয়</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">নিষ্ক্রিয়</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                কোন প্ল্যান তৈরি করা হয়নি।
            </div>
        @endforelse
    </div>

    <!-- Create Plan Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="createModalOpen = false"></div>

            <div class="relative bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl z-10 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-black text-gray-900 text-base">নতুন সাবস্ক্রিপশন প্ল্যান তৈরি</h3>
                    <button @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('admin.subscriptions.plans.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">প্ল্যানের নাম (English)</label>
                            <input type="text" name="name" required placeholder="Monthly VIP Pass" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">প্ল্যানের নাম (বাংলা)</label>
                            <input type="text" name="name_bn" placeholder="মাসিক ভিআইপি পাস" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">মেয়াদ (দিন)</label>
                            <input type="number" name="duration_days" required value="30" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">নিয়মিত মূল্য (৳)</label>
                            <input type="number" step="0.01" name="price" required placeholder="499" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ডিসকাউন্ট মূল্য (৳)</label>
                            <input type="number" step="0.01" name="sale_price" placeholder="299 (ঐচ্ছিক)" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">দৈনিক ডাউনলোড লিমিট (০ = আনলিমিটেড)</label>
                            <input type="number" name="download_limit_daily" value="0" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ডিভাইস লিমিট</label>
                            <input type="number" name="device_limit" value="2" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer mb-2">
                            <input type="checkbox" name="all_categories_access" value="1" x-model="allCats" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                            <span class="text-xs font-bold text-gray-900">সকল সাবস্ক্রিপশন ক্যাটাগরিতে এক্সেস দিন</span>
                        </label>

                        <div x-show="!allCats" class="bg-gray-50 p-3 rounded-xl border border-gray-200 grid grid-cols-2 gap-2">
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2 text-xs text-gray-700">
                                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" class="w-3.5 h-3.5 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span>{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                            <span class="text-xs font-bold text-gray-800">ফিচার্ড / হাইলাইট প্ল্যান হিসেবে দেখান</span>
                        </label>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs">
                            প্ল্যান সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
