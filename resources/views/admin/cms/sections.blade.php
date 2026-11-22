@extends('layouts.admin', ['title' => 'STORPIFY - হোমপেজ বিল্ডার ও সেকশন বিন্যাস'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">হোমপেজ সেকশন বিল্ডার</h1>
            <p class="text-xs text-gray-500 mt-1">হোমপেজের প্রতিটি সেকশনের শিরোনাম, সাবটাইটেল, ক্রম এবং ডিসপ্লে ডিভাইস (ডেস্কটপ/ট্যাবলেট/মোবাইল) নিয়ন্ত্রণ করুন।</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-50 text-purple-700 font-bold hover:bg-purple-100 transition-colors text-xs border border-purple-200">
            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
            লাইভ হোমপেজ প্রিভিউ দেখুন
        </a>
    </div>

    <!-- Sections List -->
    <div class="space-y-4">
        @foreach($sections as $section)
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden" x-data="{ expanded: false }">
                <div class="p-5 flex items-center justify-between cursor-pointer hover:bg-gray-50/50 transition-colors" @click="expanded = !expanded">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">
                            #{{ $section->sort_order }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-bold text-gray-900 text-sm">{{ $section->title_bn ?: $section->title }}</h3>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase bg-gray-100 text-gray-600 font-bold">
                                    {{ $section->section_type }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $section->subtitle_bn ?: ($section->subtitle ?: 'কাস্টম সাবটাইটেল নেই') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex items-center gap-2 text-xs">
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $section->show_on_desktop ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-400' }}">ডেস্কটপ</span>
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $section->show_on_tablet ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-400' }}">ট্যাব</span>
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $section->show_on_mobile ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-400' }}">মোবাইল</span>
                        </div>

                        <div>
                            @if($section->is_active)
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">অন</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">অফ</span>
                            @endif
                        </div>

                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': expanded }"></i>
                    </div>
                </div>

                <!-- Edit Drawer / Form -->
                <div x-show="expanded" x-collapse class="border-t border-gray-100 bg-gray-50/50 p-6">
                    <form action="{{ route('admin.cms.sections.update', $section->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">সেকশন শিরোনাম (বাংলা)</label>
                                <input type="text" name="title_bn" value="{{ old('title_bn', $section->title_bn) }}"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">সেকশন শিরোনাম (English)</label>
                                <input type="text" name="title" value="{{ old('title', $section->title) }}"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">সাবটাইটেল (বাংলা)</label>
                                <input type="text" name="subtitle_bn" value="{{ old('subtitle_bn', $section->subtitle_bn) }}"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">সাবটাইটেল (English)</label>
                                <input type="text" name="subtitle" value="{{ old('subtitle', $section->subtitle) }}"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">ক্রম (Sort Order)</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', $section->sort_order) }}"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium focus:ring-2 focus:ring-purple-500 focus:outline-none bg-white">
                            </div>

                            <div class="sm:col-span-3 flex flex-wrap items-center gap-6 pt-5">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ $section->is_active ? 'checked' : '' }} class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span class="text-xs font-bold text-gray-800">সেকশন সক্রিয় রাখুন</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="show_on_desktop" value="1" {{ $section->show_on_desktop ? 'checked' : '' }} class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span class="text-xs text-gray-700">Desktop</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="show_on_tablet" value="1" {{ $section->show_on_tablet ? 'checked' : '' }} class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span class="text-xs text-gray-700">Tablet</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="show_on_mobile" value="1" {{ $section->show_on_mobile ? 'checked' : '' }} class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span class="text-xs text-gray-700">Mobile</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 text-white font-bold text-xs hover:bg-purple-700 transition-colors shadow-xs">
                                সেকশন সেটিংস সংরক্ষণ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
