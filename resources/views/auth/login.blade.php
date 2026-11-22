@extends('layouts.app')

@section('content')
<div class="py-14 bg-[#F8F9FD] flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        
        <div class="bg-white rounded-3xl border border-gray-100 p-8 sm:p-10 shadow-xl space-y-6">
            
            <div class="text-center space-y-2">
                <img src="{{ asset('assets/images/storpify-logo.png') }}" alt="STORPIFY" class="h-10 mx-auto">
                <h2 class="text-xl font-extrabold text-gray-900 pt-2">অ্যাকাউন্টে লগইন করুন</h2>
                <p class="text-xs text-gray-500">আপনার অর্ডার ও সাবস্ক্রিপশন ম্যানেজ করতে লগইন করুন</p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-xs text-red-600 space-y-1">
                    @foreach($errors->all() as $err)
                        <div>• {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">ইমেইল অ্যাড্রেস *</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="user@example.com"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1.5">পাসওয়ার্ড *</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-purple-600 outline-none text-sm">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-purple-600 focus:ring-purple-500">
                        <span class="text-gray-600 font-medium">মনে রাখুন (Remember me)</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/30 transition-transform hover:scale-[1.01]">
                    লগইন করুন
                </button>
            </form>

            <!-- Test credentials quick reminder -->
            <div class="p-3.5 rounded-2xl bg-purple-50 border border-purple-100 text-[11px] text-purple-900 space-y-1">
                <span class="font-bold block">ডেমো ক্রেডেনশিয়াল (Default Credentials):</span>
                <div>Super Admin: <span class="font-mono font-bold">admin@storpify.com</span> (Pass: Storpify@2026!)</div>
                <div>Customer: <span class="font-mono font-bold">customer@storpify.com</span> (Pass: Storpify@2026!)</div>
            </div>

            <div class="text-center pt-2 text-xs text-gray-500">
                <span>অ্যাকাউন্ট নেই?</span>
                <a href="{{ route('register') }}" class="font-bold text-purple-600 hover:underline ml-1">নতুন অ্যাকাউন্ট তৈরি করুন</a>
            </div>

        </div>

    </div>
</div>
@endsection
