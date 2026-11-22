@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-purple-600 font-bold hover:underline">← সকল অর্ডারে ফিরুন</a>
            <h1 class="text-xl font-extrabold text-gray-900 mt-1">অর্ডার নিরীক্ষা #{{ $order->order_number }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                {{ $order->payment_status === 'paid' ? 'Paid & Verified' : 'Pending Verification' }}
            </span>
        </div>
    </div>

    <!-- Payment Verification Card -->
    @php $payment = $order->payments->first(); @endphp
    @if($payment)
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-xs space-y-6">
            <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-5 h-5 text-purple-600"></i>
                    <span>পেমেন্ট ভেরিফিকেশন ও ট্রানজেকশন তথ্য</span>
                </span>
                <span class="text-xs uppercase font-bold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-md">
                    {{ $payment->method_code }}
                </span>
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs bg-[#F8F9FD] p-5 rounded-2xl border border-gray-100">
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">ট্রানজেকশন আইডি (TrxID)</span>
                    <span class="font-mono font-extrabold text-purple-800 text-sm select-all">{{ $payment->transaction_id ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">প্রেরক নম্বর (Sender)</span>
                    <span class="font-mono font-bold text-gray-900 text-sm select-all">{{ $payment->sender_number ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">প্রাপক নম্বর (Merchant/Agent)</span>
                    <span class="font-mono font-bold text-gray-900">{{ $payment->receiver_number ?: 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[10px] uppercase font-bold">টাকার পরিমাণ</span>
                    <span class="font-extrabold text-emerald-600 text-sm">{{ format_price($payment->amount) }}</span>
                </div>
            </div>

            @if($payment->proof_file)
                <div>
                    <span class="text-xs font-bold text-gray-700 block mb-2">গ্রাহকের আপলোডকৃত পেমেন্ট প্রুফ (Screenshot):</span>
                    <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank" class="inline-block p-2 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100 transition-colors">
                        <img src="{{ asset('storage/' . $payment->proof_file) }}" alt="Payment Proof" class="max-h-48 rounded-xl object-contain">
                    </a>
                </div>
            @endif

            <!-- Verification Action Form -->
            @if(auth()->user()->hasPermission('payment.verify'))
                <div class="border-t border-gray-100 pt-6">
                    <span class="text-xs font-bold text-gray-700 block mb-3">অ্যাডমিন অ্যাকশন নিন:</span>
                    <div class="flex flex-wrap items-center gap-4">
                        
                        <!-- Approve Form -->
                        @if($payment->status !== 'verified')
                            <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" onsubmit="return confirm('আপনি কি বিকাশ/নগদে টাকা প্রাপ্তি নিশ্চিত করেছেন?')">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                    <span>পেমেন্ট অনুমোদন করুন (Approve & Grant Download)</span>
                                </button>
                            </form>
                        @else
                            <div class="text-xs font-bold text-emerald-700 bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-200 flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                                <span>এই পেমেন্টটি ইতিমধ্যে অনুমোদিত এবং কাস্টমারকে ডাউনলোড এক্সেস দেওয়া হয়েছে।</span>
                            </div>
                        @endif

                        <!-- Reject Form -->
                        @if($payment->status === 'pending')
                            <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="flex items-center gap-2" onsubmit="return confirm('পেমেন্টটি বাতিল করতে চান?')">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <input type="text" name="rejection_reason" placeholder="বাতিলের কারণ লিখুন..." required class="px-3 py-2.5 rounded-xl border border-gray-200 text-xs outline-none">
                                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl transition-colors">
                                    বাতিল করুন
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            @endif

        </div>
    @endif

    <!-- Order Items & Customer Card -->
    <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-xs space-y-6">
        <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">অর্ডারের আইটেম ও কাস্টমার তথ্য</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <span class="text-gray-400 block text-[10px] uppercase font-bold">গ্রাহকের নাম</span>
                <span class="font-bold text-gray-900 text-sm">{{ $order->customer_name }}</span>
            </div>
            <div>
                <span class="text-gray-400 block text-[10px] uppercase font-bold">ইমেইল</span>
                <span class="font-bold text-gray-900">{{ $order->customer_email }}</span>
            </div>
            <div>
                <span class="text-gray-400 block text-[10px] uppercase font-bold">মোবাইল</span>
                <span class="font-bold text-gray-900">{{ $order->customer_phone }}</span>
            </div>
            <div>
                <span class="text-gray-400 block text-[10px] uppercase font-bold">অর্ডার সময়</span>
                <span class="font-bold text-gray-900">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <h4 class="text-xs font-bold text-gray-700 mb-2">কেনা প্রোডাক্ট:</h4>
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-gray-900 text-sm block">{{ $item->product_name }}</span>
                            @if($item->variant_name)
                                <span class="text-purple-600 text-xs block">{{ $item->variant_name }}</span>
                            @endif
                        </div>
                        <span class="font-extrabold text-gray-900">{{ format_price($item->subtotal) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
