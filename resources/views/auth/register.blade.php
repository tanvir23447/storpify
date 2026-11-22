@extends('layouts.app')

@section('content')
<div class="py-14 bg-[#F8F9FD] flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        
        <div class="bg-white rounded-3xl border border-gray-100 p-8 sm:p-10 shadow-xl space-y-6">
            
            <div class="text-center space-y-2">
                <img src="{{ asset('assets/images/storpify-logo.png') }}" alt="STORPIFY" class="h-10 mx-auto">
                <h2 class="text-xl font-extrabold text-gray-900 pt-2">রেজিস্ট্রেশন করুন</h2>
                <p class="text-xs text-gray-500">১টি অ্যাকাউন্ট দিয়ে শপ এবং সাবস্ক্রিপশন ব্যবহার করুন</p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-xs text-red-600 space-y-1">
                    @foreach($errors->all() as $err)
                        <div>• {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">আপনার পুরো নাম *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="মোঃ রাকিবুল হাসান"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">ইমেইল অ্যাড্রেস *</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="name@example.com"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">মোবাইল নম্বর</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="017XXXXXXXX"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">পাসওয়ার্ড (কমপক্ষে ৮ অক্ষর) *</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">পাসওয়ার্ড নিশ্চিত করুন *</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/30 transition-transform hover:scale-[1.01]">
                    অ্যাকাউন্ট তৈরি করুন
                </button>
            </form>

            <div class="text-center pt-2 text-xs text-gray-500">
                <span>পূর্বেই অ্যাকাউন্ট আছে?</span>
                <a href="{{ route('login') }}" class="font-bold text-purple-600 hover:underline ml-1">লগইন করুন</a>
            </div>

        </div>

    </div>
</div>
@endsection
