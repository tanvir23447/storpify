@extends('layouts.app')

@section('content')

    <!-- 1. HERO SLIDER SECTION (from Approved Mockup) -->
    @if(isset($sections['hero_slider']) && $sections['hero_slider']->is_active)
        <section class="relative bg-gradient-to-b from-[#17152B] via-[#1F1C3B] to-[#17152B] text-white overflow-hidden py-12 md:py-20">
            <!-- Background glow accents -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="{ currentSlide: 0, totalSlides: {{ count($heroSlides) ?: 1 }} }">
                
                @if(count($heroSlides) > 0)
                    @foreach($heroSlides as $index => $slide)
                        <div x-show="currentSlide === {{ $index }}" 
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                            
                            <!-- Slide Left: Content -->
                            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-white tracking-tight">
                                    {{ app()->getLocale() === 'bn' && $slide->title_bn ? $slide->title_bn : $slide->title }}
                                </h1>

                                <p class="text-sm sm:text-base text-gray-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                                    {{ app()->getLocale() === 'bn' && $slide->subtitle_bn ? $slide->subtitle_bn : $slide->subtitle }}
                                </p>

                                <!-- Feature Pills -->
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2 text-xs font-semibold text-purple-200">
                                    <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full border border-white/10">
                                        <i data-lucide="zap" class="w-4 h-4 text-yellow-400"></i>
                                        <span>Instant Delivery</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full border border-white/10">
                                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                                        <span>Secure Payment</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full border border-white/10">
                                        <i data-lucide="headphones" class="w-4 h-4 text-blue-400"></i>
                                        <span>24/7 Support</span>
                                    </div>
                                </div>

                                <!-- CTAs -->
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-4">
                                    <a href="{{ $slide->primary_button_url ?: route('shop') }}" class="px-7 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-full shadow-lg shadow-purple-600/30 flex items-center gap-2 transition-all hover:scale-105">
                                        <span>{{ $slide->primary_button_text ?: (app()->getLocale() === 'bn' ? 'সব প্রোডাক্ট দেখুন' : 'Explore Products') }}</span>
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>

                                    <a href="{{ $slide->secondary_button_url ?: route('shop', ['filter' => 'discounted']) }}" class="px-7 py-3.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-bold text-sm rounded-full shadow-lg shadow-pink-600/30 transition-all hover:scale-105">
                                        <span>{{ $slide->secondary_button_text ?: (app()->getLocale() === 'bn' ? 'আজকের অফার' : 'Today Deals') }}</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Slide Right: 3D Laptop Mockup Art (from Uploaded Image) -->
                            <div class="lg:col-span-5 flex justify-center relative">
                                <div class="relative w-full max-w-md">
                                    <div class="relative rounded-3xl p-6 bg-gradient-to-br from-purple-900/40 via-indigo-900/20 to-transparent border border-purple-500/20 backdrop-blur-xs shadow-2xl flex flex-col items-center">
                                        
                                        <!-- Mockup Graphic Container -->
                                        <div class="w-full bg-[#121024] rounded-2xl p-4 border border-purple-500/30 shadow-inner relative overflow-hidden group">
                                            <div class="flex items-center gap-1.5 pb-3 border-b border-gray-800">
                                                <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div>
                                                <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                                                <span class="text-[10px] text-gray-500 font-mono ml-2">storpify.com</span>
                                            </div>

                                            <div class="py-8 text-center space-y-4">
                                                <div class="inline-block p-4 rounded-2xl bg-purple-600/20 border border-purple-500/30">
                                                    <img src="{{ asset('assets/images/storpify-logo.png') }}" alt="STORPIFY" class="h-10 mx-auto brightness-125">
                                                </div>
                                                <div class="text-xs font-bold text-purple-300">PREMIUM DIGITAL HUB</div>
                                                <div class="flex justify-center gap-3 text-xs text-gray-400">
                                                    <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/5 font-mono">WordPress</span>
                                                    <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/5 font-mono">Photoshop</span>
                                                    <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/5 font-mono">&lt;/&gt; Code</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Floating Badge 1 -->
                                        <div class="absolute -top-3 -right-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-[11px] font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5 border border-purple-400/40 animate-bounce">
                                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                            <span>100% Genuine</span>
                                        </div>

                                        <!-- Floating Badge 2 -->
                                        <div class="absolute -bottom-3 -left-3 bg-[#17152B] border border-purple-500/40 text-purple-300 text-[11px] font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
                                            <i data-lucide="download-cloud" class="w-3.5 h-3.5 text-purple-400"></i>
                                            <span>Direct ZIP Access</span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach

                    <!-- Carousel Dots Indicator -->
                    <div class="flex justify-center items-center gap-2 mt-8">
                        @foreach($heroSlides as $i => $s)
                            <button @click="currentSlide = {{ $i }}" 
                                class="h-2 rounded-full transition-all duration-300"
                                :class="currentSlide === {{ $i }} ? 'w-8 bg-purple-500' : 'w-2 bg-gray-600 hover:bg-gray-400'"></button>
                        @endforeach
                    </div>
                @endif

            </div>
        </section>
    @endif

    <!-- 2. TRUST FEATURES BAR (from Approved Mockup) -->
    @if(isset($sections['trust_bar']) && $sections['trust_bar']->is_active)
        <section class="bg-white border-b border-gray-100 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center md:text-left">
                    @foreach($trustFeatures as $tf)
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 shadow-xs border border-purple-100">
                                <i data-lucide="{{ $tf->icon ?? 'zap' }}" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">
                                    {{ app()->getLocale() === 'bn' && $tf->title_bn ? $tf->title_bn : $tf->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ app()->getLocale() === 'bn' && $tf->subtitle_bn ? $tf->subtitle_bn : $tf->subtitle }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 3. POPULAR CATEGORIES (from Approved Mockup) -->
    @if(isset($sections['featured_categories']) && $sections['featured_categories']->is_active)
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                            {{ __('messages.sections.popular_categories') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            {{ __('messages.sections.popular_categories_subtitle') }}
                        </p>
                    </div>
                    <a href="{{ route('shop') }}" class="text-xs sm:text-sm font-bold text-purple-600 hover:text-purple-700 flex items-center gap-1 group">
                        <span>{{ __('messages.sections.view_all_categories') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-4">
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="flex flex-col items-center text-center p-4 rounded-2xl border border-gray-100 bg-white hover:border-purple-300 hover:shadow-lg transition-all group hover:-translate-y-1">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-50 to-indigo-50 text-purple-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-xs">
                                <i data-lucide="{{ $category->icon ?? 'folder' }}" class="w-7 h-7"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-900 group-hover:text-purple-600 transition-colors line-clamp-1">
                                {{ app()->getLocale() === 'bn' && $category->name_bn ? $category->name_bn : $category->name }}
                            </span>
                            <span class="text-[10px] text-gray-400 mt-1">
                                ({{ app()->getLocale() === 'bn' ? bangla_number($category->products_count ?: rand(50, 300)) . '+' : ($category->products_count ?: rand(50, 300)) . '+' }})
                            </span>
                        </a>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- 4. DUAL PROMOTIONAL CARDS (Web Subscription & Big Offer 70%) -->
    @if(isset($sections['promotional_banners']) && $sections['promotional_banners']->is_active)
        <section class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Left: Web Subscription Banner Card (from Mockup) -->
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-900 via-indigo-900 to-[#17152B] p-6 sm:p-8 text-white shadow-xl flex items-center justify-between border border-purple-500/20">
                        <div class="space-y-4 max-w-sm z-10">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-400/20 text-yellow-300 rounded-full text-[11px] font-bold border border-yellow-400/30">
                                <span>👑</span>
                                <span>Web Subscription</span>
                            </div>
                            <h3 class="text-lg sm:text-xl font-extrabold leading-snug">
                                সদস্যপদ নিন এবং হাজারো প্রিমিয়াম ফাইল একসাথেই অ্যাকসেস করুন!
                            </h3>
                            <a href="{{ route('subscription.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition-transform hover:scale-105">
                                <span>সাবস্ক্রিপশন প্ল্যান দেখুন</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                        <div class="hidden sm:block shrink-0 relative">
                            <div class="w-24 h-24 rounded-2xl bg-purple-600/30 border border-purple-400/30 backdrop-blur-sm flex items-center justify-center text-4xl shadow-inner">
                                📁
                            </div>
                        </div>
                    </div>

                    <!-- Right: Big Offer 70% Discount Banner Card (from Mockup) -->
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-rose-600 via-pink-600 to-purple-800 p-6 sm:p-8 text-white shadow-xl flex items-center justify-between border border-pink-400/20">
                        <div class="space-y-4 max-w-sm z-10">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 text-white rounded-full text-[11px] font-bold border border-white/20">
                                <span>🔥</span>
                                <span>বড় অফার</span>
                            </div>
                            <h3 class="text-lg sm:text-xl font-extrabold leading-snug">
                                সর্বোচ্চ ৭০% ছাড়! নির্বাচিত প্রিমিয়াম প্রোডাক্টে
                            </h3>
                            <a href="{{ route('shop', ['filter' => 'discounted']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-pink-700 hover:bg-gray-50 text-xs font-bold rounded-xl shadow-md transition-transform hover:scale-105">
                                <span>অফার দেখুন</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                        <div class="hidden sm:block shrink-0 relative">
                            <div class="w-24 h-24 rounded-2xl bg-white/20 border border-white/30 backdrop-blur-sm flex items-center justify-center text-4xl shadow-inner">
                                🎁
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endif

    <!-- 5. FEATURED PRODUCTS (from Approved Mockup) -->
    @if(isset($sections['featured_products']) && $sections['featured_products']->is_active)
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                            {{ __('messages.sections.featured_products') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            {{ __('messages.sections.featured_products_subtitle') }}
                        </p>
                    </div>
                    <a href="{{ route('shop') }}" class="text-xs sm:text-sm font-bold text-purple-600 hover:text-purple-700 flex items-center gap-1 group">
                        <span>{{ __('messages.sections.view_all') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
                            
                            <!-- Thumbnail Area -->
                            <div class="relative bg-gradient-to-tr from-gray-900 to-purple-950 h-48 overflow-hidden flex items-center justify-center p-4">
                                <a href="{{ route('product.show', $product->slug) }}" class="w-full h-full flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 rounded-2xl bg-purple-600/30 border border-purple-400/30 flex items-center justify-center text-white mb-2 shadow-inner group-hover:scale-110 transition-transform">
                                        <i data-lucide="layers" class="w-8 h-8 text-purple-300"></i>
                                    </div>
                                    <span class="text-[11px] font-mono text-purple-200 line-clamp-1 px-2">v{{ $product->version }}</span>
                                </a>

                                <!-- Discount Badge -->
                                @if($product->discount_percent > 0)
                                    <span class="absolute top-3 left-3 bg-pink-600 text-white font-extrabold text-[10px] px-2.5 py-1 rounded-full shadow-md">
                                        -{{ $product->discount_percent }}%
                                    </span>
                                @endif

                                <!-- Wishlist Button -->
                                <form action="{{ route('customer.wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white text-white hover:text-pink-600 backdrop-blur-sm flex items-center justify-center transition-colors">
                                        <i data-lucide="heart" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Body Area -->
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- Category Pill -->
                                    <div class="flex items-center gap-2 mb-2">
                                        @foreach($product->categories as $c)
                                            <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2.5 py-0.5 rounded-full">
                                                {{ app()->getLocale() === 'bn' && $c->name_bn ? $c->name_bn : $c->name }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Product Name -->
                                    <h3 class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors line-clamp-2">
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            {{ app()->getLocale() === 'bn' && $product->name_bn ? $product->name_bn : $product->name }}
                                        </a>
                                    </h3>

                                    <!-- Rating -->
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <div class="flex items-center text-yellow-400">
                                            <i data-lucide="star" class="w-3.5 h-3.5 fill-yellow-400"></i>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700">{{ $product->avg_rating }}</span>
                                        <span class="text-xs text-gray-400">({{ $product->review_count }})</span>
                                    </div>
                                </div>

                                <!-- Price & Action Area -->
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <div>
                                        <div class="text-base font-extrabold text-gray-900">
                                            {{ format_price($product->effective_price) }}
                                        </div>
                                        @if($product->sale_price !== null && $product->sale_price < $product->regular_price)
                                            <div class="text-xs text-gray-400 line-through">
                                                {{ format_price($product->regular_price) }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Add to Cart Form -->
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        @if($product->variants->count() > 0)
                                            <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id }}">
                                        @endif
                                        <button type="submit" class="w-10 h-10 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white flex items-center justify-center shadow-md shadow-purple-600/30 transition-transform hover:scale-105" title="Add to Cart">
                                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- 6. WHY CHOOSE STORPIFY (from Approved Mockup) -->
    @if(isset($sections['why_choose_us']) && $sections['why_choose_us']->is_active)
        <section class="py-16 bg-[#17152B] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <h2 class="text-2xl sm:text-3xl font-extrabold">
                        {{ __('messages.sections.why_choose_us') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-400 mt-2">
                        বাংলাদেশের বিশ্বস্ত ডিজিটাল প্রোডাক্ট ও রিসোর্স মার্কেটপ্লেস
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 text-center hover:bg-white/10 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white mx-auto flex items-center justify-center mb-4 shadow-lg">
                            <i data-lucide="award" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-sm font-bold">প্রিমিয়াম কোয়ালিটি</h4>
                        <p class="text-xs text-gray-400 mt-1">বাছাইকৃত ও মানসম্মত প্রোডাক্ট</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 text-center hover:bg-white/10 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white mx-auto flex items-center justify-center mb-4 shadow-lg">
                            <i data-lucide="zap" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-sm font-bold">ইনস্ট্যান্ট ডেলিভারি</h4>
                        <p class="text-xs text-gray-400 mt-1">দ্রুত ও সহজ ওয়ান-ক্লিক ডাউনলোড</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 text-center hover:bg-white/10 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-pink-600 text-white mx-auto flex items-center justify-center mb-4 shadow-lg">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-sm font-bold">নিরাপদ পেমেন্ট</h4>
                        <p class="text-xs text-gray-400 mt-1">bKash, Nagad, Rocket ও কার্ড</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-white/5 border border-white/10 text-center hover:bg-white/10 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white mx-auto flex items-center justify-center mb-4 shadow-lg">
                            <i data-lucide="headphones" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-sm font-bold">বিশেষজ্ঞ সাপোর্ট</h4>
                        <p class="text-xs text-gray-400 mt-1">বাংলায় সরাসরি দ্রুত সহায়তা</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- 7. CUSTOMER REVIEWS (from Approved Mockup) -->
    @if(isset($sections['reviews']) && $sections['reviews']->is_active)
        <section class="py-14 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-xl mx-auto mb-10">
                    <h2 class="text-2xl font-extrabold text-gray-900">
                        {{ __('messages.sections.customer_reviews') }}
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                    @foreach($testimonials as $review)
                        <div class="p-6 rounded-3xl bg-[#F8F9FD] border border-gray-100 flex gap-4 items-start shadow-xs">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 text-white font-bold text-base flex items-center justify-center shrink-0 shadow-md">
                                {{ strtoupper(substr($review->customer_name, 0, 1)) }}
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center text-yellow-400 gap-1">
                                    @for($i=0; $i<$review->rating; $i++)
                                        <i data-lucide="star" class="w-3.5 h-3.5 fill-yellow-400"></i>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-700 italic leading-relaxed">
                                    "{{ app()->getLocale() === 'bn' && $review->review_text_bn ? $review->review_text_bn : $review->review_text }}"
                                </p>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">{{ $review->customer_name }}</h4>
                                    <p class="text-[10px] text-gray-500">{{ app()->getLocale() === 'bn' && $review->customer_role_bn ? $review->customer_role_bn : $review->customer_role }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 8. NEWSLETTER SECTION (from Approved Mockup) -->
    @if(isset($sections['newsletter']) && $sections['newsletter']->is_active)
        <section class="py-12 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-3xl bg-gradient-to-r from-purple-700 via-indigo-700 to-purple-800 p-8 sm:p-10 text-white text-center shadow-xl">
                    <h3 class="text-xl sm:text-2xl font-extrabold mb-2">
                        {{ __('messages.sections.newsletter_title') }}
                    </h3>
                    <p class="text-xs sm:text-sm text-purple-200 mb-6 max-w-md mx-auto">
                        {{ __('messages.sections.newsletter_subtitle') }}
                    </p>

                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row items-center justify-center gap-3 max-w-md mx-auto">
                        @csrf
                        <input type="email" name="email" required placeholder="{{ __('messages.sections.email_placeholder') }}" 
                            class="w-full px-5 py-3 rounded-full bg-white text-gray-900 text-xs sm:text-sm focus:outline-none shadow-inner">
                        <button type="submit" class="w-full sm:w-auto px-7 py-3 bg-gray-900 hover:bg-black text-white font-bold text-xs sm:text-sm rounded-full shadow-lg transition-transform hover:scale-105 shrink-0 flex items-center justify-center gap-2">
                            <span>{{ __('messages.sections.subscribe') }}</span>
                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    @endif

@endsection
