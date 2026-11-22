@extends('layouts.subscription-admin', ['title' => 'STORPIFY - সাবস্ক্রিপশন অর্ডার ও পেমেন্ট'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">সাবস্ক্রিপশন অর্ডার ও পেমেন্ট ট্রানজেকশন</h1>
            <p class="text-xs text-gray-500 mt-1">মেম্বারশিপ সাবস্ক্রিপশনের জন্য গৃহীত bKash, Nagad ও অন্যান্য পেমেন্টের বিস্তারিত।</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">অর্ডার নং</th>
                        <th class="px-5 py-4">গ্রাহক</th>
                        <th class="px-5 py-4">প্ল্যান</th>
                        <th class="px-5 py-4">পেমেন্ট মেথড</th>
                        <th class="px-5 py-4">ট্রানজেকশন আইডি (TrxID)</th>
                        <th class="px-5 py-4">টাকার পরিমাণ</th>
                        <th class="px-5 py-4">তারিখ ও সময়</th>
                        <th class="px-5 py-4">পেমেন্ট স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($orders as $order)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-purple-700">
                                #{{ $order->order_number }}
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-900">
                                {{ $order->user ? $order->user->name : 'N/A' }}
                                <span class="block text-[10px] text-gray-500 font-mono font-normal">{{ $order->user ? $order->user->email : '' }}</span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-800">
                                {{ $order->plan ? ($order->plan->name_bn ?: $order->plan->name) : 'N/A' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-pink-50 text-pink-700">
                                    {{ $order->method_code }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-gray-800 select-all">
                                {{ $order->transaction_id }}
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-gray-900">
                                ৳ {{ number_format($order->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-gray-400">কোন সাবস্ক্রিপশন অর্ডার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
