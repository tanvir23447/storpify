@extends('layouts.app')

@section('content')
<div class="py-12 bg-[#F8F9FD]" x-data="{
    selectedMethodId: {{ $paymentMethods->first() ? $paymentMethods->first()->id : 'null' }},
    methods: {{ json_encode($paymentMethods) }},
    activeMethod: {{ json_encode($paymentMethods->first()) }},
    selectMethod(mId) {
        this.selectedMethodId = mId;
        this.activeMethod = this.methods.find(x => x.id == mId);
    }
}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-xl mx-auto mb-8">
            <span class="px-3 py-1 bg-yellow-400/20 text-yellow-800 text-xs font-bold rounded-full border border-yellow-400/30">
                👑 মেম্বারশিপ চেকআউট
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">
                সাবস্ক্রিপশন সম্পন্ন করুন
            </h1>
        </div>

        <form action="{{ route('subscription.process') }}" method="POST">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                
                <!-- Left: Payment details (7 cols) -->
                <div class="md:col-span-7 space-y-6">
                    
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-xs space-y-5">
                        <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <i data-lucide="wallet" class="w-4 h-4 text-purple-600"></i>
                            <span>পেমেন্ট মেথড বেছে নিন</span>
                        </h3>

                        <!-- Methods Radios -->
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($paymentMethods as $pm)
                                <label @click="selectMethod({{ $pm->id }})" 
                                    class="p-3.5 rounded-2xl border cursor-pointer text-center transition-all flex flex-col items-center justify-center space-y-1"
                                    :class="selectedMethodId == {{ $pm->id }} ? 'border-purple-600 bg-purple-50 text-purple-900 font-bold shadow-xs' : 'border-gray-200 text-gray-700'">
                                    <input type="radio" name="payment_method_id" value="{{ $pm->id }}" :checked="selectedMethodId == {{ $pm->id }}" class="sr-only">
                                    <span class="text-xs font-bold">{{ $pm->name }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Live Account Numbers Box -->
                        <div class="p-4 rounded-2xl bg-purple-50 border border-purple-100 space-y-2 text-xs">
                            <span class="font-bold text-purple-900 block">টাকা পাঠানোর নির্ধারিত নম্বর:</span>
                            <template x-if="activeMethod && activeMethod.account_numbers">
                                <div class="space-y-1.5">
                                    <template x-for="(acc, idx) in activeMethod.account_numbers" :key="idx">
                                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-white border border-purple-200">
                                            <span class="font-bold font-mono text-gray-900 text-sm" x-text="acc.number"></span>
                                            <span class="text-[10px] text-purple-700 font-semibold" x-text="acc.type || ''"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <p class="text-[11px] text-purple-800 pt-1 leading-relaxed">
                                উপরের নম্বরে <span class="font-bold">{{ format_price($plan->effective_price) }}</span> টাকা সেন্ড/পেমেন্ট করুন এবং নিচের বক্সে প্রেরক নম্বর ও TrxID দিন।
                            </p>
                        </div>

                        <!-- Input fields -->
                        <div class="space-y-3 text-xs">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">আপনার প্রেরক নম্বর (Sender Number) *</label>
                                <input type="text" name="sender_number" required placeholder="017XXXXXXXX"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">ট্রানজেকশন আইডি (TrxID) *</label>
                                <input type="text" name="transaction_id" required placeholder="যেমন: 9X24519A"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none uppercase font-mono">
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Right: Plan Summary (5 cols) -->
                <div class="md:col-span-5 space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-lg space-y-5">
                        <h3 class="text-base font-extrabold text-gray-900 border-b border-gray-100 pb-3">
                            প্ল্যান বিবরণী
                        </h3>

                        <div class="space-y-2">
                            <span class="text-xs text-purple-600 font-bold uppercase tracking-wider">নির্বাচিত প্ল্যান</span>
                            <h4 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $plan->duration_days }} দিনের আনলিমিটেড লাইব্রেরি অ্যাকসেস</p>
                        </div>

                        <div class="border-t border-gray-100 pt-4 flex items-baseline justify-between">
                            <span class="text-sm font-bold text-gray-600">মোট প্রদেয় টাকা:</span>
                            <span class="text-2xl font-extrabold text-purple-700">{{ format_price($plan->effective_price) }}</span>
                        </div>

                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-2xl shadow-xl shadow-purple-600/30 flex items-center justify-center gap-2 transition-transform hover:scale-[1.02]">
                            <i data-lucide="crown" class="w-4 h-4 text-yellow-300"></i>
                            <span>সাবস্ক্রিপশন নিশ্চিত করুন</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
