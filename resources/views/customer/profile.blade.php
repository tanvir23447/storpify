@extends('layouts.app')

@section('content')
<div class="py-10 bg-[#F8F9FD]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">প্রোফাইল ও নিরাপত্তা সেটিংস</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">আপনার ব্যক্তিগত তথ্য ও অ্যাকাউন্টের পাসওয়ার্ড পরিবর্তন করুন</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-purple-600 hover:underline">
                ← ড্যাশবোর্ডে ফিরুন
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xs space-y-6">
            
            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1.5">আপনার নাম *</label>
                        <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1.5">ইমেইল অ্যাড্রেস (পরিবর্তনযোগ্য নয়)</label>
                        <input type="email" disabled value="{{ $user->email }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-100 text-gray-500 outline-none text-sm cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1.5">মোবাইল নম্বর</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="017XXXXXXXX"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1.5">রোল (Role)</label>
                        <span class="inline-block px-3 py-2 bg-purple-50 text-purple-700 font-bold rounded-xl text-xs">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">পাসওয়ার্ড পরিবর্তন (Password Change)</h3>
                    <p class="text-xs text-gray-500 mb-4">পাসওয়ার্ড পরিবর্তন না করতে চাইলে নিচের ঘরগুলো ফাঁকা রাখুন।</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1.5">নতুন পাসওয়ার্ড</label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-1.5">পাসওয়ার্ড নিশ্চিত করুন</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors">
                        পরিবর্তনসমূহ সংরক্ষণ করুন
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>
@endsection
