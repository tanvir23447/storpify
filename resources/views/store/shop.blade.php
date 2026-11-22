@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs & Title -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-purple-600">হোম</a>
                <span>/</span>
                <span class="text-gray-800 font-semibold">ডিজিটাল প্রোডাক্ট</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                সকল প্রিমিয়াম ডিজিটাল প্রোডাক্ট
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                {{ $products->total() }} টি প্রোডাক্ট পাওয়া গেছে
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Category Filter Box -->
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-xs">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center justify-between">
                        <span>ক্যাটাগরি সমূহ</span>
                        <i data-lucide="filter" class="w-4 h-4 text-purple-600"></i>
                    </h3>
                    <div class="space-y-1.5 text-xs font-medium">
                        <a href="{{ route('shop') }}" class="flex items-center justify-between p-2 rounded-xl {{ !request('category') ? 'bg-purple-50 text-purple-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                            <span>সকল প্রোডাক্ট</span>
                            <span>{{ \App\Models\Product::published()->count() }}</span>
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="flex items-center justify-between p-2 rounded-xl {{ request('category') === $cat->slug ? 'bg-purple-50 text-purple-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="{{ $cat->icon ?? 'folder' }}" class="w-3.5 h-3.5 text-purple-500"></i>
                                    {{ app()->getLocale() === 'bn' && $cat->name_bn ? $cat->name_bn : $cat->name }}
                                </span>
                                <span class="text-gray-400">({{ $cat->products_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Price Sort -->
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-xs">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">সাজান (Sort By)</h3>
                    <form method="GET" action="{{ route('shop') }}" class="space-y-2 text-xs">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        <select name="sort" onchange="this.form.submit()" class="w-full p-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-semibold focus:outline-none focus:border-purple-600">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>নতুন রিলিজ (Newest)</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>সর্বাধিক বিক্রিত (Most Popular)</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>মূল্য: কম থেকে বেশি</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>মূল্য: বেশি থেকে কম</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>সর্বোচ্চ রেটিং (Top Rated)</option>
                        </select>
                    </form>
                </div>

                <!-- Web Subscription Promo Box -->
                <div class="p-5 rounded-3xl bg-gradient-to-br from-purple-900 to-[#17152B] text-white border border-purple-500/20 text-center space-y-3 shadow-lg">
                    <div class="text-2xl">👑</div>
                    <h4 class="text-sm font-bold">আনলিমিটেড ডাউনলোড চান?</h4>
                    <p class="text-[11px] text-purple-200 leading-relaxed">
                        আলাদা আলাদা না কিনে আমাদের ওয়েব সাবস্ক্রিপশন প্ল্যান নিয়ে সব ফাইল একসাথে অ্যাকসেস করুন।
                    </p>
                    <a href="{{ route('subscription.index') }}" class="inline-block px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-transform hover:scale-105">
                        সাবস্ক্রিপশন প্ল্যান দেখুন →
                    </a>
                </div>

            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                @if($products->isEmpty())
                    <div class="bg-white p-12 rounded-3xl text-center border border-gray-100 shadow-xs space-y-3">
                        <div class="w-16 h-16 rounded-full bg-purple-50 text-purple-600 mx-auto flex items-center justify-center">
                            <i data-lucide="package-search" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">কোন প্রোডাক্ট পাওয়া যায়নি</h3>
                        <p class="text-xs text-gray-500">অনুগ্রহ করে অন্য কোনো কি-ওয়ার্ড দিয়ে সার্চ করুন অথবা ফিল্টার পরিবর্তন করুন।</p>
                        <a href="{{ route('shop') }}" class="inline-block mt-2 px-5 py-2.5 bg-purple-600 text-white rounded-xl text-xs font-bold">
                            সকল প্রোডাক্ট দেখুন
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                                <div>
                                    <!-- Image / Icon Header -->
                                    <div class="relative bg-gradient-to-tr from-gray-900 to-purple-950 h-44 overflow-hidden flex items-center justify-center p-4">
                                        <a href="{{ route('product.show', $product->slug) }}" class="w-full h-full flex flex-col items-center justify-center text-center">
                                            <div class="w-14 h-14 rounded-2xl bg-purple-600/30 border border-purple-400/30 flex items-center justify-center text-white mb-2 shadow-inner group-hover:scale-110 transition-transform">
                                                <i data-lucide="layers" class="w-7 h-7 text-purple-300"></i>
                                            </div>
                                            <span class="text-[10px] font-mono text-purple-200">v{{ $product->version }}</span>
                                        </a>

                                        @if($product->discount_percent > 0)
                                            <span class="absolute top-3 left-3 bg-pink-600 text-white font-extrabold text-[10px] px-2.5 py-1 rounded-full shadow-md">
                                                -{{ $product->discount_percent }}%
                                            </span>
                                        @endif

                                        <form action="{{ route('customer.wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white text-white hover:text-pink-600 backdrop-blur-sm flex items-center justify-center transition-colors">
                                                <i data-lucide="heart" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="p-5">
                                        <div class="flex items-center gap-2 mb-2">
                                            @foreach($product->categories as $c)
                                                <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2.5 py-0.5 rounded-full">
                                                    {{ app()->getLocale() === 'bn' && $c->name_bn ? $c->name_bn : $c->name }}
                                                </span>
                                            @endforeach
                                        </div>

                                        <h3 class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors line-clamp-2">
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                {{ app()->getLocale() === 'bn' && $product->name_bn ? $product->name_bn : $product->name }}
                                            </a>
                                        </h3>

                                        <div class="flex items-center gap-1.5 mt-2">
                                            <i data-lucide="star" class="w-3.5 h-3.5 text-yellow-400 fill-yellow-400"></i>
                                            <span class="text-xs font-bold text-gray-700">{{ $product->avg_rating }}</span>
                                            <span class="text-xs text-gray-400">({{ $product->review_count }})</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 pb-5 pt-3 border-t border-gray-100 flex items-center justify-between">
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

                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        @if($product->variants->count() > 0)
                                            <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id }}">
                                        @endif
                                        <button type="submit" class="w-10 h-10 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white flex items-center justify-center shadow-md shadow-purple-600/30 transition-transform hover:scale-105">
                                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
