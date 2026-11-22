@extends('layouts.admin', ['title' => 'STORPIFY - স্টাফ ও পারমিশন কন্ট্রোল (RBAC)'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">স্টাফ ও পারমিশন ম্যানেজমেন্ট (RBAC)</h1>
            <p class="text-xs text-gray-500 mt-1">সুপার এডমিন, জেনারেল এডমিন ও মডারেটরদের ভূমিকা, ক্যাটাগরি ভিত্তিক পারমিশন এবং অ্যাক্সেস কন্ট্রোল।</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            নতুন স্টাফ অ্যাকাউন্ট যোগ করুন
        </a>
    </div>

    <!-- Staff List Table -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">নাম ও ইমেইল</th>
                        <th class="px-5 py-4">ভূমিকা (Role)</th>
                        <th class="px-5 py-4">বরাদ্দকৃত ক্যাটাগরি (Scope)</th>
                        <th class="px-5 py-4">স্ট্যাটাস</th>
                        <th class="px-5 py-4">সর্বশেষ লগইন</th>
                        <th class="px-5 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($staff as $member)
                        <tr class="hover:bg-purple-50/30 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-700 font-black flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $member->name }}</p>
                                        <p class="text-gray-500 text-[11px] font-mono">{{ $member->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if($member->role === 'super_admin')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-purple-900 text-purple-200 border border-purple-700">
                                        Super Admin
                                    </span>
                                @elseif($member->role === 'general_admin')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-blue-100 text-blue-800">
                                        General Admin
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-800">
                                        Moderator
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($member->isSuperAdmin())
                                    <span class="text-purple-600 font-bold text-xs">সকল ক্যাটাগরি (Full Access)</span>
                                @elseif(!empty($member->assigned_categories))
                                    @php
                                        $cats = \App\Models\ProductCategory::whereIn('id', $member->assigned_categories)->pluck('name');
                                    @endphp
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($cats as $catName)
                                            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[10px] font-semibold">{{ $catName }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs italic">কোন নির্দিষ্ট ক্যাটাগরি সীমাবদ্ধ নয়</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($member->status === 'active')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">সক্রিয়</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">স্থগিত</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $member->last_login_at ? $member->last_login_at->format('d M Y, h:i A') : 'কখনও লগইন করেনি' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($member->id !== auth()->id())
                                    <a href="{{ route('admin.staff.edit', $member->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-purple-600 hover:text-white font-bold text-xs transition-colors">
                                        <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                                        পারমিশন পরিবর্তন
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">বর্তমান অ্যাকাউন্ট</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">কোন স্টাফ মেম্বার পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
