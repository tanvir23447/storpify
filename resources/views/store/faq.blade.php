@extends('layouts.app')

@section('content')
<div class="py-12 bg-[#F8F9FD]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-full">সাহায্য ও জিজ্ঞাসা</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">
                সাধারণ প্রশ্নাবলী (FAQ)
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                STORPIFY সম্পর্কে সচরাচর জানতে চাওয়া প্রশ্নের উত্তরসমূহ
            </p>
        </div>

        <div class="space-y-4" x-data="{ activeAccordion: 1 }">
            @php $idx = 1; @endphp
            @foreach($faqs as $categoryName => $groupFaqs)
                @foreach($groupFaqs as $faq)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-xs">
                        <button @click="activeAccordion = (activeAccordion === {{ $idx }} ? null : {{ $idx }})" 
                            class="w-full p-5 text-left flex items-center justify-between text-sm font-bold text-gray-900 hover:text-purple-600 transition-colors">
                            <span>{{ app()->getLocale() === 'bn' && $faq->question_bn ? $faq->question_bn : $faq->question }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="activeAccordion === {{ $idx }} ? 'rotate-180 text-purple-600' : ''"></i>
                        </button>
                        <div x-show="activeAccordion === {{ $idx }}" x-collapse x-cloak class="px-5 pb-5 text-xs text-gray-600 leading-relaxed border-t border-gray-50 pt-3">
                            {{ app()->getLocale() === 'bn' && $faq->answer_bn ? $faq->answer_bn : $faq->answer }}
                        </div>
                    </div>
                    @php $idx++; @endphp
                @endforeach
            @endforeach
        </div>

        <!-- Contact Support CTA -->
        <div class="mt-12 p-8 rounded-3xl bg-gradient-to-r from-purple-900 to-[#17152B] text-white text-center shadow-lg space-y-3">
            <h3 class="text-base font-bold">আপনার কাঙ্ক্ষিত প্রশ্নের উত্তর পাননি?</h3>
            <p class="text-xs text-purple-200 max-w-md mx-auto">
                আমাদের ২৪/৭ সাপোর্ট টিম আপনাকে সহায়তা করার জন্য প্রস্তুত। সরাসরি টিকিট ওপেন করুন অথবা যোগাযোগ করুন।
            </p>
            <div class="pt-2">
                <a href="{{ route('customer.tickets') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-transform hover:scale-105">
                    <i data-lucide="headphones" class="w-4 h-4"></i>
                    <span>সাপোর্ট টিকিট তৈরি করুন</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
