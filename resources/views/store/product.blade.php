@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]" x-data="{
    selectedVariantId: {{ $product->variants->first() ? $product->variants->first()->id : 'null' }},
    variants: {{ json_encode($product->variants) }},
    basePrice: {{ $product->effective_price }},
    currentPrice: {{ $product->variants->first() ? $product->variants->first()->effective_price : $product->effective_price }},
    updatePrice(variantId) {
        this.selectedVariantId = variantId;
        let v = this.variants.find(x => x.id == variantId);
        if (v) {
            this.currentPrice = v.sale_price && v.sale_price < v.regular_price ? v.sale_price : v.regular_price;
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-purple-600">হোম</a>
            <span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-purple-600">ডিজিটাল প্রোডাক্ট</a>
            <span>/</span>
            <span class="text-gray-800 font-semibold truncate max-w-xs">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Gallery & Preview (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Main Preview Box -->
                <div class="rounded-3xl bg-gradient-to-tr from-gray-900 via-[#17152B] to-purple-950 p-8 sm:p-12 text-center text-white border border-purple-500/20 shadow-xl relative overflow-hidden">
                    <div class="w-24 h-24 rounded-3xl bg-purple-600/30 border border-purple-400/40 mx-auto flex items-center justify-center text-purple-300 shadow-inner mb-4">
                        <i data-lucide="layers" class="w-12 h-12"></i>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-extrabold text-white leading-snug">
                        {{ app()->getLocale() === 'bn' && $product->name_bn ? $product->name_bn : $product->name }}
                    </h1>

                    <div class="flex flex-wrap items-center justify-center gap-3 mt-4 text-xs">
                        <span class="px-3 py-1 bg-white/10 rounded-full font-mono text-purple-200 border border-white/10">Version {{ $product->version }}</span>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-emerald-300 border border-white/10 flex items-center gap-1">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Verified Script
                        </span>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-yellow-300 border border-white/10 flex items-center gap-1">
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-yellow-400"></i> {{ $product->avg_rating }} ({{ $product->review_count }} Reviews)
                        </span>
                    </div>

                    @if($product->demo_url)
                        <div class="mt-6">
                            <a href="{{ $product->demo_url }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-xs font-bold text-white transition-colors">
                                <i data-lucide="external-link" class="w-4 h-4 text-purple-400"></i>
                                <span>লাইভ ডেমো দেখুন (Live Preview)</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Description & Tabs -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-xs space-y-6">
                    <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">
                        প্রোডাক্টের বিস্তারিত বিবরণ
                    </h3>
                    <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed space-y-4">
                        <p class="font-medium text-gray-800">
                            {{ app()->getLocale() === 'bn' && $product->short_description_bn ? $product->short_description_bn : $product->short_description }}
                        </p>
                        <p>
                            {{ app()->getLocale() === 'bn' && $product->full_description_bn ? $product->full_description_bn : $product->full_description }}
                        </p>
                    </div>

                    <!-- Digital Delivery Guarantee Box -->
                    <div class="p-5 rounded-2xl bg-purple-50 border border-purple-100 flex items-start gap-3">
                        <div class="p-2 bg-purple-600 text-white rounded-xl">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-purple-900">STORPIFY নিশ্চিত ডেলিভারি গ্যারান্টি</h4>
                            <p class="text-xs text-purple-700 mt-0.5 leading-relaxed">
                                পেমেন্ট সম্পন্ন করার পর আপনার ড্যাশবোর্ডে ফাইল লিংক সরাসরি উন্মুক্ত হবে। যেকোনো সমস্যায় আমাদের সাপোর্ট টিম সার্বক্ষণিক প্রস্তুত।
                            </p>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="pt-6 border-t border-gray-100">
                        <h4 class="text-sm font-bold text-gray-900 mb-4">গ্রাহকদের রিভিউ ({{ $product->reviews->count() }})</h4>
                        @if($product->reviews->isEmpty())
                            <p class="text-xs text-gray-500">এখনও কোনো রিভিউ যুক্ত হয়নি।</p>
                        @else
                            <div class="space-y-3">
                                @foreach($product->reviews as $rev)
                                    <div class="p-4 rounded-2xl bg-gray-50 text-xs space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-gray-800">{{ $rev->user ? $rev->user->name : 'Customer' }}</span>
                                            <div class="flex items-center text-yellow-400">
                                                @for($i=0; $i<$rev->rating; $i++)
                                                    <i data-lucide="star" class="w-3 h-3 fill-yellow-400"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-gray-600">{{ $rev->review_text }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right: Pricing & Variation Checkout Selector (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-lg sticky top-24 space-y-6">
                    
                    <!-- Dynamic Price Display -->
                    <div class="flex items-baseline justify-between border-b border-gray-100 pb-4">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">মোট মূল্য</span>
                            <div class="text-3xl font-extrabold text-purple-700 mt-1">
                                ৳ <span x-text="currentPrice.toLocaleString()"></span>
                            </div>
                        </div>
                        @if($product->sale_price !== null && $product->sale_price < $product->regular_price)
                            <div class="text-right">
                                <span class="px-3 py-1 bg-pink-100 text-pink-700 font-extrabold text-xs rounded-full">
                                    -{{ $product->discount_percent }}% ছাড়
                                </span>
                                <div class="text-xs text-gray-400 line-through mt-1">
                                    {{ format_price($product->regular_price) }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Purchase Form -->
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Variation Selection (if product has variations) -->
                        @if($product->variants->count() > 0)
                            <div class="space-y-3">
                                <label class="text-xs font-bold text-gray-900 block">লাইসেন্স বা ভ্যারিয়েশন বেছে নিন:</label>
                                <div class="space-y-2">
                                    @foreach($product->variants as $v)
                                        <label @click="updatePrice({{ $v->id }})" 
                                            class="flex items-center justify-between p-3.5 rounded-2xl border cursor-pointer transition-all"
                                            :class="selectedVariantId == {{ $v->id }} ? 'border-purple-600 bg-purple-50/50 shadow-xs' : 'border-gray-200 hover:border-gray-300'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="variant_id" value="{{ $v->id }}" 
                                                    :checked="selectedVariantId == {{ $v->id }}"
                                                    class="text-purple-600 focus:ring-purple-500">
                                                <div>
                                                    <span class="text-xs font-bold text-gray-900 block">
                                                        {{ app()->getLocale() === 'bn' && $v->name_bn ? $v->name_bn : $v->name }}
                                                    </span>
                                                    @if($v->description)
                                                        <span class="text-[10px] text-gray-500">{{ $v->description }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="text-xs font-extrabold text-purple-700">
                                                {{ format_price($v->effective_price) }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit" name="buy_now" value="1" 
                                class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-purple-600/30 flex items-center justify-center gap-2 transition-transform hover:scale-[1.02]">
                                <i data-lucide="zap" class="w-4 h-4 text-yellow-300"></i>
                                <span>এখনই কিনুন (Buy Now)</span>
                            </button>

                            <button type="submit" 
                                class="w-full py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-bold text-sm rounded-2xl flex items-center justify-center gap-2 transition-colors">
                                <i data-lucide="shopping-bag" class="w-4 h-4 text-purple-600"></i>
                                <span>কার্টে যোগ করুন (Add to Cart)</span>
                            </button>
                        </div>

                    </form>

                    <!-- Trust Checklist -->
                    <div class="border-t border-gray-100 pt-4 space-y-2.5 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500"></i>
                            <span>পেমেন্ট সম্পন্ন হওয়ার সাথে সাথেই ডাউনলোড অ্যাক্সেস</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500"></i>
                            <span>বিকাশ, নগদ, রকেটে ১০০% নিরাপদ পেমেন্ট</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-500"></i>
                            <span>ভবিষ্যতের সকল ভার্সন আপডেট ডাউনলোড সুবিধা</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
@endsection
