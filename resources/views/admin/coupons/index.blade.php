@extends('layouts.admin', ['title' => 'STORPIFY - ডিসকাউন্ট কুপন ম্যানেজার'])

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">ডিসকাউন্ট কুপন ম্যানেজার</h1>
            <p class="text-xs text-gray-500 mt-1">প্রোমোশনাল কুপন কোড, পার্সেন্টেজ অথবা নির্দিষ্ট টাকার ছাড় নির্ধারণ করুন।</p>
        </div>
        <button @click="createModalOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            নতুন কুপন কোড যোগ করুন
        </button>
    </div>

    <!-- Coupons Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">কুপন কোড</th>
                        <th class="px-5 py-4">ছাড়ের পরিমাণ</th>
                        <th class="px-5 py-4">নূন্যতম কেনাকাটা</th>
                        <th class="px-5 py-4">ব্যবহারের সংখ্যা</th>
                        <th class="px-5 py-4">মেয়াদ</th>
                        <th class="px-5 py-4">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4 font-mono font-black text-purple-700 tracking-wider">
                                {{ $coupon->code }}
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-900">
                                @if($coupon->discount_type === 'percentage')
                                    {{ $coupon->discount_value }}% ছাড়
                                @else
                                    ৳ {{ number_format($coupon->discount_value, 2) }} ফ্ল্যাট ছাড়
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-600">
                                {{ $coupon->min_spend ? '৳ ' . number_format($coupon->min_spend, 2) : 'কোন শর্ত নেই' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-0.5 rounded bg-gray-100 font-mono text-[11px] text-gray-800">
                                    {{ $coupon->usage_count }} / {{ $coupon->usage_limit ?: '∞' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'আজীবন সক্রিয়' }}
                            </td>
                            <td class="px-5 py-4">
                                @if($coupon->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">সক্রিয়</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">কোন কুপন পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

    <!-- Create Coupon Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="createModalOpen = false"></div>

            <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl z-10 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-black text-gray-900 text-base">নতুন ডিসকাউন্ট কুপন তৈরি</h3>
                    <button @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">কুপন কোড (যেমন: STORPIFY20)</label>
                        <input type="text" name="code" required placeholder="STORPIFY20"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-mono uppercase font-black tracking-wider focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ছাড়ের প্রকার</label>
                            <select name="discount_type" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                <option value="percentage">শতাংশ (%)</option>
                                <option value="fixed">নির্দিষ্ট টাকা (৳)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ছাড়ের মান (Value)</label>
                            <input type="number" step="0.01" name="discount_value" required placeholder="যেমন: 20 বা 100"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">নূন্যতম অর্ডার খরচ (৳)</label>
                            <input type="number" step="0.01" name="min_spend" placeholder="ঐচ্ছিক"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">সর্বোচ্চ ব্যবহার সীমা</label>
                            <input type="number" name="usage_limit" placeholder="অসীম হলে খালি রাখুন"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">মেয়াদ শেষ হওয়ার তারিখ</label>
                        <input type="date" name="expires_at"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs">
                            কুপন তৈরি করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
