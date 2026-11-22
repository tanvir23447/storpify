@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-purple-600 font-bold hover:underline">← সকল প্রোডাক্টে ফিরুন</a>
            <h1 class="text-xl font-extrabold text-gray-900 mt-1">নতুন প্রোডাক্ট যোগ করুন</h1>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xs">
        
        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">প্রোডাক্টের নাম (English) *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="যেমন: SIS School Management System"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm font-semibold">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">প্রোডাক্টের নাম (বাংলা)</label>
                    <input type="text" name="name_bn" value="{{ old('name_bn') }}" placeholder="যেমন: স্কুল ম্যানেজমেন্ট সিস্টেম – কমপ্লিট স্ক্রিপ্ট"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm font-semibold">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">ক্যাটাগরি *</label>
                    <select name="category_id" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">প্রোডাক্ট টাইপ *</label>
                    <select name="product_type" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none">
                        <option value="website_template">Website Template / Script</option>
                        <option value="wordpress_plugin">WordPress Plugin</option>
                        <option value="wordpress_theme">WordPress Theme</option>
                        <option value="graphics">Graphic Resource</option>
                        <option value="ai_tools">AI Prompts & Tools</option>
                        <option value="course">Video Course</option>
                        <option value="business">Business Document / Excel</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">রেগুলার মূল্য (BDT ৳) *</label>
                    <input type="number" step="0.01" name="regular_price" required value="{{ old('regular_price', '999.00') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm font-bold font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">অফার/সেল মূল্য (BDT ৳ - ঐচ্ছিক)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" placeholder="যেমন: 499.00"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm font-bold font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">ভার্সন (Version)</label>
                    <input type="text" name="version" value="{{ old('version', '1.0.0') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">SKU কোড (ঐচ্ছিক)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="SP-101"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none font-mono uppercase">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">সংক্ষিপ্ত বিবরণ (Short Description)</label>
                    <textarea name="short_description" rows="2" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-gray-700 mb-1.5">বিস্তারিত বিবরণ (Full Description)</label>
                    <textarea name="full_description" rows="5" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="rounded text-purple-600 focus:ring-purple-500">
                        <span class="font-bold text-gray-800">হোমপেজে ফিচার্ড প্রোডাক্ট হিসেবে দেখান (Featured Product)</span>
                    </label>
                </div>

            </div>

            <div class="border-t border-gray-100 pt-6">
                <button type="submit" class="px-7 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-transform hover:scale-105">
                    প্রোডাক্ট সংরক্ষণ করুন
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
