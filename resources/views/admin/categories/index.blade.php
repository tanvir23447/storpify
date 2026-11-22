@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ addCatModal: false }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">ক্যাটাগরি ম্যানেজমেন্ট</h1>
            <p class="text-xs text-gray-500 mt-1">মেইন স্টোরের সকল প্রোডাক্ট ক্যাটাগরি ও আইকন সেটিংস</p>
        </div>
        <button @click="addCatModal = true" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md transition-transform hover:scale-105 flex items-center gap-1.5 self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>নতুন ক্যাটাগরি</span>
        </button>
    </div>

    <!-- Categories Grid / Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="py-3 px-6">আইকন</th>
                        <th class="py-3 px-6">ক্যাটাগরি নাম</th>
                        <th class="py-3 px-6">স্লাগ (Slug)</th>
                        <th class="py-3 px-6">প্রোডাক্ট সংখ্যা</th>
                        <th class="py-3 px-6">সর্ট অর্ডার</th>
                        <th class="py-3 px-6">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $cat)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-4 px-6">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                                    <i data-lucide="{{ $cat->icon ?? 'folder' }}" class="w-5 h-5"></i>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-gray-900 text-sm block">{{ $cat->name }}</span>
                                <span class="text-gray-400 text-xs">{{ $cat->name_bn }}</span>
                            </td>
                            <td class="py-4 px-6 font-mono text-gray-600">
                                /category/{{ $cat->slug }}
                            </td>
                            <td class="py-4 px-6 font-bold text-purple-700">
                                {{ $cat->products_count }} টি প্রোডাক্ট
                            </td>
                            <td class="py-4 px-6 text-gray-600 font-mono">
                                {{ $cat->sort_order }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($cat->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div x-show="addCatModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.away="addCatModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900">নতুন ক্যাটাগরি তৈরি করুন</h3>
                <button @click="addCatModal = false" class="p-1 text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">ক্যাটাগরি নাম (English) *</label>
                    <input type="text" name="name" required placeholder="WordPress"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">ক্যাটাগরি নাম (বাংলা)</label>
                    <input type="text" name="name_bn" placeholder="ওয়ার্ডপ্রেস (থিম ও প্লাগইন)"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">আইকন নাম (Lucide Icon, যেমন: code, palette, globe)</label>
                    <input type="text" name="icon" value="folder"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none font-mono">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">সর্ট অর্ডার (Sort Order)</label>
                    <input type="number" name="sort_order" value="0"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none font-mono">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">বিবরণ</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none"></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-md transition-colors">
                    ক্যাটাগরি সেভ করুন
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
