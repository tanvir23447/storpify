@extends('layouts.admin', ['title' => 'STORPIFY - কাস্টমার তালিকা'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">নিবন্ধিত কাস্টমার তালিকা</h1>
            <p class="text-xs text-gray-500 mt-1">STORPIFY প্ল্যাটফর্মের সকল নিবন্ধিত গ্রাহক ও তাদের মোট অর্ডারের বিবরণ।</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">গ্রাহকের নাম ও ইমেইল</th>
                        <th class="px-5 py-4">ফোন নম্বর</th>
                        <th class="px-5 py-4">মোট অর্ডার</th>
                        <th class="px-5 py-4">স্ট্যাটাস</th>
                        <th class="px-5 py-4">যুক্ত হওয়ার তারিখ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $customer->name }}</p>
                                        <p class="text-gray-500 text-[11px] font-mono">{{ $customer->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-mono text-gray-700">
                                {{ $customer->phone ?: 'প্রদান করেনি' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $customer->orders_count }} টি অর্ডার
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($customer->status === 'active')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">সক্রিয়</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">স্থগিত</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $customer->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">কোন কাস্টমার অ্যাকাউন্ট পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
