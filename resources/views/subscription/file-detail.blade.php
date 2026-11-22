@extends('layouts.app')

@section('content')
<div class="py-12 bg-[#F8F9FD]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-6">
            <a href="{{ route('subscription.index') }}" class="hover:text-purple-600">ওয়েব সাবস্ক্রিপশন</a>
            <span>/</span>
            @if($file->category)
                <a href="{{ route('subscription.category', $file->category->slug) }}" class="hover:text-purple-600">{{ $file->category->name }}</a>
                <span>/</span>
            @endif
            <span class="text-gray-800 font-semibold truncate max-w-xs">{{ $file->title }}</span>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 p-8 sm:p-10 shadow-lg space-y-6">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-6">
                <div>
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-full">
                        {{ $file->category ? $file->category->name : 'Subscription File' }}
                    </span>
                    <h1 class="text-2xl font-extrabold text-gray-900 mt-2">
                        {{ app()->getLocale() === 'bn' && $file->title_bn ? $file->title_bn : $file->title }}
                    </h1>
                </div>

                <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 p-2.5 rounded-2xl shrink-0">
                    <span class="font-bold text-gray-800">Version {{ $file->version }}</span>
                    <span>•</span>
                    <span>{{ number_format($file->file_size / 1048576, 1) }} MB</span>
                    <span>•</span>
                    <span class="uppercase">{{ $file->file_type }}</span>
                </div>
            </div>

            <!-- Description -->
            <div class="prose prose-sm text-gray-700 leading-relaxed">
                <p>
                    {{ app()->getLocale() === 'bn' && $file->description_bn ? $file->description_bn : $file->description }}
                </p>
            </div>

            <!-- Download Access Protection Box -->
            <div class="p-6 rounded-3xl border {{ $canDownload ? 'bg-emerald-50 border-emerald-200' : 'bg-purple-50 border-purple-200' }} space-y-4">
                @if($canDownload)
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-emerald-950">আপনার অ্যাক্সেস সক্রিয় রয়েছে</h4>
                                <p class="text-xs text-emerald-700">মেয়াদ বাকি: {{ $userSubscription->remainingDays() }} দিন | আজকের অবশিষ্ট ডাউনলোড রয়েছে</p>
                            </div>
                        </div>

                        <a href="{{ route('subscription.download', $file->slug) }}" class="px-7 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg shadow-emerald-600/30 flex items-center gap-2 transition-transform hover:scale-105">
                            <i data-lucide="download-cloud" class="w-5 h-5"></i>
                            <span>ফাইল ডাউনলোড করুন (Instant ZIP)</span>
                        </a>
                    </div>
                @else
                    <div class="text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center shrink-0 shadow-md">
                                <i data-lucide="lock" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-purple-950">এই ফাইলটি প্রিমিয়াম মেম্বারদের জন্য সংরক্ষিত</h4>
                                <p class="text-xs text-purple-700 mt-0.5">ফাইলটি ডাউনলোড করতে আপনার সক্রিয় সাবস্ক্রিপশন প্রয়োজন।</p>
                            </div>
                        </div>

                        <a href="{{ route('subscription.plans') }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-2xl shadow-md transition-transform hover:scale-105 shrink-0">
                            সাবস্ক্রিপশন প্ল্যান দেখুন →
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
