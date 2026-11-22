@extends('layouts.subscription-admin', ['title' => 'STORPIFY - সাবস্ক্রিপশন ফাইল লাইব্রেরি'])

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">সাবস্ক্রিপশন ডিজিটাল ফাইল লাইব্রেরি</h1>
            <p class="text-xs text-gray-500 mt-1">সক্রিয় মেম্বারদের জন্য সংরক্ষিত ডিজিটাল রিসোর্স, থিম, প্লাগইন ও স্ক্রিপ্ট আপলোড করুন।</p>
        </div>
        <button @click="createModalOpen = true" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition-all">
            <i data-lucide="upload-cloud" class="w-4 h-4"></i>
            নতুন ফাইল আপলোড করুন
        </button>
    </div>

    <!-- Files Table -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-600 uppercase font-bold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4">ফাইলের নাম</th>
                        <th class="px-5 py-4">ক্যাটাগরি</th>
                        <th class="px-5 py-4">ভার্সন</th>
                        <th class="px-5 py-4">সাইজ ও ধরণ</th>
                        <th class="px-5 py-4">মোট ডাউনলোড</th>
                        <th class="px-5 py-4">আপলোডের তারিখ</th>
                        <th class="px-5 py-4">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($files as $file)
                        <tr class="hover:bg-purple-50/20 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center">
                                        <i data-lucide="file-archive" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $file->title_bn ?: $file->title }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono">/{{ $file->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-gray-100 text-gray-700">
                                    {{ $file->category ? ($file->category->name_bn ?: $file->category->name) : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-purple-700">
                                v{{ $file->version }}
                            </td>
                            <td class="px-5 py-4 font-mono text-gray-600 uppercase">
                                {{ round($file->file_size / 1024 / 1024, 2) }} MB ({{ $file->file_type }})
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold font-mono text-xs">
                                    {{ $file->download_count }} বার
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                {{ $file->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($file->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">সক্রিয়</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-400">কোন সাবস্ক্রিপশন ফাইল নেই।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($files->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $files->links() }}
            </div>
        @endif
    </div>

    <!-- Create File Modal -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="createModalOpen = false"></div>

            <div class="relative bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl z-10 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-black text-gray-900 text-base">লাইব্রেরিতে ফাইল আপলোড করুন</h3>
                    <button @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('admin.subscriptions.files.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">রিসোর্স / ফাইলের শিরোনাম</label>
                        <input type="text" name="title" required placeholder="Astra Pro Agency Theme Bundle" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ক্যাটাগরি</label>
                            <select name="category_id" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ভার্সন</label>
                            <input type="text" name="version" required value="1.0.0" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">সংক্ষিপ্ত বিবরণ</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ডিজিটাল আর্কাইভ ফাইল (.ZIP / .RAR / .PDF)</label>
                        <input type="file" name="digital_file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-100">
                            বাতিল
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs">
                            আপলোড ও সংরক্ষণ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
