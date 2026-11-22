@extends('layouts.app', ['title' => 'STORPIFY - মেম্বারশিপ প্ল্যান সমূহ'])

@section('content')
<div class="py-12 bg-linear-to-b from-[#F5F3FF] to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-100 text-purple-800 text-xs font-black uppercase tracking-wider mb-4">
                <span>👑</span>
                ভিআইপি মেম্বারশিপ প্ল্যান
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-gray-900 tracking-tight leading-tight">
                এক সাবস্ক্রিপশনে <span class="bg-linear-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">সকল ডিজিটাল রিসোর্স</span> আনলিমিটেড এক্সেস
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-4">
                ওয়ার্ডপ্রেস থিম, প্লাগইন, পিএইচপি স্ক্রিপ্ট, গ্রাফিক্স ও টেমপ্লেট — কোন বাড়তি চার্জ ছাড়াই প্রতিদিন ডাউনলোড করুন।
            </p>
        </div>

        <!-- Pricing Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            @foreach($plans as $plan)
                <div class="bg-white rounded-3xl border-2 {{ $plan->is_featured ? 'border-purple-600 ring-4 ring-purple-600/10 shadow-2xl relative scale-105 z-10' : 'border-gray-200/80 shadow-md' }} p-8 flex flex-col justify-between transition-all hover:shadow-xl">
                    
                    @if($plan->is_featured)
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-linear-to-r from-purple-600 to-indigo-600 text-white text-xs font-black uppercase px-4 py-1.5 rounded-full shadow-md tracking-wider">
                            সবচেয়ে জনপ্রিয় চয়েস
                        </div>
                    @endif

                    <div class="space-y-6">
                        <div>
                            <span class="text-xs font-bold text-purple-600 uppercase tracking-wider block mb-2">
                                {{ $plan->duration_days }} দিনের এক্সেস
                            </span>
                            <h3 class="text-2xl font-black text-gray-900">{{ $plan->name_bn ?: $plan->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $plan->description_bn ?: ($plan->description ?: 'ডিজিটাল রিসোর্স লাইব্রেরি আনলিমিটেড ডাউনলোড') }}</p>
                        </div>

                        <!-- Price display -->
                        <div class="py-4 border-y border-gray-100">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-gray-900 font-mono">৳ {{ number_format($plan->effective_price, 0) }}</span>
                                @if($plan->sale_price && $plan->regular_price > $plan->sale_price)
                                    <span class="text-base text-gray-400 line-through font-mono">৳ {{ number_format($plan->regular_price, 0) }}</span>
                                @endif
                                <span class="text-xs font-medium text-gray-500">/ {{ $plan->duration_days }} দিন</span>
                            </div>
                        </div>

                        <!-- Features -->
                        <ul class="space-y-3.5 text-xs text-gray-700">
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <span>দৈনিক ডাউনলোড: <strong>{{ $plan->download_limit_daily > 0 ? $plan->download_limit_daily . ' টি' : 'সম্পূর্ণ আনলিমিটেড' }}</strong></span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <span>ডিভাইস কানেকশন: <strong>{{ $plan->device_limit }} টি ডিভাইস</strong></span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <span>ক্যাটাগরি কভারেজ: <strong>{{ $plan->all_categories_access ? 'সকল প্রিমিয়াম ক্যাটাগরি' : 'নির্বাচিত ক্যাটাগরি' }}</strong></span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <span>নিয়মিত নতুন আপডেট ও রিলিজ</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <span>২৪/৭ প্রায়োরিটি মেম্বার সাপোর্ট</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-8">
                        @if($userSubscription && $userSubscription->plan_id == $plan->id && $userSubscription->isActive())
                            <button disabled class="w-full py-3.5 rounded-2xl bg-emerald-50 text-emerald-700 font-black text-xs border border-emerald-200 cursor-default">
                                ✓ বর্তমানে সক্রিয় প্ল্যান
                            </button>
                        @else
                            <a href="{{ route('subscription.checkout', $plan->id) }}" class="w-full py-3.5 rounded-2xl {{ $plan->is_featured ? 'bg-purple-600 hover:bg-purple-700 text-white shadow-lg shadow-purple-600/30' : 'bg-gray-900 hover:bg-purple-600 text-white' }} font-black text-xs text-center flex items-center justify-center gap-2 transition-all">
                                <span>এখনই সাবস্ক্রাইব করুন</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- FAQ note -->
        <div class="mt-16 text-center text-xs text-gray-500">
            <p>bKash, Nagad ও Rocket এর মাধ্যমে সরাসরি ইনস্ট্যান্ট পেমেন্ট করা যায়। কোন হিডেন চার্জ নেই।</p>
        </div>
    </div>
</div>
@endsection
