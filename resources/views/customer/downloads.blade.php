@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                    মাই ডিজিটাল ডাউনলোডস
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    আপনার ক্রয়কৃত এবং অনুমোদিত সকল প্রোডাক্ট ফাইলের তালিকা
                </p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-purple-600 hover:underline">
                ← ড্যাশবোর্ডে ফিরুন
            </a>
        </div>

        @if($purchasedItems->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-xs max-w-md mx-auto space-y-4">
                <div class="w-16 h-16 rounded-full bg-purple-50 text-purple-600 mx-auto flex items-center justify-center">
                    <i data-lucide="download-cloud" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900">এখনও কোনো ফাইল কেনা হয়নি</h3>
                <p class="text-xs text-gray-500">আপনার অনুমোদিত অর্ডারের ফাইলসমূহ পেমেন্ট ভেরিফিকেশনের পর এখানে প্রদর্শিত হবে।</p>
                <a href="{{ route('shop') }}" class="inline-block px-5 py-2.5 bg-purple-600 text-white font-bold text-xs rounded-xl shadow-md">
                    শপ ব্রাউজ করুন →
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($purchasedItems as $item)
                    @php
                        $product = $item->product;
                        $files = $item->variant && $item->variant->files->count() > 0 
                            ? $item->variant->files 
                            : ($product ? $product->files : collect());
                    @endphp
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-xs flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">
                                    অর্ডার: #{{ $item->order->order_number }}
                                </span>
                                <span class="text-xs font-bold text-purple-700 font-mono">
                                    {{ format_price($item->subtotal) }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-gray-900">
                                {{ $item->product_name }}
                            </h3>

                            @if($item->variant_name)
                                <span class="inline-block text-xs font-semibold text-purple-600 mt-1">
                                    লাইসেন্স: {{ $item->variant_name }}
                                </span>
                            @endif

                            <div class="text-xs text-gray-500 mt-2">
                                মোট ডাউনলোড হয়েছে: <span class="font-bold text-gray-800">{{ $item->download_count }} বার</span>
                            </div>
                        </div>

                        <!-- Available Files Download Section -->
                        <div class="border-t border-gray-100 pt-4 space-y-2">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">ডাউনলোড ফাইল সমূহ:</span>
                            @if($files->isEmpty())
                                <p class="text-xs text-gray-400">এই প্রোডাক্টের ফাইলশীঘ্রই আপলোড করা হবে।</p>
                            @else
                                @foreach($files as $file)
                                    <div class="flex items-center justify-between p-3 rounded-2xl bg-[#F8F9FD] border border-gray-100">
                                        <div class="flex items-center gap-2.5">
                                            <i data-lucide="file-archive" class="w-5 h-5 text-purple-600"></i>
                                            <div>
                                                <h5 class="text-xs font-bold text-gray-900 truncate max-w-[180px]">{{ $file->file_name }}</h5>
                                                <span class="text-[10px] text-gray-400">v{{ $file->version }} • {{ number_format($file->file_size / 1048576, 1) }} MB</span>
                                            </div>
                                        </div>

                                        <a href="{{ route('customer.download.file', ['fileId' => $file->id, 'orderItemId' => $item->id]) }}" 
                                            class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center gap-1.5 transition-transform hover:scale-105">
                                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                            <span>ডাউনলোড</span>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
