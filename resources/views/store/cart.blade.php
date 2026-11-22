@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-8">
            {{ __('messages.cart.title') }}
        </h1>

        @if($cart->items->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-xs max-w-lg mx-auto space-y-4">
                <div class="w-20 h-20 rounded-full bg-purple-50 text-purple-600 mx-auto flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-10 h-10"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ __('messages.cart.empty') }}</h3>
                <p class="text-xs text-gray-500">আপনার কার্টে বর্তমানে কোনো প্রোডাক্ট নেই। আমাদের শপ থেকে আপনার পছন্দের রিসোর্স বেছে নিন।</p>
                <a href="{{ route('shop') }}" class="inline-block px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-transform hover:scale-105">
                    {{ __('messages.cart.continue_shopping') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Cart Items List (8 Cols) -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
                        <div class="p-6 border-b border-gray-100 hidden sm:grid sm:grid-cols-12 text-xs font-bold text-gray-400 uppercase">
                            <span class="sm:col-span-6">প্রোডাক্ট</span>
                            <span class="sm:col-span-2 text-center">মূল্য</span>
                            <span class="sm:col-span-2 text-center">পরিমাণ</span>
                            <span class="sm:col-span-2 text-right">মোট</span>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach($cart->items as $item)
                                <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                                    
                                    <!-- Product Info -->
                                    <div class="sm:col-span-6 flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 border border-purple-100">
                                            <i data-lucide="file-code" class="w-7 h-7"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">
                                                <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-purple-600">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h4>
                                            @if($item->variant)
                                                <span class="inline-block text-[11px] font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full mt-1">
                                                    {{ $item->variant->name }}
                                                </span>
                                            @endif
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 font-semibold">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    <span>{{ __('messages.cart.remove') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="sm:col-span-2 text-left sm:text-center text-xs sm:text-sm font-bold text-gray-700">
                                        {{ format_price($item->price) }}
                                    </div>

                                    <!-- Qty -->
                                    <div class="sm:col-span-2 text-left sm:text-center text-xs font-semibold text-gray-600">
                                        {{ $item->quantity }}
                                    </div>

                                    <!-- Total -->
                                    <div class="sm:col-span-2 text-left sm:text-right text-sm font-extrabold text-purple-700">
                                        {{ format_price($item->subtotal) }}
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Coupon Code Box -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="w-full sm:w-auto">
                            <h4 class="text-xs font-bold text-gray-900 mb-1">কুপন ডিসকাউন্ট</h4>
                            <p class="text-[11px] text-gray-500">আপনার কাছে কুপন কোড থাকলে এখানে প্রয়োগ করুন</p>
                        </div>
                        
                        @if($appliedCoupon)
                            <div class="flex items-center gap-3 bg-purple-50 border border-purple-200 px-4 py-2 rounded-2xl">
                                <div class="text-xs">
                                    <span class="text-gray-500">কুপন:</span>
                                    <span class="font-bold text-purple-700 font-mono">{{ $appliedCoupon->code }}</span>
                                    <span class="text-emerald-600 font-bold ml-1">(-{{ format_price($discount) }})</span>
                                </div>
                                <form action="{{ route('cart.remove-coupon') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('cart.apply-coupon') }}" method="POST" class="flex items-center gap-2 w-full sm:w-auto">
                                @csrf
                                <input type="text" name="code" required placeholder="কুপন কোড (যেমন: STORPIFY10)" 
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-mono uppercase focus:outline-none focus:border-purple-600">
                                <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-colors shrink-0">
                                    প্রয়োগ করুন
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Right: Summary (4 Cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-lg space-y-4">
                        <h3 class="text-base font-extrabold text-gray-900 border-b border-gray-100 pb-3">
                            অর্ডার সামারি
                        </h3>

                        <div class="space-y-3 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>সাবটোটাল</span>
                                <span class="font-bold text-gray-900">{{ format_price($cart->subtotal) }}</span>
                            </div>

                            @if($discount > 0)
                                <div class="flex justify-between text-emerald-600 font-bold">
                                    <span>কুপন ছাড়</span>
                                    <span>-{{ format_price($discount) }}</span>
                                </div>
                            @endif

                            <div class="border-t border-gray-100 pt-3 flex justify-between text-base font-extrabold text-gray-900">
                                <span>সর্বমোট মূল্য</span>
                                <span class="text-purple-700">{{ format_price($total) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}" 
                            class="block w-full py-4 text-center bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-purple-600/30 transition-transform hover:scale-[1.02]">
                            {{ __('messages.cart.proceed_to_checkout') }} →
                        </a>

                        <div class="text-center pt-2">
                            <a href="{{ route('shop') }}" class="text-xs text-purple-600 hover:underline font-semibold">
                                ← কেনাকাটা চালিয়ে যান
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>
@endsection
