@extends('layouts.admin', ['title' => 'STORPIFY - নতুন স্টাফ তৈরি করুন'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">নতুন স্টাফ অ্যাকাউন্ট তৈরি</h1>
            <p class="text-xs text-gray-500 mt-1">জেনারেল এডমিন অথবা মডারেটরের জন্য অ্যাকাউন্ট তৈরি ও নির্দিষ্ট পারমিশন বরাদ্দ করুন।</p>
        </div>
        <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-100 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-6"
          x-data="{ selectedRole: 'moderator' }">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-5">
            <h3 class="text-sm font-black text-gray-900 border-b border-gray-100 pb-3">১. মৌলিক তথ্য ও লগইন ক্রেডেনশিয়াল</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">স্টাফের পুরো নাম</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">অফিসিয়াল ইমেইল অ্যাড্রেস</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">লগইন পাসওয়ার্ড (নূন্যতম ৮ অক্ষর)</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">অ্যাসাইন করা রোল (Role)</label>
                    <select name="role" x-model="selectedRole" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="moderator">মডারেটর (Moderator - সীমিত পারমিশন ও অনুমোদন সাপেক্ষে)</option>
                        <option value="general_admin">জেনারেল এডমিন (General Admin - উচ্চ ক্ষমতা)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Category Scoping Section (Especially for Moderator) -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-4">
            <div>
                <h3 class="text-sm font-black text-gray-900">২. ক্যাটাগরি ভিত্তিক সীমাবদ্ধতা (Category Scope)</h3>
                <p class="text-xs text-gray-500 mt-0.5">মডারেটর শুধুমাত্র নিচের নির্বাচিত ক্যাটাগরির প্রোডাক্ট পরিচালনা করতে পারবেন। কোনোটি সিলেক্ট না করলে সব ক্যাটাগরিতে অ্যাক্সেস পাবে।</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                @foreach($categories as $cat)
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:border-purple-300 hover:bg-purple-50/20 cursor-pointer transition-all">
                        <input type="checkbox" name="assigned_categories[]" value="{{ $cat->id }}" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                        <span class="text-xs font-bold text-gray-800">{{ $cat->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Permissions Checkboxes Grouped by Module -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-4">
            <div>
                <h3 class="text-sm font-black text-gray-900">৩. মডিউল ভিত্তিক পারমিশন (Granular Permissions)</h3>
                <p class="text-xs text-gray-500 mt-0.5">এই ব্যবহারকারীকে যে যে পারমিশনগুলো সরাসরি দিতে চান তা টিক দিন।</p>
            </div>

            <div class="space-y-4 pt-2">
                @foreach($permissions as $moduleName => $modulePermissions)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h4 class="text-xs font-black uppercase text-purple-900 tracking-wider mb-2.5">{{ $moduleName }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($modulePermissions as $perm)
                                <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-gray-200/60 hover:border-purple-300 transition-colors">
                                    <input type="checkbox" name="permission_ids[]" value="{{ $perm->id }}" class="w-3.5 h-3.5 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span class="text-xs text-gray-700 font-medium">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.staff.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                বাতিল
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-black text-xs shadow-md transition-all">
                স্টাফ অ্যাকাউন্ট তৈরি করুন
            </button>
        </div>
    </form>
</div>
@endsection
