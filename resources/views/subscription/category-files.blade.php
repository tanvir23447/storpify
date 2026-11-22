@extends('layouts.app', ['title' => 'STORPIFY - ' . ($category->name_bn ?: $category->name)])

@section('content')
<div class="py-12 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl border border-gray-200/80 p-8 mb-10 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $category->icon ?: 'folder' }}" class="w-7 h-7"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-purple-600 uppercase tracking-wider block mb-0.5">সাবস্ক্রিপশন ক্যাটাগরি</span>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">{{ $category->name_bn ?: $category->name }}</h1>
                    <p class="text-xs text-gray-500 mt-1">{{ $category->description_bn ?: ($category->description ?: 'এই ক্যাটাগরির সংরক্ষিত রিসোর্স সমূহ') }}</p>
                </div>
            </div>
        </div>

        <!-- Files Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($files as $file)
                <div class="bg-white rounded-3xl border border-gray-200/80 p-6 flex flex-col justify-between hover:shadow-lg transition-shadow">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-purple-50 text-purple-700 uppercase font-mono">
                                v{{ $file->version }}
                            </span>
                            <span class="text-[11px] text-gray-400 font-mono">
                                {{ round($file->file_size / 1024 / 1024, 2) }} MB
                            </span>
                        </div>

                        <div>
                            <h3 class="font-black text-gray-900 text-base leading-snug">
                                <a href="{{ route('subscription.file', $file->slug) }}" class="hover:text-purple-600 transition-colors">
                                    {{ $file->title_bn ?: $file->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">
                                {{ $file->description_bn ?: ($file->description ?: 'সুরক্ষিত মেম্বারশিপ রিসোর্স।') }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-6 mt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-gray-400 font-mono">{{ $file->download_count }} বার ডাউনলোড</span>
                        <a href="{{ route('subscription.file', $file->slug) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-colors">
                            <span>ডাউনলোড বিস্তারিত</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white rounded-3xl border border-gray-200 p-16 text-center text-gray-400">
                    <i data-lucide="folder-open" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm font-bold">এই ক্যাটাগরিতে এখনও কোন ফাইল আপলোড করা হয়নি।</p>
                </div>
            @endforelse
        </div>

        @if($files->hasPages())
            <div class="mt-8">
                {{ $files->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
