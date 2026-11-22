@extends('layouts.admin', ['title' => 'STORPIFY - ওয়েবসাইট সেটিংস'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">ওয়েবসাইট ব্র্যান্ড ও যোগাযোগ সেটিংস</h1>
            <p class="text-xs text-gray-500 mt-1">STORPIFY প্ল্যাটফর্মের নাম, ট্যাগলাইন, সোশ্যাল লিংক এবং সাপোর্ট যোগাযোগ তথ্য।</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.website.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-5">
            <h3 class="text-sm font-black text-gray-900 border-b border-gray-100 pb-3">১. ব্র্যান্ড ও ট্যাগলাইন</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">ওয়েবসাইট / প্ল্যাটফর্মের নাম</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $siteName) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">ট্যাগলাইন (বাংলা)</label>
                    <input type="text" name="site_tagline_bn" value="{{ old('site_tagline_bn', $taglineBn) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Tagline (English)</label>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', $tagline) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-5">
            <h3 class="text-sm font-black text-gray-900 border-b border-gray-100 pb-3">২. কাস্টমার সাপোর্ট ও যোগাযোগ</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">অফিসিয়াল সাপোর্ট ইমেইল</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $email) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-mono focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">হটলাইন / মোবাইল নম্বর</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $phone) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-mono focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-5">
            <h3 class="text-sm font-black text-gray-900 border-b border-gray-100 pb-3">৩. সোশ্যাল মিডিয়া ও কমিউনিটি লিঙ্ক</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">ফেসবুক পেজ / গ্রুপ URL</label>
                    <input type="url" name="facebook" value="{{ $social['facebook'] ?? '' }}" placeholder="https://facebook.com/storpify"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">ইউটিউব চ্যানেল URL</label>
                    <input type="url" name="youtube" value="{{ $social['youtube'] ?? '' }}" placeholder="https://youtube.com/@storpify"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">টেলিগ্রাম চ্যানেল / গ্রুপ</label>
                    <input type="url" name="telegram" value="{{ $social['telegram'] ?? '' }}" placeholder="https://t.me/storpify"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">হোয়াটসঅ্যাপ সাপোর্ট লিংক</label>
                    <input type="url" name="whatsapp" value="{{ $social['whatsapp'] ?? '' }}" placeholder="https://wa.me/8801700000000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-black text-xs shadow-md transition-all">
                সেটিংস সংরক্ষণ করুন
            </button>
        </div>
    </form>
</div>
@endsection
