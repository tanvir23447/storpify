@extends('layouts.subscription-admin', ['title' => 'STORPIFY - সাবস্ক্রিপশন ক্যাটাগরি'])

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">সাবস্ক্রিপশন ক্যাটাগরি ম্যানেজমেন্ট</h1>
            <p class="text-xs text-gray-500 mt-1">সাবস্ক্রিপশন লাইব্রেরির ফাইলগুলোকে বিভিন্ন ক্যাটাগরিতে সুবিন্যস্ত করুন (মেইন স্টোর থেকে সম্পূর্ণ স্বতন্ত্র)।</p>
        </div>
        <button @click="createModalOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            নতুন ক্যাটাগরি তৈরি
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 flex flex-col justify-between hover:border-purple-300 transition-colors">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            <i data-lucide="{{ $category->icon ?: 'folder' }}" class="w-6 h-6"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800">
                            {{ $category->files_count }} টি ফাইল
                        </span>
                    </div>

                    <div>
                        <h3 class="font-black text-gray-900 text-base">{{ $category->name_bn ?: $category->name }}</h3>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">/{{ $category->slug }}</p>
                        <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ $category->description_bn ?: ($category->description ?: 'কোন বিবরণ নেই') }}</p>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-gray-400 font-mono text-[11px]">ক্রম: {{ $category->sort_order }}</span>
                    @if($category->is_active)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">সক্রিয়</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">নিষ্ক্রিয়</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                কোন সাবস্ক্রিপশন ক্যাটাগরি তৈরি হয়নি।
            </div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="createModalOpen = false"></div>

            <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl z-10 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-black text-gray-900 text-base">নতুন সাবস্ক্রিপশন ক্যাটাগরি</h3>
                    <button @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('admin.subscriptions.categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ক্যাটাগরির নাম (English)</label>
                        <input type="text" name="name" required placeholder="WordPress Themes" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ক্যাটাগরির নাম (বাংলা)</label>
                        <input type="text" name="name_bn" placeholder="ওয়ার্ডপ্রেস থিম" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">আইকন নাম (Lucide)</label>
                            <input type="text" name="icon" value="folder" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ক্রম (Sort Order)</label>
                            <input type="number" name="sort_order" value="1" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">বিবরণ</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs">
                            ক্যাটাগরি তৈরি করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
