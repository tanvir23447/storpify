@extends('layouts.app')

@section('content')

    <!-- Web Subscription Hero Banner -->
    <section class="bg-gradient-to-r from-purple-950 via-[#17152B] to-indigo-950 text-white py-16 sm:py-20 border-b border-purple-900/30 relative overflow-hidden">
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6 relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-yellow-400/20 border border-yellow-400/30 text-yellow-300 text-xs font-bold">
                <span>👑</span>
                <span>VIP Web Subscription Library</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight max-w-3xl mx-auto leading-tight">
                সদস্যপদ নিন এবং হাজারো প্রিমিয়াম ফাইল একসাথেই অ্যাকসেস করুন!
            </h1>

            <p class="text-xs sm:text-sm text-purple-200 max-w-2xl mx-auto leading-relaxed">
                ওয়ার্ডপ্রেস প্লাগইন, প্রিমিয়াম থিম, পিএসডি গ্রাফিক্স, রেডি সোর্স কোড ও অটোমেশন ফাইল ডাউনলোড করুন একক সাবস্ক্রিপশনে।
            </p>

            @if($userSubscription && $userSubscription->isActive())
                <div class="inline-block p-4 rounded-2xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-200 text-xs font-bold">
                    ✓ আপনার সাবস্ক্রিপশন সক্রিয় রয়েছে (মেয়াদ বাকি: {{ $userSubscription->remainingDays() }} দিন)
                </div>
            @endif
        </div>
    </section>

    <!-- Subscription Plans Section (matching Mockup) -->
    <section class="py-16 bg-[#F8F9FD]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-xs font-bold text-purple-600 uppercase tracking-wider">সাবস্ক্রিপশন প্ল্যান</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">
                    আপনার প্রয়োজন অনুযায়ী প্ল্যান বেছে নিন
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    যেকোনো প্ল্যানেই রয়েছে সম্পূর্ণ ফাইল লাইব্রেরিতে আনলিমিটেড অ্যাকসেস
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @foreach($plans as $plan)
                    <div class="rounded-3xl bg-white border {{ $plan->is_featured ? 'border-purple-600 shadow-xl ring-2 ring-purple-600/20 relative' : 'border-gray-200 shadow-xs' }} p-8 flex flex-col justify-between hover:shadow-2xl transition-all duration-300">
                        
                        @if($plan->is_featured)
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-extrabold text-[10px] rounded-full uppercase tracking-wider shadow-md">
                                সেরা পছন্দ (Most Popular)
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ app()->getLocale() === 'bn' && $plan->name_bn ? $plan->name_bn : $plan->name }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ app()->getLocale() === 'bn' && $plan->description_bn ? $plan->description_bn : $plan->description }}
                                </p>
                            </div>

                            <div class="border-y border-gray-100 py-4">
                                <div class="text-3xl font-extrabold text-purple-700">
                                    {{ format_price($plan->effective_price) }}
                                </div>
                                <span class="text-xs text-gray-400 font-medium">/ {{ $plan->duration_days }} দিনের মেয়াদের জন্য</span>
                            </div>

                            <ul class="space-y-3 text-xs text-gray-600">
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                    <span>সম্পূর্ণ ডিজিটাল লাইব্রেরিতে সরাসরি অ্যাকসেস</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                    <span>প্রতিদিন সর্বোচ্চ {{ $plan->download_limit_daily ?: 'আনলিমিটেড' }} টি ফাইল ডাউনলোড</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                    <span>ওয়ার্ডপ্রেস থিম, প্লাগইন ও পিএসডি ফাইল</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                    <span>দৈনিক নতুন ফাইল আপডেট যুক্ত</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                    <span>২৪/৭ প্রায়োরিটি বাংলা সাপোর্ট</span>
                                </li>
                            </ul>
                        </div>

                        <div class="pt-8">
                            <a href="{{ route('subscription.subscribe', $plan->id) }}" class="block w-full py-3.5 text-center text-xs font-bold rounded-2xl shadow-md transition-all duration-200 {{ $plan->is_featured ? 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white shadow-purple-600/30' : 'bg-gray-900 hover:bg-black text-white' }}">
                                সাবস্ক্রিপশন নিন →
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Subscription Categories -->
    <section class="py-14 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">সাবস্ক্রিপশন ক্যাটাগরি</h2>
                    <p class="text-xs text-gray-500 mt-0.5">যেসব ক্যাটাগরির রিসোর্স সাবস্ক্রিপশনে অন্তর্ভুক্ত</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories as $cat)
                    <a href="{{ route('subscription.category', $cat->slug) }}" class="p-6 rounded-3xl border border-gray-100 bg-[#F8F9FD] hover:bg-white hover:border-purple-300 hover:shadow-lg transition-all group flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="{{ $cat->icon ?? 'folder' }}" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors">
                                {{ app()->getLocale() === 'bn' && $cat->name_bn ? $cat->name_bn : $cat->name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                {{ app()->getLocale() === 'bn' && $cat->description_bn ? $cat->description_bn : $cat->description }}
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200/60 flex items-center justify-between text-xs font-bold text-purple-600">
                            <span>{{ $cat->files_count }} টি ফাইল</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Recent Library Files -->
    <section class="py-14 bg-[#F8F9FD]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">সাম্প্রতিক যুক্ত হওয়া রিসোর্স</h2>
                    <p class="text-xs text-gray-500 mt-0.5">সাবস্ক্রাইবারদের জন্য সদ্য রিলিজকৃত প্রিমিয়াম ফাইলসমূহ</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentFiles as $f)
                    <div class="bg-white rounded-3xl border border-gray-100 p-5 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 text-[10px] font-bold">
                                    {{ $f->category ? $f->category->name : 'Resource' }}
                                </span>
                                <span class="text-[10px] font-mono text-gray-400">v{{ $f->version }}</span>
                            </div>

                            <h4 class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors line-clamp-2">
                                <a href="{{ route('subscription.file', $f->slug) }}">
                                    {{ app()->getLocale() === 'bn' && $f->title_bn ? $f->title_bn : $f->title }}
                                </a>
                            </h4>

                            <p class="text-xs text-gray-500 line-clamp-2">
                                {{ app()->getLocale() === 'bn' && $f->description_bn ? $f->description_bn : $f->description }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400 font-medium">
                                {{ number_format($f->file_size / 1048576, 1) }} MB • {{ strtoupper($f->file_type) }}
                            </span>
                            <a href="{{ route('subscription.file', $f->slug) }}" class="p-2 rounded-xl bg-purple-50 hover:bg-purple-600 text-purple-600 hover:text-white transition-colors" title="View & Download">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
