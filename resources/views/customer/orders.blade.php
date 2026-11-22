@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">আমার অর্ডার সমূহ</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">আপনার সকল অতীত ও বর্তমান অর্ডারের তালিকা</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-purple-600 hover:underline">
                ← ড্যাশবোর্ডে ফিরুন
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
            @if($orders->isEmpty())
                <div class="p-12 text-center text-xs text-gray-500">আপনার কোনো অর্ডার পাওয়া যায়নি।</div>
            @else
                <div class="p-6 border-b border-gray-100 hidden sm:grid sm:grid-cols-12 text-xs font-bold text-gray-400 uppercase">
                    <span class="sm:col-span-3">অর্ডার নম্বর</span>
                    <span class="sm:col-span-3">তারিখ</span>
                    <span class="sm:col-span-2 text-center">স্ট্যাটাস</span>
                    <span class="sm:col-span-2 text-right">মূল্য</span>
                    <span class="sm:col-span-2 text-right">অ্যাকশন</span>
                </div>

                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($orders as $o)
                        <div class="p-5 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center hover:bg-gray-50 transition-colors">
                            <div class="sm:col-span-3">
                                <span class="font-bold font-mono text-purple-700 text-sm">#{{ $o->order_number }}</span>
                                <span class="text-gray-400 text-[10px] block">{{ $o->items->count() }} টি আইটেম</span>
                            </div>

                            <div class="sm:col-span-3 text-gray-600">
                                {{ $o->created_at->format('d M Y, h:i A') }}
                            </div>

                            <div class="sm:col-span-2 sm:text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold {{ $o->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $o->payment_status === 'paid' ? 'Paid' : 'Pending' }}
                                </span>
                            </div>

                            <div class="sm:col-span-2 sm:text-right font-extrabold text-gray-900 text-sm">
                                {{ format_price($o->total_amount) }}
                            </div>

                            <div class="sm:col-span-2 sm:text-right">
                                <a href="{{ route('customer.orders.show', $o->order_number) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-purple-100 text-purple-700 font-bold rounded-lg text-xs">
                                    বিস্তারিত →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
