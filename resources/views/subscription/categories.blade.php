@extends('layouts.app', ['title' => 'STORPIFY - সাবস্ক্রিপশন রিসোর্স ক্যাটাগরি'])

@section('content')
<div class="py-12 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
            <div>
                <span class="text-xs font-bold text-purple-600 uppercase tracking-wider block mb-1">ডিজিটাল রিসোর্স লাইব্রেরি</span>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">সাবস্ক্রিপশন ক্যাটাগরি ব্রাউজ করুন</h1>
            </div>
            <a href="{{ route('subscription.plans') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-600 text-white font-bold text-xs hover:bg-purple-700 transition-colors shadow-xs">
                <span>👑 মেম্বারশিপ প্ল্যান দেখুন</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('subscription.category', $category->slug) }}" class="bg-white rounded-3xl border border-gray-200/80 p-6 flex items-start gap-5 hover:border-purple-300 hover:shadow-lg transition-all group">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i data-lucide="{{ $category->icon ?: 'folder' }}" class="w-7 h-7"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="font-black text-gray-900 text-base group-hover:text-purple-600 transition-colors">
                                {{ $category->name_bn ?: $category->name }}
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700">
                                {{ $category->files_count }} ফাইল
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">
                            {{ $category->description_bn ?: ($category->description ?: 'এই ক্যাটাগরির সংরক্ষিত রিসোর্স সমূহ ডাউনলোড করুন।') }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
