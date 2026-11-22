@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">অর্ডার সমূহ</h1>
            <p class="text-xs text-gray-500 mt-1">কাস্টমারদের সমস্ত ডিজিটাল ক্রয়াদেশ ও পেমেন্ট হিস্ট্রি</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-3 w-full sm:w-auto">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="অর্ডার বা কাস্টমার খুঁজুন..." 
                    class="px-4 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:border-purple-600">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-gray-200 text-xs bg-gray-50">
                    <option value="">সকল পেমেন্ট স্ট্যাটাস</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="py-3 px-6">অর্ডার নং</th>
                        <th class="py-3 px-6">গ্রাহক</th>
                        <th class="py-3 px-6">পেমেন্ট মেথড</th>
                        <th class="py-3 px-6">আইটেম</th>
                        <th class="py-3 px-6">মূল্য</th>
                        <th class="py-3 px-6">পেমেন্ট স্ট্যাটাস</th>
                        <th class="py-3 px-6 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $o)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-4 px-6 font-bold font-mono text-purple-700">
                                #{{ $o->order_number }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-gray-900 block">{{ $o->customer_name }}</span>
                                <span class="text-[10px] text-gray-400 block">{{ $o->customer_email }}</span>
                                <span class="text-[10px] text-gray-400 block">{{ $o->customer_phone }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @php $pay = $o->payments->first(); @endphp
                                <span class="font-bold uppercase text-gray-700 block">{{ $pay ? $pay->method_code : 'Manual' }}</span>
                                @if($pay && $pay->transaction_id)
                                    <span class="text-[10px] font-mono text-purple-600 font-bold block">{{ $pay->transaction_id }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-600">
                                {{ $o->items->count() }} টি
                            </td>
                            <td class="py-4 px-6 font-extrabold text-gray-900">
                                {{ format_price($o->total_amount) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $o->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($o->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.orders.show', $o->id) }}" class="px-3 py-1.5 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-xs">
                                    বিস্তারিত ও ভেরিফাই →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">কোনো অর্ডার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
