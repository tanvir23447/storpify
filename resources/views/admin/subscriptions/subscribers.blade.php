@extends('layouts.subscription-admin', ['title' => 'STORPIFY - সাবস্ক্রাইবার তালিকা'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">সাবস্ক্রাইবার মেম্বার তালিকা</h1>
            <p class="text-xs text-gray-500 mt-1">প্ল্যাটফর্মের সকল সক্রিয় ও মেয়াদোত্তীর্ণ মেম্বারদের তথ্য ও ডাউনলোড স্ট্যাটাস।</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">গ্রাহকের নাম ও ইমেইল</th>
                        <th class="px-5 py-4">মেম্বারশিপ প্ল্যান</th>
                        <th class="px-5 py-4">মেয়াদের শুরু</th>
                        <th class="px-5 py-4">মেয়াদ শেষ</th>
                        <th class="px-5 py-4">মোট ডাউনলোড</th>
                        <th class="px-5 py-4">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($subscribers as $sub)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($sub->user ? $sub->user->name : 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $sub->user ? $sub->user->name : 'N/A' }}</p>
                                        <p class="text-gray-500 text-[11px] font-mono">{{ $sub->user ? $sub->user->email : '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-bold text-purple-700">
                                {{ $sub->plan ? ($sub->plan->name_bn ?: $sub->plan->name) : 'N/A' }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 font-mono">
                                {{ $sub->starts_at ? $sub->starts_at->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-5 py-4 font-mono font-bold {{ $sub->isActive() ? 'text-gray-900' : 'text-rose-600' }}">
                                {{ $sub->ends_at ? $sub->ends_at->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded bg-purple-50 text-purple-700 font-bold font-mono">
                                    {{ $sub->total_downloads }} টি
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($sub->isActive())
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        সক্রিয়
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        মেয়াদোত্তীর্ণ
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">কোন সাবস্ক্রাইবার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
