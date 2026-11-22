@extends('layouts.subscription-admin', ['title' => 'STORPIFY - সাবস্ক্রিপশন ডাউনলোড লগ'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">সাবস্ক্রিপশন রিসোর্স ডাউনলোড অডিট লগ</h1>
            <p class="text-xs text-gray-500 mt-1">মেম্বারদের প্রতিটি ফাইল ডাউনলোডের আইপি, সময় ও ব্যবহারকারীর বিস্তারিত ট্র্যাকিং।</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">গ্রাহক</th>
                        <th class="px-5 py-4">ডাউনলোডকৃত ফাইল</th>
                        <th class="px-5 py-4">ক্যাটাগরি</th>
                        <th class="px-5 py-4">আইপি অ্যাড্রেস</th>
                        <th class="px-5 py-4">তারিখ ও সময়</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($downloads as $log)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-bold text-gray-900">{{ $log->user ? $log->user->name : 'N/A' }}</span>
                                <span class="block text-[10px] text-gray-400 font-mono">{{ $log->user ? $log->user->email : '' }}</span>
                            </td>
                            <td class="px-5 py-4 font-bold text-purple-700">
                                {{ $log->file ? ($log->file->title_bn ?: $log->file->title) : 'File Deleted' }}
                            </td>
                            <td class="px-5 py-4 text-gray-600">
                                {{ $log->file && $log->file->category ? ($log->file->category->name_bn ?: $log->file->category->name) : 'N/A' }}
                            </td>
                            <td class="px-5 py-4 font-mono text-gray-600">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $log->downloaded_at ? $log->downloaded_at->format('d M Y, h:i A') : $log->created_at->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">এখনও কোন ডাউনলোড লগ তৈরি হয়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($downloads->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $downloads->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
