@extends('layouts.subscription-admin', ['title' => 'STORPIFY - সাবস্ক্রিপশন মডিউল সেটিংস'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">ওয়েব সাবস্ক্রিপশন মডিউল সেটিংস</h1>
            <p class="text-xs text-gray-500 mt-1">সাবস্ক্রিপশন আর্কিটেকচার, বেস ইউআরএল স্লাগ ও গ্লোবাল রুলস।</p>
        </div>
    </div>

    <form action="{{ route('admin.subscriptions.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-5">
            <div>
                <h3 class="text-sm font-black text-gray-900">১. মডিউল বেস ইউআরএল স্লাগ (Base Route Slug)</h3>
                <p class="text-xs text-gray-500 mt-0.5">মেইন স্টোর ও সাবস্ক্রিপশন মডিউলকে ইউআরএল লেভেলে পৃথক রাখতে এই স্লাগ ব্যবহৃত হয়। ডিফল্ট: <code class="font-mono text-purple-700 font-bold">web-subscription</code></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">বেস স্লাগ (Base URL Path)</label>
                <div class="flex items-center">
                    <span class="px-3.5 py-2.5 rounded-l-xl bg-gray-100 border border-r-0 border-gray-200 text-xs font-mono text-gray-500">
                        {{ url('/') }}/
                    </span>
                    <input type="text" name="base_slug" value="{{ old('base_slug', $baseSlug) }}" required
                           class="flex-1 px-3.5 py-2.5 rounded-r-xl border border-gray-200 text-xs font-mono font-bold focus:ring-2 focus:ring-purple-500 focus:outline-none">
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
