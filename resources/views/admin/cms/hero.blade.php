@extends('layouts.admin', ['title' => 'STORPIFY - হিরো স্লাইডার ম্যানেজমেন্ট'])

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">হিরো স্লাইডার ম্যানেজমেন্ট</h1>
            <p class="text-xs text-gray-500 mt-1">হোমপেজের মূল আকর্ষণ ব্যানার স্লাইড তৈরি, সম্পাদনা ও সাজান।</p>
        </div>
        <button @click="createModalOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            নতুন স্লাইড তৈরি করুন
        </button>
    </div>

    <!-- Slides Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($slides as $slide)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Banner Visual Mockup -->
                    <div class="p-6 text-white relative" style="background-color: {{ $slide->bg_color ?: '#17152B' }}">
                        <div class="absolute top-4 right-4 flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/20 text-white backdrop-blur-xs">
                                সিরিয়াল: {{ $slide->sort_order }}
                            </span>
                            @if($slide->is_active)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white">সক্রিয়</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-500 text-white">নিষ্ক্রিয়</span>
                            @endif
                        </div>

                        <span class="text-xs font-bold text-purple-300 uppercase tracking-wider block mb-1">
                            {{ $slide->subtitle_bn ?: ($slide->subtitle ?: 'এক্সক্লুসিভ অফার') }}
                        </span>
                        <h3 class="text-xl font-black text-white leading-tight mb-3">
                            {{ $slide->title_bn ?: $slide->title }}
                        </h3>
                        <p class="text-xs text-gray-300 line-clamp-2 mb-4">
                            {{ $slide->description_bn ?: ($slide->description ?: '') }}
                        </p>

                        <div class="flex items-center gap-3">
                            @if($slide->primary_button_text)
                                <span class="px-3 py-1.5 rounded-lg bg-purple-600 text-[11px] font-bold text-white shadow-xs">
                                    {{ $slide->primary_button_text }}
                                </span>
                            @endif
                            @if($slide->secondary_button_text)
                                <span class="px-3 py-1.5 rounded-lg bg-white/10 border border-white/20 text-[11px] font-medium text-white">
                                    {{ $slide->secondary_button_text }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Slide Control Actions -->
                <div class="p-4 bg-gray-50/60 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[11px] text-gray-400 font-mono">ID: #{{ $slide->id }}</span>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.cms.hero.delete', $slide->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই স্লাইডটি মুছে ফেলতে চান?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                <i data-lucide="image" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                কোন স্লাইড পাওয়া যায়নি। উপরের বাটনে ক্লিক করে নতুন স্লাইড তৈরি করুন।
            </div>
        @endforelse
    </div>

    <!-- Create Slide Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="createModalOpen = false"></div>

            <div class="relative bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl z-10 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-black text-gray-900 text-base">নতুন হিরো স্লাইড তৈরি করুন</h3>
                    <button @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('admin.cms.hero.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">শিরোনাম (বাংলা)</label>
                            <input type="text" name="title_bn" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Title (English)</label>
                            <input type="text" name="title" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">সাবটাইটেল / ব্যাজ টেক্সট</label>
                            <input type="text" name="subtitle_bn" placeholder="যেমন: নতুন রিলিজ" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ব্যাকগ্রাউন্ড কালার কোড</label>
                            <input type="text" name="bg_color" value="#17152B" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">সংক্ষিপ্ত বিবরণ</label>
                        <textarea name="description_bn" rows="2" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">বাটন টেক্সট</label>
                            <input type="text" name="primary_button_text" value="প্রোডাক্টগুলো দেখুন" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">বাটন লিঙ্ক (URL)</label>
                            <input type="text" name="primary_button_url" value="/shop" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ক্রম (Sort Order)</label>
                            <input type="number" name="sort_order" value="1" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs">
                            স্লাইড তৈরি করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
