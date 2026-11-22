@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('customer.orders') }}" class="text-xs text-purple-600 font-bold hover:underline">← সকল অর্ডারে ফিরুন</a>
                <h1 class="text-2xl font-extrabold text-gray-900 mt-1">অর্ডার #{{ $order->order_number }}</h1>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                {{ $order->payment_status === 'paid' ? 'পেমেন্ট সম্পন্ন (Paid)' : 'ভেরিফিকেশন অপেক্ষমান (Pending)' }}
            </span>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-xs space-y-6">
            
            <!-- Items List -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-4">অর্ডারের আইটেমসমূহ</h3>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="py-4 flex items-center justify-between text-xs">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $item->product_name }}</h4>
                                @if($item->variant_name)
                                    <span class="text-purple-600 block text-xs mt-0.5">{{ $item->variant_name }}</span>
                                @endif
                                <span class="text-gray-400 mt-1 block">Qty: {{ $item->quantity }} • মূল্য: {{ format_price($item->price) }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-extrabold text-gray-900 text-sm block">{{ format_price($item->subtotal) }}</span>
                                @if($order->payment_status === 'paid')
                                    <a href="{{ route('customer.downloads') }}" class="text-[11px] text-purple-600 font-bold hover:underline mt-1 inline-block">
                                        ডাউনলোড পেজে যান →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="border-t border-gray-100 pt-4 space-y-2 text-xs text-gray-600 max-w-xs ml-auto">
                <div class="flex justify-between">
                    <span>সাবটোটাল:</span>
                    <span class="font-bold text-gray-900">{{ format_price($order->subtotal) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span>ডিসকাউন্ট:</span>
                        <span>-{{ format_price($order->discount_amount) }}</span>
                    </div>
                @endif
                <div class="border-t border-gray-100 pt-2 flex justify-between text-base font-extrabold text-gray-900">
                    <span>সর্বমোট:</span>
                    <span class="text-purple-700">{{ format_price($order->total_amount) }}</span>
                </div>
            </div>

            <!-- Payment details -->
            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-xs font-bold text-gray-700 uppercase mb-3">পেমেন্ট বিবরণ</h4>
                @php $pay = $order->payments->first(); @endphp
                @if($pay)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs bg-gray-50 p-4 rounded-2xl">
                        <div>
                            <span class="text-gray-400 block text-[10px]">পদ্ধতি</span>
                            <span class="font-bold uppercase">{{ $pay->method_code }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">ট্রানজেকশন আইডি</span>
                            <span class="font-mono font-bold">{{ $pay->transaction_id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">প্রেরক নম্বর</span>
                            <span class="font-mono font-bold">{{ $pay->sender_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px]">স্ট্যাটাস</span>
                            <span class="font-bold text-purple-700 uppercase">{{ $pay->status }}</span>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
