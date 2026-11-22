@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-purple-600 font-bold hover:underline">← সকল প্রোডাক্টে ফিরুন</a>
            <h1 class="text-xl font-extrabold text-gray-900 mt-1">প্রোডাক্ট সম্পাদনা: {{ $product->name }}</h1>
        </div>
        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">
            Status: {{ ucfirst($product->status) }}
        </span>
    </div>

    <!-- Main Edit Form -->
    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xs">
        
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">প্রোডাক্টের নাম (English) *</label>
                    <input type="text" name="name" required value="{{ old('name', $product->name) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm font-semibold">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">প্রোডাক্টের নাম (বাংলা)</label>
                    <input type="text" name="name_bn" value="{{ old('name_bn', $product->name_bn) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm font-semibold">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">ক্যাটাগরি *</label>
                    <select name="category_id" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->categories->contains($cat->id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">স্ট্যাটাস (Workflow Status) *</label>
                    <select name="status" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none font-bold text-purple-700">
                        <option value="published" {{ $product->status === 'published' ? 'selected' : '' }}>Published (সরাসরি লাইভ)</option>
                        <option value="pending_approval" {{ $product->status === 'pending_approval' ? 'selected' : '' }}>Pending Approval (অনুমোদনের অপেক্ষায়)</option>
                        <option value="draft" {{ $product->status === 'draft' ? 'selected' : '' }}>Draft (খসড়া)</option>
                        <option value="archived" {{ $product->status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">রেগুলার মূল্য (BDT ৳) *</label>
                    <input type="number" step="0.01" name="regular_price" required value="{{ old('regular_price', $product->regular_price) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm font-bold font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">অফার/সেল মূল্য (BDT ৳)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm font-bold font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">ভার্সন</label>
                    <input type="text" name="version" value="{{ old('version', $product->version) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">SKU কোড</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none font-mono uppercase">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">সংক্ষিপ্ত বিবরণ</label>
                    <textarea name="short_description" rows="2" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">{{ $product->short_description }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">বিস্তারিত বিবরণ</label>
                    <textarea name="full_description" rows="5" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">{{ $product->full_description }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="rounded text-purple-600 focus:ring-purple-500">
                        <span class="font-bold text-gray-800">হোমপেজে ফিচার্ড প্রোডাক্ট হিসেবে দেখান</span>
                    </label>
                </div>

            </div>

            <div class="border-t border-gray-100 pt-6">
                <button type="submit" class="px-7 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors">
                    পরিবর্তন সংরক্ষণ করুন
                </button>
            </div>

        </form>

    </div>

    <!-- Digital File Upload Box -->
    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xs space-y-6">
        <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
            <span>ডিজিটাল ডেলিভারি ফাইলসমূহ ({{ $product->files->count() }})</span>
            <span class="text-xs text-purple-600 font-normal">কাস্টমার পেমেন্ট নিশ্চিত হলে এই ফাইল পাবে</span>
        </h3>

        <!-- Existing Files List -->
        @if($product->files->count() > 0)
            <div class="space-y-2">
                @foreach($product->files as $f)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-xs">
                        <div class="flex items-center gap-3">
                            <i data-lucide="file-archive" class="w-5 h-5 text-purple-600"></i>
                            <div>
                                <span class="font-bold text-gray-900 block">{{ $f->file_name }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">v{{ $f->version }} • {{ number_format($f->file_size / 1048576, 1) }} MB • Bucket: {{ $f->storage_bucket }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold text-[10px]">Active</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Upload New Version Form -->
        <form action="{{ route('admin.products.upload-file', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-5 rounded-2xl bg-purple-50 border border-purple-100 space-y-4 text-xs">
            @csrf
            <h4 class="font-bold text-purple-950">নতুন ভার্সন বা ফাইল আপলোড করুন:</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">ফাইলের নাম *</label>
                    <input type="text" name="file_name" required placeholder="script-v{{ $product->version }}.zip"
                        class="w-full px-3 py-2 rounded-xl border border-purple-200 bg-white outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">ভার্সন *</label>
                    <input type="text" name="version" required value="{{ $product->version }}"
                        class="w-full px-3 py-2 rounded-xl border border-purple-200 bg-white outline-none font-mono">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1">ফাইল সিলেক্ট করুন (ZIP, PDF, RAR) *</label>
                    <input type="file" name="digital_file" required
                        class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                </div>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-purple-700 hover:bg-purple-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                ফাইল আপলোড করুন
            </button>
        </form>
    </div>

</div>
@endsection
