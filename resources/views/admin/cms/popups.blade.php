@extends('layouts.admin', ['title' => 'STORPIFY - অফার পপআপ ক্যাম্পেইন'])

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">অফার ও ডিসকাউন্ট পপআপ ক্যাম্পেইন</h1>
            <p class="text-xs text-gray-500 mt-1">হোমপেজ ও প্রোডাক্ট পেজে গ্রাহকদের জন্য প্রোমোশনাল ডিসকাউন্ট বা কুপন পপআপ নির্ধারণ করুন।</p>
        </div>
        <button @click="createModalOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            নতুন পপআপ ক্যাম্পেইন যোগ করুন
        </button>
    </div>

    <!-- Popups List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($popups as $popup)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-5 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $popup->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $popup->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                        </span>
                        <span class="text-[11px] text-gray-400 font-mono">দেরি: {{ $popup->display_delay_sec }}s</span>
                    </div>

                    <div>
                        <h3 class="font-black text-gray-900 text-base">{{ $popup->title_bn ?: $popup->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $popup->description_bn ?: ($popup->description ?: 'কোন বিবরণ নেই') }}</p>
                    </div>

                    <!-- Discount badge -->
                    <div class="bg-purple-50 rounded-xl p-3 border border-purple-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-purple-600 uppercase block">ডিসকাউন্ট</span>
                            <span class="text-sm font-black text-purple-900">{{ $popup->discount_text ?: 'বিশেষ ছাড়' }}</span>
                        </div>
                        @if($popup->coupon_code)
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-gray-400 uppercase block">কুপন কোড</span>
                                <span class="px-2 py-0.5 rounded bg-white text-xs font-mono font-black text-purple-700 border border-purple-200">
                                    {{ $popup->coupon_code }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 text-[11px] text-gray-500">
                        <i data-lucide="layout" class="w-3.5 h-3.5 text-purple-500"></i>
                        <span>প্রদর্শিত হবে: {{ $popup->show_on_homepage ? 'হোমপেজ' : '' }} {{ $popup->show_on_products ? ', প্রোডাক্ট পেজ' : '' }}</span>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between">
                    <form action="{{ route('admin.cms.popups.toggle', $popup->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $popup->is_active ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} transition-colors">
                            {{ $popup->is_active ? 'বন্ধ করুন' : 'চালু করুন' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
                <i data-lucide="message-square" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                কোন পপআপ ক্যাম্পেইন সক্রিয় নেই।
            </div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="createModalOpen = false"></div>

            <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl z-10 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-black text-gray-900 text-base">নতুন অফার পপআপ ক্যাম্পেইন</h3>
                    <button @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('admin.cms.popups.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">পপআপ শিরোনাম</label>
                        <input type="text" name="title" required placeholder="যেমন: বিশেষ অফার ২০% ছাড়!" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ডিসকাউন্ট টেক্সট</label>
                            <input type="text" name="discount_text" placeholder="যেমন: FLAT 20% OFF" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">কুপন কোড (যদি থাকে)</label>
                            <input type="text" name="coupon_code" placeholder="যেমন: EID20" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs font-mono uppercase focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">বিবরণ</label>
                        <textarea name="description" rows="2" placeholder="আজকের অর্ডারে এই কুপন কোড ব্যবহার করুন।" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">বাটন টেক্সট</label>
                            <input type="text" name="button_text" value="এখনই কিনুন" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ডিসপ্লে ডিলে (সেকেন্ড)</label>
                            <input type="number" name="display_delay_sec" value="3" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="flex items-center gap-6 pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="show_on_homepage" value="1" checked class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                            <span class="text-xs text-gray-800 font-bold">হোমপেজে দেখান</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="show_on_products" value="1" checked class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                            <span class="text-xs text-gray-800 font-bold">প্রোডাক্ট পেজে দেখান</span>
                        </label>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs">
                            ক্যাম্পেইন শুরু করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
