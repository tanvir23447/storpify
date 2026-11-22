@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]" x-data="{
    selectedMethodId: {{ $paymentMethods->first() ? $paymentMethods->first()->id : 'null' }},
    methods: {{ json_encode($paymentMethods) }},
    activeMethod: {{ json_encode($paymentMethods->first()) }},
    selectMethod(mId) {
        this.selectedMethodId = mId;
        this.activeMethod = this.methods.find(x => x.id == mId);
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-8">
            {{ __('messages.checkout.title') }}
        </h1>

        <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Customer Information & Payment (7 Cols) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- 1. Customer Information Card -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-xs space-y-4">
                        <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-purple-600"></i>
                            <span>{{ __('messages.checkout.customer_info') }}</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-gray-700 mb-1.5">{{ __('messages.checkout.name') }} *</label>
                                <input type="text" name="customer_name" required value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none transition-colors">
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1.5">{{ __('messages.checkout.email') }} *</label>
                                <input type="email" name="customer_email" required value="{{ auth()->check() ? auth()->user()->email : old('customer_email') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none transition-colors">
                                <span class="text-[10px] text-gray-400 mt-1 block">এই ইমেইলে অর্ডার ও ডাউনলোড রিসিট পাঠানো হবে</span>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1.5">{{ __('messages.checkout.phone') }} *</label>
                                <input type="text" name="customer_phone" required value="{{ auth()->check() ? auth()->user()->phone : old('customer_phone') }}" placeholder="017XXXXXXXX"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Dynamic Payment Method Selection -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-xs space-y-6">
                        <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i data-lucide="wallet" class="w-4 h-4 text-purple-600"></i>
                            <span>{{ __('messages.checkout.payment_method') }}</span>
                        </h3>

                        <!-- Methods Radios -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach($paymentMethods as $pm)
                                <label @click="selectMethod({{ $pm->id }})" 
                                    class="p-4 rounded-2xl border cursor-pointer text-center transition-all flex flex-col items-center justify-center space-y-2"
                                    :class="selectedMethodId == {{ $pm->id }} ? 'border-purple-600 bg-purple-50 text-purple-900 font-bold shadow-xs' : 'border-gray-200 hover:border-gray-300 text-gray-700'">
                                    <input type="radio" name="payment_method_id" value="{{ $pm->id }}" 
                                        :checked="selectedMethodId == {{ $pm->id }}" class="sr-only">
                                    <div class="text-sm font-bold">
                                        {{ app()->getLocale() === 'bn' && $pm->name_bn ? $pm->name_bn : $pm->name }}
                                    </div>
                                    <span class="text-[10px] uppercase font-semibold text-gray-400">
                                        {{ $pm->account_type }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Dynamic Account Numbers Box -->
                        <div class="p-5 rounded-2xl bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-100 space-y-3">
                            <div class="flex items-center gap-2 text-purple-900 font-bold text-xs">
                                <i data-lucide="info" class="w-4 h-4 text-purple-600"></i>
                                <span>পেমেন্ট পাঠানোর অফিশিয়াল নম্বর:</span>
                            </div>

                            <!-- Live Display of active payment method numbers -->
                            <template x-if="activeMethod && activeMethod.account_numbers">
                                <div class="space-y-2">
                                    <template x-for="(acc, idx) in activeMethod.account_numbers" :key="idx">
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-purple-200 text-xs shadow-xs">
                                            <div>
                                                <span class="font-extrabold text-gray-900 text-sm tracking-wider font-mono" x-text="acc.number"></span>
                                                <span class="text-[10px] text-gray-500 ml-2" x-text="acc.type || ''"></span>
                                            </div>
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-[10px] font-bold rounded-md" x-text="acc.note || 'Active'"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="activeMethod && activeMethod.instructions_bn">
                                <div class="pt-2 text-xs text-purple-950 font-medium whitespace-pre-line leading-relaxed" x-text="activeMethod.instructions_bn"></div>
                            </template>
                        </div>

                        <!-- 3. Transaction Details Form Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1.5">
                                    {{ __('messages.checkout.sender_number') }} *
                                </label>
                                <input type="text" name="sender_number" required placeholder="যেমন: 017XXXXXXXX"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1.5">
                                    {{ __('messages.checkout.transaction_id') }} *
                                </label>
                                <input type="text" name="transaction_id" required placeholder="যেমন: 9X24519A"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none uppercase font-mono">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-gray-700 mb-1.5">
                                    {{ __('messages.checkout.payment_proof') }}
                                </label>
                                <input type="file" name="payment_proof" accept="image/*"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                <span class="text-[10px] text-gray-400 mt-1 block">পেমেন্টের স্ক্রিনশট থাকলে দ্রুত যাচাইয়ে সাহায্য করবে (ঐচ্ছিক)</span>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Right: Order Summary (5 Cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-lg sticky top-24 space-y-6">
                        <h3 class="text-base font-extrabold text-gray-900 border-b border-gray-100 pb-3">
                            {{ __('messages.checkout.order_summary') }}
                        </h3>

                        <!-- Items List -->
                        <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto pr-1">
                            @foreach($cart->items as $item)
                                <div class="py-3 flex items-center justify-between text-xs">
                                    <div class="max-w-[180px]">
                                        <h5 class="font-bold text-gray-900 truncate">{{ $item->product->name }}</h5>
                                        @if($item->variant)
                                            <span class="text-[10px] text-purple-600 block">{{ $item->variant->name }}</span>
                                        @endif
                                        <span class="text-[10px] text-gray-400">Qty: {{ $item->quantity }}</span>
                                    </div>
                                    <span class="font-extrabold text-gray-900">
                                        {{ format_price($item->subtotal) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Calculations -->
                        <div class="border-t border-gray-100 pt-4 space-y-2.5 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>সাবটোটাল</span>
                                <span class="font-bold text-gray-900">{{ format_price($cart->subtotal) }}</span>
                            </div>

                            @if($discount > 0)
                                <div class="flex justify-between text-emerald-600 font-bold">
                                    <span>কুপন ছাড় ({{ $appliedCoupon ? $appliedCoupon->code : '' }})</span>
                                    <span>-{{ format_price($discount) }}</span>
                                </div>
                            @endif

                            <div class="border-t border-gray-100 pt-3 flex justify-between text-lg font-extrabold text-gray-900">
                                <span>পরিশোধযোগ্য টাকা</span>
                                <span class="text-purple-700">{{ format_price($total) }}</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                            class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-2xl shadow-xl shadow-purple-600/30 flex items-center justify-center gap-2 transition-transform hover:scale-[1.02]">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-300"></i>
                            <span>{{ __('messages.checkout.place_order') }}</span>
                        </button>

                        <div class="text-center pt-2">
                            <p class="text-[11px] text-gray-400 flex items-center justify-center gap-1">
                                <i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-600"></i>
                                <span>{{ __('messages.checkout.secure_badge') }}</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
