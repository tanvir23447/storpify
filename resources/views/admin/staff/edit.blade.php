@extends('layouts.admin', ['title' => 'STORPIFY - স্টাফ পারমিশন সম্পাদনা'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">স্টাফ অ্যাকাউন্ট ও পারমিশন সম্পাদনা</h1>
            <p class="text-xs text-gray-500 mt-1">{{ $staffMember->name }} ({{ $staffMember->email }}) এর ভূমিকা ও পারমিশন পরিবর্তন করুন।</p>
        </div>
        <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-100 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('admin.staff.update', $staffMember->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-5">
            <h3 class="text-sm font-black text-gray-900 border-b border-gray-100 pb-3">১. মৌলিক তথ্য ও স্ট্যাটাস</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">স্টাফের পুরো নাম</label>
                    <input type="text" name="name" value="{{ old('name', $staffMember->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">অফিসিয়াল ইমেইল (পরিবর্তনযোগ্য নয়)</label>
                    <input type="email" value="{{ $staffMember->email }}" disabled
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs bg-gray-100 text-gray-500 font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">অ্যাসাইন করা রোল (Role)</label>
                    <select name="role" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="moderator" {{ $staffMember->role === 'moderator' ? 'selected' : '' }}>মডারেটর (Moderator)</option>
                        <option value="general_admin" {{ $staffMember->role === 'general_admin' ? 'selected' : '' }}>জেনারেল এডমিন (General Admin)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">অ্যাকাউন্ট স্ট্যাটাস</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="active" {{ $staffMember->status === 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                        <option value="suspended" {{ $staffMember->status === 'suspended' ? 'selected' : '' }}>স্থগিত (Suspended)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Category Scoping Section -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-4">
            <div>
                <h3 class="text-sm font-black text-gray-900">২. ক্যাটাগরি ভিত্তিক সীমাবদ্ধতা (Category Scope)</h3>
                <p class="text-xs text-gray-500 mt-0.5">মডারেটর শুধুমাত্র নিচের নির্বাচিত ক্যাটাগরির প্রোডাক্ট পরিচালনা করতে পারবেন। কোনোটি সিলেক্ট না করলে সব ক্যাটাগরিতে অ্যাক্সেস পাবে।</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                @php
                    $assigned = is_array($staffMember->assigned_categories) ? $staffMember->assigned_categories : [];
                @endphp
                @foreach($categories as $cat)
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:border-purple-300 hover:bg-purple-50/20 cursor-pointer transition-all">
                        <input type="checkbox" name="assigned_categories[]" value="{{ $cat->id }}" {{ in_array($cat->id, $assigned) ? 'checked' : '' }} class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
                        <span class="text-xs font-bold text-gray-800">{{ $cat->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Permissions Checkboxes Grouped by Module -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 space-y-4">
            <div>
                <h3 class="text-sm font-black text-gray-900">৩. মডিউল ভিত্তিক পারমিশন (Granular Permissions)</h3>
                <p class="text-xs text-gray-500 mt-0.5">এই ব্যবহারকারীর পারমিশনগুলো সরাসরি কাস্টমাইজ করুন।</p>
            </div>

            @php
                $userOverrides = $staffMember->permissionOverrides->pluck('permission_id')->toArray();
            @endphp

            <div class="space-y-4 pt-2">
                @foreach($permissions as $moduleName => $modulePermissions)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h4 class="text-xs font-black uppercase text-purple-900 tracking-wider mb-2.5">{{ $moduleName }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($modulePermissions as $perm)
                                <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-gray-200/60 hover:border-purple-300 transition-colors">
                                    <input type="checkbox" name="permission_ids[]" value="{{ $perm->id }}" {{ in_array($perm->id, $userOverrides) ? 'checked' : '' }} class="w-3.5 h-3.5 rounded text-purple-600 focus:ring-purple-500 border-gray-300">
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
                আপডেট সংরক্ষণ করুন
            </button>
        </div>
    </form>
</div>
@endsection
