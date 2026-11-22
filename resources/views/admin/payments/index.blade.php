@extends('layouts.admin', ['title' => 'STORPIFY - পেমেন্ট ভেরিফিকেশন ও ট্রানজেকশন'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">পেমেন্ট ভেরিফিকেশন তালিকা</h1>
            <p class="text-xs text-gray-500 mt-1">bKash, Nagad, Rocket সহ সকল পেমেন্টের ট্রানজেকশন ও ভেরিফিকেশন স্ট্যাটাস।</p>
        </div>
    </div>

    <!-- Payments Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">অর্ডার নং</th>
                        <th class="px-5 py-4">মেথড</th>
                        <th class="px-5 py-4">ট্রানজেকশন আইডি (TrxID)</th>
                        <th class="px-5 py-4">প্রেরকের নম্বর</th>
                        <th class="px-5 py-4">টাকার পরিমাণ</th>
                        <th class="px-5 py-4">তারিখ ও সময়</th>
                        <th class="px-5 py-4">স্ট্যাটাস</th>
                        <th class="px-5 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-purple-50/30 transition-colors">
                            <td class="px-5 py-4 font-bold text-purple-700">
                                @if($payment->order)
                                    <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="hover:underline">
                                        #{{ $payment->order->order_number }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase {{ $payment->method_code === 'bkash' ? 'bg-pink-100 text-pink-700' : ($payment->method_code === 'nagad' ? 'bg-orange-100 text-orange-700' : ($payment->method_code === 'rocket' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $payment->method_code }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-gray-900 tracking-wide select-all">
                                {{ $payment->transaction_id }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 font-mono">
                                {{ $payment->sender_number }}
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-900">
                                ৳ {{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $payment->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($payment->status === 'verified')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        ভেরিফাইড
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 animate-pulse">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        অপেক্ষমান
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                        বাতিল
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($payment->order)
                                    <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-800 font-bold hover:bg-purple-600 hover:text-white transition-all text-xs">
                                        যাচাই করুন
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                কোন পেমেন্ট রেকর্ড পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
